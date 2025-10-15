<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Detail Pengajuan {{ $pengajuan->nama_kegiatan }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 5px; text-align: left; }
        th { background-color: #f2f2f2; }
        .tanda-tangan { display: flex; justify-content: space-between; margin-top: 100px; }
        .ttd { text-align: center; width: 30%; }
    </style>
</head>
<body>

<h2 style="text-align:center;">Detail Laporan Keuangan</h2>

<p><strong>Nama Kegiatan:</strong> {{ $pengajuan->nama_kegiatan }}</p>
<p><strong>Waktu Kegiatan:</strong> {{ $pengajuan->waktu_kegiatan }}</p>
<p><strong>Jenis Pengajuan:</strong> {{ ucfirst($pengajuan->jenis_pengajuan) }}</p>

{{-- Tabel detail --}}
<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Barang / Uraian</th>
            @if($pengajuan->jenis_pengajuan !== 'honor')
                <th>Volume</th>
            @endif
            @if($pengajuan->jenis_pengajuan === 'pembelian')
                <th>KRO/Kode Akun</th>
                <th>Harga Satuan</th>
                <th>Jumlah Dana</th>
                <th>Ongkos Kirim</th>
            @elseif($pengajuan->jenis_pengajuan === 'kerusakan')
                <th>Lokasi</th>
                <th>Jenis Kerusakan</th>
                <th>Harga Satuan</th>
                <th>Jumlah Dana</th>
                <th>Foto</th>
            @elseif($pengajuan->jenis_pengajuan === 'honor')
                <th>Keterangan</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach($pengajuan->items as $index => $item)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $item->nama_barang ?? $item->nama ?? '-' }}</td>
            @if($pengajuan->jenis_pengajuan !== 'honor')
                <td>{{ $item->volume ?? '-' }}</td>
            @endif
            @if($pengajuan->jenis_pengajuan === 'pembelian')
                <td>{{ $item->kro ?? '-' }}</td>
                <td>{{ number_format($item->harga_satuan ?? 0) }}</td>
                <td>{{ number_format($item->jumlah_dana_pengajuan ?? 0) }}</td>
                <td>{{ number_format($item->ongkos_kirim ?? 0) }}</td>
            @elseif($pengajuan->jenis_pengajuan === 'kerusakan')
                <td>{{ $item->lokasi ?? '-' }}</td>
                <td>{{ $item->jenis_kerusakan ?? '-' }}</td>
                <td>{{ number_format($item->harga_satuan ?? 0) }}</td>
                <td>{{ number_format($item->jumlah_dana_pengajuan ?? 0) }}</td>
                <td>@if($item->foto)<a href="{{ asset('storage/' . $item->foto) }}">Lihat</a>@else - @endif</td>
            @elseif($pengajuan->jenis_pengajuan === 'honor')
                <td>Tanggal: {{ $item->tanggal ?? '-' }}, Nama: {{ $item->nama ?? '-' }}, Jabatan: {{ $item->jabatan ?? '-' }}</td>
            @endif
        </tr>
        @endforeach
    </tbody>
</table>

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
            <div>Penanggun Jawab</div>
            <div style="margin-top:60px;">
                {{ $pengajuan->user->name ?? 'Nama Penanggung Jawab' }}<br>
                NIP. {{ $pengajuan->user->nip ?? '-' }}
            </div>
        </td>
    </tr>
</table>

</body>
</html>
