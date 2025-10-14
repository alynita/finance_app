@extends('layouts.app')

@section('title','Arsip Pengajuan')
@section('header','Arsip Pengajuan')

@section('content')
<h2>Daftar Arsip Pengajuan</h2>

@if(session('success'))
    <div style="color:green;">{{ session('success') }}</div>
@endif

@foreach($pengajuans as $p)
    <h3>{{ $p->nama_kegiatan }} ({{ ucfirst($p->jenis_pengajuan) }}) - {{ ucfirst($p->status) }}</h3>
    <p>Pengaju: {{ $p->user->name }} | Tanggal: {{ $p->created_at->format('d-m-Y') }}</p>

    <table border="1" cellpadding="8" cellspacing="0" style="border-collapse:collapse; width:100%; margin-bottom:1rem;">
        <thead>
            <tr style="background:#f2f2f2;">
                <th>Nama Barang</th>
                @if($p->jenis_pengajuan !== 'honor')
                    <th>Volume</th>
                @endif

                @if($p->jenis_pengajuan === 'pembelian')
                    <th>KRO/Kode Akun</th>
                    <th>Harga Satuan</th>
                    <th>Jumlah Dana</th>
                    <th>Ongkos Kirim</th>
                @elseif($p->jenis_pengajuan === 'kerusakan')
                    <th>Lokasi</th>
                    <th>Jenis Kerusakan</th>
                    <th>Harga Satuan</th>
                    <th>Jumlah Dana</th>
                    <th>Foto</th>
                @elseif($p->jenis_pengajuan === 'honor')
                    <th>Keterangan</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($p->items as $item)
                <tr>
                    <td>{{ $item->nama_barang ?? $item->nama ?? '-' }}</td>
                    @if($p->jenis_pengajuan !== 'honor')
                        <td>{{ $item->volume ?? '-' }}</td>
                    @endif

                    @if($p->jenis_pengajuan === 'pembelian')
                        <td>{{ $item->kro ?? '-' }}</td>
                        <td>{{ $item->harga_satuan ?? '-' }}</td>
                        <td>{{ $item->jumlah_dana_pengajuan ?? '-' }}</td>
                        <td>{{ $item->ongkos_kirim ?? '-' }}</td>
                    @elseif($p->jenis_pengajuan === 'kerusakan')
                        <td>{{ $item->lokasi ?? '-' }}</td>
                        <td>{{ $item->jenis_kerusakan ?? '-' }}</td>
                        <td>{{ $item->harga_satuan ?? '-' }}</td>
                        <td>{{ $item->jumlah_dana_pengajuan ?? '-' }}</td>
                        <td>
                            @if($item->foto)
                                <a href="{{ asset('storage/' . $item->foto) }}" target="_blank">Lihat</a>
                            @else
                                -
                            @endif
                        </td>
                    @elseif($p->jenis_pengajuan === 'honor')
                        <td>{{ $item->jabatan ?? $item->nama ?? '-' }}</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
    <hr>
@endforeach

@endsection
