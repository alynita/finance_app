<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kro;

class KroController extends Controller
{
    public function index()
    {
        $kro = Kro::with('children')->whereNull('parent_id')->get();
        return view('admin.kro.index', compact('kro'));
    }

    public function create()
    {
        return view('admin.kro.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:255',
            'kode_akun' => 'nullable|string|max:255|unique:kro,kode_akun',
            'parent_id' => 'nullable|exists:kro,id'
        ]);

        $kro = Kro::create([
            'kode' => $request->kode,
            'kode_akun' => $request->kode_akun,
            'parent_id' => $request->parent_id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'KRO berhasil ditambahkan',
            'kro' => $kro
        ]);
    }

    public function edit($id)
    {
        $kro = Kro::findOrFail($id);
        return view('admin.kro.index', compact('kro'));
    }

    public function update(Request $request, $id)
    {
        $kro = Kro::findOrFail($id);

        $request->validate([
            'kode' => 'nullable|string|max:255',
            'kode_akun' => 'nullable|string|max:255|unique:kros,kode_akun,' . $kro->id,
        ]);

        $kro->update($request->only('kode', 'kode_akun'));

        return response()->json(['success' => true, 'message' => 'KRO berhasil diperbarui']);
    }

    public function destroy($id)
    {
        $kro = Kro::findOrFail($id);
        $kro->delete();

        return response()->json([
            'success' => true,
            'message' => 'KRO berhasil dihapus'
        ]);
    }

    public function children() {
        return $this->hasMany(Kro::class, 'parent_id');
    }

}
