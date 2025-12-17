<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\Laporan;
use App\Models\Profile;
use App\Models\Honor;
use App\Models\PpkGroup;
use App\Mail\NotifikasiPengajuan;
use App\Helpers\NotifikasiHelper;
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

        // PERSEDIAAN
        if ($user->role === 'persediaan') {
            $pengajuan->persediaan_id = $user->id;
            $pengajuan->persediaan_approved_at = now();
            $pengajuan->persediaan_keterangan = $request->keterangan;
            $pengajuan->status = 'pending_mengetahui';
        }

        // MENGETAHUI (TIMKER ATAU ADUM)
        elseif (str_starts_with($user->role, 'timker_') || $user->role === 'adum') {
            $pengajuan->mengetahui_id = $user->id;
            $pengajuan->mengetahui_jabatan = $user->role;
            $pengajuan->mengetahui_approved_at = now();
            $pengajuan->mengetahui_keterangan = $request->keterangan;
            $pengajuan->status = 'pending_ppk';
        }

        // PPK
        elseif ($user->role === 'ppk') {
            if ($pengajuan->status !== 'pending_ppk') {
                return back()->with('error', 'Belum saatnya PPK approve.');
            }

            $pengajuan->ppk_id = $user->id;
            $pengajuan->ppk_approved_at = now();
            $pengajuan->ppk_keterangan = $request->keterangan;
            $pengajuan->status = 'pending_pengadaan';
        }

        $pengajuan->save();
        // Kirim email ke role berikutnya
        if ($user->role === 'adum') {
            $nextRole = 'ppk';
        } elseif ($user->role === 'ppk') {
            $nextRole = 'pengadaan';
        } elseif (str_starts_with($user->role, 'timker_')) {
            $nextRole = 'adum'; // atau 'ketua_timker' sesuai logika timker
        } else {
            $nextRole = null;
        }

        // Kirim notifikasi kalau ada role tujuan
        if ($nextRole) {
            $pesan = "Pengajuan ID {$pengajuan->id} telah disetujui oleh {$user->role}. Silakan lakukan tindakan selanjutnya.";

            if (app()->environment('local')) {
                // Kalau di local environment, jangan kirim email, cukup log
                \Log::info("Email NOT sent: {$pesan} -> {$nextRole}");
            } else {
                // Kirim email beneran kalau bukan local
                NotifikasiHelper::kirim($pengajuan, $nextRole, $pesan);
            }
        }

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

    public function show($id)
    {
        $pengajuan = Pengajuan::with(['items', 'user', 'mengetahui', 'adum', 'ppk'])
                    ->findOrFail($id);

        $user = auth()->user();

        if(str_starts_with($user->role, 'timker_')) {
            return redirect()->route('timker.dashboard'); // misal route dashboard timker
        } elseif($user->role === 'adum') {
            return redirect()->route('adum.dashboard');
        } elseif($user->role === 'ppk') {
            return redirect()->route('ppk.dashboard');
        } elseif($user->role === 'pengadaan') {
            return redirect()->route('pengadaan.dashboard');
        } elseif($user->role === 'bendahara') {
            return redirect()->route('bendahara.dashboard');
        } else {
            abort(403, 'Akses ditolak.');
        }
    }

}
