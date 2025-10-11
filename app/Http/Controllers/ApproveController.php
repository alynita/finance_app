<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\Laporan;
use App\Models\Profile;

class ApproveController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        if($user->role == 'adum'){
            $pengajuans = Pengajuan::with('items', 'user')
                ->where('status', 'pending_adum')
                ->get();
        } elseif($user->role == 'ppk'){
            $pengajuans = Pengajuan::with('items', 'user')
                ->where('status', 'pending_ppk')
                ->get();
        }

        return view('approve.dashboard', compact('pengajuans', 'user'));
    }

    public function pengajuan()
    {
        $pengajuans = Pengajuan::with('items', 'user')->get();
        return view('approve.pengajuan', compact('pengajuans'));
    }

    public function approve($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        $userRole = auth()->user()->role;

        if ($userRole === 'adum') {
            $pengajuan->status = 'pending_ppk'; // lanjut ke PPK
            $pengajuan->approved_by = 'adum';
        } elseif ($userRole === 'ppk') {
            $pengajuan->status = 'approve'; // final approve
            $pengajuan->approved_by = 'ppk';
        }

        $pengajuan->approved_at = now();
        $pengajuan->save();

        return redirect()->back()->with('success', 'Pengajuan berhasil diapprove!');
    }

    public function reject($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        $pengajuan->status = 'reject';
        $pengajuan->approved_by = auth()->user()->role;
        $pengajuan->approved_at = now();
        $pengajuan->save();

        return redirect()->back()->with('error', 'Pengajuan ditolak.');
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