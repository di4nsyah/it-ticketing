<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
    // Boleh lihat: teknisi (semua) atau pemilik ticket
    public function view(User $user, Ticket $ticket): bool
    {
        return $user->isTeknisi() || $ticket->user_id === $user->id;
    }

    // Boleh batalkan: hanya pemilik, dan hanya saat status open
    public function cancel(User $user, Ticket $ticket): bool
    {
        return $ticket->user_id === $user->id && $ticket->status === 'open';
    }
}