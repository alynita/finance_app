<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pengajuan;
use App\Models\KroAccount;

class AdminController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalPengajuan = Pengajuan::count();
        $pending = Pengajuan::where('status', 'pending')->count();
        $approved = Pengajuan::where('status', 'approved')->count();

        $totalKro = \DB::table('kro_accounts')->count();

        return view('admin.dashboard', compact('totalUsers', 'totalPengajuan', 'pending', 'approved', 'totalKro'));
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

    // Tampilkan list KRO
    public function kroIndex()
    {
        $kros = KroAccount::all();
        return view('admin.kro.index', compact('kros'));
    }

    // Simpan KRO baru
    public function kroStore(Request $request)
    {
        $request->validate([
            'nama_kro' => 'required|string',
            'kode_akun' => 'required|string|unique:kro_accounts,kode_akun',
        ]);

        KroAccount::create([
            'nama_kro' => $request->nama_kro,
            'kode_akun' => $request->kode_akun,
        ]);

        return redirect()->back()->with('success', 'KRO berhasil ditambahkan!');
    }

    // Update KRO
    public function kroUpdate(Request $request, $id)
    {
        $request->validate([
            'nama_kro' => 'required|string',
            'kode_akun' => 'required|string|unique:kro_accounts,kode_akun,' . $id,
        ]);

        $kro = KroAccount::findOrFail($id);
        $kro->nama_kro = $request->nama_kro;
        $kro->kode_akun = $request->kode_akun;
        $kro->save();

        return redirect()->back()->with('success', 'KRO berhasil diperbarui!');
    }

    // Hapus KRO
    public function kroDelete($id)
    {
        $kro = KroAccount::findOrFail($id);
        $kro->delete();

        return redirect()->back()->with('success', 'KRO berhasil dihapus!');
    }

}
