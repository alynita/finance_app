@extends('layouts.app')

@section('content')

<h2 style="margin-bottom: 20px;">Arsip Honor (Approved)</h2>

<style>
    table.tabel-border {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
        font-size: 14px;
    }
    table.tabel-border th,
    table.tabel-border td {
        border: 1px solid #000;
        padding: 8px;
        text-align: left;
    }
    table.tabel-border th {
        background: #f2f2f2;
        font-weight: bold;
    }
</style>
<!-- Pilih jumlah entri per halaman -->
    <form method="GET" action="{{ route('verifikator.arsip') }}" style="margin-bottom:15px;">
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

<table class="tabel-border">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Kegiatan</th>
            <th>Waktu Kegiatan</th>
            <th>Penanggung Jawab</th>
            <th>Aksi</th>
        </tr>
    </thead>

    <tbody>
        @foreach($honors as $i => $h)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $h->nama_kegiatan }}</td>
                <td>{{ \Carbon\Carbon::parse($h->waktu)->format('d-m-Y') }}</td>
                <td>{{ $h->user->name ?? '-' }}</td>
                <td>
                    <a href="{{ route('verifikator.honor.detail', $h->id) }}">
                        Detail
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@endsection
