<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
    //hanya karyawan yang boleh bikin tiket
    public function create(User $user): bool
    {
        return ! $user->isTeknisi();
    }

    public function view(User $user, Ticket $ticket): bool
    {
        return $user->isTeknisi() || $ticket->user_id === $user->id;
    }

    public function cancel(User $user, Ticket $ticket): bool
    {
        return $ticket->user_id === $user->id && $ticket->status === 'open';
    }
}