<?php

namespace App\Http\Controllers;

use App\Models\JobPosting;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JobController extends Controller
{
    /**
     * Public job board: newest first, with search and filters.
     *
     * Free tier (guests and users without an active subscription) sees
     * 3 results per browse/search; the cap is enforced here, server-side,
     * by limiting the query — not by hiding rows in the view.
     */
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:120'],
            'source' => ['nullable', 'string', 'max:40'],
            'age' => ['nullable', 'string', 'in:24h,7d,all'],
            'remote' => ['nullable', 'boolean'],
        ]);

        $query = JobPosting::query()->newestFirst();

        if (! empty($validated['q'])) {
            $term = '%'.$validated['q'].'%';
            $query->where(function ($w) use ($term) {
                $w->where('company', 'like', $term)
                    ->orWhere('title', 'like', $term);
            });
        }

        if (! empty($validated['source'])) {
            $query->where('source', $validated['source']);
        }

        $age = $validated['age'] ?? 'all';
        if ($age === '24h') {
            $query->whereRaw('COALESCE(posted_at, first_seen) >= ?', [now()->subDay()]);
        } elseif ($age === '7d') {
            $query->whereRaw('COALESCE(posted_at, first_seen) >= ?', [now()->subDays(7)]);
        }

        if (! empty($validated['remote'])) {
            $query->where('location', 'like', '%remote%');
        }

        $total = (clone $query)->count();

        $user = $request->user();
        $subscribed = $user && $user->subscribed('default');

        if ($subscribed) {
            $jobs = $query->paginate(50)->withQueryString();
            $paywalled = false;
        } else {
            $jobs = $query->limit(3)->get();
            $paywalled = $total > 3;
        }

        $sources = JobPosting::query()
            ->distinct()
            ->pluck('source')
            ->mapWithKeys(fn ($s) => [$s => JobPosting::sourceLabels()[$s] ?? ucfirst($s)])
            ->all();

        return view('jobs.index', [
            'jobs' => $jobs,
            'total' => $total,
            'paywalled' => $paywalled,
            'subscribed' => (bool) $subscribed,
            'sources' => $sources,
            'filters' => [
                'q' => $validated['q'] ?? '',
                'source' => $validated['source'] ?? '',
                'age' => $age,
                'remote' => ! empty($validated['remote']),
            ],
        ]);
    }
}
