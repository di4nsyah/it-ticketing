<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// MVC model: nyambung ke tabel categories, isinya nama kategori aja
class Category extends Model
{
    // kolom yg boleh diisi, seeder pake Category::create(['name' => ...])
    protected $fillable = ['name'];

    // 1 kategori punya banyak ticket, dari sisi ticket dia belongsTo
    public function tickets() {
        return $this->hasMany(Ticket::class);
    }
}
