<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenanggungJawab;
use App\Models\Pengajuan;

class PenanggungJawabController extends Controller
{
    // Dashboard PJ
    public function dashboard()
    {
        $user = auth()->user();

        // Semua pengajuan menunggu PJ
        $pengajuans = Pengajuan::with('user', 'items')
                        ->where('status', 'pending_pj')
                        ->get();

        return view('pegawai.penanggung_jawab.dashboard', compact('pengajuans', 'user'));
    }

    // PJ approve pengajuan
    public function approve($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        $user = auth()->user();

        // isi pj_id saat approve
        $pengajuan->pj_id = $user->id;
        $pengajuan->pj_approved_at = now();
        $pengajuan->status = 'pending_adum'; // lanjut ke ADUM
        $pengajuan->save();

        return redirect()->back()->with('success', 'Pengajuan berhasil diapprove PJ!');
    }

    // PJ reject pengajuan
    public function reject($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        $user = auth()->user();

        $pengajuan->pj_id = $user->id;
        $pengajuan->status = 'rejected_pj';
        $pengajuan->pj_approved_at = now();
        $pengajuan->save();

        return redirect()->back()->with('error', 'Pengajuan ditolak PJ.');
    }

    // Daftar PJ
    public function index()
    {
        $pj = PenanggungJawab::all();
        return view('pegawai.penanggung_jawab.index', compact('pj'));
    }

    public function create()
    {
        return view('pegawai.penanggung_jawab.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'required|string|max:50',
            'jabatan' => 'required|string|max:255',
        ]);

        PenanggungJawab::create([
            'nama' => $request->nama,
            'nip' => $request->nip,
            'jabatan' => $request->jabatan,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('pegawai.penanggung_jawab.index')->with('success', 'Penanggung Jawab berhasil ditambahkan.');
    }
}
