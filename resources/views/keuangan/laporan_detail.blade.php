@extends('layouts.app')

@section('title', 'Detail Laporan')
@section('header', 'Detail Laporan')

@section('content')
<div style="max-width:1000px; margin:auto;">

@if(session('success'))
    <div style="background-color:#d4edda; color:#155724; padding:10px; margin-bottom:10px; border-radius:5px;">
        {{ session('success') }}
    </div>
@endif

{{-- Info Pengajuan --}}
<table style="width:100%; border-collapse:collapse; margin-bottom:10px;">
    <tr>
        <td style="width:200px;"><strong>Nama Kegiatan</strong></td>
        <td style="width:10px;">:</td>
        <td>{{ $group->pengajuan->nama_kegiatan }}</td>
    </tr>
    <tr>
        <td><strong>Waktu Kegiatan</strong></td>
        <td>:</td>
        <td>{{ $group->pengajuan->waktu_kegiatan }}</td>
    </tr>
    <tr>
        <td><strong>Jenis Pengajuan</strong></td>
        <td>:</td>
        <td>{{ ucfirst($group->pengajuan->jenis_pengajuan) }}</td>
    </tr>
    <tr>
        <td><strong>Kode Akun</strong></td>
        <td>:</td>
        <td>{{ $group->kode_akun ?? '-' }}</td>
    </tr>
</table>

@php
$totalPajak = 0;
$totalDiterima = 0;

// CEK APAKAH ADA PAJAK BARU
$firstItem = $group->items->first();
$hasPajakBaru = !empty($firstItem->nama_pajak_baru) && ($firstItem->hasil_pajak_baru ?? 0) > 0;
@endphp

<table border="1" cellpadding="10" cellspacing="0" style="width:100%; border-collapse:collapse; margin-top:1rem;">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama/Nomor Invoice</th>
            <th>Nama Barang</th>
            <th>Detail Akun</th>
            <th>Uraian</th>
            <th>Jumlah Pengajuan</th>
            <th>PPH 21</th>
            <th>PPH 22</th>
            <th>PPH 23</th>

            @if($hasPajakBaru)
                <th>{{ $firstItem->nama_pajak_baru }}</th>
            @endif

            <th>PPN</th>
            <th>Dibayarkan</th>
            <th>No Rekening</th>
            <th>Bank</th>
        </tr>
    </thead>

    <tbody>
        @foreach($group->items as $index => $item)
        @php
            $pph21 = $item->pph21 ?? 0;
            $pph22 = $item->pph22 ?? 0;
            $pph23 = $item->pph23 ?? 0;
            $ppn   = $item->ppn ?? 0;

            // jika tidak ada pajak baru → otomatis jadi 0
            $hasilPajakBaru = $hasPajakBaru ? ($item->hasil_pajak_baru ?? 0) : 0;

            $dibayarkan = $item->dibayarkan ?? (
                $item->jumlah_dana_pengajuan
                - ($pph21 + $pph22 + $pph23 + $ppn + $hasilPajakBaru)
            );

            // hitung total pajak — lebih aman
            $totalPajak += ($pph21 + $pph22 + $pph23 + $ppn);
            if($hasPajakBaru) {
                $totalPajak += $hasilPajakBaru;
            }

            $totalDiterima += $dibayarkan;
        @endphp

        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $item->invoice ?? '-' }}</td>
            <td>{{ $item->nama_barang ?? $item->nama }}</td>
            <td>{{ $item->detail_akun ?? 'Tidak ada detail akun' }}</td>
            <td>{{ $item->uraian ?? 'Tidak ada uraian' }}</td>
            <td>{{ number_format($item->jumlah_dana_pengajuan, 0, ',', '.') }}</td>

            <td>{{ number_format($pph21, 0, ',', '.') }}</td>
            <td>{{ number_format($pph22, 0, ',', '.') }}</td>
            <td>{{ number_format($pph23, 0, ',', '.') }}</td>

            @if($hasPajakBaru)
                <td>{{ number_format($hasilPajakBaru, 0, ',', '.') }}</td>
            @endif

            <td>{{ number_format($ppn, 0, ',', '.') }}</td>
            <td>{{ number_format($dibayarkan, 0, ',', '.') }}</td>
            <td>{{ $item->no_rekening ?? '-' }}</td>
            <td>{{ $item->bank ?? '-' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>


{{-- Total Pajak & Diterima --}}
<div style="margin-top:1rem; padding:10px; border:1px solid #000; border-radius:5px; width:300px;">
    <p><strong>Total Pajak:</strong> Rp {{ number_format($totalPajak, 0, ',', '.') }}</p>
    <p><strong>Total Diterima:</strong> Rp {{ number_format($totalDiterima, 0, ',', '.') }}</p>
</div>

{{-- Tanda tangan --}}
<div style="display:flex; justify-content:space-between; margin-top:40px;">
    {{-- ADUM --}}
    <div style="flex:1; text-align:center; display:flex; flex-direction:column; align-items:center;">
        <div>MENGETAHUI</div>
        <div>Subbagian Administrasi Umum</div>
        <div style="margin-top:60px;">
            @if($group->adum_approved_process)
                <div style="opacity:0.5; font-weight:bold;">APPROVED</div>
                {{ $group->adum->name ?? 'Nama ADUM' }}<br>
                NIP. {{ $group->adum->nip ?? '-' }}
            @else
                <em style="color:red;">Tanda tangan menunggu approve</em>
            @endif
        </div>
    </div>

    {{-- PPK --}}
    <div style="flex:1; text-align:center; display:flex; flex-direction:column; align-items:center;">
        <div>MENYETUJUI</div>
        <div>PPK</div>
        <div style="margin-top:60px;">
            @if($group->ppk_approved_process)
                <div style="opacity:0.5; font-weight:bold;">APPROVED</div>
                {{ $group->ppk->name ?? 'Nama PPK' }}<br>
                NIP. {{ $group->ppk->nip ?? '-' }}
            @else
                <em style="color:red;">Tanda tangan menunggu approve</em>
            @endif
        </div>
    </div>

    {{-- VERIFIKATOR --}}
    <div style="flex:1; text-align:center;">
        <div>MENGETAHUI</div>
        <div>Verifikator</div>
        <div style="margin-top:60px;">
            @if($group->verifikator_approved_process)
                <div style="opacity:0.6; font-weight:bold;">APPROVED</div>
                {{ $group->verifikator->name ?? 'Nama Verifikator' }}<br>
                NIP. {{ $group->verifikator->nip ?? '-' }}
            @else
                <div><em style="color:red;">Tanda tangan menunggu approve</em></div>
            @endif
        </div>
    </div>
</div>

</div>
@endsection
