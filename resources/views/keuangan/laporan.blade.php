@extends('layouts.app')

@section('title', 'Laporan Keuangan')
@section('header', 'Laporan Keuangan')

@section('content')
<h2>Laporan Keuangan</h2>

<table border="1" cellpadding="10" cellspacing="0" style="width:100%; border-collapse: collapse;">
    <thead>
        <tr>
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
        @foreach($pengajuans as $index => $pengajuan)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $pengajuan->nama_kegiatan }}</td>
            <td>{{ $pengajuan->waktu_kegiatan }}</td>
            <td>{{ ucfirst($pengajuan->jenis_pengajuan) }}</td>
            <td>{{ $pengajuan->user->name }}</td>
            <td>{{ ucfirst($pengajuan->status) }}</td>
            <td>
                <a href="{{ route('keuangan.laporan_detail', $pengajuan->id) }}" style="padding:5px 10px; background:#4CAF50; color:white; border-radius:3px;">Lihat Detail</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
