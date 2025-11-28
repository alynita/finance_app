@extends('layouts.app')

@section('title', 'Dashboard PPK')
@section('header', 'Dashboard PPK')

@section('content')
<div style="max-width:1200px; margin:auto;">

<!-- Ucapan Selamat Datang -->
    <div style="background:#eaf3ea; padding:1.5rem; border-radius:10px; margin-bottom:20px; border-left:6px solid #2e7d32;">
        <h2 style="margin:0; color:#1b5e20;">Selamat datang, {{ Auth::user()->name }}👋</h2>
        <p style="margin:5px 0 0 0; color:#333;">
            Proses Persetujuan Pengajuan dengan mudah dan efisien.
        </p>
    </div>

    <h3>Daftar Pengajuan Masuk PPK</h3>

    <!-- CARD -->
    <div style="display:flex; gap:15px; margin-bottom:20px;">
        <div style="flex:1; background:white; padding:15px; border-radius:8px; box-shadow:0 0 5px rgba(0,0,0,0.1); text-align:center;">
            <h4>Pengadaan & Kerusakan Barang</h4>
            <p style="font-size:24px; font-weight:bold;">{{ $pendingPembelian }}</p>
        </div>

        <div style="flex:1; background:white; padding:15px; border-radius:8px; box-shadow:0 0 5px rgba(0,0,0,0.1); text-align:center;">
            <h4>Proses Keuangan</h4>
            <p style="font-size:24px; font-weight:bold;">{{ $pendingProsesKeuangan }}</p>
        </div>

        <div style="flex:1; background:white; padding:15px; border-radius:8px; box-shadow:0 0 5px rgba(0,0,0,0.1); text-align:center;">
            <h4>Honor</h4>
            <p style="font-size:24px; font-weight:bold;">{{ $pendingHonor }}</p>
        </div>
    </div>

    <!-- SUMMARY -->
    <div style="display:flex; gap:15px; margin-bottom:20px;">
        <div style="flex:1; background:#f9f9f9; padding:10px; border-radius:6px; text-align:center;">
            <strong>Total Pending</strong>
            <p>{{ $totalPending }}</p>
        </div>
        <div style="flex:1; background:#f9f9f9; padding:10px; border-radius:6px; text-align:center;">
            <strong>Total Approved</strong>
            <p>{{ $totalApproved }}</p>
        </div>
        <div style="flex:1; background:#f9f9f9; padding:10px; border-radius:6px; text-align:center;">
            <strong>Total Rejected</strong>
            <p>{{ $totalRejected }}</p>
        </div>
    </div>

    <!-- TABEL DAFTAR PENGAJUAN -->
    @if($pengajuans->isEmpty())
        <p>Tidak ada pengajuan yang menunggu persetujuan.</p>
    @else
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="background:#f2f2f2;">
                    <th style="border:1px solid #ccc; padding:0.5rem;">No</th>
                    <th style="border:1px solid #ccc; padding:0.5rem;">Nama Kegiatan</th>
                    <th style="border:1px solid #ccc; padding:0.5rem;">Diajukan Oleh</th>
                    <th style="border:1px solid #ccc; padding:0.5rem;">Tanggal Pengajuan</th>
                    <th style="border:1px solid #ccc; padding:0.5rem;">Status</th>
                    <th style="border:1px solid #ccc; padding:0.5rem;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pengajuans as $key => $pengajuan)
                    <tr>
                        <td style="border:1px solid #ccc; padding:0.5rem;">{{ $key + 1 }}</td>
                        <td style="border:1px solid #ccc; padding:0.5rem;">{{ $pengajuan->nama_kegiatan ?? '-' }}</td>
                        <td style="border:1px solid #ccc; padding:0.5rem;">{{ $pengajuan->user->name ?? '-' }}</td>
                        <td style="border:1px solid #ccc; padding:0.5rem;">{{ $pengajuan->created_at->format('d M Y') }}</td>
                        <td style="border:1px solid #ccc; padding:0.5rem;">{{ ucfirst(str_replace('_',' ',$pengajuan->status)) }}</td>
                        <td style="border:1px solid #ccc; padding:0.5rem;">
                            <a href="{{ route('ppk.show', $pengajuan->id) }}" 
                                style="background:#007bff; color:white; padding:4px 8px; border-radius:4px; text-decoration:none;">
                                Lihat Detail
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
