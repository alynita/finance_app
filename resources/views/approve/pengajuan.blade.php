@extends('layouts.app')

@section('title', 'Daftar Pengajuan')
@section('header', 'Daftar Pengajuan')

@section('content')
@if(session('success'))
    <div style="background-color: #d4edda; color: #155724; padding: 10px; margin-bottom: 10px; border-radius: 5px;">
        {{ session('success') }}
    </div>
@endif

<h2 style="margin-bottom: 1rem;">Daftar Pengajuan</h2>

<div style="display: flex; gap: 1rem; margin-bottom: 2rem;">
    {{-- Kategori Pengadaan & Kerusakan Barang --}}
    <div style="flex: 1; background: #fff; padding: 1rem; border-radius: 6px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
        <h3>Pengadaan & Kerusakan Barang</h3>
        <p>Total: {{ $totalPengadaanBarang ?? 0 }}</p>
        <a href="{{ route('adum.pengajuan.kategori', 'pengadaan') }}" 
           style="display:inline-block; margin-top: 0.5rem; padding:0.5rem 1rem; background:#007bff; color:#fff; text-decoration:none; border-radius:4px;">
           Lihat Semua
        </a>
    </div>

    {{-- Kategori Proses Keuangan --}}
    <div style="flex: 1; background: #fff; padding: 1rem; border-radius: 6px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
        <h3>Proses Keuangan</h3>
        <p>Total: {{ $totalProsesKeuangan ?? 0 }}</p>
        <a href="{{ route('adum.pengajuan.kategori', 'proses_keuangan') }}" 
           style="display:inline-block; margin-top: 0.5rem; padding:0.5rem 1rem; background:#007bff; color:#fff; text-decoration:none; border-radius:4px;">
           Lihat Semua
        </a>
    </div>

    {{-- Kategori Honor --}}
    <div style="flex: 1; background: #fff; padding: 1rem; border-radius: 6px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
        <h3>Honor</h3>
        <p>Total: {{ $totalHonor ?? 0 }}</p>
        <a href="{{ route('adum.pengajuan.kategori', 'honor') }}" 
            style="display:inline-block; margin-top: 0.5rem; padding:0.5rem 1rem; background:#007bff; color:#fff; text-decoration:none; border-radius:4px;">
            Lihat Semua
        </a>
    </div>
</div>

{{-- Tabel Daftar Pengajuan --}}
<table border="1" cellpadding="10" cellspacing="0" style="width:100%; border-collapse: collapse; margin-top: 1rem;">
    <thead style="background-color:#f2f2f2;">
        <tr>
            <th>No</th>
            <th>Nama Pegawai</th>
            <th>Judul Pengajuan</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($pengajuans as $index => $pengajuan)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $pengajuan->user->name }}</td>
                <td>{{ $pengajuan->nama_kegiatan ?? '-' }}</td>
                <td>{{ ucfirst(str_replace('_',' ', $pengajuan->status)) }}</td>
                <td>
                    @if(auth()->user()->role == 'adum' && $pengajuan->status == 'pending_adum' ||
                        auth()->user()->role == 'ppk' && $pengajuan->status == 'pending_ppk')

                        <form method="POST" action="{{ route(auth()->user()->role.'.approve', $pengajuan->id) }}" style="display:inline;">
                            @csrf
                            <button type="submit" style="background: green; color: white; padding: 5px 10px; border:none; border-radius:3px;">Approve</button>
                        </form>

                        <form method="POST" action="{{ route(auth()->user()->role.'.reject', $pengajuan->id) }}" style="display:inline;">
                            @csrf
                            <button type="submit" style="background: red; color: white; padding: 5px 10px; border:none; border-radius:3px;">Reject</button>
                        </form>

                    @else
                        Sudah Approve
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" style="text-align:center;">Belum ada pengajuan</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection
