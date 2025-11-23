<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Honor PDF</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 20px;
        }
        h2, h4 {
            text-align: center;
            margin: 10px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        th, td {
            border: 1px solid #000;
            padding: 4px;
            font-size: 11px;
        }
        th {
            background: #f1f1f1;
        }
        .ttd-wrapper {
            display: flex;
            justify-content: space-between;
            margin-top: 40px; /* jarak dari tabel dikurangi */
        }
        .ttd {
            width: 30%;
            text-align: center;
            vertical-align: top;
        }
        .ttd div {
            margin-top: 5px;
        }
        .page-break {
            page-break-after: always;
        }
        .laporan-wrapper {
            max-width: 900px;
            margin: 20px auto;
            padding: 15px;
            font-size: 12px;
            background: #fff;
            page-break-inside: avoid; /* penting untuk mencegah terpecah */
        }
        .center { text-align: center; }
        .right { text-align: right; }
        .no-border td { border: none !important; }
        .row {
            display: flex;
            margin-bottom: 4px;
        }

        .label {
            width: 150px; /* atur lebarnya agar semua sejajar */
            font-weight: bold;
        }

        .colon {
            width: 10px; /* titik dua berada di kolom tetap */
            font-weight: bold;
        }

        .value {
            flex: 1; /* mengikuti panjang teks */
        }
    </style>
</head>
<body>

<div class="container">
    {{-- Halaman 1: Tabel Honor Keseluruhan --}}
    <h2>DETAIL HONOR</h2>

    <div class="info" style="font-size:12px; line-height:1.4;">
        <p style="margin:2px 0;">
            <span style="display:inline-block; width:130px; font-weight:bold; font-size:12px;">
                Nama Kegiatan
            </span>
            <span style="display:inline-block; width:10px; text-align:center; font-weight:bold; font-size:12px;">
                :
            </span>
            <span style="font-size:12px;">{{ $honors->nama_kegiatan }}</span>
        </p>
        <p style="margin:2px 0;">
            <span style="display:inline-block; width:130px; font-weight:bold; font-size:12px;">
                Waktu Kegiatan
            </span>
            <span style="display:inline-block; width:10px; text-align:center; font-weight:bold; font-size:12px;">
                :
            </span>
            <span style="font-size:12px;">{{ \Carbon\Carbon::parse($honors->waktu)->format('d-m-Y') }}</span>
        </p>
        <p style="margin:2px 0;">
            <span style="display:inline-block; width:130px; font-weight:bold; font-size:12px;">
                Alokasi Anggaran
            </span>
            <span style="display:inline-block; width:10px; text-align:center; font-weight:bold; font-size:12px;">
                :
            </span>
            <span style="font-size:12px;">{{ $honors->alokasi_anggaran }}</span>
        </p>
    </div>

    @php
        $detailPertama = $honors->details->first();
        $jenisUang = $detailPertama->uang_transport > 0 ? 'transport' : 'harian';
        $labelUang = $jenisUang === 'transport' ? 'Uang Transport' : 'Uang Harian';
        $totalKeseluruhan = 0;
    @endphp

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Jabatan</th>
                <th>Tujuan</th>
                <th>Jumlah Hari</th>
                <th>{{ $labelUang }}</th>
                <th>PPH 21 (%)</th>
                <th>Potongan Lain (%)</th>
                <th>Jumlah Dibayar</th>
            </tr>
        </thead>
        <tbody>
            @php $totalKeseluruhan = 0; @endphp
            @foreach($honors->details as $index => $detail)
                @php
                    $nominal = $detail->uang_transport > 0 ? $detail->uang_transport : $detail->uang_harian;
                    $totalKeseluruhan += $detail->jumlah_dibayar;
                @endphp
                <tr>
                    <td>{{ $index+1 }}</td>
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

    {{-- Tanda tangan --}}
    <div style="width:100%; margin-top:80px; text-align:center;"> <!-- ubah margin-top -->

        {{-- ADUM --}}
        <div style="width:30%; display:inline-block; vertical-align:top; text-align:center;">
            <div>MENGETAHUI</div>
            <div>Subbagian Administrasi Umum</div>
            <div style="margin-top:20px; min-height:100px;">
                @if($honors->adum_id)
                    <div style="opacity:0.5; font-weight:bold;">APPROVED</div>
                    {{ $honors->adum->name ?? '-' }}<br>
                    NIP. {{ $honors->adum->nip ?? '-' }}<br>
                    <small>{{ $honors->adum_approved_at ? \Carbon\Carbon::parse($honors->adum_approved_at)->format('d M Y H:i') : '' }}</small>
                @else
                    <em style="color:red;">Menunggu approve</em>
                @endif
            </div>
        </div>

        {{-- PPK --}}
        <div style="width:30%; display:inline-block; vertical-align:top; text-align:center;">
            <div>MENYETUJUI</div>
            <div>Pejabat Pembuat Komitmen</div>
            <div style="margin-top:20px; min-height:100px;">
                @if($honors->ppk_id)
                    <div style="opacity:0.6; font-weight:bold;">APPROVED</div>
                    {{ $honors->ppk->name ?? '-' }}<br>
                    NIP. {{ $honors->ppk->nip ?? '-' }}<br>
                    <small>{{ $honors->ppk_approved_at ? \Carbon\Carbon::parse($honors->ppk_approved_at)->format('d M Y H:i') : '' }}</small>
                @else
                    <em style="color:red;">Menunggu approve</em>
                @endif
            </div>
        </div>

        {{-- PENANGGUNG JAWAB --}}
        <div style="width:30%; display:inline-block; vertical-align:top; text-align:center;">
            <div>PENANGGUNG JAWAB</div>
            <div style="margin-top:20px; min-height:100px;">
                {{ $honors->user->name ?? '-' }}<br>
                NIP. {{ $honors->user->nip ?? '-' }}
            </div>
        </div>

    </div>

    <div class="page-break"></div>

    {{-- Halaman 2+: Detail per Honor --}}
    @foreach($honors->details as $detail)
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

        <div class="laporan-wrapper" style="page-break-inside: avoid;">
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
                        <td>
                            <strong>{{ $labelUang }}</strong><br>
                            ({{ $detail->jumlah_hari }} hari × Rp {{ number_format($nominal,0,',','.') }}
                            × {{ $detail->potongan_lain ?? 0 }}%)
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

            <p><strong>Terbilang:</strong> : {{ terbilang($totalBayar) }} Rupiah</p>

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
                        Yang Menerima,<br><br><br>
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

            <br>
            <div class="right" style="margin-top:20px;">
                Pejabat Pembuat Komitmen<br><br><br>
                <strong>{{ $honors->ppk->name ?? '__________________________' }}</strong><br>
                NIP. {{ $honors->ppk->nip ?? '-' }}
            </div>
        </div>

    @endforeach

</div>
</body>
</html>
