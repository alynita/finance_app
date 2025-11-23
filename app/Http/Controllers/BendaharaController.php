<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\PpkGroup;
use App\Models\Honor;
use App\Models\HonorDetail;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;

class BendaharaController extends Controller
{
    // ================= DASHBOARD =================
    public function dashboard()
    {
        // Total Pengadaan & Kerusakan YANG SUDAH PROCESSED & BELUM DIARSIPKAN
        $totalPengadaan = \App\Models\PpkGroup::whereIn('status', ['processed', 'approved'])
                            ->where('arsip', 0)
                            ->count();

        // Total Honor YANG SUDAH PROCESSED & BELUM DIARSIPKAN
        $totalHonor = \App\Models\Honor::whereIn('status', ['pending', 'ppk_approved'])
                        ->where('arsip', 0)
                        ->count();

        // Total Arsip (yang sudah diarsipkan)
        $totalArsip =
            \App\Models\PpkGroup::where('arsip', 1)->count() +
            \App\Models\Honor::where('arsip', 1)->count();

        // Menunggu Arsip (processed/approved dan belum diarsipkan)
        $menungguArsip =
            \App\Models\PpkGroup::whereIn('status', ['processed', 'approved'])
                ->where('arsip', 0)
                ->count() +
            \App\Models\Honor::whereIn('status', ['pending', 'ppk_approved'])
                ->where('arsip', 0)
                ->count();

        // Laporan terakhir (hanya yg belum diarsipkan)
        $laporans = collect()
            ->merge(\App\Models\PpkGroup::whereIn('status', ['processed', 'approved'])
                ->where('arsip', 0)
                ->latest()->take(10)->get())
            ->merge(\App\Models\Honor::whereIn('status', ['pending', 'ppk_approved'])
                ->where('arsip', 0)
                ->latest()->take(10)->get())
            ->sortByDesc('created_at')
            ->values();

        return view('bendahara.dashboard', compact(
            'totalPengadaan',
            'totalHonor',
            'totalArsip',
            'menungguArsip',
            'laporans'
        ));
    }

    
    // =============== DETAIL LAPORAN ===============
    public function show($groupId)
    {
        $group = PpkGroup::with([
            'pengajuan.user',
            'items',
            'pengajuan.adum',
            'pengajuan.ppk',
            'pengajuan.verifikator'
        ])->findOrFail($groupId);

        return view('bendahara.detail_laporan', compact('group'));
    }

    public function showHonor($id)
    {
        $honor = Honor::findOrFail($id);
        // Ambil bendahara
        $bendahara = User::where('role', 'bendahara')->first();

        return view('bendahara.detail_honor', compact('honor', 'bendahara'));
    }

    // ================= SIMPAN ARSIP =================
    public function arsipPengadaan($id)
    {
        $pengadaan = PpkGroup::findOrFail($id);
        $pengadaan->arsip = 1;
        $pengadaan->save();

        return redirect()->route('bendahara.arsip.pengadaan.list')
            ->with('success', 'Pengadaan berhasil diarsipkan!');
    }

    public function arsipKerusakan($id)
    {
        $kerusakan = PpkGroup::findOrFail($id);
        $kerusakan->arsip = 1;
        $kerusakan->save();

        return redirect()->route('bendahara.arsip.kerusakan.list')
            ->with('success', 'Kerusakan berhasil diarsipkan!');
    }

    public function arsipHonor($id)
    {
        $honor = Honor::findOrFail($id);
        $honor->arsip = 1;
        $honor->save();

        return redirect()->route('bendahara.arsip.honor.list')
            ->with('success', 'Honor berhasil diarsipkan!');
    }

    // ================= LIST ARSIP =================
    public function arsipPengadaanList()
    {
        $perPage = request('perPage', 10);

        $pengadaans = PpkGroup::with('pengajuan')
            ->where('arsip', 1)
            ->where('jenis', 'pengadaan')
            ->paginate($perPage); // jangan pakai ->get()

        return view('bendahara.arsip.pengadaan', compact('pengadaans'));
    }

    public function arsipKerusakanList()
    {
        $kerusakans = PpkGroup::with('pengajuan')
            ->where('arsip', 1)
            ->where('jenis', 'kerusakan')
            ->get();

        return view('bendahara.arsip.kerusakan', compact('kerusakans'));
    }

    public function arsipHonorList()
    {
        $perPage = request('perPage', 10);

        $honors = Honor::where('arsip', 1)->paginate($perPage);

        return view('bendahara.arsip.honor', compact('honors'));
    }

    // ================= DOWNLOAD PDF HONOR =================
    public function downloadHonorPDF($id)
    {
        // Ambil honor beserta relasinya
        $honors = Honor::with(['details', 'adum', 'ppk'])->findOrFail($id);

        // Ambil bendahara
        $bendahara = User::where('role', 'bendahara')->first();

        $pdf = Pdf::loadView('bendahara.honor_pdf', compact('honors', 'bendahara'))
            ->setPaper('A4', 'landscape');

        return $pdf->download('honor_'.$honors->id.'.pdf');
    }

    // ================= DOWNLOAD PDF PENGADAAN =================
    public function downloadPengadaanPdf($id)
    {
        $group = PpkGroup::with(['pengajuan','items','adum','ppk','verifikator'])
            ->findOrFail($id);

        $pdf = Pdf::loadView('bendahara.pengadaan_pdf', compact('group'))
            ->setPaper('A4', 'landscape');

        return $pdf->download('pengadaan_'.$group->id.'.pdf');
    }

}
