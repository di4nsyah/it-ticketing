<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TicketController extends Controller
{
    public function index(Request $request) 
    {
        $user = $request->user();

        if ($user->isTeknisi()) {
            $query = Ticket::query();
        } else {
            $query = $user->tickets();
        }

        $tickets = $query->paginate(10);
        $categories = Category::orderBy('name')->get();

        return view ('tickets.index', compact('tickets', 'categories'));
    }

    public function create()
    {
        Gate::authorize('create', Ticket::class);
        $categories = Category::orderBy('name')->get();
        return view('tickets.create', compact('categories'));
    }

    public function store(Request $request)
    {
        Gate::authorize('create'. Ticket::class);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'priority' => ['required', 'in:low,medium,high'],
        ]);

        Ticket::create([
            ...$validated,
            'user_id' => $request->user()->id,
            'status' => 'open',
        ]);

        return redirect()->route('tickets.index')->with('success', 'Tiket berhasil dibuat.');
    }

    public function show(Ticket $ticket)
    {
        Gate::authorize('view', $ticket);
        $ticket->load(['user', 'category']);
        return view('tickets.show', compact('ticket'));
    }

    public function cancel(Ticket $ticket)
    {
        Gate::authorize('cancel', $ticket);
        $ticket->update(['status' => 'cancelled']);
        return redirect()->route('tickets.index')->with('success', 'Tiket dibatalkan.');
    }
}
