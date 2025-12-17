<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\PengajuanItem;
use App\Models\PpkGroup;
use App\Models\KroAccount;
use App\Models\Honor;
use App\Helpers\NotifikasiHelper;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class PpkController extends Controller
{
    // Dashboard PPK
    public function dashboard()
    {
        $user = auth()->user(); // opsional, kalau mau tampil nama PPK

        // Ambil semua pengajuan yang pending PPK
        $pengajuans = \App\Models\Pengajuan::with('user', 'items')
            ->where('status', 'pending_ppk')
            ->get();

        // Hitung per kategori untuk card
        $pendingPembelian = $pengajuans->whereIn('jenis_pengajuan', ['pembelian', 'pengadaan', 'kerusakan'])->count();
        $pendingProsesKeuangan = PpkGroup::where('status', 'adum_approved')
                ->count();
        $pendingHonor = Honor::where('status','adum_approved')
                ->count();

        // Hitung summary
        $totalPending = $pengajuans->count();
        $totalApproved = \App\Models\Pengajuan::where('status', 'pending_pengadaan')->count(); // setelah PPK approve
        $totalRejected = \App\Models\Pengajuan::where('status', 'rejected_ppk')->count();

        // Kirim ke view
        return view('ppk.dashboard', compact(
            'pengajuans',
            'user',
            'pendingPembelian',
            'pendingProsesKeuangan',
            'pendingHonor',
            'totalPending',
            'totalApproved',
            'totalRejected'
        ));
    }

    public function show($id)
    {
        $pengajuan = Pengajuan::with([
            'items' => function ($q) {
                $q->whereIn('status_persediaan', ['TIDAK_ADA', 'SEBAGIAN']);
            },
            'user',
            'ppkGroups.items'
        ])->findOrFail($id);

        /* ================================
        * HITUNG DATA TAMPILAN UNTUK PPK
        * ================================ */
        foreach ($pengajuan->items as $item) {

            // default
            $item->volume_tampil = $item->volume;
            $item->jumlah_dana_tampil = $item->jumlah_dana_pengajuan;

            if ($item->status_persediaan === 'SEBAGIAN') {

                $sisa = max(
                    $item->volume - ($item->jumlah_tersedia ?? 0),
                    0
                );

                $item->volume_tampil = $sisa;
                $item->jumlah_dana_tampil = $sisa * ($item->harga_satuan ?? 0);
            }

            if ($item->status_persediaan === 'TIDAK_ADA') {
                $item->volume_tampil = $item->volume;
                $item->jumlah_dana_tampil = $item->jumlah_dana_pengajuan;
            }

            $item->kro_full = $item->kro ?? '-';
        }

        // Ambil semua KRO
        $kroData = DB::table('kro')->get();
        $kroAll = $this->buildTree($kroData);

        return view('ppk.show', compact('pengajuan', 'kroAll'));
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

    public function updateKRO(Request $request, $id)
    {
        $request->validate([
            'kro' => 'nullable|string|max:255',
        ]);

        $item = \App\Models\PengajuanItem::findOrFail($id);
        $item->kro = $request->kro;
        $item->save();

        return response()->json([
            'success' => true,
            'message' => 'KRO berhasil diupdate',
            'kro' => $item->kro,
        ]);
    }

    public function updateCatatan(Request $request, $id)
    {
        $request->validate([
            'catatan_ppk' => 'nullable|string|max:255',
        ]);

        $item = PengajuanItem::findOrFail($id);
        $item->catatan_ppk = $request->catatan_ppk;
        $item->save();

        return response()->json([
            'success' => true,
            'message' => 'Catatan berhasil diupdate',
            'catatan_ppk' => $item->catatan_ppk
        ]);
}

    // Approve semua grup
    public function approveAll($pengajuanId)
    {
        $pengajuan = Pengajuan::with('items', 'ppkGroups.items')->findOrFail($pengajuanId);

        $catatanPpk = request()->input('catatan_ppk', []); // ambil input dari form

        foreach ($pengajuan->items as $item) {
            if (isset($catatanPpk[$item->id])) {
                $item->catatan_ppk = $catatanPpk[$item->id];
                $item->save();
            }
        }

        if($pengajuan->ppkGroups->count() === 0){
            $group = PpkGroup::create([
                'pengajuan_id' => $pengajuan->id,
                'group_name' => 'Grup ' . now()->format('YmdHis'),
                'status' => 'pending_pengadaan',
            ]);
            $group->items()->attach($pengajuan->items->pluck('id'));
        } else {
            foreach($pengajuan->ppkGroups as $group){
                $group->status = 'pending_pengadaan';
                $group->save();
            }
        }

        $pengajuan->status = 'pending_pengadaan';
        $pengajuan->ppk_id = auth()->id();
        $pengajuan->ppk_approved_at = now();
        $pengajuan->save();

        $nextRole = 'pengadaan';
        $pesan = "Pengajuan ID {$pengajuan->id} telah disetujui oleh PPK. Silakan proses selanjutnya.";

        if (app()->environment('local')) {
            \Log::info("Email NOT sent: {$pesan} -> {$nextRole}");
        } else {
            NotifikasiHelper::kirim($pengajuan, $nextRole, $pesan);
        }

        return redirect()->route('ppk.show', $pengajuan->id);
    }

    public function storeGroup(Request $request, $id)
    {
        $pengajuan = Pengajuan::with('items')->findOrFail($id);

        $groupNames = $request->group_name;
        $groupsItems = $request->groups;

        // catatan PPK sudah ada di item, jadi nggak perlu ambil lagi dari $request
        foreach ($groupNames as $index => $name) {
            $group = PpkGroup::create([
                'pengajuan_id' => $pengajuan->id,
                'group_name' => $name,
                'status' => 'pending_ppk',
            ]);

            if (isset($groupsItems[$index])) {
                $itemIds = $groupsItems[$index];

                // pastikan flat array
                $itemIds = is_array($itemIds) ? Arr::flatten($itemIds) : [$itemIds];

                $items = \App\Models\PengajuanItem::whereIn('id', $itemIds)->get();

                foreach ($items as $item) {
                    $group->items()->attach($item->id);
                    // Catatan PPK sudah ada di item, tidak perlu diubah
                }
            }
        }

        // Jika ada flag approve all, langsung approve grup
        if ($request->approve_all) {
            foreach ($pengajuan->ppkGroups as $group) {
                $group->status = 'pending_pengadaan';
                $group->save();
            }

            $pengajuan->status = 'pending_pengadaan';
            $pengajuan->ppk_id = auth()->id();
            $pengajuan->ppk_approved_at = now();
            $pengajuan->save();
        }

        return redirect()->route('ppk.show', $pengajuan->id);
    }

    public function approveAllGroups(Request $request, $pengajuanId)
    {
        $pengajuan = Pengajuan::with('ppkGroups.items')->findOrFail($pengajuanId);

        $groupsCatatan = $request->input('groups_catatan', []); // ambil catatan PPK jika ada dari request

        foreach ($pengajuan->ppkGroups as $group) {
            if ($group->status === 'pending_ppk') {
                // simpan catatan PPK ke setiap item sebelum approve
                foreach ($group->items as $item) {
                    if (isset($groupsCatatan[$group->id][$item->id])) {
                        $item->catatan_ppk = $groupsCatatan[$group->id][$item->id];
                        $item->save();
                    }
                }

                $group->status = 'pending_pengadaan';
                $group->save();
            }
        }

        $pengajuan->status = 'pending_pengadaan';
        $pengajuan->ppk_id = auth()->id();
        $pengajuan->ppk_approved_at = now();
        $pengajuan->save();

        $nextRole = 'pengadaan';
        $pesan = "Pengajuan ID {$pengajuan->id} telah disetujui oleh PPK. Silakan proses selanjutnya.";

        if (app()->environment('local')) {
            \Log::info("Email NOT sent: {$pesan} -> {$nextRole}");
        } else {
            NotifikasiHelper::kirim($pengajuan, $nextRole, $pesan);
        }

        return redirect()->route('ppk.show', $pengajuan->id);
    }

    public function approvedList()
    {

        $perPage = request('perPage', 10);

        // Ambil grup-grup yang sudah diapprove PPK
        $pengajuans = Pengajuan::with('items', 'user')
                ->where('ppk_id')
                ->latest()
                ->paginate($perPage);

        return view('ppk.approve', compact('pengajuans'));
    }
}
