<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pengajuan;

class PegawaiController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user(); // data pegawai yg login

        // Ambil semua pengajuan milik user ini
        $pengajuans = Pengajuan::where('user_id', $user->id)->get();

        // Hitung status
        $pending = $pengajuans->whereIn('status', ['pending_adum', 'pending_ppk'])->count();
        $approved = $pengajuans->where('status', 'approved')->count();
        $rejected = $pengajuans->filter(fn($p) => str_starts_with($p->status,'rejected'))->count();

        return view('dashboard.pegawai', compact('user', 'pengajuans', 'pending', 'approved', 'rejected'));
    }
}
