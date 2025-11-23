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

<!-- Pilih jumlah entri per halaman -->
    <form method="GET" action="{{ route(auth()->user()->role . '.pengajuan') }}" style="margin-bottom:15px;">
        <label for="perPage" style="font-weight:bold; margin-right:10px;">Tampilkan:</label>
        <select name="perPage" id="perPage" onchange="this.form.submit()" style="padding:4px;">
            @foreach([10, 25, 50, 100] as $size)
                <option value="{{ $size }}" {{ request('perPage', 10) == $size ? 'selected' : '' }}>
                    {{ $size }}
                </option>
            @endforeach
        </select>
        <span>entri</span>
    </form>

{{-- Tabel Daftar Pengajuan --}}
<table border="1" cellpadding="10" cellspacing="0" 
    style="width:100%; border-collapse: collapse; margin-top: 1rem;">
    <thead style="background-color:#f2f2f2;">
        <tr>
            <th>No</th>
            <th>Waktu Pengajuan</th>
            <th>Nama Pegawai</th>
            <th>Judul Pengajuan</th>
            <th>Status Terakhir</th>
            <th>Detail</th>
        </tr>
    </thead>
    <tbody>
        @forelse($pengajuans as $index => $pengajuan)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $pengajuan->created_at->format('d M Y H:i') }}</td>
                <td>{{ $pengajuan->user->name }}</td>
                <td>{{ $pengajuan->nama_kegiatan ?? '-' }}</td>
                <td>{{ ucfirst(str_replace('_',' ', $pengajuan->status)) }}</td>
                <td>
                    <a href="{{ route('pegawai.pengajuan.show', $pengajuan->id) }}"
                        style="background:#3498db; color:white; padding:5px 10px; 
                                text-decoration:none; border-radius:3px;">
                        Lihat Detail
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" style="text-align:center;">Belum ada data arsip</td>
            </tr>
        @endforelse
    </tbody>
</table>

@endsection
