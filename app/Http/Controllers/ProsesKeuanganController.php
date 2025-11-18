<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\PpkGroup;
use Illuminate\Support\Facades\Auth;

class ProsesKeuanganController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        if ($user->role === 'adum') {
            $pengajuans = PpkGroup::with('pengajuan.user', 'items')
                ->where('status', 'processed')
                ->where('adum_approved_process', 0)
                ->get();
        } elseif ($user->role === 'ppk') {
            $pengajuans = PpkGroup::with('pengajuan.user', 'items')
                ->where('status', 'adum_approved')
                ->where('adum_approved_process', 1)
                ->where('ppk_approved_process', 0)
                ->get();
        } elseif ($user->role === 'verifikator') {
            $pengajuans = PpkGroup::with('pengajuan.user', 'items')
                ->where('status', 'ppk_approved')
                ->where('adum_approved_process', 1)
                ->where('ppk_approved_process', 1)
                ->where('verifikator_approved_process', 0)
                ->get();
        } else {
            $pengajuans = collect();
        }

        return view('proses_keuangan.dashboard', compact('pengajuans', 'user'));
    }

    public function approve($id)
    {
        // Cari PpkGroup berdasarkan ID yang dikirim dari blade
        $group = PpkGroup::with('pengajuan')->findOrFail($id);
        $user = auth()->user();

        switch ($user->role) {
            case 'adum':
                $group->adum_approved_process = 1;
                $group->adum_id = $user->id;
                $group->adum_approved_at = now(); // optional: simpan timestamp
                $group->status = 'adum_approved';
                break;

            case 'ppk':
                if ($group->adum_approved_process != 1) {
                    return back()->with('error', 'Harus diapprove ADUM dulu!');
                }
                $group->ppk_approved_process = 1;
                $group->ppk_id = $user->id;
                $group->ppk_approved_at = now(); // optional
                $group->status = 'ppk_approved';
                break;

            case 'verifikator':
                if ($group->adum_approved_process != 1 || $group->ppk_approved_process != 1) {
                    return back()->with('error', 'Harus disetujui ADUM dan PPK dulu!');
                }
                $group->verifikator_approved_process = 1;
                $group->verifikator_id = $user->id;
                $group->verifikator_approved_at = now(); // optional
                $group->status = 'approved';
                break;

            default:
                return back()->with('error', 'Role tidak memiliki hak approve.');
        }

        $group->save();

        return back()->with('success', 'Pengajuan berhasil diapprove!');
    }

    public function reject($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        $pengajuan->status = 'rejected';
        $pengajuan->save();

        return redirect()->back()->with('success', 'Pengajuan berhasil direject.');
    }


}
