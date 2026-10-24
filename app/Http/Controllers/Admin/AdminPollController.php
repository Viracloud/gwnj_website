<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Poll;
use App\Models\PollAnswer;
use App\Models\PollOption;
use App\Models\PollQuestion;
use App\Services\GoogleCsvPoll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminPollController extends Controller
{
    public function index()
    {
        $polls = Poll::withCount('responses')->orderBy('sort_order')->orderBy('id', 'desc')->get();
        return view('admin.poll.index', compact('polls'));
    }

    public function create()
    {
        return view('admin.poll.form', ['poll' => null]);
    }

    public function store(Request $request)
    {
        $poll = new Poll();
        $this->fill($poll, $request);
        $poll->save();

        return redirect()->route($poll->isGoogle() ? 'admin_poll_google' : 'admin_poll_questions', $poll->id)
            ->with('success', $poll->isGoogle() ? 'Poll created. Now connect the Google Sheet.' : 'Poll created. Now add the questions.');
    }

    public function edit($id)
    {
        $poll = Poll::findOrFail($id);
        return view('admin.poll.form', compact('poll'));
    }

    public function update(Request $request, $id)
    {
        $poll = Poll::findOrFail($id);
        $this->fill($poll, $request);
        $poll->save();

        return redirect()->back()->with('success', 'Poll updated successfully.');
    }

    private function fill(Poll $poll, Request $request): void
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'title_fa' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:3000',
            'description_fa' => 'nullable|string|max:3000',
            'source' => 'required|in:site,google',
            'status' => 'required|in:draft,open,closed',
            'results_visibility' => 'required|in:after_vote,after_close,always',
            'ends_at' => 'nullable|date',
            'sort_order' => 'nullable|integer|min:0',
            'google_form_url' => ['nullable', 'url:https', 'max:1000'],
        ]);

        // The source can't be switched once votes have been collected on the site.
        if ($poll->exists && $poll->source !== $request->source && $poll->responses()->exists()) {
            abort(back()->with('error', 'This poll already has votes; its source cannot be changed.'));
        }

        $poll->title = $request->title;
        $poll->title_fa = $request->title_fa;
        $poll->slug = resolve_slug('polls', $request->slug, $request->title, $poll->id)
            ?: ($poll->slug ?: 'poll-'.strtolower(\Illuminate\Support\Str::random(8)));
        $poll->description = $request->description;
        $poll->description_fa = $request->description_fa;
        $poll->source = $request->source;
        $poll->status = $request->status;
        $poll->results_visibility = $request->results_visibility;
        $poll->ends_at = $request->ends_at ?: null;
        $poll->show_on_home = $request->boolean('show_on_home');
        $poll->sort_order = (int) $request->sort_order;
        $poll->google_form_url = $request->google_form_url;
    }

    public function delete($id)
    {
        Poll::findOrFail($id)->delete();
        return redirect()->route('admin_poll_index')->with('success', 'Poll deleted.');
    }

    /* ----------------------------- Questions (site polls) ----------------------------- */

    public function questions($id)
    {
        $poll = Poll::with('questions.options')->withCount('responses')->findOrFail($id);
        return view('admin.poll.questions', compact('poll'));
    }

    public function question_store(Request $request, $id)
    {
        $poll = Poll::findOrFail($id);
        if ($poll->responses()->exists()) {
            return back()->with('error', 'Questions cannot be added after people have voted. Create a new poll instead.');
        }
        $data = $this->validatedQuestion($request);

        DB::transaction(function () use ($poll, $data, $request) {
            $q = PollQuestion::create($data + ['poll_id' => $poll->id]);
            $this->saveOptions($q, $request);
        });

        return back()->with('success', 'Question added.');
    }

    public function question_update(Request $request, $qid)
    {
        $q = PollQuestion::with('poll')->findOrFail($qid);
        $data = $this->validatedQuestion($request);
        $locked = $q->poll->responses()->exists();

        DB::transaction(function () use ($q, $data, $request, $locked) {
            if ($locked) {
                // After voting started only the wording may change (keeps results meaningful).
                $q->update(['question' => $data['question'], 'question_fa' => $data['question_fa'], 'sort_order' => $data['sort_order']]);
                foreach ($q->options as $i => $opt) {
                    $opt->update([
                        'label' => $request->input("opt_label.{$opt->id}", $opt->label),
                        'label_fa' => $request->input("opt_label_fa.{$opt->id}", $opt->label_fa),
                    ]);
                }
            } else {
                $q->update($data);
                $q->options()->delete();
                $this->saveOptions($q, $request);
            }
        });

        return back()->with('success', 'Question updated.');
    }

    public function question_delete($qid)
    {
        $q = PollQuestion::with('poll')->findOrFail($qid);
        if ($q->poll->responses()->exists()) {
            return back()->with('error', 'Questions cannot be deleted after people have voted.');
        }
        $q->delete();
        return back()->with('success', 'Question deleted.');
    }

    private function validatedQuestion(Request $request): array
    {
        $data = $request->validate([
            'question' => 'required|string|max:500',
            'question_fa' => 'nullable|string|max:500',
            'type' => 'required|in:single,multiple,rating,text',
            'sort_order' => 'nullable|integer|min:0',
            'options' => 'nullable|string|max:5000',
            'options_fa' => 'nullable|string|max:5000',
        ]);
        if (in_array($data['type'], ['single', 'multiple'], true) && count($this->lines($data['options'] ?? '')) < 2) {
            abort(back()->withInput()->with('error', 'Choice questions need at least two options (one per line).'));
        }
        return [
            'question' => $data['question'],
            'question_fa' => $data['question_fa'] ?? null,
            'type' => $data['type'],
            'required' => $request->boolean('required'),
            'sort_order' => (int) ($data['sort_order'] ?? 0),
        ];
    }

    private function saveOptions(PollQuestion $q, Request $request): void
    {
        if (!$q->hasOptions()) {
            return;
        }
        $en = $this->lines($request->options);
        $sv = $this->lines($request->options_fa);
        foreach ($en as $i => $label) {
            PollOption::create(['poll_question_id' => $q->id, 'label' => $label, 'label_fa' => $sv[$i] ?? null, 'sort_order' => $i]);
        }
    }

    private function lines(?string $text): array
    {
        return array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', (string) $text)), 'strlen'));
    }

    /* ----------------------------- Google Form source ----------------------------- */

    public function google(Request $request, $id)
    {
        $poll = Poll::findOrFail($id);
        $headers = null;
        $error = null;

        // "Load columns" step: read the header row of the published tab.
        if ($request->filled('check') && $poll->google_csv_url) {
            try {
                $headers = GoogleCsvPoll::headers($poll->google_csv_url);
            } catch (\Throwable $e) {
                $error = $e->getMessage();
            }
        }

        return view('admin.poll.google', compact('poll', 'headers', 'error'));
    }

    public function google_save_url(Request $request, $id)
    {
        $poll = Poll::findOrFail($id);
        $request->validate(['google_csv_url' => 'required|string|max:1000']);
        $url = GoogleCsvPoll::normaliseUrl($request->google_csv_url);
        if (!GoogleCsvPoll::isAllowedUrl($url)) {
            return back()->withInput()->with('error', 'Please paste the "Publish to web" link of the Google Sheet (it starts with https://docs.google.com/spreadsheets/).');
        }
        $poll->google_csv_url = $url;
        $poll->save();

        return redirect()->route('admin_poll_google', ['id' => $poll->id, 'check' => 1])->with('success', 'Link saved. Choose the questions to show.');
    }

    public function google_save_columns(Request $request, $id)
    {
        $poll = Poll::findOrFail($id);
        $request->validate([
            'cols' => 'nullable|array',
            'cols.*.index' => 'required|integer|min:0',
            'cols.*.type' => 'required|in:single,multiple,rating',
            'cols.*.label' => 'nullable|string|max:500',
            'cols.*.label_fa' => 'nullable|string|max:500',
        ]);

        $columns = collect($request->input('cols', []))
            ->filter(fn ($c) => !empty($c['use']))
            ->map(fn ($c) => [
                'index' => (int) $c['index'],
                'type' => $c['type'],
                'label' => $c['label'] ?? '',
                'label_fa' => $c['label_fa'] ?? null,
            ])->values()->all();

        $poll->google_columns = $columns;
        $poll->google_synced_at = null; // force a fresh read
        $poll->save();
        $ok = $poll->syncFromGoogle();

        return redirect()->route('admin_poll_google', $poll->id)->with($ok ? 'success' : 'error',
            $ok ? 'Saved. Results were read from Google.' : 'Saved, but Google could not be read: '.$poll->google_sync_error);
    }

    public function google_refresh($id)
    {
        $poll = Poll::findOrFail($id);
        $ok = $poll->syncFromGoogle();
        return back()->with($ok ? 'success' : 'error', $ok ? 'Results updated from Google.' : 'Google could not be read: '.$poll->google_sync_error);
    }

    /* ----------------------------- Results ----------------------------- */

    public function results($id)
    {
        $poll = Poll::findOrFail($id);
        $results = $poll->results();
        $text_answers = $poll->isGoogle() ? collect() : PollQuestion::where('poll_id', $poll->id)->where('type', 'text')
            ->with(['answers' => fn ($q) => $q->whereNotNull('text')->where('text', '!=', '')->latest('id')])->get();

        return view('admin.poll.results', compact('poll', 'results', 'text_answers'));
    }

    /** CSV export of all individual answers (site polls) — no personal data is stored, only answers. */
    public function export($id)
    {
        $poll = Poll::with('questions.options')->findOrFail($id);
        abort_if($poll->isGoogle(), 404);

        $questions = $poll->questions;
        $options = $questions->flatMap->options->keyBy('id');
        $filename = 'poll-'.$poll->slug.'-'.now()->format('Y-m-d').'.csv';

        return response()->streamDownload(function () use ($poll, $questions, $options) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // so Excel opens Dari text correctly
            fputcsv($out, array_merge(['Response #', 'Date'], $questions->pluck('question')->all()));
            $poll->responses()->with('answers')->orderBy('id')->chunk(500, function ($chunk) use ($out, $questions, $options) {
                foreach ($chunk as $resp) {
                    $byQ = $resp->answers->groupBy('poll_question_id');
                    $row = [$resp->id, $resp->created_at?->format('Y-m-d H:i')];
                    foreach ($questions as $q) {
                        $ans = $byQ->get($q->id, collect());
                        $row[] = match ($q->type) {
                            'rating' => (string) optional($ans->first())->rating,
                            'text' => (string) optional($ans->first())->text,
                            default => $ans->map(fn ($a) => optional($options->get($a->poll_option_id))->label)->filter()->implode(' | '),
                        };
                    }
                    fputcsv($out, $row);
                }
            });
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
