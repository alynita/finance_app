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
        .ttd.ppk { margin-top: 40px; } /* PPK agak ke bawah */
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
@if($pengajuan->jenis_pengajuan === 'honor')
<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Jabatan</th>
            <th>Uraian</th>
            <th>Jumlah Honor</th>
            <th>Bulan</th>
            <th>Total Honor</th>
            <th>PPH 21</th>
            <th>Jumlah Akhir</th>
            <th>No Rekening</th>
            <th>Bank</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pengajuan->honorariums as $index => $h)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $h->nama }}</td>
            <td>{{ $h->jabatan }}</td>
            <td>{{ $h->uraian }}</td>
            <td>{{ number_format($h->jumlah_honor,0,',','.') }}</td>
            <td>{{ $h->bulan }}</td>
            <td>{{ number_format($h->total_honor,0,',','.') }}</td>
            <td>{{ number_format($h->pph_21,0,',','.') }}</td>
            <td>{{ number_format($h->jumlah,0,',','.') }}</td>
            <td>{{ $h->no_rekening }}</td>
            <td>{{ $h->bank }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
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
        @foreach($pengajuan->items as $index => $item)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $item->invoice ?? '-' }}</td>
            <td>{{ $item->nama_barang ?? $item->nama }}</td>
            <td>{{ $item->uraian ?? 'Tidak ada uraian' }}</td>
            <td>{{ number_format($item->jumlah_dana_pengajuan,0,',','.') }}</td>
            <td>{{ number_format($item->pph21 ?? 0,0,',','.') }}</td>
            <td>{{ number_format($item->pph22 ?? 0,0,',','.') }}</td>
            <td>{{ number_format($item->pph23 ?? 0,0,',','.') }}</td>
            <td>{{ number_format($item->ppn ?? 0,0,',','.') }}</td>
            <td>{{ number_format($item->dibayarkan ?? 0,0,',','.') }}</td>
            <td>{{ $item->no_rekening ?? '-' }}</td>
            <td>{{ $item->bank ?? '-' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

{{-- Tanda tangan --}}
<table style="width:100%; margin-top:80px; border-collapse:collapse; border:none;">
    <tr>
        <!-- ADUM kiri -->
        <td style="width:33%; text-align:center; vertical-align:top; border:none;">
            <div>MENGETAHUI</div>
            <div>Subbagian Administrasi Umum</div>
            <div style="margin-top:60px;">
                {{ $pengajuan->adum->name ?? 'Nama ADUM' }}<br>
                NIP. {{ $pengajuan->adum->nip ?? '-' }}
            </div>
        </td>

        <!-- PPK tengah, agak ke bawah -->
        <td style="width:33%; text-align:center; vertical-align:bottom; border:none;">
            <div>MENYETUJUI</div>
            <div>PPK</div>
            <div style="margin-top:60px;">
                {{ $pengajuan->ppk->name ?? 'Nama PPK' }}<br>
                NIP. {{ $pengajuan->ppk->nip ?? '-' }}
            </div>
        </td>

        <!-- Verifikator kanan -->
        <td style="width:33%; text-align:center; vertical-align:top; border:none;">
            <div>MENGETAHUI</div>
            <div>Verifikator</div>
            <div style="margin-top:60px;">
                {{ $pengajuan->verifikator->name ?? 'Nama Verifikator' }}<br>
                NIP. {{ $pengajuan->verifikator->nip ?? '-' }}
            </div>
        </td>
    </tr>
</table>



</body>
</html>
