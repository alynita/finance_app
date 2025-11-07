<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan {{ $pengajuan->nama_kegiatan }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h2 { text-align: center; margin-bottom: 5px; }
        p { margin: 2px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 11px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 4px; text-align: left; }
        th { background-color: #f2f2f2; }
        .tanda-tangan {
            display: flex;
            justify-content: space-between;
            margin-top: 80px;
            align-items: flex-start;
        }
        .ttd { text-align: center; width: 30%; }
        .ttd.ppk { margin-top: 40px; }
    </style>
</head>
<body>

<h2>Detail Laporan Keuangan</h2>

{{-- Info Kegiatan --}}
<p><strong>Nama Kegiatan:</strong> {{ $pengajuan->nama_kegiatan }}</p>
<p><strong>Waktu Kegiatan:</strong> {{ $pengajuan->waktu_kegiatan }}</p>
<p><strong>Jenis Pengajuan:</strong> {{ ucfirst($pengajuan->jenis_pengajuan) }}</p>
<p><strong>Kode Akun:</strong> {{ $pengajuan->kode_akun ?? '-' }}</p>

{{-- Tabel Detail Keuangan --}}
@php
    $totalPajak = 0;
    $totalDiterima = 0;
@endphp
<table>
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
            <td>{{ number_format($item->jumlah_dana_pengajuan,0,',','.') }}</td>
            <td>{{ number_format($pph21,0,',','.') }}</td>
            <td>{{ number_format($pph22,0,',','.') }}</td>
            <td>{{ number_format($pph23,0,',','.') }}</td>
            <td>{{ number_format($ppn,0,',','.') }}</td>
            <td>{{ number_format($dibayarkan,0,',','.') }}</td>
            <td>{{ $item->no_rekening ?? '-' }}</td>
            <td>{{ $item->bank ?? '-' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

{{-- Total Pajak & Total Diterima --}}
<div style="margin-top:1rem; padding:10px; border:1px solid #000; border-radius:5px; width:300px;">
    <p><strong>Total Pajak:</strong> Rp {{ number_format($totalPajak,0,',','.') }}</p>
    <p><strong>Total Diterima:</strong> Rp {{ number_format($totalDiterima,0,',','.') }}</p>
</div>

{{-- Tanda Tangan --}}
<table style="width:100%; margin-top:80px; border-collapse:collapse; border:none;">
    <tr>
        <td style="width:33%; text-align:center; vertical-align:top; border:none;">
            <div>MENGETAHUI</div>
            <div>Subbagian Administrasi Umum</div>
            <div style="margin-top:60px;">
                @if($pengajuan->adum_approved_process)
                    <div style="opacity:0.5; font-weight:bold;">APPROVED</div>
                @endif
                {{ $pengajuan->adum->name ?? 'Nama ADUM' }}<br>
                NIP. {{ $pengajuan->adum->nip ?? '-' }}
            </div>
        </td>

        <td style="width:33%; text-align:center; vertical-align:bottom; border:none;">
            <div>MENYETUJUI</div>
            <div>PPK</div>
            <div style="margin-top:60px;">
                @if($pengajuan->ppk_approved_process)
                    <div style="opacity:0.5; font-weight:bold;">APPROVED</div>
                @endif
                {{ $pengajuan->ppk->name ?? 'Nama PPK' }}<br>
                NIP. {{ $pengajuan->ppk->nip ?? '-' }}
            </div>
        </td>

        <td style="width:33%; text-align:center; vertical-align:top; border:none;">
            <div>MENGETAHUI</div>
            <div>Verifikator</div>
            <div style="margin-top:60px;">
                @if($pengajuan->verifikator_approved_process)
                    <div style="opacity:0.5; font-weight:bold;">APPROVED</div>
                @endif
                {{ $pengajuan->verifikator->name ?? 'Nama Verifikator' }}<br>
                NIP. {{ $pengajuan->verifikator->nip ?? '-' }}
            </div>
        </td>
    </tr>
</table>

</body>
</html>
