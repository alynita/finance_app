@extends('layouts.app')

@section('title', 'Draf PPK')
@section('header', 'Grup PPK yang Sudah Dibuat')

@section('content')
<div style="max-width:1000px; margin:auto;">
    <h3 class="mb-3">Draf</h3>

    @if($groups->isEmpty())
        <p>Tidak ada grup yang menunggu persetujuan.</p>
    @else
        <table style="width:100%; border-collapse: collapse;" border="1">
            <thead style="background:#f2f2f2;">
                <tr>
                    <th>No</th>
                    <th>Nama Kegiatan</th>
                    <th>Pengaju</th>
                    <th>Nama Barang</th>
                    <th>Volume</th>
                    <th>KRO / Kode Akun</th>
                    <th>Ongkos Kirim</th>
                    <th>Jumlah Dana</th>
                    <th>Link / Foto</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($groups as $i => $group)
                    @php
                        $pengajuan = $group->pengajuan;
                    @endphp
                    @foreach($group->items as $j => $item)
                        <tr>
                            @if($j === 0)
                                <td rowspan="{{ $group->items->count() }}" style="text-align:center; font-weight:bold;">{{ $i + 1 }}</td>
                                <td rowspan="{{ $group->items->count() }}">{{ $pengajuan->nama_kegiatan ?? '-' }}</td>
                                <td rowspan="{{ $group->items->count() }}">{{ $pengajuan->user->name ?? '-' }}</td>
                            @endif

                            <td>{{ $item->nama_barang ?? '-' }}</td>
                            <td style="text-align:center;">{{ $item->volume ?? '-' }}</td>
                            <td>{{ $item->kro ?? '-' }}</td>
                            <td style="text-align:right;">{{ number_format($item->ongkos_kirim ?? 0,0,',','.') }}</td>
                            <td style="text-align:right;">{{ number_format($item->jumlah_dana_pengajuan ?? 0,0,',','.') }}</td>
                            <td>
                                @if($item->link)
                                    <a href="{{ $item->link }}" target="_blank">Link</a>
                                @elseif($item->foto)
                                    <a href="{{ $item->foto }}" target="_blank">Foto</a>
                                @else
                                    -
                                @endif
                            </td>

                            @if($j === 0)
                                <td rowspan="{{ $group->items->count() }}" style="text-align:center;">
                                    <span style="padding:5px 10px; background:#17a2b8; color:white; border-radius:5px;">
                                        {{ ucfirst($group->status) }}
                                    </span>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
