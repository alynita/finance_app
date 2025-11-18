<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\PengajuanItem;
use App\Models\PpkGroup;
use App\Models\KroAccount;
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
        $pendingProsesKeuangan = $pengajuans->where('jenis_pengajuan', 'proses_keuangan')->count();
        $pendingHonor = $pengajuans->where('jenis_pengajuan', 'honor')->count();

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
        $pengajuan = Pengajuan::with('items', 'user', 'ppkGroups.items')->findOrFail($id);

        // Ambil semua KRO dari DB
        $kroData = DB::table('kro')->get();

        // Buat nested array
        $kroAll = $this->buildTree($kroData);

        // Kirim juga $kroAccounts ke view
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

    // Approve semua pengajuan langsung tanpa pecah grup
    public function approveGroup($groupId)
    {
        $group = PpkGroup::with('items', 'pengajuan')->findOrFail($groupId);

        // Set status grup ke pending_pengadaan
        $group->status = 'pending_pengadaan';
        $group->save();

        $pengajuan = $group->pengajuan;

        // Cek semua grup sudah approve
        if($pengajuan->ppkGroups()->where('status','pending_ppk')->count() === 0){
            $pengajuan->status = 'pending_pengadaan';
            $pengajuan->ppk_id = auth()->id();
            $pengajuan->ppk_approved_at = now();
            $pengajuan->save();
        }

        return redirect()->route('ppk.show', $pengajuan->id);
    }

    public function approveAll($pengajuanId)
    {
        $pengajuan = Pengajuan::with('ppkGroups.items')->findOrFail($pengajuanId);
        
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

        return redirect()->route('ppk.show', $pengajuan->id);
    }

    public function storeGroup(Request $request, $id)
    {
        $pengajuan = Pengajuan::with('items')->findOrFail($id);

        $groupNames = $request->group_name;
        $groupsItems = $request->groups;

        foreach ($groupNames as $index => $name) {
            $group = PpkGroup::create([
                'pengajuan_id' => $pengajuan->id,
                'group_name' => $name,
                'status' => 'pending_ppk',
            ]);

            if (isset($groupsItems[$index])) {
                // Ambil ulang item dari DB agar KRO terbarunya ikut
                $itemIds = $groupsItems[$index];
                $items = \App\Models\PengajuanItem::whereIn('id', $itemIds)->get();

                // Pastikan data KRO tersimpan di pivot atau log
                foreach ($items as $item) {
                    $group->items()->attach($item->id);
                }
            }
        }

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

    public function approvedList()
    {
        // Ambil grup-grup yang sudah diapprove PPK
        $groups = PpkGroup::where('status', 'pending_pengadaan')
                    ->with('pengajuan.user', 'items')
                    ->get();

        return view('ppk.approve', compact('groups'));
    }

}
