<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\Laporan;
use App\Models\Profile;
use App\Models\Honor;
use App\Models\PpkGroup;
use Illuminate\Support\Facades\Auth;

class ApproveController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        // Default
        $totalPending = $totalApproved = $totalRejected = 0;
        $pendingPembelian = $pendingProsesKeuangan = $pendingHonor = 0;

        // Ambil pengajuan sesuai role
        if (str_starts_with($user->role, 'timker_')) {
            $pengajuans = Pengajuan::with('items', 'user', 'mengetahui')
                ->where('status', 'pending_' . $user->role)
                ->get();

            // Total pending / approved / rejected
            $totalPending = $pengajuans->count();
            $totalApproved = Pengajuan::where('status', 'approved')->count(); // setelah ADUM approve
            $totalRejected = Pengajuan::where('status', 'rejected_adum')->count();


        } elseif ($user->role === 'adum') {
            $pengajuans = Pengajuan::with('items', 'user')
                ->where('status', 'pending_adum')
                ->get();

            // Total pending / approved / rejected
            $totalPending = $pengajuans->count();
            $totalApproved = Pengajuan::where('status', 'pending_ppk')->count(); // setelah ADUM approve
            $totalRejected = Pengajuan::where('status', 'rejected_adum')->count();

            // Per kategori (ambil dari DB supaya Proses Keuangan juga muncul)
            $pendingPembelian = Pengajuan::whereIn('jenis_pengajuan', ['pembelian', 'kerusakan'])
                ->whereIn('status', ['pending_adum', 'pending_ppk'])
                ->count();

            $pendingProsesKeuangan = PpkGroup::where('status', 'processed')
                ->count();

            $pendingHonor = Honor::where('status','pending')
                ->count();

        } elseif ($user->role === 'ppk') {
            $pengajuans = Pengajuan::with('items', 'user')
                ->where('status', 'pending_ppk')
                ->get();

            // Total pengajuan untuk card
            $totalPending = $pengajuans->count();
            $totalApproved = Pengajuan::where('status', 'pending_pengadaan')->count(); // setelah PPK approve
            $totalRejected = Pengajuan::where('status', 'rejected_ppk')->count();

            // Kalau mau bisa tambahkan kategori juga di sini untuk PPK
        } else {
            $pengajuans = collect();
        }

        return view('approve.dashboard', compact(
            'pengajuans',
            'user',
            'totalPending',
            'totalApproved',
            'totalRejected',
            'pendingPembelian',
            'pendingProsesKeuangan',
            'pendingHonor'
        ));
    }

    public function pengajuan()
    {
        $user = auth()->user();

        if (str_starts_with($user->role, 'timker_')) {

            $perPage = request('perPage', 10);

            // Arsip khusus timker yang bersangkutan
            $pengajuans = Pengajuan::with('items', 'user', 'mengetahui')
                ->where('mengetahui_jabatan', $user->role)
                ->latest()
                ->paginate($perPage);

        } elseif ($user->role === 'adum') {

            $perPage = request('perPage', 10);

            // Arsip khusus ADUM
            $pengajuans = Pengajuan::with('items', 'user', 'mengetahui')
                ->where('mengetahui_jabatan', 'adum')
                ->latest()
                ->paginate($perPage);
        } else {
            $pengajuans = collect();
        }

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

    public function pengajuanKategori($kategori)
    {
        $user = auth()->user();

        // Ambil pengajuan sesuai kategori
        $query = Pengajuan::with('user');

        if ($kategori === 'pengadaan') {
            $query->whereIn('jenis_pengajuan', ['pembelian', 'pengadaan', 'kerusakan']);
        } elseif ($kategori === 'proses_keuangan') {
            $query->where('jenis_pengajuan', 'proses_keuangan');
        } elseif ($kategori === 'honor') {
            $query->where('jenis_pengajuan', 'honor');
        } else {
            abort(404);
        }

        $pengajuans = $query->get();

        return view('approve.daftar_kategori', compact('pengajuans', 'kategori', 'user'));
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
