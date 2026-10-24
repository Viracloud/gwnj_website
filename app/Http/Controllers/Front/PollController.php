<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Poll;
use App\Models\PollAnswer;
use App\Models\PollResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PollController extends Controller
{
    /** Name of the cookie that holds the visitor's anonymous voter id. */
    private const VOTER_COOKIE = 'gwjn_voter';

    /** Generous limit per IP address per poll, so families / internet cafés sharing one connection can all vote. */
    private const MAX_VOTES_PER_IP = 25;

    public function index()
    {
        $polls = Poll::published()->withCount('questions')->orderByRaw("CASE WHEN status = 'open' THEN 0 ELSE 1 END")
            ->orderBy('sort_order')->orderBy('id', 'desc')->paginate(10);
        return view('front.polls', compact('polls'));
    }

    public function show(Request $request, $slug)
    {
        $poll = Poll::published()->where('slug', $slug)->with('questions.options')->firstOrFail();
        $voted = $poll->hasVoted($request->cookie(self::VOTER_COOKIE));
        $show_results = $poll->resultsVisible($voted);
        $results = $show_results ? $poll->results() : null;

        return view('front.poll', compact('poll', 'voted', 'show_results', 'results'));
    }

    public function vote(Request $request, $slug)
    {
        $poll = Poll::published()->where('slug', $slug)->with('questions.options')->firstOrFail();
        $back = redirect()->route('poll', $poll->slug);
        $rtl = is_rtl();

        if ($poll->isGoogle() || !$poll->isOpen()) {
            return $back->with('error', __('This poll is not open for voting.'));
        }

        // Honeypot: real visitors never fill this hidden field.
        if ($request->filled('website')) {
            return $back;
        }

        $token = $request->cookie(self::VOTER_COOKIE);
        if (!$token || strlen($token) < 32) {
            $token = Str::random(40);
        }
        if ($poll->hasVoted($token)) {
            return $back->with('error', __('You have already taken part in this poll.'));
        }

        $ip_hash = hash_hmac('sha256', $request->ip().'|'.$poll->id, (string) config('app.key'));
        if (PollResponse::where('poll_id', $poll->id)->where('ip_hash', $ip_hash)->count() >= self::MAX_VOTES_PER_IP) {
            return $back->with('error', __('The vote limit for this internet connection has been reached.'));
        }

        // Validate every question against its own options.
        $answers = [];
        foreach ($poll->questions as $q) {
            $val = $request->input('q.'.$q->id);
            $optionIds = $q->options->pluck('id')->all();

            if ($q->type === 'single') {
                $val = in_array((int) $val, $optionIds, true) ? (int) $val : null;
                if ($val) $answers[] = ['q' => $q->id, 'option' => $val];
            } elseif ($q->type === 'multiple') {
                $val = array_values(array_unique(array_intersect(array_map('intval', (array) $val), $optionIds)));
                foreach ($val as $oid) $answers[] = ['q' => $q->id, 'option' => $oid];
                $val = $val ?: null;
            } elseif ($q->type === 'rating') {
                $val = (int) $val >= 1 && (int) $val <= 5 ? (int) $val : null;
                if ($val) $answers[] = ['q' => $q->id, 'rating' => $val];
            } else {
                $val = trim(mb_substr((string) $val, 0, 2000));
                $val = $val === '' ? null : $val;
                if ($val) $answers[] = ['q' => $q->id, 'text' => strip_tags($val)];
            }

            if ($q->required && $val === null) {
                return $back->withInput()->with('error', __('Please answer all required questions.'));
            }
        }
        if (!$answers) {
            return $back->with('error', __('Please answer at least one question.'));
        }

        DB::transaction(function () use ($poll, $token, $ip_hash, $answers) {
            $resp = PollResponse::create(['poll_id' => $poll->id, 'voter_token' => $token, 'ip_hash' => $ip_hash]);
            foreach ($answers as $a) {
                PollAnswer::create([
                    'poll_response_id' => $resp->id,
                    'poll_question_id' => $a['q'],
                    'poll_option_id' => $a['option'] ?? null,
                    'rating' => $a['rating'] ?? null,
                    'text' => $a['text'] ?? null,
                ]);
            }
        });

        Cookie::queue(self::VOTER_COOKIE, $token, 60 * 24 * 365 * 2); // two years

        return $back->with('success', __('Thank you! Your vote has been recorded.'));
    }
}
