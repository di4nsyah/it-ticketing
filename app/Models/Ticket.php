<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'user_id', 'category_id', 'title', 'description', 'priority', 'status'
    ];

    //1 user 1 tiket boyooo
    public function user(){
        return $this->belongsTo(User::class);
    }

    //1 tiket 1 kategori boi
    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function priorityLabel() {
        return match ($this->priority) {
            'low' => 'Rendah',
            'medium' => 'Menengah',
            'high' => 'Tinggi',
            default => $this->priority,
        };
    }

    public function statusLabel() {
        return match ($this->status) {
            'open' => 'Terbuka',
            'in_progress' => 'Sedang Diproses',
            'done' => 'Selesai',
            'closed' => 'Tertutup',
            default => $this->status,
        };
    }
}
