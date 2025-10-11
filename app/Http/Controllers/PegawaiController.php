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
        $pending = $pengajuans->where('status', 'pending')->count();
        $approved = $pengajuans->where('status', 'approved')->count();
        $rejected = $pengajuans->where('status', 'rejected')->count();

        return view('dashboard.pegawai', compact('user', 'pengajuans', 'pending', 'approved', 'rejected'));
    }
}
