@extends('layouts.app')

@section('title', 'Detail Laporan')
@section('header', 'Detail Laporan')

@section('content')
<div style="max-width:1000px; margin:auto;">

    {{-- Notifikasi --}}
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
            <td>{{ $pengajuan->nama_kegiatan }}</td>
        </tr>
        <tr>
            <td><strong>Waktu Kegiatan</strong></td>
            <td>:</td>
            <td>{{ $pengajuan->waktu_kegiatan }}</td>
        </tr>
        <tr>
            <td><strong>Jenis Pengajuan</strong></td>
            <td>:</td>
            <td>{{ ucfirst($pengajuan->jenis_pengajuan) }}</td>
        </tr>
        <tr>
            <td><strong>Kode Akun</strong></td>
            <td>:</td>
            <td>{{ $pengajuan->kode_akun ?? '-' }}</td>
        </tr>
    </table>

    @php
        $totalPajak = 0;
        $totalDiterima = 0;
    @endphp

    @if($pengajuan->jenis_pengajuan === 'honor')
        {{-- Tabel Honorarium --}}
        <table border="1" cellpadding="10" cellspacing="0" style="width:100%; border-collapse:collapse; margin-top:1rem;">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Nama</th>
                    <th>Jabatan</th>
                    <th>Uraian</th>
                    <th>Jumlah Honor</th>
                    <th>Bulan</th>
                    <th>Total Honor</th>
                    <th>PPH 21 (15%)</th>
                    <th>Jumlah Akhir</th>
                    <th>No Rekening</th>
                    <th>Bank</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pengajuan->honorariums as $index => $h)
                    @php
                        $pajak = $h->pph_21 ?? 0;
                        $akhir = $h->jumlah ?? 0;
                        $totalPajak += $pajak;
                        $totalDiterima += $akhir;
                    @endphp
                    <tr>
                        <td>{{ $h->tanggal }}</td>
                        <td>{{ $h->nama }}</td>
                        <td>{{ $h->jabatan }}</td>
                        <td>{{ $h->uraian }}</td>
                        <td>{{ number_format($h->jumlah_honor, 0, ',', '.') }}</td>
                        <td>{{ $h->bulan }}</td>
                        <td>{{ number_format($h->total_honor, 0, ',', '.') }}</td>
                        <td>{{ number_format($h->pph_21, 0, ',', '.') }}</td>
                        <td>{{ number_format($h->jumlah, 0, ',', '.') }}</td>
                        <td>{{ $h->no_rekening }}</td>
                        <td>{{ $h->bank }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        {{-- Tabel Pengajuan Biasa --}}
        <table border="1" cellpadding="10" cellspacing="0" style="width:100%; border-collapse:collapse; margin-top:1rem;">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama/Nomor Invoice</th>
                    <th>Detail Akun / Nama Barang</th>
                    <th>Uraian</th>
                    <th>Jumlah Pengajuan</th>
                    <th>PPH 21</th>
                    <th>PPH 22</th>
                    <th>PPH 23</th>
                    <th>PPN</th>
                    <th>Dibayarkan</th>
                    <th>No Rekening</th>
                    <th>Bank</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pengajuan->items as $index => $item)
                    @php
                        // skip baris kosong
                        if (!$item->jumlah_dana_pengajuan) continue;

                        $pph21 = $item->pph21 ?? 0;
                        $pph22 = $item->pph22 ?? 0;
                        $pph23 = $item->pph23 ?? 0;
                        $ppn   = $item->ppn ?? 0;
                        $dibayarkan = $item->dibayarkan ?? ($item->jumlah_dana_pengajuan - ($pph21+$pph22+$pph23+$ppn));

                        $totalPajak += ($pph21 + $pph22 + $pph23 + $ppn);
                        $totalDiterima += $dibayarkan;
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->invoice ?? '-' }}</td>
                        <td>{{ $item->nama_barang ?? $item->nama }}</td>
                        <td>{{ $item->uraian ?? 'Tidak ada uraian' }}</td>
                        <td>{{ number_format($item->jumlah_dana_pengajuan, 0, ',', '.') }}</td>
                        <td>{{ number_format($pph21, 0, ',', '.') }}</td>
                        <td>{{ number_format($pph22, 0, ',', '.') }}</td>
                        <td>{{ number_format($pph23, 0, ',', '.') }}</td>
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
    @endif

    {{-- Tanda tangan --}}
    <div style="display:flex; justify-content:space-between; margin-top:40px;">
        {{-- ADUM --}}
        <div style="flex:1; text-align:center; display:flex; flex-direction:column; align-items:center;">
            <div>MENGETAHUI</div>
            <div>Subbagian Administrasi Umum</div>
            <div style="margin-top:60px;">
                @if($pengajuan->adum_approved_process)
                    <div style="opacity:0.5; font-weight:bold;">APPROVED</div>
                    {{ $pengajuan->adum->name ?? 'Nama ADUM' }}<br>
                    NIP. {{ $pengajuan->adum->nip ?? '-' }}
                @else
                    Tanda tangan menunggu approve
                @endif
            </div>
        </div>

        {{-- PPK --}}
        <div style="flex:1; text-align:center; display:flex; flex-direction:column; align-items:center;">
            <div>MENYETUJUI</div>
            <div>PPK</div>
            <div style="margin-top:60px;">
                @if($pengajuan->ppk_approved_process)
                    <div style="opacity:0.5; font-weight:bold;">APPROVED</div>
                    {{ $pengajuan->ppk->name ?? 'Nama PPK' }}<br>
                    NIP. {{ $pengajuan->ppk->nip ?? '-' }}
                @else
                    Tanda tangan menunggu approve
                @endif
            </div>
        </div>

        {{-- Verifikator --}}
        <div style="flex:1; text-align:center;">
            <div>MENGETAHUI</div>
            <div>Verifikator</div>
            <div style="margin-top:60px;">
                @if($pengajuan->verifikator_approved_process)
                    <div style="opacity:0.6; font-weight:bold;">APPROVED</div>
                    {{ $pengajuan->verifikator->name ?? 'Nama Verifikator' }}<br>
                    NIP. {{ $pengajuan->verifikator->nip ?? '-'}}
                @else
                    <div>Tanda tangan menunggu approve</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
