@extends('layouts.app')

@section('title', 'Detail Arsip Pengadaan')
@section('header', 'Detail Arsip Pengadaan')

@section('content')
<div style="max-width:1200px; margin:auto;">
    <h3 style="margin-bottom:0.5rem;">Detail Arsip Grup: <span style="color:#007bff;">{{ $group->group_name }}</span></h3>
    <p><strong>Pengajuan:</strong> {{ $group->pengajuan->nama_kegiatan }}</p>
    <p><strong>Pengaju:</strong> {{ $group->pengajuan->user->name }}</p>
    <p><strong>Status Grup:</strong> {{ ucfirst(str_replace('_',' ',$group->status)) }}</p>

    <div style="overflow-x:auto; margin-top:1rem; background:#fff; border-radius:8px; box-shadow:0 2px 5px rgba(0,0,0,0.1); padding:1rem;">
        @php
            $jenis = $group->items->first()?->tipe_item ?? 'pembelian';
        @endphp

        @if($jenis === 'pembelian')
            <table style="width:100%; border-collapse:collapse; min-width:900px; text-align:left;">
                <thead style="background:#007bff; color:white;">
                    <tr>
                        <th style="border:1px solid #ccc; padding:0.6rem;">No</th>
                        <th style="border:1px solid #ccc; padding:0.6rem;">Nama Barang</th>
                        <th style="border:1px solid #ccc; padding:0.6rem;">Volume</th>
                        <th style="border:1px solid #ccc; padding:0.6rem;">KRO / Kode Akun</th>
                        <th style="border:1px solid #ccc; padding:0.6rem;">Harga Satuan</th>
                        <th style="border:1px solid #ccc; padding:0.6rem;">Ongkos Kirim</th>
                        <th style="border:1px solid #ccc; padding:0.6rem;">Jumlah Dana</th>
                        <th style="border:1px solid #ccc; padding:0.6rem;">Foto / Keterangan</th>
                        <th style="border:1px solid #ccc; padding:0.6rem;">Link</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($group->items as $index => $item)
                    <tr>
                        <td style="border:1px solid #ccc; padding:0.5rem; text-align:center;">{{ $index + 1 }}</td>
                        <td style="border:1px solid #ccc; padding:0.5rem;">{{ $item->nama_barang }}</td>
                        <td style="border:1px solid #ccc; padding:0.5rem;">{{ $item->volume }}</td>
                        <td style="border:1px solid #ccc; padding:0.5rem;">{{ $item->kro ?? '-' }}</td>
                        <td style="border:1px solid #ccc; padding:0.5rem; text-align:right;">{{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                        <td style="border:1px solid #ccc; padding:0.5rem; text-align:right;">{{ number_format($item->ongkos_kirim, 0, ',', '.') }}</td>
                        <td style="border:1px solid #ccc; padding:0.5rem; text-align:right;">{{ number_format($item->jumlah_dana_pengajuan, 0, ',', '.') }}</td>
                        <td style="border:1px solid #ccc; padding:0.5rem;">{{ $item->foto ?? '-' }}</td>
                        <td style="border:1px solid #ccc; padding:0.5rem;">@if($item->link)<a href="{{ $item->link }}" target="_blank">Lihat</a>@endif</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @elseif($jenis === 'kerusakan')
            <table style="width:100%; border-collapse:collapse; min-width:900px; text-align:left;">
                <thead style="background:#007bff; color:white;">
                    <tr>
                        <th style="border:1px solid #ccc; padding:0.6rem;">No</th>
                        <th style="border:1px solid #ccc; padding:0.6rem;">Nama Barang</th>
                        <th style="border:1px solid #ccc; padding:0.6rem;">Volume</th>
                        <th style="border:1px solid #ccc; padding:0.6rem;">Lokasi</th>
                        <th style="border:1px solid #ccc; padding:0.6rem;">Jenis Kerusakan</th>
                        <th style="border:1px solid #ccc; padding:0.6rem;">Jumlah Dana</th>
                        <th style="border:1px solid #ccc; padding:0.6rem;">Foto / Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($group->items as $index => $item)
                    <tr>
                        <td style="border:1px solid #ccc; padding:0.5rem; text-align:center;">{{ $index + 1 }}</td>
                        <td style="border:1px solid #ccc; padding:0.5rem;">{{ $item->nama_barang }}</td>
                        <td style="border:1px solid #ccc; padding:0.5rem;">{{ $item->volume }}</td>
                        <td style="border:1px solid #ccc; padding:0.5rem;">{{ $item->lokasi ?? '-' }}</td>
                        <td style="border:1px solid #ccc; padding:0.5rem;">{{ $item->jenis_kerusakan ?? '-' }}</td>
                        <td style="border:1px solid #ccc; padding:0.5rem; text-align:right;">{{ number_format($item->jumlah_dana_pengajuan, 0, ',', '.') }}</td>
                        <td style="border:1px solid #ccc; padding:0.5rem;">{{ $item->foto ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection
