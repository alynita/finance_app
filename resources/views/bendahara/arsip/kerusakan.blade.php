@extends('layouts.app')

@section('title', 'Daftar Arsip Pengadaan Barang')
@section('header', 'Daftar Arsip Pengadaan Barang')

@section('content')
<div style="max-width:900px; margin:auto;">
    <table style="width:100%; border-collapse: collapse;">
        <thead>
            <tr style="background:#f2f2f2;">
                <th style="border:1px solid #ccc; padding:0.5rem;">Nama Kegiatan</th>
                <th style="border:1px solid #ccc; padding:0.5rem;">Waktu Kegiatan</th>
                <th style="border:1px solid #ccc; padding:0.5rem;">Jenis Pengajuan</th>
                <th style="border:1px solid #ccc; padding:0.5rem;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kerusakans as $kerusakan)
            <tr>
                <td style="border:1px solid #ccc; padding:0.5rem;">{{ $pengadaan->nama_kegiatan }}</td>
                <td style="border:1px solid #ccc; padding:0.5rem;">{{ $pengadaan->waktu_kegiatan }}</td>
                <td style="border:1px solid #ccc; padding:0.5rem;">Kerusakan Barang</td>
                <td style="border:1px solid #ccc; padding:0.5rem;">
                    <a href="{{ route('bendahara.laporan.show', $pengadaan->id ) }}" style="background:#3490dc; color:white; padding:0.3rem 0.6rem; border-radius:4px; text-decoration:none;">Lihat Detail</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
