<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\PengajuanItem;

class PengajuanController extends Controller
{
    public function create()
    {
        return view('pegawai.pengajuan.create');
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'waktu_kegiatan' => 'required|date',
            'jenis_pengajuan' => 'required|string',
            'items.*.nama_barang' => 'required|string|max:255',
            'items.*.volume' => 'required|numeric|min:1',
            'items.*.harga_satuan' => 'required|numeric|min:0',
        ]);

        // Buat pengajuan baru
        $pengajuan = Pengajuan::create([
            'user_id' => auth()->id(),
            'nama_kegiatan' => $request->nama_kegiatan,
            'waktu_kegiatan' => $request->waktu_kegiatan,
            'jenis_pengajuan' => $request->jenis_pengajuan,
            'status' => 'pending_adum', // status default awal
        ]);

        // Simpan item pengajuan
        if ($request->has('items')) {
            foreach ($request->items as $item) {
                $pengajuan->items()->create([
                    'nama_barang' => $item['nama_barang'],
                    'volume' => $item['volume'],
                    'harga_satuan' => $item['harga_satuan'],
                ]);
            }
        }

        return redirect()->route('pegawai.pengajuan.index')
                        ->with('success', 'Pengajuan berhasil dibuat.');
    }

    public function index()
    {
        $pengajuans = \App\Models\Pengajuan::with('items')
            ->where('user_id', auth()->id())
            ->get();

        return view('pegawai.pengajuan.index', compact('pengajuans'));
    }

    public function show($id)
    {
        $pengajuan = Pengajuan::with('items')->findOrFail($id);

        return view('pegawai.pengajuan.show', compact('pengajuan'));
    }
}
