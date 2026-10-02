<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

// MVC controller: terima request dari route, nyiapin data, lempar ke view
// controller nggak nulis HTML dan juga nggak nulis SQL
class TicketController extends Controller
{
    // user buka /tickets
    public function index(Request $request) 
    {
        $user = $request->user();

        // authorization: teknisi lihat semua, karyawan cuma ticket sendiri
        if ($user->isTeknisi()) {
            $query = Ticket::query();
        } else {
            $query = $user->tickets();
        }

        // with() eager loading, kalo nggak tiap baris nanya 2x ke database
        $tickets = $query->with(['user', 'category'])->paginate(10);
        $categories = Category::orderBy('name')->get();

        // MVC controller -> view: data dikirim ke blade tickets.index
        return view ('tickets.index', compact('tickets', 'categories'));
    }

    // user buka /tickets/create, form doang belum ada yang disimpan
    public function create()
    {
        // cek dulu boleh nggak bikin ticket, aturannya ada di TicketPolicy
        Gate::authorize('create', Ticket::class);
        $categories = Category::orderBy('name')->get();

        return view('tickets.create', compact('categories'));
    }

    // user submit form (POST /tickets)
    // alur: cek boleh -> validasi -> Ticket::create() -> database -> redirect
    public function store(Request $request)
    {
        Gate::authorize('create', Ticket::class);

        // data form nggak bisa dipercaya, dicek dulu sebelum masuk database
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'priority' => ['required', 'in:low,medium,high'],
        ]);

        // ...$validated gabungin isi validasi ke array ini
        // user_id dari user yg login, bukan dari form, biar nggak bisa ngaku ticket orang
        Ticket::create([
            ...$validated,
            'user_id' => $request->user()->id,
            'status' => 'open',
        ]);

        // redirect + pesan flash yg cuma muncul sekali di halaman tujuan
        return redirect()->route('tickets.index')->with('success', 'Tiket berhasil dibuat.');
    }

    // user buka /tickets/5
    public function show(Ticket $ticket)
    {
        // route model binding: Laravel otomatis cari Ticket id 5, nggak perlu find() manual
        Gate::authorize('view', $ticket);
        $ticket->load(['user', 'category']);

        return view('tickets.show', compact('ticket'));
    }

    // PATCH /tickets/{ticket} : teknisi ubah status & prioritas
    public function update(Request $request, Ticket $ticket)
    {
        Gate::authorize('update', $ticket);

        $validated = $request->validate([
            // status boleh tetap, atau maju ke status berikutnya
            'status' => ['required', Rule::in([$ticket->status, ...$ticket->nextStatuses()])],
            'priority' => ['required', Rule::in(array_keys(Ticket::PRIORITIES))],
        ]);

        $ticket->update($validated);
        return redirect()->route('tickets.show', $ticket)->with('success', 'Tiket berhasil diperbarui.');
    }

    // user klik tombol batalin (PATCH /tickets/{id}/cancel)
    public function cancel(Ticket $ticket)
    {
        // policy cek 2 hal: ticket punya dia, dan statusnya masih open
        Gate::authorize('cancel', $ticket);
        $ticket->update(['status' => 'cancelled']);

        return redirect()->route('tickets.index')->with('success', 'Tiket dibatalkan.');
    }
}
