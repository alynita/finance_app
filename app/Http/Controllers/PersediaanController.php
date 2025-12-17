<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\PengajuanItem;
use App\Models\PengeluaranBarang;
use App\Models\PengeluaranBarangItem;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class PersediaanController extends Controller
{
    /* =======================
     *  DASHBOARD
     * ======================= */
    public function dashboard()
    {
        return view('persediaan.dashboard');
    }

    /* =======================
     *  LIST PENGAJUAN MASUK
     * ======================= */
    public function pengajuanMasuk()
    {
        $pengajuan = Pengajuan::where('status', 'menunggu_persediaan')
            ->latest()
            ->get();

        return view('persediaan.pengajuan.index', compact('pengajuan'));
    }

    /* =======================
     *  DETAIL PENGAJUAN + ITEM
     * ======================= */
    public function detailPengajuan($id)
    {
        $pengajuan = Pengajuan::with('items')->findOrFail($id);

        return view('persediaan.pengajuan.detail', compact('pengajuan'));
    }

    /* =======================
     *  UPDATE STATUS ITEM
     *  ADA / TIDAK ADA
     * ======================= */
    public function updateItemStatus(Request $request, $item_id)
    {
        $item = PengajuanItem::findOrFail($item_id);
        if ($request->status === 'SEBAGIAN') {
            $request->validate([
                'jumlah_tersedia' => [
                    'required',
                    'integer',
                    'min:1',
                    'max:' . $item->volume,
                ],
            ]);

            $item->jumlah_tersedia = $request->jumlah_tersedia;
            $item->status_persediaan = 'SEBAGIAN';

        } elseif ($request->status === 'ADA') {

            $item->jumlah_tersedia = $item->volume;
            $item->status_persediaan = 'ADA';

        } else { // TIDAK_ADA

            $item->jumlah_tersedia = 0;
            $item->status_persediaan = 'TIDAK_ADA';
        }

        $item->save();

        return back()->with('success', 'Status barang diperbarui');
    }

    /* =======================
     *  FINALISASI CEK ITEM
     * ======================= */
    public function finalizePengajuan($pengajuan_id)
    {
        $pengajuan = Pengajuan::with(['items', 'user'])->findOrFail($pengajuan_id);

        // 1️⃣ Pastikan semua item sudah dicek
        if ($pengajuan->items->whereNull('status_persediaan')->count() > 0) {
            return back()->with('error', 'Masih ada item yang belum dicek');
        }

        // 2️⃣ Cek kondisi item
        $ada = $pengajuan->items->where('status_persediaan', 'ADA')->count();
        $sebagian = $pengajuan->items->where('status_persediaan', 'SEBAGIAN')->count();
        $tidakAda = $pengajuan->items->where('status_persediaan', 'TIDAK_ADA')->count();

        /**
         * =========================================
         * KASUS: ADA / SEBAGIAN
         * =========================================
         * ➜ Masuk pengeluaran
         * ➜ TAPI kalau ada SEBAGIAN / TIDAK ADA
         * ➜ STATUS PENGAJUAN HARUS DIUBAH
         */
        if ($ada > 0) {

            // ⬇️ ADA SISA → kirim ke approval
            if ($sebagian > 0 || $tidakAda > 0) {
                $rolePengaju = $pengajuan->user->role;

                if (str_starts_with($rolePengaju, 'anggota_timker_')) {
                    $index = str_replace('anggota_timker_', '', $rolePengaju);
                    $pengajuan->status = 'pending_timker_' . $index;
                } else {
                    $pengajuan->status = 'pending_adum';
                }

                $pengajuan->save();
            }

            return redirect()
                ->route('persediaan.formPengeluaran', $pengajuan_id)
                ->with('success', 'Barang tersedia diproses, sisa diteruskan ke approval');
        }

        /**
         * =========================================
         * KASUS: SEMUA TIDAK ADA
         * =========================================
         */
        $rolePengaju = $pengajuan->user->role;

        if (str_starts_with($rolePengaju, 'anggota_timker_')) {
            $index = str_replace('anggota_timker_', '', $rolePengaju);
            $pengajuan->status = 'pending_timker_' . $index;
        } else {
            $pengajuan->status = 'pending_adum';
        }

        $pengajuan->save();

        return redirect()
            ->route('persediaan.pengajuan')
            ->with('success', 'Pengajuan diteruskan ke proses approval');
    }

    private function setStatusApproval(Pengajuan $pengajuan)
    {
        $role = $pengajuan->user->role;

        if (str_starts_with($role, 'anggota_timker_')) {
            $index = str_replace('anggota_timker_', '', $role);
            $pengajuan->status = 'pending_timker_' . $index;
        } else {
            $pengajuan->status = 'pending_adum';
        }

        $pengajuan->save();
    }

    /* =======================
     *  FORM PENGELUARAN BARANG  
     * ======================= */
    public function formPengeluaran($pengajuan_id)
    {
        $pengajuan = Pengajuan::with(['items' => function ($q) {
            $q->whereIn('status_persediaan', ['ADA', 'SEBAGIAN']);
        }])->findOrFail($pengajuan_id);

        // ambil nomor urut terakhir di tahun ini
        $tahun = now()->year;
        $last = PengeluaranBarang::whereYear('created_at', $tahun)
            ->count() + 1;

        $nomorUrut = str_pad($last, 3, '0', STR_PAD_LEFT);
        $bulanRomawi = $this->bulanRomawi(now()->month);

        $kodePengeluaran = "Persed/K/{$nomorUrut}/{$bulanRomawi}/{$tahun}";

        return view('persediaan.pengeluaran.create', compact(
            'pengajuan',
            'kodePengeluaran'
        ));
    }

    private function bulanRomawi($bulan)
    {
        return [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV',
            5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII',
            9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
        ][$bulan];
    }

    /* =======================
     *  SIMPAN PENGELUARAN
     * ======================= */
    public function simpanPengeluaran(Request $request)
    {
        DB::beginTransaction();

        try {
            $pengeluaran = PengeluaranBarang::create([
                'pengajuan_id' => $request->pengajuan_id,
                'kode_pengeluaran' => $request->kode_pengeluaran,
                'bidang_bagian' => $request->bidang_bagian,
                'nama_penerima' => $request->nama_penerima,
                'nama_petugas_persediaan' => $request->nama_petugas_persediaan,
                'nama_penyerah' => $request->nama_penyerah,
                'persediaan_id' => auth()->id(),
                'tanggal_pengeluaran' => now(),
            ]);

            foreach ($request->items as $item) {
                PengeluaranBarangItem::create([
                    'pengeluaran_id' => $pengeluaran->id,
                    'pengajuan_item_id' => $item['pengajuan_item_id'],
                    'nama_barang' => $item['nama_barang'],
                    'jumlah' => $item['jumlah'],
                    'harga_satuan' => $item['harga_satuan'] ?? null,
                    'total' => $item['total'] ?? null,
                    'keterangan' => $item['keterangan'] ?? null,
                ]);
            }

            DB::commit();

            return redirect()->route('persediaan.draft')
                ->with('success', 'Form pengeluaran disimpan ke draft');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    /* =======================
     *  DRAFT FORM PENGELUARAN
     * ======================= */
    public function draft()
    {
        $drafts = PengeluaranBarang::latest()->get();

        return view('persediaan.draft.index', compact('drafts'));
    }

    /* =======================
     *  DETAIL DRAFT
     * ======================= */
    public function detailDraft($id)
    {
        $pengeluaran = PengeluaranBarang::with('items')->findOrFail($id);

        return view('persediaan.draft.detail', compact('pengeluaran'));
    }

    public function cetakPdf($id)
    {
        $pengeluaran = PengeluaranBarang::with('items')->findOrFail($id);

        $pdf = Pdf::loadView('persediaan.pengeluaran.pdf',compact('pengeluaran'));

        $namaFile = 'pengeluaran_' .
            preg_replace('/[\/\\\\]/', '-', $pengeluaran->kode_pengeluaran)
            . '_' . now()->format('Ymd_His')
            . '.pdf';

        return $pdf->download($namaFile);
    }


}
