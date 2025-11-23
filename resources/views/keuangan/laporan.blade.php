@extends('layouts.app')

@section('title', 'Laporan Keuangan')
@section('header', 'Laporan Keuangan')

@section('content')
<h2>Laporan Keuangan</h2>

<!-- Pilih jumlah entri per halaman -->
    <form method="GET" action="{{ route('keuangan.laporan') }}" style="margin-bottom:15px;">
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

@if(session('success'))
    <div style="background-color:#d4edda; color:#155724; padding:10px; margin-bottom:10px; border-radius:5px;">
        {{ session('success') }}
    </div>
@endif

@if($ppkGroups->count())
    <table border="1" cellpadding="8" cellspacing="0" width="100%">
        <thead>
            <tr style="background-color:#f2f2f2;">
                <th>No</th>
                <th>Nama Kegiatan</th>
                <th>Waktu Kegiatan</th>
                <th>Jenis Pengajuan</th>
                <th>Nama Pengaju</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ppkGroups as $index => $group)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $group->pengajuan->nama_kegiatan ?? '-' }}</td>
                    <td>{{ $group->pengajuan->waktu_kegiatan ?? '-' }}</td>
                    <td>{{ ucfirst($group->pengajuan->jenis_pengajuan ?? '-') }}</td>
                    <td>{{ $group->pengajuan->user->name ?? '-' }}</td>
                    <td>{{ ucfirst($group->status) }}</td>
                    <td>
                        <a href="{{ route('keuangan.laporan_detail', $group->id) }}" 
                            style="padding:5px 10px; background:#4CAF50; color:white; border-radius:3px;">
                            Lihat Detail
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <p style="text-align:center; color:gray;">Belum ada data laporan keuangan.</p>
@endif

@endsection
