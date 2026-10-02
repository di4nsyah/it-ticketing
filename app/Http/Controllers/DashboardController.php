<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\View\View;

// MVC controller: cuma nyiapin angka buat halaman dashboard, nggak nyimpat apa-apa
class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $isTeknisi = $user->isTeknisi();

        // query builder bisa berubah kalo udah di-chain, makanya dibikin fungsi
        // biar tiap query dapet yang baru dan aturannya nggak ditulis ulang
        $scope = fn () => $isTeknisi ? Ticket::query() : $user->tickets();

        // MVC model -> database: 1 query buat ngitung semua status sekaligus
        // hasilnya kayak gini: ['open' => 3, 'done' => 5]
        $counts = $scope()
            ->selectRaw('status, count(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        // 3 ticket paling baru buat bagian aktivitas terakhir
        $recent = $scope()
            ->with(['user', 'category'])
            ->latest()
            ->limit(3)
            ->get();

        // MVC controller -> view: total cukup dijumlahin, nggak perlu query lagi
        return view('dashboard', [
            'counts' => $counts,
            'total' => $counts->sum(),
            'recent' => $recent,
            'isTeknisi' => $isTeknisi,
        ]);
    }
}
