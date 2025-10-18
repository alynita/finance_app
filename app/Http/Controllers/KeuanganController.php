<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\PengajuanItem;
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
            'items.*.nama' => 'required|string',
            'items.*.jabatan' => 'required|string',
            'items.*.uraian' => 'required|string',
            'items.*.jumlah_honor' => 'required|numeric',
            'items.*.bulan' => 'required|numeric',
            'items.*.no_rekening' => 'required|string',
            'items.*.bank' => 'required|string',
        ]);

        $pengajuan = Pengajuan::findOrFail($id);

         // Simpan kode akun di pengajuan
        $pengajuan->kode_akun = $request->kode_akun;
        $pengajuan->save();

        // Hapus honorarium lama (opsional)
        $pengajuan->honorariums()->delete();

        foreach ($request->items as $item) {
            $total_honor = $item['jumlah_honor'] * $item['bulan'];
            $pph21 = $total_honor * 0.15;
            $jumlah = $total_honor - $pph21;

            $pengajuan->honorariums()->create([
                'tanggal' => $item['tanggal'] ?? $pengajuan->tanggal,
                'nama' => $item['nama'],
                'jabatan' => $item['jabatan'],
                'uraian' => $item['uraian'],
                'jumlah_honor' => $item['jumlah_honor'],
                'bulan' => $item['bulan'],
                'total_honor' => $total_honor,
                'pph_21' => $pph21,
                'jumlah' => $jumlah,
                'no_rekening' => $item['no_rekening'],
                'bank' => $item['bank'],
            ]);
        }

        $pengajuan->status = 'processed';
        $pengajuan->save();

        return redirect()->route('keuangan.laporan')
                        ->with('success', 'Honorarium berhasil disimpan!');
    }

    public function storeProses(Request $request, $id)
    {
        $pengajuan = Pengajuan::findOrFail($id);

        $convertToDecimal = function($value) {
            if (!$value) return 0;
            $value = str_replace('.', '', $value);
            $value = str_replace(',', '.', $value);
            return floatval($value);
        };

        // 🔹 Hapus semua item lama sebelum simpan ulang
        PengajuanItem::where('pengajuan_id', $pengajuan->id)->delete();

        $uraianList = $request->uraian ?? [];

        foreach ($uraianList as $index => $uraian) {
            $jumlah = $request->jumlah_pengajuan[$index] ?? null;

            if (
                (empty($request->detail_akun[$index]) && trim($uraian) === '') ||
                empty($jumlah)
            ) continue;

            // 🔹 Ambil nilai hasil pajak (bukan persentase)
            $pph21 = $convertToDecimal($request->hasil_pph21[$index] ?? 0);
            $pph22 = $convertToDecimal($request->hasil_pph22[$index] ?? 0);
            $pph23 = $convertToDecimal($request->hasil_pph23[$index] ?? 0);
            $ppn   = $convertToDecimal($request->hasil_ppn[$index] ?? 0);

            $jumlahDecimal = $convertToDecimal($jumlah);
            $totalPajak = $pph21 + $pph22 + $pph23 + $ppn;

            // 🔹 Hitung dibayarkan otomatis jika kosong
            $dibayarkan = $convertToDecimal($request->dibayarkan[$index] ?? null);
            if ($dibayarkan == 0) {
                $dibayarkan = $jumlahDecimal - $totalPajak;
            }

            PengajuanItem::create([
                'pengajuan_id' => $pengajuan->id,
                'invoice' => $request->invoice[$index] ?? null,
                'nama_barang' => $request->detail_akun[$index] ?? null,
                'uraian' => $uraian,
                'jumlah_dana_pengajuan' => $jumlahDecimal,
                'pph21' => $pph21,
                'pph22' => $pph22,
                'pph23' => $pph23,
                'ppn' => $ppn,
                'dibayarkan' => $dibayarkan,
                'no_rekening' => $request->no_rekening[$index] ?? null,
                'bank' => $request->bank[$index] ?? null,
            ]);
        }

        if ($request->filled('kode_akun')) {
            $pengajuan->kode_akun = $request->kode_akun;
        }

        $pengajuan->status = 'processed';
        $pengajuan->save();

        return redirect()->route('keuangan.laporan')
            ->with('success', 'Data pengajuan berhasil disimpan ke laporan tanpa duplikasi!');
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
        $pengajuan = Pengajuan::with('items', 'honorariums', 'adum', 'ppk', 'verifikator')->findOrFail($id);

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

        if(auth()->user()->role == 'verifikator') {
            $pengajuan->verifikator_approved_process = true;
        }

        $pengajuan->save();

        return redirect()->route('keuangan.laporan_detail', $id)
                        ->with('success', 'Proses keuangan berhasil di-approve.');
    }


}
