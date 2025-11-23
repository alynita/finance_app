<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Honor;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class HonorController extends Controller
{
    // DASHBOARD ROLE
    public function dashboard()
    {
        $user = auth()->user();

        switch ($user->role) {
            case 'adum':
                // ADUM lihat honor pending
                $honors = Honor::where('status', 'pending')->get();
                break;

            case 'ppk':
                // PPK lihat honor yang sudah disetujui ADUM
                $honors = Honor::where('status', 'adum_approved')->get();
                break;

            case 'bendahara':
            case 'verifikator':
                // Bendahara & Verifikator lihat honor yang sudah disetujui ADUM
                // dan belum diarsipkan
                $honors = Honor::where('status', 'adum_approved')
                    ->where('arsip', false)
                    ->get();
                break;

            default:
                $honors = collect();
        }

        return view('honor.dashboard', compact('honors', 'user'));
    }

    // APPROVE
    public function approve($id)
    {
        $honor = Honor::findOrFail($id);
        $user = auth()->user();

        switch ($user->role) {
            case 'adum':
                $honor->adum_id = $user->id;
                $honor->adum_approved_at = Carbon::now();
                $honor->status = 'adum_approved';
                $honor->save();

                return back()->with('success', 'Honor berhasil diapprove ADUM!');
            

            case 'ppk':
                if ($honor->status !== 'adum_approved') {
                    return back()->with('error', 'Harus diapprove ADUM dulu!');
                }

                $honor->ppk_id = $user->id;
                $honor->ppk_approved_at = Carbon::now();
                $honor->status = 'ppk_approved';
                $honor->save();

                // 🔥 setelah PPK approve → buka halaman laporan
                return redirect()->route('keuangan.honor_index_laporan', $honor->id);
            

            default:
                return back()->with('error', 'Role tidak memiliki hak approve.');
        }
    }


    // SIMPAN ARSIP (khusus Bendahara & Verifikator)
    public function simpanArsip($id)
    {
        $honor = Honor::findOrFail($id);
        $user = auth()->user();

        if (!in_array($user->role, ['bendahara', 'verifikator'])) {
            return back()->with('error', 'Hanya Bendahara dan Verifikator yang dapat mengarsipkan!');
        }

        if (!in_array($honor->status, ['adum_approved', 'ppk_approved'])) {
            return back()->with('error', 'Honor belum bisa diarsipkan, tunggu proses approval!');
        }

        $honor->arsip = true;
        $honor->save();

        return back()->with('success', 'Honor berhasil disimpan ke arsip!');
    }

    // REJECT
    public function reject($id)
    {
        $honor = Honor::findOrFail($id);
        $honor->status = 'rejected';
        $honor->save();

        return back()->with('success', 'Honor berhasil direject!');
    }

    // DETAIL
    public function detail($id)
    {
        $honor = Honor::findOrFail($id);
        return view('honor.detail', compact('honor'));
    }
    
}
