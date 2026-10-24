<?php

namespace App\Models;

use App\Services\GoogleCsvPoll;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Poll extends Model
{
    use \App\Models\Concerns\SavesExtraLocales;

    protected $fillable = [
        'title', 'title_fa', 'slug', 'description', 'description_fa',
        'source', 'status', 'results_visibility', 'ends_at', 'show_on_home', 'sort_order',
        'google_form_url', 'google_csv_url',
    ];

    protected $casts = [
        'ends_at' => 'datetime',
        'google_synced_at' => 'datetime',
        'show_on_home' => 'boolean',
        'google_columns' => 'array',
        'google_cache' => 'array',
    ];

    public const STATUSES = ['draft' => 'Draft (hidden)', 'open' => 'Open', 'closed' => 'Closed'];
    public const VISIBILITY = [
        'after_vote' => 'After the visitor has voted (and once closed)',
        'after_close' => 'Only after the poll is closed',
        'always' => 'Always',
    ];

    /** How often Google Form results are re-read (minutes). */
    public const GOOGLE_REFRESH_MINUTES = 10;

    public function questions()
    {
        return $this->hasMany(PollQuestion::class)->orderBy('sort_order')->orderBy('id');
    }

    public function responses()
    {
        return $this->hasMany(PollResponse::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', '!=', 'draft');
    }

    public function isGoogle(): bool
    {
        return $this->source === 'google';
    }

    public function isOpen(): bool
    {
        return $this->status === 'open' && (!$this->ends_at || $this->ends_at->isFuture());
    }

    public function isClosed(): bool
    {
        return $this->status === 'closed' || ($this->status === 'open' && $this->ends_at && $this->ends_at->isPast());
    }

    /**
     * Whether results may be shown. Votes for Google polls happen on Google,
     * so "after the visitor voted" is treated as "always" for them.
     */
    public function resultsVisible(bool $hasVoted): bool
    {
        if ($this->isClosed() || $this->results_visibility === 'always') {
            return true;
        }
        if ($this->results_visibility === 'after_vote') {
            return $hasVoted || $this->isGoogle();
        }
        return false;
    }

    public function hasVoted(?string $voterToken): bool
    {
        return !$this->isGoogle() && $voterToken
            && $this->responses()->where('voter_token', $voterToken)->exists();
    }

    public function totalResponses(): int
    {
        if ($this->isGoogle()) {
            return (int) ($this->googleResults()['total'] ?? 0);
        }
        return $this->responses()->count();
    }

    /**
     * Aggregated results in one shape for both sources:
     * ['total' => n, 'questions' => [[label, label_fa, type, answered, average, options => [[label, label_fa, count, pct]]]]]
     * Free-text answers are never part of this (they stay in the admin panel).
     */
    public function results(): array
    {
        return $this->isGoogle() ? $this->googleResults() : $this->siteResults();
    }

    protected function siteResults(): array
    {
        $out = ['total' => $this->responses()->count(), 'questions' => []];

        foreach ($this->questions()->with('options')->get() as $q) {
            if ($q->type === 'text') {
                continue;
            }
            $answered = PollAnswer::where('poll_question_id', $q->id)->distinct('poll_response_id')->count('poll_response_id');
            $row = ['label' => $q->question, 'label_fa' => $q->question_fa, 'type' => $q->type,
                    'answered' => $answered, 'average' => null, 'options' => []];

            if ($q->type === 'rating') {
                $counts = PollAnswer::where('poll_question_id', $q->id)->whereNotNull('rating')
                    ->selectRaw('rating, count(*) c')->groupBy('rating')->pluck('c', 'rating');
                $sum = 0;
                for ($i = 1; $i <= 5; $i++) {
                    $c = (int) ($counts[$i] ?? 0);
                    $sum += $i * $c;
                    $row['options'][] = ['label' => str_repeat('★', $i), 'label_fa' => str_repeat('★', $i), 'count' => $c];
                }
                $row['average'] = $answered ? round($sum / max(1, array_sum(array_column($row['options'], 'count'))), 1) : null;
            } else {
                $counts = PollAnswer::where('poll_question_id', $q->id)->whereNotNull('poll_option_id')
                    ->selectRaw('poll_option_id, count(*) c')->groupBy('poll_option_id')->pluck('c', 'poll_option_id');
                foreach ($q->options as $opt) {
                    $row['options'][] = ['label' => $opt->label, 'label_fa' => $opt->label_fa, 'count' => (int) ($counts[$opt->id] ?? 0)];
                }
            }
            $out['questions'][] = self::withPercentages($row);
        }

        return $out;
    }

    /** Cached Google results; re-read from Google when older than GOOGLE_REFRESH_MINUTES. */
    public function googleResults(bool $force = false): array
    {
        $stale = !$this->google_synced_at
            || $this->google_synced_at->lt(now()->subMinutes(self::GOOGLE_REFRESH_MINUTES));

        if (($force || $stale) && $this->google_csv_url && $this->google_columns) {
            // Only one request re-reads Google at a time; the others use the stored results.
            $lock = Cache::lock('poll-sync-'.$this->id, 30);
            if ($force || $lock->get()) {
                try {
                    $this->syncFromGoogle();
                } finally {
                    optional($lock)->release();
                }
            }
        }

        return $this->google_cache ?: ['total' => 0, 'questions' => []];
    }

    /** Read the published CSV and store only aggregated counts. Keeps the old results on failure. */
    public function syncFromGoogle(): bool
    {
        try {
            $rows = GoogleCsvPoll::fetchRows($this->google_csv_url);
            $results = GoogleCsvPoll::aggregate($rows, $this->google_columns ?? []);
            foreach ($results['questions'] as $i => $row) {
                $results['questions'][$i] = self::withPercentages($row);
            }
            $this->google_cache = $results;
            $this->google_sync_error = null;
            $this->google_synced_at = now();
            $this->saveQuietly();
            return true;
        } catch (\Throwable $e) {
            $this->google_sync_error = mb_substr($e->getMessage(), 0, 490);
            $this->google_synced_at = now(); // wait for the next interval before retrying
            $this->saveQuietly();
            report($e);
            return false;
        }
    }

    public static function withPercentages(array $row): array
    {
        $base = $row['type'] === 'multiple' ? max(1, (int) $row['answered']) : max(1, array_sum(array_column($row['options'], 'count')));
        foreach ($row['options'] as $k => $o) {
            $row['options'][$k]['pct'] = round(100 * $o['count'] / $base);
        }
        return $row;
    }
}
