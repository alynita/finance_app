<?php

namespace App\Helpers;

use App\Mail\NotifikasiPengajuan;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class NotifikasiHelper
{
    public static function kirim($pengajuan, $role, $pesan)
    {
        // Ambil email user sesuai role
        $emails = User::where('role', $role)->pluck('email');

        if ($emails->isEmpty()) return;

        // URL untuk tombol "Lihat Pengajuan"
        $url = route('pegawai.pengajuan.show', $pengajuan->id);

        // Kirim email
        foreach ($emails as $email) {
            Mail::to($email)->send(new NotifikasiPengajuan($pesan, $url));
        }
    }
}
