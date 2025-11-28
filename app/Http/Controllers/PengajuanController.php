<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\PengajuanItem;
use App\Models\Kro;
use Illuminate\Support\Facades\DB;

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
                ->where('status', '!=', 'approved')
                ->get();


        $pending = $pengajuans->whereIn('status', ['pending_adum', 'pending_ppk', 'pending_timker_1','pending_timker_2','pending_timker_3','pending_timker_4','pending_timker_5','pending_timker_6'])->count();
        $approved = $pengajuans->where('status', 'approved')->count();
        $rejected = $pengajuans->filter(fn($p) => str_starts_with($p->status,'rejected'))->count();

        return view('dashboard.pegawai', compact('user', 'pengajuans', 'pending', 'approved', 'rejected'));
    }

    public function create()
    {
        $user = auth()->user();

        // Jika role sarpras/bmn → form kerusakan
        if(in_array($user->role, ['sarpras','bmn'])){
            return view('pegawai.pengajuan.form_kerusakan');
        }

        // Ambil semua KRO dari DB
        $kroData = DB::table('kro')->get();

        // Buat nested array
        $kroAll = $this->buildTree($kroData);

        return view('pegawai.pengajuan.form_pembelian', compact('kroAll'));
    }

    private function buildTree($elements, $parentId = null) {
        $branch = [];
        foreach ($elements as $element) {
            if ($element->parent_id == $parentId) {
                $children = $this->buildTree($elements, $element->id);
                if ($children) $element->children = $children;
                $branch[] = $element;
            }
        }
        return $branch;
    }

    /**
     * Ambil semua level terakhir (A/B/C) beserta kode akun
     */
    private function getFinalOptions($elements, $parentId = null)
    {
        $options = [];

        foreach ($elements as $el) {
            if ($el->parent_id == $parentId) {
                // cek apakah ini punya kode_akun → level terakhir
                if ($el->kode_akun) {
                    $options[] = [
                        'id' => $el->id,
                        'label' => $el->nama . ' (' . $el->kode_akun . ')',
                        'kode_akun' => $el->kode_akun
                    ];
                } else {
                    // rekursif ke anaknya
                    $childOptions = $this->getFinalOptions($elements, $el->id);
                    if ($childOptions) {
                        $options = array_merge($options, $childOptions);
                    }
                }
            }
        }

        return $options;
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
            // Atur mengetahui & status tergantung role pegawai
            $userRole = auth()->user()->role;

            // Jika dia anggota timker → kirim ke timker tersebut
            if (str_starts_with($userRole, 'anggota_timker_')) {
                $pengajuan->mengetahui_jabatan = str_replace('anggota_', '', $userRole);
                $pengajuan->status = 'pending_' . $pengajuan->mengetahui_jabatan;
            } else {
                // Jika bukan timker → otomatis ADUM
                $pengajuan->mengetahui_jabatan = 'adum';
                $pengajuan->status = 'pending_adum';
            }
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
            if ($request->file("items.$i.foto")) {
                $foto = $request->file("items.$i.foto");
                $filename = time() . '_' . $foto->getClientOriginalName();

                // Simpan ke storage/app/public/pengajuan
                $foto->storeAs('pengajuan', $filename, 'public');

                // Simpan path yang benar ke database
                $dataItem['foto'] = 'pengajuan/' . $filename;
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
                $dataItem['kro'] = implode('.', array_unique(explode('.', $item['kro'] ?? '')));
                $dataItem['ongkos_kirim'] = $item['ongkos_kirim'] ?? 0;
                $dataItem['tipe_item'] = 'pembelian';
                $dataItem['link'] = $item['link'] ?? null;
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
        $perPage = request('perPage', 10);

        $pengajuans = Pengajuan::with('items', 'adum', 'ppk')
            ->where('user_id', auth()->id())
            ->latest() // otomatis sort by created_at DESC
            ->paginate($perPage);

        return view('pegawai.pengajuan.index', compact('pengajuans'));
    }

    public function show($id)
    {
        $pengajuan = Pengajuan::with(['items', 'user', 'mengetahui', 'adum', 'ppk'])
                    ->findOrFail($id);

        // Buat kro_full per item
        foreach ($pengajuan->items as $item) {
            // Cek dulu kalau kro ada isinya
            if ($item->kro) {
                $item->kro_full = $item->kro;
            } else {
                $item->kro_full = '-';
            }
        }

        return view('pegawai.pengajuan.show', compact('pengajuan'));
    }

}
