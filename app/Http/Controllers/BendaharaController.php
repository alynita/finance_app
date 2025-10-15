<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengajuan;
use Barryvdh\DomPDF\Facade\Pdf;

class BendaharaController extends Controller
{
    // Dashboard pengajuan aktif
    public function dashboard()
    {
        $laporans = Pengajuan::where('status', 'processed')
            ->where('arsip', false)
            ->get();

        return view('bendahara.dashboard', compact('laporans'));
    }

    // Detail laporan
    public function show($id)
    {
        $pengajuan = Pengajuan::with(['items', 'honorariums', 'adum', 'ppk', 'verifikator'])->findOrFail($id);
        return view('bendahara.detail_laporan', compact('pengajuan'));
    }

    // Simpan arsip
    public function simpanArsip($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);

        if($pengajuan->ppk_approved_process && $pengajuan->adum_approved_process && $pengajuan->verifikator_approved_process) {
            $pengajuan->arsip = true;
            $pengajuan->save();

            return redirect()->route('bendahara.dashboard')->with('success', 'Laporan berhasil diarsipkan.');
        }

        return redirect()->route('bendahara.laporan.show', $id)
                            ->with('error', 'Semua approval harus selesai sebelum arsip.');
    }

    // Download PDF
    public function downloadPDF($id)
    {
        $pengajuan = Pengajuan::with(['items','honorariums','adum','ppk','verifikator'])->findOrFail($id);

        $pdf = Pdf::loadView('bendahara.detail_laporan_pdf', compact('pengajuan'))
                ->setPaper('A4', 'landscape');

        return $pdf->download('laporan_'.$pengajuan->id.'.pdf');
    }
    
    // Halaman arsip
    public function arsip()
    {
        $laporans = Pengajuan::where('arsip', true)->get();
        return view('bendahara.arsip', compact('laporans'));
    }
}
