<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\PengajuanItem;
use App\Models\PpkGroup;

class PpkController extends Controller
{
    // Dashboard PPK
    public function dashboard()
    {
        $pengajuans = \App\Models\Pengajuan::with('user', 'items')
        ->where('status', 'pending_ppk')
        ->get();

        return view('ppk.dashboard', compact('pengajuans'));
    }

    public function show($id)
    {
        $pengajuan = Pengajuan::with('items', 'user', 'ppkGroups.items')->findOrFail($id);
        return view('ppk.show', compact('pengajuan'));
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
        $pengajuan = Pengajuan::findOrFail($id);

        $groupNames = $request->group_name;
        $groupsItems = $request->groups;

        foreach ($groupNames as $index => $name) {
            $group = PpkGroup::create([
                'pengajuan_id' => $pengajuan->id,
                'group_name' => $name,
                'status' => 'pending_ppk',
            ]);

            if(isset($groupsItems[$index])) {
                $group->items()->attach($groupsItems[$index]);
            }
        }

        if($request->approve_all) {
            foreach($pengajuan->ppkGroups as $group) {
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
