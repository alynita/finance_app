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

    @forelse($pengajuans as $pengajuan)
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
                        <td>{{ $pengajuan->kode_akun ?? '-' }}</td>
                    </tr>
                </table> 

            <table border="1" cellpadding="5" cellspacing="0" style="width:100%; border-collapse:collapse; margin-top:10px;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama / Invoice</th>
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
                    @foreach($pengajuan->items as $index => $item)
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
                        <td colspan="1">{{ number_format($totalPajak,2,',','.') }}</td>
                        <td colspan="1"></td>
                        <td colspan="1"></td>
                        <td colspan="1"></td>
                        <td colspan="1">{{ number_format($totalDibayarkan,2,',','.') }}</td>
                    </tr>
                </tbody>
            </table>

            <div style="margin-top:10px;">
                <a href="{{ route('proses.approve', $pengajuan->id) }}" 
                    onclick="return confirm('Yakin ingin approve pengajuan ini?')" 
                    style="padding:0.5rem 1rem; background-color:#28a745; color:white; border-radius:5px; text-decoration:none;">Approve</a>
            </div>
        </div>
    @empty
        <p>Tidak ada pengajuan untuk diapprove.</p>
    @endforelse

</div>
@endsection
