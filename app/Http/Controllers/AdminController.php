<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pengajuan;
use App\Models\Kro;

class AdminController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalPengajuan = Pengajuan::count();
        $pending = Pengajuan::where('status', 'pending')->count();
        $approved = Pengajuan::where('status', 'approved')->count();

        $totalKro = Kro::count();

        return view('admin.dashboard', compact('totalUsers', 'totalPengajuan', 'pending', 'approved', 'totalKro',));
    }

    public function users()
    {
        $users = User::all();
        return view('admin.users', compact('users'));
    }

    // Update Role
    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|string'
        ]);

        $user = User::findOrFail($id);
        $user->role = $request->role;
        $user->save();

        return redirect()->back()->with('success', 'Role pengguna berhasil diperbarui!');
    }

    //Reset Password
    public function resetPassword($id)
    {
        $user = User::findOrFail($id);
        $user->password = \Hash::make('password123');
        $user->save();

        return back()->with('success', 'Password berhasil direset ke "password123".');
    }

}
