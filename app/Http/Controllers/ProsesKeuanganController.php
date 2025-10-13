<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengajuan;
use Illuminate\Support\Facades\Auth;

class ProsesKeuanganController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        if($user->role === 'adum') {
            $pengajuans = Pengajuan::with('items', 'user')
                ->where('status', 'processed')
                ->where('adum_approved_process', 0)
                ->get();
        } elseif($user->role === 'ppk') {
            $pengajuans = Pengajuan::with('items', 'user')
                ->where('status', 'processed')
                ->where('adum_approved_process', 1)
                ->where('ppk_approved_process', 0)
                ->get();
        } else {
            $pengajuans = collect();
        }

        return view('proses_keuangan.dashboard', compact('pengajuans', 'user'));
    }

    public function approve($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        $user = auth()->user();

        if($user->role === 'adum') {
            $pengajuan->adum_approved_process = 1;
        } elseif($user->role === 'ppk') {
            if($pengajuan->adum_approved_process != 1) {
                return back()->with('error', 'Harus diapprove ADUM dulu!');
            }
            $pengajuan->ppk_approved_process = 1;
            $pengajuan->status = 'approved'; // Final approve
        } else {
            return back()->with('error', 'Role tidak memiliki hak approve.');
        }

        $pengajuan->save();
        return back()->with('success', 'Pengajuan berhasil diapprove!');
    }
}
