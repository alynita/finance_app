@extends('layouts.app')

@section('content')
<div style="max-width: 1000px; margin:auto; padding:20px; font-family:Arial, sans-serif;">
    <h2 style="text-align:center; margin-bottom:30px;">DAFTAR LAPORAN HONOR KEGIATAN</h2>

    <!-- Pilih jumlah entri per halaman -->
    <form method="GET" action="{{ route('keuangan.honor.index.laporan') }}" style="margin-bottom:15px;">
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

    <table style="width:100%; border-collapse:collapse; border:1px solid #000; font-size:14px;">
        <thead>
            <tr style="background-color:#e0e0e0; text-align:center; font-weight:bold;">
                <th style="border:1px solid #000; padding:8px; width:50px;">No</th>
                <th style="border:1px solid #000; padding:8px;">Nama Kegiatan</th>
                <th style="border:1px solid #000; padding:8px; width:150px;">Tanggal Pengajuan</th>
                <th style="border:1px solid #000; padding:8px; width:120px;">Aksi</th>
            </tr>
        </thead>

        <tbody>
            @foreach($honors as $index => $row)
            <tr style="text-align:left;">
                <td style="border:1px solid #000; padding:6px; text-align:center;">{{ $index + 1 }}</td>
                <td style="border:1px solid #000; padding:6px;">{{ $row->nama_kegiatan }}</td>
                <td style="border:1px solid #000; padding:6px; text-align:center;">{{ $row->created_at->format('d-m-Y') }}</td>
                <td style="border:1px solid #000; padding:6px; text-align:center;">
                    <a href="{{ route('keuangan.honor.detail.laporan', $row->id) }}" 
                        style="text-decoration:none; padding:4px 8px; background:#0E7C3A; color:white; border-radius:3px; font-size:13px;">
                        Lihat Detail
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
