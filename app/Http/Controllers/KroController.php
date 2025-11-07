<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KroAccount;

class KroController extends Controller
{
    public function index(Request $request)
    {
        $query = KroAccount::query();

        if ($request->has('search')) {
            $query->where('value', 'like', '%'.$request->search.'%')
                ->orWhere('kro', 'like', '%'.$request->search.'%')
                ->orWhere('kode_akun', 'like', '%'.$request->search.'%');
        }

        $kros = $query->get();

        return view('admin.kro.index', compact('kros'));
    }

    //Tambah KRO
    public function store(Request $request)
    {
        $request->validate([
            'value' => 'required|string|unique:kro_accounts,value',
        ]);

        KroAccount::create([
            'value' => $request->value,
            'kro' => null, // optional, bisa diisi nanti
            'kode_akun' => null, // optional, bisa diisi nanti
        ]);

        return redirect()->back()->with('success', 'KRO berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $kro = KroAccount::findOrFail($id);
        return view('admin.kro.edit', compact('kro'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'value' => 'required|string|unique:kro_accounts,value,' . $id,
        ]);

        $kro = KroAccount::findOrFail($id);
        $kro->value = $request->value;
        $kro->save();

        return redirect()->back()->with('success', 'KRO berhasil diperbarui!');
    }

    public function destroy($id)
    {
        KroAccount::destroy($id);
        return back()->with('success', 'Data KRO berhasil dihapus.');
    }
}
