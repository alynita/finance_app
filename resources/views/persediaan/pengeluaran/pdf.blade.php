<!DOCTYPE html>
<html>
<head>
    <title>Form Pengeluaran Barang</title>
    <style>
        body { font-family: Arial; font-size: 12px; }
        table { width:100%; border-collapse: collapse; }
        th, td { border:1px solid #000; padding:5px; }
    </style>
</head>
<body>

<h3 align="center">FORM PENGELUARAN BARANG</h3>

<p style="margin:0;">
    <span style="display:inline-block; width:90px;">Kode</span>
    <span style="display:inline-block; width:10px;">:</span>
    <span>{{ $pengeluaran->kode_pengeluaran }}</span>
</p>

<p style="margin:0;">
    <span style="display:inline-block; width:90px;">Tanggal</span>
    <span style="display:inline-block; width:10px;">:</span>
    <span>{{ $pengeluaran->tanggal_pengeluaran }}</span>
</p>

<p style="margin:0;">
    <span style="display:inline-block; width:90px;">Bidang</span>
    <span style="display:inline-block; width:10px;">:</span>
    <span>{{ $pengeluaran->bidang_bagian }}</span>
</p>

<table>
<tr>
    <th>No</th>
    <th>Barang</th>
    <th>Jumlah</th>
    <th>Harga</th>
    <th>Total</th>
</tr>

@foreach($pengeluaran->items as $item)
<tr>
    <td>{{ $loop->iteration }}</td>
    <td>{{ $item->nama_barang }}</td>
    <td>{{ $item->jumlah }}</td>
    <td>{{ $item->harga_satuan }}</td>
    <td>{{ $item->total }}</td>
</tr>
@endforeach
</table>

<br><br><br>

<div style="width:100%; text-align:center;">

    <div style="width:32%; display:inline-block; vertical-align:top;">
        <div>Diterima Oleh</div>
        <br><br><br>
        <strong>
            {{ $pengeluaran->pengajuan->user->name ?? '-' }}
        </strong>
    </div>

    <div style="width:32%; display:inline-block; vertical-align:top;">
        <div>Mengetahui</div>
        <br><br><br>
        <strong>
            {{ $pengeluaran->persediaan->name ?? 'Petugas Persediaan' }}
        </strong>
    </div>

    <div style="width:32%; display:inline-block; vertical-align:top;">
        <div>Diserahkan Oleh</div>
        <br><br><br>
        <strong>
            {{ $pengeluaran->nama_penyerah ?? '-' }}
        </strong>
    </div>

</div>

</body>
</html>
