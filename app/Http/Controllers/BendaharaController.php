<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\PpkGroup;
use App\Models\Honor;
use Barryvdh\DomPDF\Facade\Pdf;

class BendaharaController extends Controller
{
    // ====== DASHBOARD BENDAHARA ======
    public function dashboard()
    {
        // 🔹 Ambil semua laporan pengadaan/pembelian barang
        $laporanBarang = PpkGroup::with(['pengajuan.user', 'items'])
            ->whereIn('status', [
                'processed',
                'adum_approved',
                'ppk_approved',
                'approved',
                'completed'
            ])
            ->get();

        // 🔹 Ambil semua laporan honor yang sudah masuk ke Bendahara
        $laporanHonor = Honor::with('user')
            ->whereIn('status', [
                'adum_approved',
                'ppk_approved',
                'approved',
                'completed'
            ])
            ->get();

        // 🔹 Gabungkan dua jenis laporan jadi satu koleksi untuk ditampilkan di tabel
        $laporans = collect([
            ...$laporanBarang,
            ...$laporanHonor
        ]);

        // 🔹 Hitung total pengadaan & honor
        $totalPengadaan = $laporanBarang->count();
        $totalHonor     = $laporanHonor->count();

        // 🔹 Hitung total arsip & menunggu arsip
        $totalArsip = Pengajuan::where('arsip', true)->count()
                    + Honor::where('arsip', true)->count();

        $menungguArsip = Pengajuan::where('arsip', false)
                            ->whereIn('status', ['adum_approved','ppk_approved','approved'])
                            ->count()
                        + Honor::where('arsip', false)
                            ->whereIn('status', ['adum_approved','ppk_approved','approved'])
                            ->count();

        // 🔹 Kirim semua ke view
        return view('bendahara.dashboard', compact(
            'laporans',
            'totalPengadaan',
            'totalHonor',
            'totalArsip',
            'menungguArsip'
        ));
    }

    // ====== DETAIL LAPORAN ======
    public function show($groupId)
    {
        $group = PpkGroup::with([
            'pengajuan.user',
            'items',
            'pengajuan.adum',
            'pengajuan.ppk',
            'pengajuan.verifikator'
        ])->findOrFail($groupId);

        $pengajuan = $group->pengajuan;

        return view('bendahara.detail_laporan', compact('group','pengajuan'));
    }

    public function showHonor($id)
    {
        $honor = \App\Models\Honor::findOrFail($id);
        return view('bendahara.detail_honor', compact('honor'));
    }

    // ====== SIMPAN ARSIP ======
    public function simpanArsipPengadaan($id)
    {
        $group = PpkGroup::findOrFail($id);

        if (
            $group->adum_approved_process == 1 &&
            $group->ppk_approved_process == 1 &&
            $group->verifikator_approved_process == 1
        ) {
            $group->arsip = 1;
            $group->save();

            return back()->with('success', 'Data pengadaan berhasil diarsipkan.');
        }

        return back()->with('error', 'Approval pengadaan belum lengkap.');
    }

    public function simpanArsipHonor($id)
    {
        $honor = Honor::findOrFail($id);

        if (!is_null($honor->adum_approved_at) && !is_null($honor->ppk_approved_at)) {
            $honor->arsip = 1;
            $honor->save();

            return back()->with('success', 'Data honor berhasil diarsipkan.');
        }

        return back()->with('error', 'Approval honor belum lengkap.');
    }

    // ====== DOWNLOAD PDF ======
    public function downloadPDF($id)
    {
        $pengajuan = Pengajuan::with(['items','honorariums','adum','ppk','verifikator'])
            ->findOrFail($id);

        $pdf = Pdf::loadView('bendahara.detail_laporan_pdf', compact('pengajuan'))
            ->setPaper('A4', 'landscape');

        return $pdf->download('laporan_'.$pengajuan->id.'.pdf');
    }

    // ====== HALAMAN ARSIP ======
    public function arsip()
    {
        $laporans = Pengajuan::where('arsip', true)->get()
                    ->merge(Honor::where('arsip', true)->get());

        return view('bendahara.arsip', compact('laporans'));
    }
}
