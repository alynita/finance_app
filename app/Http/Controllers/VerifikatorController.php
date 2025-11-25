<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PpkGroup;
use App\Models\Honor;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class VerifikatorController extends Controller
{
    // Dashboard ringkasan
    public function dashboard()
    {
        $pendingCount = PpkGroup::where('status', 'ppk_approved')
            ->where('verifikator_approved_process', 0)
            ->count();

        $arsipCount = Honor::whereIn('status', ['pending', 'ppk_approved'])
            ->count();

        return view('verifikator.dashboard', compact('pendingCount', 'arsipCount'));
    }

    // Tampilkan pengajuan untuk proses keuangan (approve)
    public function prosesKeuangan()
    {
        $pengajuans = PpkGroup::with('pengajuan.user', 'items')
            ->where('status', 'ppk_approved')
            ->where('verifikator_approved_process', 0)
            ->get();

        return view('proses.dashboard', compact('pengajuans'));
    }

    // Arsip honor
    public function arsipHonor()
    {
        $perPage = request('perPage', 10);

        $honors = Honor::whereIn('status', ['pending', 'ppk_approved'])
            ->latest() 
            ->paginate($perPage);

        return view('verifikator.arsip_honor', compact('honors'));
    }

    // DETAILHONOR
    public function detailHonor($id)
    {
        $honor = Honor::with(['details', 'adum', 'ppk', 'user'])->findOrFail($id);

        // Ambil user dengan role 'bendahara'
        $bendahara = User::where('role', 'bendahara')->first();

        return view('verifikator.detail_honor', compact('honor', 'bendahara'));
    }

    // Approve proses keuangan
    public function approve($id)
    {
        $group = PpkGroup::findOrFail($id);
        if ($group->status !== 'ppk_approved') {
            return back()->with('error', 'Harus disetujui PPK dulu!');
        }

        $group->verifikator_approved_process = 1;
        $group->verifikator_id = Auth::id();
        $group->verifikator_approved_at = now();
        $group->status = 'approved';
        $group->save();

        if ($group->pengajuan) {
            $group->pengajuan->status = 'approved';
            $group->pengajuan->save();
        }

        return back()->with('success', 'Pengajuan berhasil disetujui verifikator!');
    }

    // Reject proses keuangan
    public function reject($id)
    {
        $group = PpkGroup::findOrFail($id);
        $group->status = 'rejected';
        $group->save();

        if ($group->pengajuan) {
            $group->pengajuan->status = 'rejected';
            $group->pengajuan->save();
        }

        return back()->with('success', 'Pengajuan berhasil ditolak verifikator!');
    }
}
