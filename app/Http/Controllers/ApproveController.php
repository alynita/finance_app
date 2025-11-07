<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\Laporan;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;

class ApproveController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        // Cek role timker dinamis (timker1 - timker6)
        if (str_starts_with($user->role, 'timker_')) {
            $pengajuans = Pengajuan::with('items', 'user', 'mengetahui')
                ->where('status', 'pending_' . $user->role)
                ->get();

        // Role adum
        } elseif ($user->role === 'adum') {
            $pengajuans = Pengajuan::with('items', 'user')
                ->where('status', 'pending_adum')
                ->get();

        // Role ppk
        } elseif ($user->role === 'ppk') {
            $pengajuans = Pengajuan::with('items', 'user')
                ->where('status', 'pending_ppk')
                ->get();

        // Kalau bukan salah satu role di atas
        } else {
            $pengajuans = collect();
        }

        return view('approve.dashboard', compact('pengajuans', 'user'));
    }

    public function pengajuan()
    {
        $pengajuans = Pengajuan::with('items', 'user', 'mengetahui')->get();
        return view('approve.pengajuan', compact('pengajuans'));
    }

    public function approve(Request $request, $id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        $user = auth()->user();

        if(str_starts_with($user->role, 'timker_')) {
            $pengajuan->mengetahui_id = $user->id;
            $pengajuan->mengetahui_jabatan = $user->role; // simpan role yang approve
            $pengajuan->mengetahui_approved_at = now();
            $pengajuan->mengetahui_keterangan = $request->keterangan;
            $pengajuan->status = 'pending_ppk';
        } elseif($user->role === 'adum') {
            $pengajuan->adum_id = $user->id;
            $pengajuan->adum_approved_at = now();
            $pengajuan->adum_keterangan = $request->keterangan;
            $pengajuan->status = 'pending_ppk';
        } elseif($user->role === 'ppk') {
            if($pengajuan->status !== 'pending_ppk') {
                return back()->with('error', 'Harus di-approve ADUM dulu!');
            }
            $pengajuan->ppk_id = $user->id;
            $pengajuan->ppk_approved_at = now();
            $pengajuan->ppk_keterangan = $request->keterangan;
            $pengajuan->status = 'pending_pengadaan';
        } else {
            return back()->with('error', 'Role tidak memiliki hak approve.');
        }

        $pengajuan->save();
        return back()->with('success', 'Pengajuan berhasil di-approve!');
    }

    public function reject(Request $request, $id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        $user = auth()->user();

        if(str_starts_with($user->role, 'timker_')) {
            $pengajuan->status = 'rejected_' . $user->role;
            $pengajuan->mengetahui_keterangan = $request->keterangan;
        } elseif($user->role === 'adum') {
            $pengajuan->status = 'rejected_adum';
            $pengajuan->adum_id = $user->id;
            $pengajuan->adum_keterangan = $request->keterangan;
        } elseif($user->role === 'ppk') {
            $pengajuan->status = 'rejected_ppk';
            $pengajuan->ppk_id = $user->id;
            $pengajuan->ppk_keterangan = $request->keterangan;
        }

        $pengajuan->save();
        return back()->with('error', 'Pengajuan ditolak!');
    }

    public function laporan(Request $request)
    {
        $query = Pengajuan::with('user')
            ->where('status', 'approve'); // final approve

        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->periode) {
            $query->whereMonth('created_at', date('m', strtotime($request->periode)))
                ->whereYear('created_at', date('Y', strtotime($request->periode)));
        }

        $pengajuans = $query->orderBy('created_at', 'desc')->get();

        return view('approve.laporan', compact('pengajuans'));
    }
}
