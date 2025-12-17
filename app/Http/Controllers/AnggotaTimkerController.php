<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pengajuan;
use App\Models\Kro;
use Illuminate\Support\Facades\DB;

class AnggotaTimkerController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        // Ambil pengajuan miliknya saja
        $pengajuans = Pengajuan::where('user_id', $user->id)->get();

        return view('anggota_timker.dashboard', compact('user', 'pengajuans'));
    }

    public function daftarPengajuan()
    {
        $user = Auth::user();

        // hanya data miliknya
        $pengajuans = Pengajuan::where('user_id', $user->id)->get();

        return view('anggota_timker.index', compact('user', 'pengajuans'));
    }

    public function create()
    {
        $user = auth()->user();

        // Ambil semua KRO dari DB
        $kroData = DB::table('kro')->get();

        // Buat nested array
        $kroAll = $this->buildTree($kroData);

        return view('anggota_timker.create', compact('kroAll'));
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

    public function show($id)
    {
        $pengajuan = Pengajuan::with('items', 'user')->findOrFail($id);
        return view('anggota_timker.show', compact('pengajuan'));
    }

}
