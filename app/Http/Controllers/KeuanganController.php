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

        if ($pengajuan->jenis_pengajuan === 'honorarium') {
            return view('keuangan.proses_honorarium', compact('pengajuan'));
        } else {
            return view('keuangan.proses', compact('pengajuan', 'totalPajak', 'totalDiterima'));
        }
    }

    public function simpanHonorarium(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'jabatan' => 'required',
            'jumlah_honor' => 'required|numeric',
            'bulan' => 'required|numeric',
            'no_rekening' => 'required',
            'bank' => 'required',
        ]);

        $total_honor = $request->jumlah_honor * $request->bulan;
        $pph21 = $total_honor * 0.15;
        $jumlah = $total_honor - $pph21;

        Honorarium::create([
            'pengajuan_id' => $id,
            'nama' => $request->nama,
            'jabatan' => $request->jabatan,
            'jumlah_honor' => $request->jumlah_honor,
            'bulan' => $request->bulan,
            'total_honor' => $total_honor,
            'pph21' => $pph21,
            'jumlah' => $jumlah,
            'no_rekening' => $request->no_rekening,
            'bank' => $request->bank,
        ]);

        // ubah status pengajuan jadi selesai
        $pengajuan = Pengajuan::findOrFail($id);
        $pengajuan->status = 'approved';
        $pengajuan->save();

        return redirect()->route('keuangan.laporan')->with('success', 'Data honorarium berhasil disimpan!');
    }

    public function storeProses(Request $request, $id)
    {
        $pengajuan = Pengajuan::with('items')->findOrFail($id);

        $jumlah_pengajuan = $request->input('jumlah_pengajuan');
        $pph21_input = $request->input('pph21');
        $uraian_input = $request->input('uraian');
        $no_rekening_input = $request->input('no_rekening');
        $bank_input = $request->input('bank');
        $invoice_input = $request->input('invoice'); // tambahkan ini

        foreach ($pengajuan->items as $index => $item) {
            $jumlah = floatval($jumlah_pengajuan[$index]);
            $pph21_val = floatval($pph21_input[$index] ?? 0);
            $pph22 = 0.015;
            $pph23 = 0.02;
            $ppn = 0.19;

            $item->jumlah_dana_pengajuan = $jumlah;
            $item->uraian = $uraian_input[$index] ?? 'Tidak ada uraian';
            $item->pph21 = $jumlah * $pph21_val;
            $item->pph22 = $jumlah * $pph22;
            $item->pph23 = $jumlah * $pph23;
            $item->ppn = $jumlah * $ppn;
            $item->dibayarkan = $jumlah - ($item->pph21 + $item->pph22 + $item->pph23 + $item->ppn);
            $item->no_rekening = $no_rekening_input[$index] ?? null;
            $item->bank = $bank_input[$index] ?? null;
            $item->invoice = $invoice_input[$index] ?? null; // tambahkan ini

            $item->save();
        }

        if ($request->filled('kode_akun')) {
            $pengajuan->kode_akun = $request->kode_akun;
        }

        $pengajuan->status = 'approved';
        $pengajuan->save();

        return redirect()->route('keuangan.laporan')->with('success', 'Data berhasil disimpan. Menunggu tanda tangan ADUM & PPK.');
    }

    // Tampilkan tabel laporan
    public function laporan()
    {
        // ambil semua pengajuan yang sudah diproses
        $pengajuans = Pengajuan::where('status', 'approved')->get();
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
