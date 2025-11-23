<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pengadaan</title>

    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        .title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .info {
            margin-bottom: 15px;
        }

        .info p {
            margin: 3px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table, th, td {
            border: 1px solid #000;
        }

        th {
            background: #eaeaea;
            padding: 6px;
        }

        td {
            padding: 6px;
        }

        .ttd {
            margin-top: 40px;
            width: 100%;
        }

        .ttd div {
            width: 30%;
            text-align: left;
            display: inline-block;
        }

        .mt-50 {
            margin-top: 50px;
        }

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

    <div class="title">LAPORAN PENGADAAN BARANG</div>

    <!-- INFORMASI UMUM (bukan tabel) -->
    <div class="info" style="font-size:14px; line-height:1.6;">
        <p>
            <span style="display:inline-block; width:150px; font-weight:bold;">
                Nama Kegiatan
            </span>
            : {{ $group->pengajuan->nama_kegiatan }}
        </p>

        <p>
            <span style="display:inline-block; width:150px; font-weight:bold;">
                Waktu Kegiatan
            </span>
            : {{ $group->pengajuan->waktu_kegiatan }}
        </p>

        <p>
            <span style="display:inline-block; width:150px; font-weight:bold;">
                Jenis Pengajuan
            </span>
            : {{ ucfirst($group->pengajuan->jenis_pengajuan) }}
        </p>

        <p>
            <span style="display:inline-block; width:150px; font-weight:bold;">
                Kode Akun
            </span>
            : {{ $group->kode_akun }}
        </p>
    </div>

    @php
        $totalPajak = 0;
        $totalDiterima = 0;
    @endphp

    {{-- Tabel Pengajuan --}}
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
            @foreach($group->items as $index => $item)
                @php
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

    {{-- Tanda tangan --}}
    <div style="width:100%; margin-top:40px; text-align:center;">

        {{-- ADUM --}}
        <div style="width:30%; display:inline-block; vertical-align:top; text-align:center;">
            <div>MENGETAHUI</div>
            <div>Subbagian Administrasi Umum</div>
            <div style="margin-top:20px;">
                @if($group->adum_approved_process)
                    <div style="opacity:0.5; font-weight:bold;">APPROVED</div>
                    <div>{{ $group->adum->name ?? 'Nama ADUM' }}</div>
                    <div>NIP. {{ $group->adum->nip ?? '-' }}</div>
                @else
                    <div><em style="color:red;">Tanda tangan menunggu approve</em></div>
                @endif
            </div>
        </div>

        {{-- PPK --}}
        <div style="width:30%; display:inline-block; vertical-align:top; text-align:center;">
            <div>MENYETUJUI</div>
            <div>PPK</div>
            <div style="margin-top:20px;">
                @if($group->ppk_approved_process)
                    <div style="opacity:0.5; font-weight:bold;">APPROVED</div>
                    <div>{{ $group->ppk->name ?? 'Nama PPK' }}</div>
                    <div>NIP. {{ $group->ppk->nip ?? '-' }}</div>
                @else
                    <div><em style="color:red;">Tanda tangan menunggu approve</em></div>
                @endif
            </div>
        </div>

        {{-- Verifikator --}}
        <div style="width:30%; display:inline-block; vertical-align:top; text-align:center;">
            <div>MENGETAHUI</div>
            <div>Verifikator</div>
            <div style="margin-top:20px;">
                @if($group->verifikator_approved_process)
                    <div style="opacity:0.5; font-weight:bold;">APPROVED</div>
                    <div>{{ $group->verifikator->name ?? 'Nama Verifikator' }}</div>
                    <div>NIP. {{ $group->verifikator->nip ?? '-' }}</div>
                @else
                    <div><em style="color:red;">Tanda tangan menunggu approve</em></div>
                @endif
            </div>
        </div>

    </div>

</body>
</html>
