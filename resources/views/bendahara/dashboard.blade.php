@extends('layouts.app')

@section('title', 'Dashboard Bendahara')
@section('header', 'Daftar Laporan Keuangan')

@section('content')
<div style="max-width:1000px; margin:auto;">
    <h3 style="margin-bottom:20px;">Laporan yang Sudah Diproses</h3>

    <table border="1" cellpadding="10" cellspacing="0" style="width:100%; border-collapse:collapse;">
        <thead style="background:#f2f2f2;">
            <tr>
                <th>No</th>
                <th>Nama Kegiatan</th>
                <th>Waktu Kegiatan</th>
                <th>Jenis Pengajuan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($laporans as $i => $laporan)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $laporan->nama_kegiatan }}</td>
                    <td>{{ $laporan->waktu_kegiatan }}</td>
                    <td>{{ ucfirst($laporan->jenis_pengajuan) }}</td>
                    <td style="color:green; font-weight:bold;">{{ ucfirst($laporan->status) }}</td>
                    <td>
                        <a href="{{ route('bendahara.laporan.show', $laporan->id) }}">Lihat Detail</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center;">Belum ada laporan yang diproses.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
