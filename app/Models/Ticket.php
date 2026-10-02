<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// MVC model: nyambung ke tabel tickets, Ticket::create() nulis ke tabel itu
class Ticket extends Model
{
    // kolom yg boleh diisi pake create(), kolom lain diabaikan
    // proteksi mass assignment, misal user nggak bisa ngirim role=teknisi lewat form
    protected $fillable = [
        'user_id', 'category_id', 'title', 'description', 'priority', 'status'
    ];

    public const STATUSES = [
        'open' => 'Open',
        'progress' => 'Progress',
        'done' => 'Done',
        'cancelled' => 'Cancelled',
    ];

    public const PRIORITIES = [
        'low' => 'Rendah',
        'medium' => 'Sedang',
        'high' => 'Tinggi',
    ];

    // 1 ticket punya 1 user, belongsTo karena user_id ada di tabel tickets
    // dipake di view: $ticket->user->name
    public function user(){
        return $this->belongsTo(User::class);
    }

    // 1 kategori punya banyak ticket, jadi dari sisi ticket dia belongsTo
    // dipake di view: $ticket->category->name
    public function category(){
        return $this->belongsTo(Category::class);
    }

    // database nyimpen low/medium/high, yg diliat user rendah/menengah/ringgi
    public function priorityLabel() {
        return self::PRIORITIES[$this->priority] ?? $this->priority;
    }

    // sama kayak priority, buat status ticket
    public function statusLabel() {
         return self::STATUSES[$this->status] ?? $this->status;
        }

    // status selanjutnya, dipake di TicketController buat validasi
    // misal status sekarang open, cuma boleh diubah ke progress, bukan done
    public function nextStatuses(): array {
        return match ($this->status) {
            'open' => ['progress'],
            'progress' => ['done'],
            default => [], // status nya udah final
        };
    }
}
