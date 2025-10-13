<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\PengajuanItem;
use App\Models\PenanggungJawab;

class PengajuanController extends Controller
{
    // Form buat pengajuan
    public function create()
    {
        $penanggungJawabs = PenanggungJawab::all(); // ambil semua PJ
        return view('pegawai.pengajuan.create', compact('penanggungJawabs'));
    }

    // Simpan pengajuan baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'waktu_kegiatan' => 'required|date',
            'jenis_pengajuan' => 'required|string',
            'pj_id' => 'required|exists:penanggung_jawab,id', // pilih PJ
        ]);

        $pengajuan = Pengajuan::create([
            'user_id' => auth()->id(),
            'nama_kegiatan' => $request->nama_kegiatan,
            'waktu_kegiatan' => $request->waktu_kegiatan,
            'jenis_pengajuan' => $request->jenis_pengajuan,
            'pj_id' => $request->pj_id, // simpan PJ
            'status' => 'pending_pj', // mulai dari PJ
        ]);

        // simpan semua item (kode sama seperti sebelumnya)
        if ($request->has('items')) {
            foreach ($request->items as $item) {
                $jumlah_dana = 0;
                if ($request->jenis_pengajuan === 'pembelian') {
                    $jumlah_dana = ($item['volume'] ?? 0) * ($item['harga_satuan'] ?? 0) + ($item['ongkos_kirim'] ?? 0);
                } elseif ($request->jenis_pengajuan === 'kerusakan') {
                    $jumlah_dana = ($item['volume'] ?? 0) * ($item['harga_satuan'] ?? 0);
                }

                $dataItem = [
                    'pengajuan_id' => $pengajuan->id,
                    'nama_barang' => $item['nama_barang'] ?? $item['nama'] ?? null,
                    'volume' => $item['volume'] ?? null,
                    'harga_satuan' => $item['harga_satuan'] ?? null,
                    'jumlah_dana_pengajuan' => $jumlah_dana,
                ];

                switch ($request->jenis_pengajuan) {
                    case 'kerusakan':
                        $dataItem['lokasi'] = $item['lokasi'] ?? null;
                        $dataItem['jenis_kerusakan'] = $item['jenis_kerusakan'] ?? null;
                        $dataItem['foto'] = $item['foto'] ?? null;
                        $dataItem['tipe_item'] = 'kerusakan';
                        break;

                    case 'pembelian':
                        $dataItem['kro'] = $item['kro'] ?? null;
                        $dataItem['ongkos_kirim'] = $item['ongkos_kirim'] ?? 0;
                        $dataItem['tipe_item'] = 'barang';
                        break;

                    case 'honor':
                        $dataItem['tanggal'] = $item['tanggal'] ?? null;
                        $dataItem['nama'] = $item['nama'] ?? null;
                        $dataItem['jabatan'] = $item['jabatan'] ?? null;
                        $dataItem['tipe_item'] = 'honor';
                        break;
                }

                $pengajuan->items()->create($dataItem);
            }
        }

        return redirect()->route('pegawai.daftar-pengajuan')
            ->with('success', 'Pengajuan berhasil dibuat.');
    }

    // Update status sesuai role
    public function updateStatus($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        $user = auth()->user();

        switch ($user->role) {
            case 'pj':
                $pengajuan->pj_id = $user->id;
                $pengajuan->pj_approved_at = now();
                $pengajuan->status = 'pending_adum';
                break;

            case 'adum':
                if ($pengajuan->status !== 'pending_adum') {
                    return back()->with('error', 'Pengajuan harus disetujui PJ dulu!');
                }
                $pengajuan->adum_id = $user->id;
                $pengajuan->adum_approved_at = now();
                $pengajuan->status = 'pending_ppk';
                break;

            case 'ppk':
                if ($pengajuan->status !== 'pending_ppk') {
                    return back()->with('error', 'Pengajuan harus disetujui ADUM dulu!');
                }
                $pengajuan->ppk_id = $user->id;
                $pengajuan->ppk_approved_at = now();
                $pengajuan->status = 'approved';
                break;
        }

        $pengajuan->save();

        return back()->with('success', 'Status pengajuan berhasil diperbarui!');
    }

    public function index()
    {
        $pengajuans = Pengajuan::with('items', 'pj', 'adum', 'ppk')
            ->where('user_id', auth()->id())
            ->get();

        return view('pegawai.pengajuan.index', compact('pengajuans'));
    }

    public function show($id)
    {
        $pengajuan = Pengajuan::with('items', 'pj', 'adum', 'ppk')->findOrFail($id);
        return view('pegawai.pengajuan.show', compact('pengajuan'));
    }
}
