<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        //akun teknisi
        User::create([
            'name' => 'Anton Teknisi',
            'email' => 'SigmaTeknisi@kantor.com',
            'password' => Hash::make('password'),
            'role' => 'teknisi',
            'email_verified_at' => now(),
        ]);

        //akun karyawan
        User::create([
            'name' => 'Fulan Karyawan',
            'email' => 'FulanKaryawan@kantor.com',
            'password' => Hash::make('password'),
            'role' => 'karyawan',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Joko Karyawan',
            'email' => 'JokoKaryawan@kantor.com',
            'password' => Hash::make('password'),
            'role' => 'karyawan',
            'email_verified_at' => now(),
        ]);

        //default kategori
        foreach (['Hardware', 'Software', 'Network', 'Account'] as $name) {
            Category::create(['name' => $name,]);
        }
    }
}
