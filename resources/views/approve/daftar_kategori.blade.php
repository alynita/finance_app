@extends('layouts.app')

@section('title', 'Daftar ' . ucfirst($kategori))
@section('header', 'Daftar ' . ucfirst($kategori))

@section('content')
<h2>Daftar {{ ucfirst($kategori) }}</h2>

<table border="1" cellpadding="10" cellspacing="0" style="width:100%; border-collapse: collapse;">
    <thead>
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
                <td>{{ ucfirst($pengajuan->status) }}</td>
                <td>
                    <!-- Sama seperti di dashboard, tombol Approve/Reject bisa ditambahkan jika perlu -->
                    <a href="{{ route('pegawai.pengajuan.show', $pengajuan->id) }}" style="background:#007bff; color:white; padding:5px 10px; border-radius:3px;">Lihat Detail</a>
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
