<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;

// MVC authorization: semua aturan "boleh atau nggak" ngumpet di sini
// authentication = siapa yang login, authorization = dia boleh ngapain
// Laravel otomatis nyari policy ini dari nama model Ticket
class TicketPolicy
{
    // Gate::authorize('create', Ticket::class) => teknisi ditolak
    public function create(User $user): bool
    {
        return ! $user->isTeknisi();
    }

    // Gate::authorize('view', $ticket) => teknisi semua, karyawan miliknya sendiri
    // return false bakal lempar 403
    public function view(User $user, Ticket $ticket): bool
    {
        return $user->isTeknisi() || $ticket->user_id === $user->id;
    }

    // dicek juga di view pake @can('cancel', $ticket), yg gagal tombolnya disembunyiin
    public function cancel(User $user, Ticket $ticket): bool
    {
        return $ticket->user_id === $user->id && $ticket->status === 'open';
    }

    // teknisi bisa update label status/prioritas selama belum final
    public function update(User $user, Ticket $ticket): bool
    {
        return $user->isTeknisi() && in_array($ticket->status, ['open', 'progress']);
    }

    
}