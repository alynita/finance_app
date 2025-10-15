<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengajuan;
use PDF;

class PengadaanController extends Controller
{
    // Dashboard pengadaan - hanya yang belum diarsip
    public function dashboard()
    {
        $pengajuans = Pengajuan::with('items', 'user')
            ->where('is_arsip', false)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pengadaan.dashboard', compact('pengajuans'));
    }

    // Simpan arsip
    public function simpanArsip($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        $pengajuan->is_arsip = true;
        $pengajuan->save();

        return redirect()->route('pengadaan.view-arsip')
            ->with('success', 'Pengajuan berhasil diarsipkan.');
    }

    // View Arsip
    public function viewArsip()
    {
        $pengajuans = Pengajuan::with('items', 'user')
            ->where('is_arsip', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pengadaan.arsip', compact('pengajuans'));
    }

    // Download PDF detail pengajuan
    public function downloadPDF($id)
    {
        $pengajuan = Pengajuan::with(['items', 'user', 'adum', 'ppk'])->findOrFail($id);

        // load view PDF, gunakan landscape agar tabel lebih lebar
        $pdf = PDF::loadView('pengadaan.pdf_detail', compact('pengajuan'))
                    ->setPaper('a4', 'landscape');

        // stream ke browser atau download langsung
        return $pdf->download('Laporan_' . $pengajuan->nama_kegiatan . '.pdf');
    }
}
