<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\PpkGroup;

class PengadaanController extends Controller
{
    // Dashboard: tampilkan semua grup yang pending_pengadaan
    public function dashboard()
    {
        $groups = PpkGroup::where('status', 'pending_pengadaan')
            ->with('pengajuan.user', 'items')
            ->get();

        return view('pengadaan.dashboard', compact('groups'));
    }

    // Lihat detail per grup
    public function showGroup($groupId)
    {
        $group = PpkGroup::with('items', 'pengajuan.user')->findOrFail($groupId);
        return view('pengadaan.showGroup', compact('group'));
    }

    // Update items per grup
    public function updateItems(Request $request, $groupId)
    {
        $group = PpkGroup::with('items')->findOrFail($groupId);

        foreach ($request->items ?? [] as $itemId => $data) {
            // ambil item dari koleksi grup
            $item = $group->items->where('id', $itemId)->first();
            if (!$item) continue;

            // update fields, cek dulu apakah ada key-nya
            $item->volume = $data['volume'] ?? $item->volume;
            $item->harga_satuan = $data['harga_satuan'] ?? $item->harga_satuan;
            $item->ongkos_kirim = $data['ongkos_kirim'] ?? $item->ongkos_kirim;

            // hitung ulang jumlah dana pengajuan otomatis
            if ($group->pengajuan->jenis_pengajuan === 'pembelian') {
                $item->jumlah_dana_pengajuan = ($item->volume * $item->harga_satuan) + $item->ongkos_kirim;
            } else {
                // untuk kerusakan, biasanya jumlah_dana_pengajuan = volume * harga_satuan
                $item->jumlah_dana_pengajuan = ($item->volume * $item->harga_satuan);
            }

            // update KRO & link
            if (isset($data['kro'])) $item->kro = $data['kro'];
            if (isset($data['link'])) $item->link = $data['link'];

            // update kerusakan fields
            if (isset($data['lokasi'])) $item->lokasi = $data['lokasi'];
            if (isset($data['jenis_kerusakan'])) $item->jenis_kerusakan = $data['jenis_kerusakan'];

            // update foto
            if (isset($data['foto'])) $item->foto = $data['foto'];

            $item->save();
        }

        return redirect()->route('pengadaan.showGroup', $group->id)
                        ->with('success','Data berhasil diperbarui dan jumlah dana sudah otomatis dihitung.');
    }

    public function arsip()
    {
        $groups = PpkGroup::where('is_arsip', 1)
                        ->with('pengajuan.user')
                        ->get();
        return view('pengadaan.arsip', compact('groups'));
    }

    // Submit grup ke keuangan
    public function submitToKeuangan($id)
    {
        $group = PpkGroup::with('pengajuan')->findOrFail($id);

        // ubah status grup
        $group->status = 'submitted_keuangan';
        $group->is_arsip = 1;
        $group->save();

        // ubah status pengajuan juga
        $pengajuan = $group->pengajuan;
        if ($pengajuan) {
            $pengajuan->status = 'submitted_keuangan';
            $pengajuan->save();
        }

        return redirect()->route('pengadaan.arsip')
                        ->with('success', 'Grup telah dikirim ke keuangan dan pengajuan telah diperbarui.');
    }

    public function showArsip($id)
    {
        $group = PpkGroup::with('pengajuan', 'items')->findOrFail($id);
        return view('pengadaan.show_arsip', compact('group'));
    }

}
