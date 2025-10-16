<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\Honorarium;

class KeuanganController extends Controller
{
    public function dashboard()
    {
        // tampilkan hanya pengajuan yang sudah disetujui semua pihak
        $pengajuans = Pengajuan::where('status', 'approved_ppk')->get();
        return view('keuangan.dashboard', compact('pengajuans'));
    }

    public function proses($id)
    {
        $pengajuan = Pengajuan::with('items')->findOrFail($id);

        // hitung total pajak & diterima
        $totalPajak = 0;
        $totalDiterima = 0;
        foreach ($pengajuan->items as $item) {
            $pph21 = $item->pph21 ?? 0;
            $pph22 = $item->pph22 ?? 0;
            $pph23 = $item->pph23 ?? 0;
            $ppn   = $item->ppn ?? 0;

            $pajak = $pph21 + $pph22 + $pph23 + $ppn;
            $totalPajak += $pajak;

            $dibayarkan = $item->jumlah_dana_pengajuan - $pajak;
            $totalDiterima += $dibayarkan;
        }

        if ($pengajuan->jenis_pengajuan === 'honor') {
            return view('keuangan.proses_honorarium', compact('pengajuan'));
        } else {
            return view('keuangan.proses', compact('pengajuan', 'totalPajak', 'totalDiterima'));
        }
    }

    public function simpanHonorarium(Request $request, $id)
    {
        $request->validate([
            'uraian' => 'required|string',
            'jumlah_honor' => 'required|numeric',
            'bulan' => 'required|numeric',
            'no_rekening' => 'required|string',
            'bank' => 'required|string',
        ]);

        $pengajuan = Pengajuan::findOrFail($id);

        // Ambil nama dan jabatan dari pengajuan
        $nama = $pengajuan->nama_pengaju ?? 'Tidak Diketahui';
        $jabatan = $pengajuan->jabatan_pengaju ?? 'Tidak Diketahui';

        // Hitung otomatis
        $total_honor = $request->jumlah_honor * $request->bulan;
        $pph21 = $total_honor * 0.15;
        $jumlah = $total_honor - $pph21;

        // Simpan ke tabel honorarium
        Honorarium::create([
            'pengajuan_id' => $id,
            'nama' => $nama,
            'jabatan' => $jabatan,
            'uraian' => $request->uraian,
            'jumlah_honor' => $request->jumlah_honor,
            'bulan' => $request->bulan,
            'total_honor' => $total_honor,
            'pph21' => $pph_21,
            'jumlah' => $jumlah,
            'no_rekening' => $request->no_rekening,
            'bank' => $request->bank,
            'tanggal' => now(),
        ]);

        // Ubah status pengajuan jadi processed
        $pengajuan->status = 'processed';
        $pengajuan->save();

        return redirect()->route('keuangan.laporan')
            ->with('success', 'Data honorarium berhasil disimpan ke laporan!');
    }

    public function storeProses(Request $request, $id)
    {
        $pengajuan = Pengajuan::with('items')->findOrFail($id);

        // Validasi data yang masuk
        $request->validate([
            'items' => 'required|array',
            'items.*.nama' => 'required|string',
            'items.*.jabatan' => 'required|string',
            'items.*.uraian' => 'required|string',
            'items.*.jumlah_honor' => 'required|numeric',
            'items.*.bulan' => 'required|numeric',
            'items.*.pph_21' => 'required|numeric',
            'items.*.jumlah' => 'required|numeric',
            'items.*.no_rekening' => 'required|string',
            'items.*.bank' => 'required|string',
        ]);

        // Loop semua item dan simpan ke tabel honorarium
        foreach ($request->items as $item) {
            Honorarium::create([
                'pengajuan_id' => $pengajuan->id,
                'nama' => $item['nama'],
                'jabatan' => $item['jabatan'],
                'uraian' => $item['uraian'],
                'jumlah_honor' => $item['jumlah_honor'],
                'bulan' => $item['bulan'],
                'pph_21' => $item['pph_21'],
                'jumlah' => $item['jumlah'],
                'no_rekening' => $item['no_rekening'],
                'bank' => $item['bank'],
            ]);
        }

        // Simpan kode akun kalau diisi
        if ($request->filled('no_akun')) {
            $pengajuan->kode_akun = $request->no_akun;
        }

        // Update status pengajuan
        $pengajuan->status = 'processed';
        $pengajuan->save();

        return redirect()->route('keuangan.laporan')
            ->with('success', 'Data berhasil disimpan dan menunggu tanda tangan ADUM & PPK.');
    }

    public function prosesHonorarium($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        return view('keuangan.proses_honorarium', compact('pengajuan'));
    }

    // Tampilkan tabel laporan
    public function laporan()
    {
        // ambil semua pengajuan yang sudah diproses
        $pengajuans = Pengajuan::whereIn('status', ['approved', 'processed'])->get();
        return view('keuangan.laporan', compact('pengajuans'));
    }

    public function lihatDetail($id)
    {
        $pengajuan = Pengajuan::with('items', 'honorariums')->findOrFail($id);

        return view('keuangan.laporan_detail', compact('pengajuan'));
    }

    public function approveProcess(Request $request, $id)
    {
        $pengajuan = Pengajuan::findOrFail($id);

        if(auth()->user()->role == 'adum') {
            $pengajuan->adum_approved_process = true;
        }

        if(auth()->user()->role == 'ppk') {
            $pengajuan->ppk_approved_process = true;
        }

        $pengajuan->save();

        return redirect()->route('keuangan.laporan_detail', $id)
                        ->with('success', 'Proses keuangan berhasil di-approve.');
    }


}
