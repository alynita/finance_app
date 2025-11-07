@extends('layouts.app')
@section('content')

<h3>Edit Pengadaan - {{ $group->group_name }} (Pengajuan #{{ $group->pengajuan->id }})</h3>

<form action="{{ route('pengadaan.update', $group->id) }}" method="POST">
    @csrf

    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>Nama Barang</th>
            <th>Volume</th>
            <th>Harga Satuan</th>
            <th>Ongkos Kirim</th>
            <th>Catatan</th>
        </tr>
        @foreach($group->items as $item)
        <tr>
            <td>{{ $item->nama_barang }}</td>
            <td><input type="number" name="volume[{{ $item->id }}]" value="{{ $item->volume }}"></td>
            <td><input type="text" name="harga[{{ $item->id }}]" value="{{ $item->harga }}"></td>
            <td><input type="text" name="ongkir[{{ $item->id }}]" value="{{ $item->ongkir ?? '' }}"></td>
            <td><input type="text" name="catatan[{{ $item->id }}]" value="{{ $item->catatan ?? '' }}"></td>
        </tr>
        @endforeach
    </table>

    <button type="submit" style="margin-top:10px;">Simpan Perubahan</button>
</form>

@endsection
