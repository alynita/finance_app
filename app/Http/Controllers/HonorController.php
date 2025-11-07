<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Honor;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class HonorController extends Controller
{
    // Dashboard approval berdasarkan role
    public function dashboard()
    {
        $user = auth()->user();

        if ($user->role === 'adum') {
            // ADUM lihat honor yang masih pending
            $honors = Honor::where('status', 'pending')->get();
        } elseif ($user->role === 'ppk') {
            // PPK lihat honor yang sudah diapprove ADUM
            $honors = Honor::where('status', 'adum_approved')->get();
        } else {
            // Role lain kosong
            $honors = collect();
        }

        return view('honor.dashboard', compact('honors', 'user'));
    }

    // Approve honor
    public function approve($id)
    {
        $honor = Honor::findOrFail($id);
        $user = auth()->user();

        switch ($user->role) {
            case 'adum':
                $honor->adum_id = $user->id;
                $honor->adum_approved_at = Carbon::now();
                $honor->status = 'adum_approved';
                break;

            case 'ppk':
                if ($honor->status !== 'adum_approved') {
                    return back()->with('error', 'Harus diapprove ADUM dulu!');
                }
                $honor->ppk_id = $user->id;
                $honor->ppk_approved_at = Carbon::now();
                $honor->status = 'ppk_approved';
                break;

            default:
                return back()->with('error', 'Role tidak memiliki hak approve.');
        }

        $honor->save();

        return back()->with('success', 'Honor berhasil diapprove!');
    }

    // Reject honor
    public function reject($id)
    {
        $honor = Honor::findOrFail($id);
        $honor->status = 'rejected';
        $honor->save();

        return back()->with('success', 'Honor berhasil direject!');
    }

    // Detail honor
    public function detail($id)
    {
        $honor = Honor::findOrFail($id);
        return view('honor.detail', compact('honor'));
    }
}
