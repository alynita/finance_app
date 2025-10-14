<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            ['name' => 'Pegawai', 'email' => 'pegawai@gmail.com', 'role' => 'pegawai', 'password' => Hash::make('password123')],
            ['name' => 'Adum', 'email' => 'adum@gmail.com', 'role' => 'adum', 'password' => Hash::make('password123')],
            ['name' => 'PPK', 'email' => 'ppk@gmail.com', 'role' => 'ppk', 'password' => Hash::make('password123')],
            ['name' => 'Verifikator', 'email' => 'verifikator@gmail.com', 'role' => 'verifikator', 'password' => Hash::make('password123')],
            ['name' => 'Keuangan', 'email' => 'keuangan@gmail.com', 'role' => 'keuangan', 'password' => Hash::make('password123')],
            ['name' => 'Penyelenggara Pengadaan', 'email' => 'pengadaan@gmail.com', 'role' => 'penyelenggara_pengadaan', 'password' => Hash::make('password123')],
            ['name' => 'Bendahara', 'email' => 'bendahara@gmail.com', 'role'=> 'bendahara', 'password' => Hash::make('password123')],
        ];

        foreach ($users as $user) {
        User::updateOrCreate(
            ['email' => $user['email']], // cek email
            $user                       // update atau create baru
        );
        }
    }
}
