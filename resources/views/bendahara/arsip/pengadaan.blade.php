@extends('layouts.app')

@section('title', 'Daftar Arsip Pengadaan Barang')
@section('header', 'Daftar Arsip Pengadaan Barang')

@section('content')
<div style="max-width:1000px; margin:auto; padding:20px;">
    <h2 style="margin-bottom:20px;">Daftar Arsip Pengadaan Barang</h2>

    <!-- Pilih jumlah entri per halaman -->
    <form method="GET" action="{{ route('bendahara.arsip.pengadaan.list') }}" style="margin-bottom:15px;">
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

    <table style="width:100%; border-collapse: collapse; border:1px solid #ccc;">
        <thead style="background:#f2f2f2;">
            <tr>
                <th style="border:1px solid #ccc; padding:0.5rem;">No</th>
                <th style="border:1px solid #ccc; padding:0.5rem;">Nama Kegiatan</th>
                <th style="border:1px solid #ccc; padding:0.5rem;">Waktu Kegiatan</th>
                <th style="border:1px solid #ccc; padding:0.5rem;">Jenis Pengajuan</th>
                <th style="border:1px solid #ccc; padding:0.5rem;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pengadaans as $pengadaan)
            <tr>
                <td style="border:1px solid #ccc; padding:0.5rem;">{{ $loop->iteration + ($pengadaans->currentPage()-1)*$pengadaans->perPage() }}</td>
                <td style="border:1px solid #ccc; padding:0.5rem;">{{ $pengadaan->pengajuan->nama_kegiatan }}</td>
                <td style="border:1px solid #ccc; padding:0.5rem;">{{ $pengadaan->pengajuan->waktu_kegiatan }}</td>
                <td style="border:1px solid #ccc; padding:0.5rem;">Pengadaan Barang</td>
                <td style="border:1px solid #ccc; padding:0.5rem;">
                    <a href="{{ route('bendahara.laporan.show', $pengadaan->id) }}"
                        style="background:#3490dc; color:white; padding:0.3rem 0.6rem; border-radius:4px; text-decoration:none; margin-right:5px;">
                        Lihat Detail
                    </a>
                    <a href="{{ route('bendahara.laporan.download.pdf', $pengadaan->id) }}"
                        style="background:green; color:white; padding:0.3rem 0.6rem; border-radius:4px; text-decoration:none;">
                        Download PDF
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pagination -->
    <div style="margin-top:10px;">
        {{ $pengadaans->appends(['perPage' => request('perPage', 10)])->links() }}
    </div>
</div>
@endsection
