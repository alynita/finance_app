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
                @if($p->jenis_pengajuan === 'pembelian')
                    <th>Nama Barang</th>
                    <th>Volume</th>
                    <th>KRO/Kode Akun</th>
                    <th>Harga Satuan</th>
                    <th>Jumlah Dana</th>
                    <th>Ongkos Kirim</th>
                @elseif($p->jenis_pengajuan === 'kerusakan')
                    <th>Nama Barang</th>
                    <th>Volume</th>
                    <th>Lokasi</th>
                    <th>Jenis Kerusakan</th>
                    <th>Harga Satuan</th>
                    <th>Jumlah Dana</th>
                    <th>Foto</th>
                @elseif($p->jenis_pengajuan === 'honor')
                    <th>Tanggal</th>
                    <th>Nama</th>
                    <th>Jabatan</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($p->items as $item)
                <tr>
                    @if($p->jenis_pengajuan === 'pembelian')
                        <td>{{ $item->nama_barang ?? $item->nama ?? '-' }}</td>
                        <td>{{ $item->volume ?? '-' }}</td>
                        <td>{{ $item->kro ?? '-' }}</td>
                        <td>{{ $item->harga_satuan ?? '-' }}</td>
                        <td>{{ $item->jumlah_dana_pengajuan ?? '-' }}</td>
                        <td>{{ $item->ongkos_kirim ?? '-' }}</td>
                    @elseif($p->jenis_pengajuan === 'kerusakan')
                        <td>{{ $item->nama_barang ?? $item->nama ?? '-' }}</td>
                        <td>{{ $item->volume ?? '-' }}</td>
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
                        <td>{{ $item->tanggal ?? '-' }}</td>
                        <td>{{ $item->nama ?? '-' }}</td>
                        <td>{{ $item->jabatan ?? '-'}}</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
            <a href="{{ route('pengadaan.download', $p->id) }}" 
            style="display:inline-flex; align-items:center; padding:0.5rem 1rem; background:#e53935; color:white; border-radius:4px; text-decoration:none; font-weight:bold; margin-bottom:1rem;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:20px; height:20px; margin-right:0.5rem;" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M5.5 0A1.5 1.5 0 0 0 4 1.5V14.5A1.5 1.5 0 0 0 5.5 16H10.5A1.5 1.5 0 0 0 12 14.5V1.5A1.5 1.5 0 0 0 10.5 0H5.5ZM5 1.5A.5.5 0 0 1 5.5 1h5a.5.5 0 0 1 .5.5V14.5a.5.5 0 0 1-.5.5h-5a.5.5 0 0 1-.5-.5V1.5Z"/>
                    <path d="M4.5 3.5h7v1h-7v-1zM4.5 5.5h7v1h-7v-1zM4.5 7.5h7v1h-7v-1z"/>
                </svg>
                Download PDF
            </a>
    </table>
    <hr>
@endforeach

@endsection
