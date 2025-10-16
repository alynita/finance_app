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
                <th>Aksi Detail</th>
                <th>Aksi Arsip</th>
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
                    <!-- Kolom Aksi Detail -->
                    <td>
                        <a href="{{ route('bendahara.laporan.show', $laporan->id) }}" 
                            style="padding:5px 10px; background-color:#28a745; color:white; border-radius:3px; text-decoration:none;">
                            Lihat Detail
                        </a>
                    </td>
                    <!-- Kolom Aksi Arsip -->
                    <td>
                        @if($laporan->adum_approved_process && $laporan->ppk_approved_process && $laporan->verifikator_approved_process && !$laporan->arsip)
                            <form action="{{ route('bendahara.simpan-arsip', $laporan->id) }}" method="POST">
                                @csrf
                                <button type="submit" onclick="return confirm('Yakin ingin mengarsipkan pengajuan ini?')" 
                                        style="padding:5px 10px; background-color:#007bff; color:white; border:none; border-radius:3px;">
                                    Simpan Arsip
                                </button>
                            </form>
                        @elseif($laporan->arsip)
                            <span style="color:green; font-weight:bold;">Sudah diarsipkan</span>
                        @else
                            <span style="color:red;">Menunggu semua approval</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align:center;">Belum ada laporan yang diproses.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
