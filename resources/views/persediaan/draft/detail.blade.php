@extends('layouts.app')

@section('content')
<h2>Detail Draft Pengeluaran</h2>

<style>
    .field {
        display: flex;
        margin: 4px 0;
    }
    .label {
        min-width: 80px; /* lebar minimal sebelum titik dua, sesuaikan label terpanjang */
        font-weight: bold;
    }
    .colon {
        margin: 0 5px;
    }
</style>

<div class="field">
    <div class="label">Nomor</div>
    <div class="colon">:</div>
    <div>{{ $pengeluaran->kode_pengeluaran }}</div>
</div>

<div class="field">
    <div class="label">Tanggal</div>
    <div class="colon">:</div>
    <div>{{ $pengeluaran->tanggal_pengeluaran }}</div>
</div>

<div class="field">
    <div class="label">Bidang</div>
    <div class="colon">:</div>
    <div>{{ $pengeluaran->bidang_bagian }}</div>
</div>

<hr>

<table border="1" cellpadding="8" width="100%">
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

<br>

<form action="{{ route('persediaan.pengeluaran.pdf', $pengeluaran->id) }}" method="GET">
    <button type="submit">
        📄 Download PDF
    </button>
</form>



@endsection
