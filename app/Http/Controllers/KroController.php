<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kro;

class KroController extends Controller
{
    public function index()
    {
        $kro = Kro::all();
        return view('admin.kro.index', compact('kro'));
    }

    public function create()
    {
        return view('admin.kro.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'nullable|string|max:255',
            'kode_akun' => 'nullable|string|max:255|unique:kros,kode_akun,' . $kro->id,
        ]);

        Kro::create($request->all());
        return redirect()->route('admin.kro.index')->with('success', 'KRO berhasil ditambahkan.');
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

        if ($request->filled('kode')) {
            $kro->kode = $request->kode;
        }

        if ($request->filled('kode_akun')) {
            $kro->kode_akun = $request->kode_akun;
        }

        $kro->save();

        return response()->json(['success' => true, 'message' => 'KRO berhasil diperbarui']);
    }

    public function destroy($id)
    {
        Kro::findOrFail($id)->delete();
        return redirect()->route('admin.kro.index')->with('success', 'KRO berhasil dihapus.');
    }
}
