<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $isTeknisi = $user->isTeknisi();

        // Fresh builder each call: query builders are mutable, so the grouped
        // query below must not be reused for the recent list.
        $scope = fn () => $isTeknisi ? Ticket::query() : $user->tickets();

        // One grouped query covers every status card; the total is the sum of that.
        $counts = $scope()
            ->selectRaw('status, count(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        $recent = $scope()
            ->with(['user', 'category'])
            ->latest()
            ->limit(3)
            ->get();

        return view('dashboard', [
            'counts' => $counts,
            'total' => $counts->sum(),
            'recent' => $recent,
            'isTeknisi' => $isTeknisi,
        ]);
    }
}
