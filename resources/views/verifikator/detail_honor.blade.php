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

{{-- =======================
    VERSI WEB RINGKAS
======================= --}}
<div style="max-width:1000px; margin:auto; padding:20px;">

    <h2 style="text-align:center; margin-bottom:20px;">Detail Honor</h2>

    <!-- INFO KEGIATAN -->
    <div class="info" style="font-size:15px; line-height:1.4;">
        <p style="margin:2px 0;">
            <span style="display:inline-block; width:130px; font-weight:bold; font-size:15px;">
                Nama Kegiatan
            </span>
            <span style="display:inline-block; width:10px; text-align:center; font-weight:bold; font-size:15px;">
                :
            </span>
            <span style="font-size:15px;">{{ $honor->nama_kegiatan }}</span>
        </p>
        <p style="margin:2px 0;">
            <span style="display:inline-block; width:130px; font-weight:bold; font-size:15px;">
                Waktu Kegiatan
            </span>
            <span style="display:inline-block; width:10px; text-align:center; font-weight:bold; font-size:15px;">
                :
            </span>
            <span style="font-size:15px;">{{ \Carbon\Carbon::parse($honor->waktu)->format('d-m-Y') }}</span>
        </p>
        <p style="margin:2px 0;">
            <span style="display:inline-block; width:130px; font-weight:bold; font-size:15px;">
                Alokasi Anggaran
            </span>
            <span style="display:inline-block; width:10px; text-align:center; font-weight:bold; font-size:15px;">
                :
            </span>
            <span style="font-size:15px;">{{ $honor->alokasi_anggaran }}</span>
        </p>
    </div>

    @php
        $detailPertama = $honor->details->first();
        $jenisUang = $detailPertama->uang_transport > 0 ? 'transport' : 'harian';
        $labelUang = $jenisUang === 'transport' ? 'Uang Transport' : 'Uang Harian';
        $totalKeseluruhan = 0;
    @endphp

    <table style="width:100%; font-size:13px;">
        <thead style="background:#f1f1f1;">
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Jabatan</th>
                <th>Tujuan</th>
                <th>Hari</th>
                <th>{{ $labelUang }}</th>
                <th>PPH21 (%)</th>
                <th>Potongan Lain (%)</th>
                <th>Jumlah Dibayar</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($honor->details as $i => $detail)
                @php
                    $nominal = $jenisUang === 'transport' ? $detail->uang_transport : $detail->uang_harian;
                    $totalKeseluruhan += $detail->jumlah_dibayar;
                @endphp
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $detail->nama }}</td>
                    <td>{{ $detail->jabatan }}</td>
                    <td>{{ $detail->tujuan }}</td>
                    <td>{{ $detail->jumlah_hari }}</td>
                    <td>Rp {{ number_format($nominal,0,',','.') }}</td>
                    <td>{{ $detail->pph21 }}%</td>
                    <td>{{ $detail->potongan_lain ?? 0 }}%</td>
                    <td>Rp {{ number_format($detail->jumlah_dibayar,0,',','.') }}</td>
                </tr>
            @endforeach
            <tr style="font-weight:bold;">
                <td colspan="8" class="right">TOTAL</td>
                <td>Rp {{ number_format($totalKeseluruhan,0,',','.') }}</td>
            </tr>
        </tbody>
    </table>

    <!-- BAGIAN TANDA TANGAN -->
    <div style="display:flex; justify-content:space-between; margin-top:50px;">

        <!-- ADUM -->
        <div style="flex:1; text-align:center;">
            <div>MENGETAHUI</div>
            <div>ADUM</div>
            <div style="margin-top:60px;">
                @if($honor->adum_id)
                    <div style="opacity:0.6; font-weight:bold;">APPROVED</div>
                    {{ $honor->adum->name ?? '-' }}<br>
                    NIP. {{ $honor->adum->nip ?? '-' }}<br>
                    <small>{{ $honor->adum_approved_at ? \Carbon\Carbon::parse($honor->adum_approved_at)->format('d M Y H:i') : '' }}</small>
                @else
                    <em style="color:red;">Menunggu approve</em>
                @endif
            </div>
        </div>

        <!-- PPK -->
        <div style="flex:1; text-align:center;">
            <div>MENYETUJUI</div>
            <div>Pejabat Pembuat Komitmen</div>
            <div style="margin-top:60px;">
                @if($honor->ppk_id)
                    <div style="opacity:0.6; font-weight:bold;">APPROVED</div>
                    {{ $honor->ppk->name ?? '-' }}<br>
                    NIP. {{ $honor->ppk->nip ?? '-' }}<br>
                    <small>{{ $honor->ppk_approved_at ? \Carbon\Carbon::parse($honor->ppk_approved_at)->format('d M Y H:i') : '' }}</small>
                @else
                    <em style="color:red;">Menunggu approve</em>
                @endif
            </div>
        </div>

        <!-- PENANGGUNG JAWAB -->
        <div style="flex:1; text-align:center;">
            <div>PENANGGUNG JAWAB</div>
            <div style="margin-top:60px;">
                {{ $honor->user->name ?? '-' }}<br>
                NIP. {{ $honor->user->nip ?? '-' }}
            </div>
        </div>
    </div>
</div>

<div class="page-break"></div>

{{-- =======================
VERSI PER-DETAIL (MIRIP PDF)
======================= --}}
@foreach($honor->details as $detail)
    @php
        if($detail->uang_transport > 0){
            $labelUang = 'Uang Transport';
            $nominalField = 'uang_transport';
        } else {
            $labelUang = 'Uang Harian';
            $nominalField = 'uang_harian';
        }
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
                        ({{ $detail->jumlah_hari }} hari × Rp {{ number_format($nominal,0,',','.') }} × {{ $detail->potongan_lain }}%)
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
