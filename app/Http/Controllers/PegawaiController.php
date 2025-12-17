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

        // Tampilkan SEMUA kecuali approved (biar fokus proses)
        $pengajuans = Pengajuan::where('user_id', $user->id)
            ->where('status', '!=', 'approved')
            ->latest()
            ->get();

        // 🔵 Pending = semua yang masih diproses
        $pending = Pengajuan::where('user_id', $user->id)
            ->whereIn('status', [
                'menunggu_persediaan',
                'pending_adum',
                'pending_ppk',
                'pending_pengadaan',
                'submitted_keuangan',
            ])->count();

        // 🟢 Approved
        $approved = Pengajuan::where('user_id', $user->id)
            ->where('status', 'approved')
            ->count();

        // 🔴 Rejected
        $rejected = Pengajuan::where('user_id', $user->id)
            ->where('status', 'rejected')
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
