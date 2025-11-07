<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PpkGroup;
use App\Models\PengajuanItem;
use Illuminate\Support\Facades\DB;
use \App\Models\Honor;

class KeuanganController extends Controller
{
    // Dashboard grup yang sudah submit keuangan
    public function dashboard()
    {
        $groups = PpkGroup::where('status', 'submitted_keuangan')
            ->with('pengajuan.user', 'items')
            ->get();

        return view('keuangan.dashboard', compact('groups'));
    }

    // Detail per grup untuk proses pembayaran
    public function showGroup($groupId)
    {
        $group = PpkGroup::with('pengajuan.user', 'items')->findOrFail($groupId);
        return view('keuangan.showGroup', compact('group'));
    }

    // Simpan proses pembayaran
    public function storeProses(Request $request, $groupId)
    {
        $group = PpkGroup::with('items')->findOrFail($groupId);

        foreach($request->items ?? [] as $itemId => $data){
            $item = $group->items->where('id', $itemId)->first();
            if(!$item) continue;

            $item->invoice = $data['invoice'] ?? $item->invoice;
            $item->detail_akun = $data['detail_akun'] ?? $item->nama_barang;
            $item->uraian = $data['uraian'] ?? $item->uraian;
            $item->jumlah_dana_pengajuan = str_replace('.', '', $data['jumlah_dana_pengajuan'] ?? $item->jumlah_dana_pengajuan);
            $convertToDecimal = function($value) {
                if(!$value) return 0;
                $value = str_replace('.', '', $value);
                $value = str_replace(',', '.', $value);
                return floatval($value);
            };

            $item->pph21 = $convertToDecimal($data['hasil_pph21'] ?? $item->pph21);
            $item->pph22 = $convertToDecimal($data['hasil_pph22'] ?? $item->pph22);
            $item->pph23 = $convertToDecimal($data['hasil_pph23'] ?? $item->pph23);
            $item->ppn   = $convertToDecimal($data['hasil_ppn'] ?? $item->ppn);
            $item->dibayarkan = $convertToDecimal($data['dibayarkan'] ?? $item->dibayarkan);
            $item->no_rekening = $data['no_rekening'] ?? $item->no_rekening;
            $item->bank = $data['bank'] ?? $item->bank;
            $item->save();
        }

        // Update group agar status pindah ke laporan
        $group->update([
            'status' => 'processed',  // biar bisa muncul di dashboard ADUM
            'kode_akun' => $request->kode_akun ?? $group->kode_akun,
        ]);

        return redirect()->route('keuangan.laporan')->with('success', 'Proses keuangan berhasil disimpan!');
    }

    // Laporan keuangan
    public function laporan()
    {
        $ppkGroups = PpkGroup::with('pengajuan.user')
                ->whereIn('status', ['processed', 'adum_approved', 'ppk_approved', 'approved'])
                ->get();

        return view('keuangan.laporan', compact('ppkGroups'));
    }

    public function laporan_detail($id)
    {
        $group = PpkGroup::with(['pengajuan.items', 'pengajuan.user'])->findOrFail($id);
        return view('keuangan.laporan_detail', compact('group'));
    }

    // Form Input Honor
    public function honorForm()
    {
        return view('keuangan.honor_input');
    }

    // Simpan Honor
    public function storeHonor(Request $request)
    {
        $request->validate([
            'nama_kegiatan' => 'required',
            'waktu' => 'required|date',
            'alokasi_anggaran' => 'required|numeric',
            'nama' => 'required',
            'jabatan' => 'required',
            'tujuan' => 'required',
            'uang_harian' => 'required|numeric',
            'pph21' => 'required',
            'jumlah_dibayar' => 'required|numeric',
            'nomor_rekening' => 'required',
            'atas_nama' => 'required',
            'bank' => 'required',
        ]);

        // Hitung jumlah dibayar jika pph manual
        $uangHarian = $request->uang_harian;
        $pph21 = ($request->pph21 === 'manual') ? $request->pph21_manual : $request->pph21;
        $potongan = $uangHarian * ($pph21 / 100);
        $jumlahDibayar = $uangHarian - $potongan;

        \App\Models\Honor::create([
            'nama_kegiatan' => $request->nama_kegiatan,
            'waktu' => $request->waktu,
            'alokasi_anggaran' => $request->alokasi_anggaran,
            'nama' => $request->nama,
            'jabatan' => $request->jabatan,
            'tujuan' => $request->tujuan,
            'uang_harian' => $uangHarian,
            'pph21' => $pph21,
            'jumlah_dibayar' => $jumlahDibayar,
            'nomor_rekening' => $request->nomor_rekening,
            'atas_nama' => $request->atas_nama,
            'bank' => $request->bank,
            'status' => 'pending',
        ]);

        return redirect()->route('keuangan.honor.all')->with('success', 'Data honor berhasil ditambahkan!');
    }

    // List semua honor
    public function honorData()
    {
        $honors = \App\Models\Honor::latest()->get();
        return view('keuangan.honor_data', compact('honors'));
    }

    // Detail honor per ID
    public function honorDetail($id)
    {
        $honor = \App\Models\Honor::findOrFail($id);
        return view('keuangan.honor_detail', compact('honor'));
    }

}
