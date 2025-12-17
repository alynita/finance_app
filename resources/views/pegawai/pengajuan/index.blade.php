@extends('layouts.app')

@section('title', 'Daftar Pengajuan')
@section('header', 'Daftar Pengajuan')

@section('content')
<div style="max-width:900px; margin:auto;">

    <!-- Pilih jumlah entri per halaman -->
    <form method="GET" action="{{ route('pegawai.daftar-pengajuan') }}" style="margin-bottom:15px;">
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
    
    <table style="width:100%; border-collapse: collapse;">
        <thead>
            <tr style="background:#f2f2f2;">
                <th style="border:1px solid #ccc; padding:0.5rem;">Nomor</th>
                <th style="border:1px solid #ccc; padding:0.5rem;">Nama Kegiatan</th>
                <th style="border:1px solid #ccc; padding:0.5rem;">Waktu Kegiatan</th>
                <th style="border:1px solid #ccc; padding:0.5rem;">Jenis Pengajuan</th>
                <th style="border:1px solid #ccc; padding:0.5rem;">Aksi</th>
            </tr>
        </thead>
        <tbody>
        @foreach($pengajuans as $pengajuan)
            <tr>
                <td style="border:1px solid #ccc; padding:0.5rem; font-weight:bold;">
                    {{ $pengajuan->kode_pengajuan }}
                </td>

                <td style="border:1px solid #ccc; padding:0.5rem;">
                    {{ $pengajuan->nama_kegiatan }}
                </td>

                <td style="border:1px solid #ccc; padding:0.5rem;">
                    {{ $pengajuan->waktu_kegiatan }}
                </td>

                <td style="border:1px solid #ccc; padding:0.5rem;">
                    {{ ucfirst($pengajuan->jenis_pengajuan) }}
                </td>

                <td style="border:1px solid #ccc; padding:0.5rem;">
                    <a href="{{ route('pegawai.pengajuan.show', $pengajuan->id) }}"
                    style="background:#3490dc; color:white; padding:0.3rem 0.6rem; border-radius:4px; text-decoration:none;">
                        Lihat Detail
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
