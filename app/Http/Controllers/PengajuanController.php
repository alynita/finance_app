<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\PengajuanItem;
use App\Models\KroAccount;

class PengajuanController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        if(!in_array($user->role, ['sarpras','bmn'])){
            abort(403, 'Akses ditolak.');
        }

        $pengajuans = Pengajuan::with('items', 'user')
                        ->where('user_id', $user->id)
                        ->get();

        $pending = $pengajuans->whereIn('status', ['pending_adum', 'pending_ppk', 'pending_timker_1','pending_timker_2','pending_timker_3','pending_timker_4','pending_timker_5','pending_timker_6'])->count();
        $approved = $pengajuans->where('status', 'approved')->count();
        $rejected = $pengajuans->filter(fn($p) => str_starts_with($p->status,'rejected'))->count();

        return view('dashboard.pegawai', compact('user', 'pengajuans', 'pending', 'approved', 'rejected'));
    }

    public function create()
    {
        $user = auth()->user();

        if(in_array($user->role, ['sarpras','bmn'])){
            return view('pegawai.pengajuan.form_kerusakan');
        }

        // Ambil semua KRO/Kode Akun untuk dropdown
        $kroAccounts = KroAccount::all(); 

        return view('pegawai.pengajuan.form_pembelian', compact('kroAccounts'));
    }

    public function store(Request $request)
    {
        // Validasi umum
        $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'waktu_kegiatan' => 'required|date',
            'jenis_pengajuan' => 'required|string|in:kerusakan,pembelian',
            'items' => 'required|array|min:1',
        ]);

        // Buat pengajuan baru
        $pengajuan = new Pengajuan();
        $pengajuan->user_id = auth()->id();
        $pengajuan->nama_kegiatan = $request->nama_kegiatan;
        $pengajuan->waktu_kegiatan = $request->waktu_kegiatan;
        $pengajuan->jenis_pengajuan = $request->jenis_pengajuan;

        // Atur mengetahui & status tergantung jenis pengajuan
        if ($request->jenis_pengajuan === 'kerusakan') {
            // Untuk pengajuan kerusakan → otomatis ADUM
            $pengajuan->mengetahui_jabatan = 'adum';
            $pengajuan->status = 'pending_adum';
        } else {
            // Untuk pengajuan pembelian → tetap manual pilih mengetahui
            $request->validate([
                'mengetahui' => 'required|string',
            ]);
            $pengajuan->mengetahui_jabatan = $request->mengetahui;
            $pengajuan->status = 'pending_' . $request->mengetahui;
        }

        $pengajuan->save();

        // Simpan item-item pengajuan
        foreach ($request->items as $i => $item) {
            $jumlah_dana = 0;
            $dataItem = [
                'pengajuan_id' => $pengajuan->id,
                'volume' => $item['volume'] ?? 0,
                'harga_satuan' => $item['harga_satuan'] ?? 0,
                'jumlah_dana_pengajuan' => 0,
            ];

            // Upload foto
            if ($request->hasFile("items.$i.foto")) {
                $foto = $request->file("items.$i.foto");
                $filename = time() . '_' . $foto->getClientOriginalName();
                $path = $foto->storeAs('public/pengajuan', $filename);
                $dataItem['foto'] = str_replace('public', '/storage', $path); // /storage/pengajuan/filename.jpg
            } else {
                $dataItem['foto'] = null;
            }

            // Sesuaikan field per jenis pengajuan
            if ($request->jenis_pengajuan === 'kerusakan') {
                $dataItem['nama_barang'] = $item['nama_barang'] ?? null;
                $dataItem['lokasi'] = $item['lokasi'] ?? null;
                $dataItem['jenis_kerusakan'] = $item['jenis_kerusakan'] ?? null;
                $dataItem['tipe_item'] = 'kerusakan';
                $jumlah_dana = ($item['volume'] ?? 0) * ($item['harga_satuan'] ?? 0);
            } elseif ($request->jenis_pengajuan === 'pembelian') {
                $dataItem['nama_barang'] = $item['nama_barang'] ?? null;
                $dataItem['kro'] = $item['kro'] ?? null;
                $dataItem['ongkos_kirim'] = $item['ongkos_kirim'] ?? 0;
                $dataItem['tipe_item'] = 'pembelian';
                $jumlah_dana = ($item['volume'] ?? 0) * ($item['harga_satuan'] ?? 0) + ($item['ongkos_kirim'] ?? 0);
            }

            $dataItem['jumlah_dana_pengajuan'] = $jumlah_dana;
            $pengajuan->items()->create($dataItem);
        }

        return redirect()->route('pegawai.daftar-pengajuan')
            ->with('success', 'Pengajuan berhasil dibuat.');
    }

    public function updateStatus($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        $user = auth()->user();

        if(str_starts_with($user->role, 'timker_')) {
            $pengajuan->mengetahui_id = $user->id;
            $pengajuan->mengetahui_approved_at = now();
            $pengajuan->mengetahui_jabatan = $user->role;
            $pengajuan->status = 'pending_ppk';
        } elseif($user->role === 'adum') {
            $pengajuan->adum_id = $user->id;
            $pengajuan->adum_approved_at = now();
            $pengajuan->status = 'pending_ppk';
        } elseif($user->role === 'ppk') {
            if ($pengajuan->status !== 'pending_ppk') {
                return back()->with('error', 'Pengajuan harus disetujui ADUM/Timker dulu!');
            }
            $pengajuan->ppk_id = $user->id;
            $pengajuan->ppk_approved_at = now();
            $pengajuan->status = 'pending_pengadaan';
        }

        $pengajuan->save();

        return back()->with('success', 'Status pengajuan berhasil diperbarui!');
    }

    public function index()
    {
        $pengajuans = Pengajuan::with('items', 'adum', 'ppk')
            ->where('user_id', auth()->id())
            ->get();

        return view('pegawai.pengajuan.index', compact('pengajuans'));
    }

    public function show($id)
    {
        $pengajuan = Pengajuan::with(['items', 'user', 'mengetahui', 'adum', 'ppk'])
                    ->findOrFail($id);

        return view('pegawai.pengajuan.show', compact('pengajuan'));
    }

}
