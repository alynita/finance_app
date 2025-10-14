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

        if ($user->role === 'adum') {
            $pengajuans = Pengajuan::with('items', 'user')
                ->where('status', 'pending_adum')
                ->get();
        } elseif ($user->role === 'ppk') {
            $pengajuans = Pengajuan::with('items', 'user')
                ->where('status', 'pending_ppk')
                ->get();
        } else {
            $pengajuans = collect();
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
        $user = auth()->user();

        switch ($user->role) {
            case 'adum':
                if ($pengajuan->status !== 'pending_adum') {
                    return back()->with('error', 'Harus di-approve PJ dulu!');
                }
                $pengajuan->adum_id = $user->id;
                $pengajuan->adum_approved_at = now();
                $pengajuan->status = 'pending_ppk';
                break;

            case 'ppk':
                if ($pengajuan->status !== 'pending_ppk') {
                    return back()->with('error', 'Harus di-approve ADUM dulu!');
                }
                $pengajuan->ppk_id = $user->id;
                $pengajuan->ppk_approved_at = now();
                $pengajuan->status = 'approved';
                break;
                
                return redirect()->route('adum.laporan')->with('success', 'Pengajuan berhasil di-approve!');
            default:
                return back()->with('error', 'Role tidak memiliki hak approve.');
        }

        $pengajuan->save();
        return back()->with('success', 'Pengajuan berhasil di-approve!');
    }

    public function reject($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        $user = auth()->user();

        switch ($user->role) {
            case 'adum':
                $pengajuan->status = 'rejected_adum';
                $pengajuan->adum_id = $user->id;
                break;
            case 'ppk':
                $pengajuan->status = 'rejected_ppk';
                $pengajuan->ppk_id = $user->id;
                break;
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