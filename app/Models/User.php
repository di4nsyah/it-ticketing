<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// MVC model: nyambung ke tabel users, Extend Authenticatable bukan Model biasa
// makanya bisa login, logout, sama nyimpen session user yg lagi aktif
// Fillable = kolom yg boleh diisi, Hidden = kolom yg jangan ikut ke view (password)
#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    // HasFactory buat testing (User::factory()), Notifiable buat kirim notifikasi
    use HasFactory, Notifiable;

    // casts ubah tipe data pas diambil, password di-hash otomatis pas disimpen
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // 1 user punya banyak ticket, dipake di TicketController & DashboardController
    // $user->tickets() otomatis jadi where user_id = id dia, makanya karyawan
    // cuma lihat ticket sendiri tanpa nulis where manual
    public function tickets() {
        return $this->hasMany(Ticket::class);
    }

    // cek role, dipake di controller, policy, sama blade
    public function isTeknisi() {
        return $this->role === 'teknisi';
    }
}
