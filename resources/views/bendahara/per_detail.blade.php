@extends('layouts.app')

@section('content')

<style>
    body { background: #fff; font-family:sans-serif; }
    .laporan-wrapper {
        max-width: 900px;
        margin: 50px auto;
        padding: 25px;
        font-size: 14px;
        background: #fff;
        color: #000;
        box-sizing: border-box;
    }
    table { width: 100%; border-collapse: collapse; margin-bottom:20px; }
    th, td { border: 1px solid #000; padding: 6px; }
    .no-border td { border:none !important; }
    .center { text-align:center; }
    .right { text-align:right; }
    .page-break { page-break-after: always; margin-top: 50px; }
</style>

@foreach($honor->details as $detail)
    @php
        $labelUang = $detail->uang_transport > 0 ? 'Uang Transport' : 'Uang Harian';
        $nominalField = $detail->uang_transport > 0 ? 'uang_transport' : 'uang_harian';
        $nominal = $detail->$nominalField ?? 0;
        $totalBayar = $detail->jumlah_dibayar ?? 0;
    @endphp

    <div class="laporan-wrapper">
        <div class="center">
            <strong>KEMENTERIAN KESEHATAN RI</strong><br>
            <strong>DIREKTORAT JENDERAL TENAGA KESEHATAN</strong><br>
            <strong>BALAI BESAR PELATIHAN KESEHATAN JAKARTA</strong><br><br>
            <u><strong>RINCIAN BIAYA PERJALANAN DINAS</strong></u>
        </div>

        <h4 class="center">PERINCIAN BIAYA</h4>

        <table>
            <thead>
                <tr>
                    <th>Perincian Biaya</th>
                    <th>Jumlah</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>{{ $labelUang }}</strong><br>
                        ({{ $detail->jumlah_hari }} hari × Rp {{ number_format($nominal,0,',','.') }} × {{ $detail->potongan_lain ?? 0 }}%)
                    </td>
                    <td>Rp {{ number_format($totalBayar,0,',','.') }}</td>
                    <td>{{ $detail->keterangan ?? '-' }}</td>
                </tr>
                <tr>
                    <th class="right">JUMLAH</th>
                    <th>Rp {{ number_format($totalBayar,0,',','.') }}</th>
                    <th>-</th>
                </tr>
            </tbody>
        </table>

        <p><strong>Terbilang:</strong> {{ terbilang($totalBayar) }} Rupiah</p>

        <table class="no-border">
            <tr class="no-border">
                <td>
                    Telah dibayar sejumlah<br>Rp {{ number_format($totalBayar,0,',','.') }}<br><br>
                    Bendahara Pengeluaran<br><br><br>
                    <strong>{{ $bendahara->name ?? '__________________________' }}</strong><br>
                    NIP. {{ $bendahara->nip ?? '-' }}
                </td>
                <td class="right">
                    Telah menerima jumlah uang sebesar<br>Rp {{ number_format($totalBayar,0,',','.') }}<br><br>
                    Yang Menerima<br><br><br>
                    <strong>{{ $detail->nama ?? '____________________________' }}</strong><br>
                    NIP. {{ $detail->nip ?? '-' }}
                </td>
            </tr>
        </table>

        <h4 class="center">PERHITUNGAN SPD RAMPUNG</h4>

        <table>
            <tr>
                <td>Ditetapkan sejumlah</td>
                <td>Rp {{ number_format($totalBayar,0,',','.') }}</td>
            </tr>
            <tr>
                <td>Yang telah dibayar semula</td>
                <td>Rp {{ number_format($totalBayar,0,',','.') }}</td>
            </tr>
            <tr>
                <td>Sisa kurang / lebih</td>
                <td>-</td>
            </tr>
        </table>

        <div class="right">
            Pejabat Pembuat Komitmen<br><br>
            <strong>{{ $honor->ppk->name ?? '__________________________' }}</strong><br>
            NIP. {{ $honor->ppk->nip ?? '-' }}
        </div>
    </div>

    <div class="page-break"></div>
@endforeach

@endsection
