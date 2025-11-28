<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pengajuan;

class PegawaiController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        // 🔥 Hanya tampilkan pending + rejected (approved disembunyikan)
        $pengajuans = Pengajuan::where('user_id', $user->id)
                        ->where('status', '!=', 'approved')
                        ->get();

        // Hitung status
        $pending = $pengajuans->whereIn('status', [
            'pending_adum',
            'pending_ppk',
            'pending_pengadaan',
            'submitted_keuangan',
            'processed',
            'adum_approved',
            'approve_ppk'
        ])->count();

        // approved tetap dihitung tapi tidak ditampilkan di tabel
        $approved = Pengajuan::where('user_id', $user->id)
                        ->where('status', 'approved')
                        ->count();

        $rejected = Pengajuan::where('user_id', $user->id)
                        ->where('status', 'like', 'rejected%')
                        ->count();

        return view('dashboard.pegawai', compact(
            'user',
            'pengajuans',
            'pending',
            'approved',
            'rejected'
        ));
    }

}
