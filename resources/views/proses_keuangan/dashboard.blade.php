@extends('layouts.app')

@section('title', 'Proses Keuangan')
@section('header', 'Proses Keuangan')

@section('content')
<div style="max-width:1200px; margin:auto;">

    @if(session('success'))
        <div style="background-color: #d4edda; color: #155724; padding:10px; margin-bottom:10px; border-radius:5px;">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="background-color:#f8d7da; color:#721c24; padding:10px; margin-bottom:10px; border-radius:5px;">
            {{ session('error') }}
        </div>
    @endif

    @forelse($pengajuans as $group)
        @php $pengajuan = $group->pengajuan; @endphp
        <div style="border:1px solid #ccc; padding:15px; margin-bottom:20px; border-radius:5px;">
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
                    <td>{{ $group->kode_akun ?? '-' }}</td>
                </tr>
            </table>

            {{-- === HONORARIUM === --}}
            @if($pengajuan->jenis_pengajuan === 'honor')
                <table border="1" cellpadding="10" cellspacing="0" style="width:100%; border-collapse:collapse; margin-top:1rem;">
                    <thead>
                        <tr>
                            <th>No</th>
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
                        @php
                            $totalPajak = 0;
                            $totalDiterima = 0;
                        @endphp
                        @foreach($pengajuan->honorariums as $index => $h)
                            @php
                                $pajak = $h->pph_21 ?? 0;
                                $akhir = $h->jumlah ?? 0;
                                $totalPajak += $pajak;
                                $totalDiterima += $akhir;
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
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

            {{-- === PENGAJUAN LAIN === --}}
            @else
                <table border="1" cellpadding="5" cellspacing="0" style="width:100%; border-collapse:collapse; margin-top:10px;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama / Invoice</th>
                            <th>Nama Barang</th>
                            <th>Detail Akun</th>
                            <th>Uraian</th>
                            <th>Jumlah Pengajuan</th>
                            <th>PPH21</th>
                            <th>PPH22</th>
                            <th>PPH23</th>
                            <th>PPN</th>
                            <th>Dibayarkan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalPajak = 0;
                            $totalDibayarkan = 0;
                        @endphp
                        @foreach($group->items as $index => $item)
                            @php
                                $pph21 = $item->pph21 ?? 0;
                                $pph22 = $item->pph22 ?? 0;
                                $pph23 = $item->pph23 ?? 0;
                                $ppn = $item->ppn ?? 0;
                                $dibayarkan = $item->dibayarkan ?? 0;
                                $totalPajak += $pph21 + $pph22 + $pph23 + $ppn;
                                $totalDibayarkan += $dibayarkan;
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->invoice ?? '-' }}</td>
                                <td>{{ $item->nama_barang ?? $item->nama }}</td>
                                <td>{{ $item->detail_akun ?? '-' }}</td>
                                <td>{{ $item->uraian ?? '-' }}</td>
                                <td>{{ number_format($item->jumlah_dana_pengajuan ?? 0,2,',','.') }}</td>
                                <td>{{ number_format($pph21,2,',','.') }}</td>
                                <td>{{ number_format($pph22,2,',','.') }}</td>
                                <td>{{ number_format($pph23,2,',','.') }}</td>
                                <td>{{ number_format($ppn,2,',','.') }}</td>
                                <td>{{ number_format($dibayarkan,2,',','.') }}</td>
                            </tr>
                        @endforeach
                        <tr style="font-weight:bold;">
                            <td colspan="5">Total</td>
                            <td>{{ number_format($totalPajak,2,',','.') }}</td>
                            <td colspan="4"></td>
                            <td>{{ number_format($totalDibayarkan,2,',','.') }}</td>
                        </tr>
                    </tbody>
                </table>
            @endif

            {{-- === ACTION BUTTONS === --}}
            <div style="margin-top:15px; display:flex; gap:10px;">
                <a href="{{ route('proses.approve', $group->id) }}" 
                    onclick="return confirm('Yakin ingin approve pengajuan ini?')" 
                    style="padding:0.5rem 1rem; background-color:#28a745; color:white; border-radius:5px; text-decoration:none;">Approve</a>

                <a href="{{ route('proses.reject', $group->id) }}" 
                    onclick="return confirm('Yakin ingin reject pengajuan ini?')" 
                    style="padding:0.5rem 1rem; background-color:#dc3545; color:white; border-radius:5px; text-decoration:none;">Reject</a>
            </div>
        </div>
    @empty
        <p>Tidak ada pengajuan untuk diapprove.</p>
    @endforelse

</div>
@endsection
