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
            ['name' => 'Admin', 'email' => 'admin@gmail.com', 'role' => 'admin', 'password' => Hash::make('password123')],
            ['name' => 'Pegawai', 'email' => 'pegawai@gmail.com', 'role' => 'pegawai', 'password' => Hash::make('password123')],
            ['name' => 'Adum', 'email' => 'adum@gmail.com', 'role' => 'adum', 'password' => Hash::make('password123')],
            ['name' => 'PPK', 'email' => 'ppk@gmail.com', 'role' => 'ppk', 'password' => Hash::make('password123')],
            ['name' => 'Verifikator', 'email' => 'verifikator@gmail.com', 'role' => 'verifikator', 'password' => Hash::make('password123')],
            ['name' => 'Keuangan', 'email' => 'keuangan@gmail.com', 'role' => 'keuangan', 'password' => Hash::make('password123')],
            ['name' => 'Penyelenggara Pengadaan', 'email' => 'pengadaan@gmail.com', 'role' => 'pengadaan', 'password' => Hash::make('password123')],
            ['name' => 'Bendahara', 'email' => 'bendahara@gmail.com', 'role'=> 'bendahara', 'password' => Hash::make('password123')],
            ['name' => 'Timker1', 'email' => 'timker1@gmail.com', 'role'=> 'timker_1', 'password' => Hash::make('password123')],
            ['name' => 'Timker2', 'email' => 'timker2@gmail.com', 'role'=> 'timker_2', 'password' => Hash::make('password123')],
            ['name' => 'Timker3', 'email' => 'timker3@gmail.com', 'role'=> 'timker_3', 'password' => Hash::make('password123')],
            ['name' => 'Timker4', 'email' => 'timker4@gmail.com', 'role'=> 'timker_4', 'password' => Hash::make('password123')],
            ['name' => 'Timker5', 'email' => 'timker5@gmail.com', 'role'=> 'timker_5', 'password' => Hash::make('password123')],
            ['name' => 'Timker6', 'email' => 'timker6@gmail.com', 'role'=> 'timker_6', 'password' => Hash::make('password123')],
            ['name' => 'Sarpras', 'email' => 'sarpras@gmail.com', 'role'=> 'sarpras', 'password' => Hash::make('password123')],
            ['name' => 'Bmn', 'email' => 'bmn@gmail.com', 'role'=> 'bmn', 'password' => Hash::make('password123')],
        ];

        foreach ($users as $user) {
        User::updateOrCreate(
            ['email' => $user['email']], // cek email
            $user                       // update atau create baru
        );
        }
    }
}
