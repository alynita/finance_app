@extends('layouts.app')

@section('title', 'Laporan Pengajuan')
@section('header', 'Laporan Pengajuan')

@section('content')
<div style="max-width:1200px; margin:auto;">
    <h2>Laporan Pengajuan</h2>

    <div style="margin: 1rem 0;">
        <a href="{{ route('adum.laporan.pdf') }}" class="btn" style="background:#4CAF50; color:white; padding:0.5rem 1rem; border-radius:4px;">Export PDF</a>
        <a href="{{ route('adum.laporan.excel') }}" class="btn" style="background:#2196F3; color:white; padding:0.5rem 1rem; border-radius:4px;">Export Excel</a>
    </div>

    <table style="width:100%; border-collapse:collapse;">
        <thead>
            <tr>
                <th style="border:1px solid #ccc; padding:0.5rem;">No</th>
                <th style="border:1px solid #ccc; padding:0.5rem;">Nama Kegiatan</th>
                <th style="border:1px solid #ccc; padding:0.5rem;">Jenis Pengajuan</th>
                <th style="border:1px solid #ccc; padding:0.5rem;">Pengaju</th>
                <th style="border:1px solid #ccc; padding:0.5rem;">Status</th>
                <th style="border:1px solid #ccc; padding:0.5rem;">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pengajuans as $index => $p)
            <tr>
                <td style="border:1px solid #ccc; padding:0.5rem;">{{ $index + 1 }}</td>
                <td style="border:1px solid #ccc; padding:0.5rem;">{{ $p->nama_kegiatan }}</td>
                <td style="border:1px solid #ccc; padding:0.5rem;">{{ ucfirst($p->jenis_pengajuan) }}</td>
                <td style="border:1px solid #ccc; padding:0.5rem;">{{ $p->user->name }}</td>
                <td style="border:1px solid #ccc; padding:0.5rem;">{{ ucfirst($p->status) }}</td>
                <td style="border:1px solid #ccc; padding:0.5rem;">{{ $p->created_at->format('d-m-Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
