<?php

namespace App\Http\Controllers;

use App\Support\SiteSearch;
use App\Support\TourCatalog;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request, SiteSearch $search, TourCatalog $catalog): View
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:120'],
        ]);

        $query = trim((string) ($validated['q'] ?? ''));
        $results = $search->search($query);

        return view('search.index', [
            'query' => $query,
            'results' => $results,
            'pageBg' => $catalog->packages()->first()['image']
                ?? '/aventour/assets/images/background/page-title.jpg',
            'awardBg' => $catalog->safariPackages()->first()['image']
                ?? $catalog->packages()->first()['image']
                ?? '/aventour/assets/images/background/award-bg.jpg',
            'current' => 'search',
        ]);
    }

    public function suggest(Request $request, SiteSearch $search): JsonResponse
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:120'],
        ]);

        $query = trim((string) ($validated['q'] ?? ''));
        $results = $search->search($query, 6);

        return response()->json([
            'query' => $query,
            'count' => $results->count(),
            'results' => $results->values(),
            'view_all_url' => route('search.index', ['q' => $query]),
        ]);
    }
}
