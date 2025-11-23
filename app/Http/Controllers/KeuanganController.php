<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PpkGroup;
use App\Models\PengajuanItem;
use Illuminate\Support\Facades\DB;
use \App\Models\Honor;
use \App\Models\KroAccount;
use \App\Models\HonorDetail;
use \App\Models\User;

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

        // Ambil semua KRO dari DB
        $kroData = DB::table('kro')->get();

        // Buat nested array
        $kroAll = $this->buildTree($kroData);

        return view('keuangan.showGroup', compact('group', 'kroAll'));
    }

    // Update KRO per item
    public function updateKro(Request $request, $itemId)
    {
        $request->validate([
            'kro' => 'required|string|max:255',
        ]);

        DB::table('pengajuan_items')->where('id', $itemId)->update([
            'kro' => $request->kro
        ]);

        return response()->json([
            'success' => true,
            'message' => 'KRO berhasil diupdate'
        ]);
    }

    // Fungsi build tree (rekursif)
    protected function buildTree($data, $parentId = null)
    {
        $branch = [];
        foreach ($data as $item) {
            if ($item->parent_id == $parentId) {
                $children = $this->buildTree($data, $item->id);
                $branch[] = [
                    'kode' => $item->kode,
                    'kode_akun' => $item->kode_akun,
                    'children' => $children
                ];
            }
        }
        return $branch;
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

            // Tambahan untuk Pajak Baru
            $item->nama_pajak_baru = $data['nama_pajak_baru'] ?? $item->nama_pajak_baru;
            $item->hasil_pajak_baru = $convertToDecimal($data['hasil_pajak_baru'] ?? $item->hasil_pajak_baru);

            $item->save();
        }

        $group->update([
            'status' => 'processed',
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
         // Ambil semua KRO dari DB
        $kroData = DB::table('kro')->get();

        // Buat nested array
        $kroAll = $this->buildTree($kroData);

        return view('keuangan.honor_input', compact('kroAll'));
    }

    public function storeHonor(Request $request)
    {
        //dd($request->all());

        $request->validate([
            'nama_kegiatan' => 'required',
            'waktu' => 'required|date',
            'alokasi_anggaran' => 'required',
            'nama.*' => 'required',
            'nip.*'=>'required',
            'jabatan.*' => 'required',
            'tujuan.*' => 'required',
            'jenis_uang.*' => 'required', 
            'jumlah_hari.*' => 'nullable|numeric',
            'uang_harian.*' => 'nullable|numeric',
            'uang_transport.*' => 'nullable|numeric',
            'pph21.*' => 'required',
            'potongan_lain.*' => 'nullable|numeric',
            'nomor_rekening.*' => 'required',
            'atas_nama.*' => 'required',
            'bank.*' => 'required',
        ]);

        // Header honor
        $honor = \App\Models\Honor::create([
            'nama_kegiatan' => $request->nama_kegiatan,
            'waktu' => $request->waktu,
            'alokasi_anggaran' => $request->alokasi_anggaran,
            'status' => 'pending',
            'user_id' => auth()->id(),
        ]);

        foreach ($request->nama as $index => $nama) {

            $jenis = $request->jenis_uang[$index] ?? null;

            // Default
            $hari = (int) ($request->jumlah_hari[$index] ?? 1);
            $uangHarian = (float) ($request->uang_harian[$index] ?? 0);
            $uangTransport = (float) ($request->uang_transport[$index] ?? 0);

            // Jika transport → jumlah hari otomatis 1
            if ($jenis === 'transport') {
                $hari = 1;
            }

            // Hitung total
            if ($jenis === 'harian') {
                $total = $uangHarian * $hari;
            } elseif ($jenis === 'transport') {
                $total = $uangTransport;
            } else {
                $total = 0;
            }

            // Potongan
            $potongan = (float) ($request->potongan_lain[$index] ?? 100);
            $totalSetelahPotongan = $total * ($potongan / 100);

            // PPh21
            $pph21 = (float) ($request->pph21[$index] ?? 0);
            $pphNominal = $totalSetelahPotongan * ($pph21 / 100);

            $jumlahDibayar = $totalSetelahPotongan - $pphNominal;

            // Simpan detail
            $honor->details()->create([
                'nama' => $nama,
                'nip' => $request->nip[$index],
                'jabatan' => $request->jabatan[$index],
                'tujuan' => $request->tujuan[$index],
                'jumlah_hari' => $hari,
                'uang_harian' => $jenis === 'harian' ? $uangHarian : 0,
                'uang_transport' => $jenis === 'transport' ? $uangTransport : 0,
                'pph21' => $pph21,
                'potongan_lain' => $potongan,
                'jumlah_dibayar' => round($jumlahDibayar),
                'nomor_rekening' => $request->nomor_rekening[$index],
                'atas_nama' => $request->atas_nama[$index],
                'bank' => $request->bank[$index],
            ]);
        }

        return redirect()->route('keuangan.honor.data')
            ->with('success', 'Data honor berhasil ditambahkan!');
    }

    // List semua honor
    public function honorData()
    {
        $perPage = request('perPage', 10);

        $honors = \App\Models\Honor::latest()
            ->paginate($perPage);

        return view('keuangan.honor_data', compact('honors'));
    }

    // Detail honor per ID
    public function honorDetail($id)
    {
        // Ambil data honor beserta semua detail-nya
        $honor = \App\Models\Honor::with(['details', 'adum', 'ppk', 'user'])
            ->findOrFail($id);

        return view('keuangan.honor_detail', compact('honor'));
    }

    public function indexLaporan()
    {
        $perPage = request('perPage', 10);

        // Ambil data honor yang sudah dibayar atau sudah diproses
        $honors = Honor::latest()
            ->paginate($perPage);

        return view('keuangan.honor_index_laporan', compact('honors'));
    }

    public function detailLaporan($id)
    {
        $honors = Honor::with(['details', 'ppk'])->findOrFail($id);

        // Hitung total dari kolom jumlah_dibayar
        $totalBayar = $honors->details->sum('jumlah_dibayar');

        // Ambil user dengan role 'bendahara'
        $bendahara = User::where('role', 'bendahara')->first();

        return view('keuangan.honor_detail_laporan', compact('honors', 'totalBayar', 'bendahara'));
    }

}
