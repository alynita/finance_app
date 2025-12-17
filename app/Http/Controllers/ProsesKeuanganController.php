<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\PpkGroup;
use Illuminate\Support\Facades\Auth;
use App\Helpers\NotifikasiHelper;

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
        $group = PpkGroup::with('pengajuan')->findOrFail($id);
        $user = auth()->user();
        $nextRole = null; // role tujuan notif

        switch ($user->role) {
            case 'adum':
                $group->adum_approved_process = 1;
                $group->adum_id = $user->id;
                $group->adum_approved_at = now();
                $group->status = 'adum_approved';

                $nextRole = 'ppk'; // notif ke PPK
                break;

            case 'ppk':
                if ($group->adum_approved_process != 1) {
                    return back()->with('error', 'Harus diapprove ADUM dulu!');
                }
                $group->ppk_approved_process = 1;
                $group->ppk_id = $user->id;
                $group->ppk_approved_at = now();
                $group->status = 'ppk_approved';

                $nextRole = 'pengadaan'; // notif ke Pengadaan
                break;

            case 'verifikator':
                if ($group->adum_approved_process != 1 || $group->ppk_approved_process != 1) {
                    return back()->with('error', 'Harus disetujui ADUM dan PPK dulu!');
                }
                $group->verifikator_approved_process = 1;
                $group->verifikator_id = $user->id;
                $group->verifikator_approved_at = now();
                $group->status = 'approved';

                $nextRole = null; // selesai, notif bisa optional ke ADUM/Bendahara
                break;

            default:
                return back()->with('error', 'Role tidak memiliki hak approve.');
        }

        $group->save();

        // 🔥 Update status pengajuan agar sama dengan grup
        if ($group->pengajuan) {
            $group->pengajuan->status = $group->status;
            $group->pengajuan->save();
        }

        // 🔥 Kirim notif ke role berikutnya
        if ($nextRole && $group->pengajuan) {
            $pesan = "Pengajuan ID {$group->pengajuan->id} telah disetujui oleh {$user->role}. Silakan lakukan tindakan selanjutnya.";

            if (app()->environment('local')) {
                \Log::info("Email NOT sent: {$pesan} -> {$nextRole}");
            } else {
                NotifikasiHelper::kirim($group->pengajuan, $nextRole, $pesan);
            }
        }

        // 🔥 Opsional: notif ke Bendahara saat ADUM approve
        if ($user->role === 'adum' && $group->pengajuan) {
            $pesanBendahara = "Pengajuan ID {$group->pengajuan->id} masuk arsip, silakan cek data.";

            if (app()->environment('local')) {
                \Log::info("Email NOT sent: {$pesanBendahara} -> bendahara");
            } else {
                NotifikasiHelper::kirim($group->pengajuan, 'bendahara', $pesanBendahara);
            }
        }

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
