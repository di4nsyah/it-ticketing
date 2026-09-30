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
        return $this->belongTo(Category::class);
    }
}
