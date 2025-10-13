@extends('layouts.app')

@section('title', 'Dashboard Keuangan')
@section('header', 'Dashboard Keuangan')

@section('content')
<h2>Daftar Pengajuan Siap Diproses</h2>

<table border="1" cellpadding="10" cellspacing="0" style="width:100%; border-collapse: collapse;">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Kegiatan</th>
            <th>Jenis Pengajuan</th>
            <th>Pengaju</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($pengajuans as $index => $pengajuan)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $pengajuan->nama_kegiatan }}</td>
                <td>{{ ucfirst($pengajuan->jenis_pengajuan) }}</td>
                <td>{{ $pengajuan->user->name }}</td>
                <td>{{ ucfirst($pengajuan->status) }}</td>
                <td>
                    <a href="{{ route('keuangan.proses', $pengajuan->id) }}" style="padding:5px 10px; background:blue; color:white; border-radius:3px;">Proses</a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" style="text-align:center;">Belum ada pengajuan</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection
