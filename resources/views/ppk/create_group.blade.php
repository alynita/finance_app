@extends('layouts.app')
@section('title', 'Pecah Pengajuan')
@section('content')

<h3>Pecah Pengajuan / Approve Langsung: #{{ $pengajuan->id }}</h3>

<form action="{{ route('ppk.storeGroup', $pengajuan->id) }}" method="POST">
    @csrf

    <div id="groups-container">
        <div class="group">
            <input type="text" name="group_name[]" placeholder="Nama Grup" required>

            <table style="border-collapse: collapse; width:100%; margin-top: 10px;">
                <thead>
                    <tr>
                        <th></th>
                        <th>Nama Barang</th>
                        <th>Volume</th>
                        <th>KRO/Kode Akun</th>
                        <th>Harga Satuan</th>
                        <th>Ongkos Kirim</th>
                        <th>Jumlah Dana</th>
                        <th>Foto/Ket</th>
                        <th>Link</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pengajuan->items as $item)
                        <tr>
                            <td>
                                <input type="checkbox" name="groups[0][]" value="{{ $item->id }}">
                            </td>
                            <td>{{ $item->nama_barang }}</td>
                            <td>{{ $item->volume }}</td>
                            <td>{{ $item->kro }}</td>
                            <td>{{ number_format($item->harga_satuan,0,',','.') }}</td>
                            <td>{{ number_format($item->ongkos_kirim,0,',','.') }}</td>
                            <td>{{ number_format($item->jumlah_dana_pengajuan,0,',','.') }}</td>
                            <td>@if($item->foto) <a href="{{ asset('storage/'.$item->foto) }}" target="_blank">Lihat</a> @endif {{ $item->keterangan }}</td>
                            <td>@if($item->link) <a href="{{ $item->link }}" target="_blank">Link</a> @endif</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <button type="button" id="add-group" style="margin-top:10px;">Tambah Grup</button>

    <div style="margin-top:20px;">
        <label>
            <input type="checkbox" name="approve_all" value="1"> Approve langsung semua tanpa pecah
        </label>
    </div>

    <button type="submit" style="margin-top:10px;">Submit</button>
</form>

<hr>

<h4>Grup yang sudah dibuat:</h4>
@foreach($pengajuan->ppkGroups as $group)
    <div style="border:1px solid #ccc; padding:5px; margin-bottom:10px;">
        <strong>{{ $group->group_name }}</strong> - 
        @if($group->status === 'approved')
            <span style="color:green;">APPROVED</span>
        @else
            <span style="color:red;">Pending</span>
            <form action="{{ route('ppk.approveGroup', $group->id) }}" method="POST" style="display:inline-block;">
                @csrf
                <button type="submit">Approve Grup</button>
            </form>
        @endif

        <ul>
            @foreach($group->items as $item)
                <li>{{ $item->nama_barang }} ({{ $item->volume }})</li>
            @endforeach
        </ul>
    </div>
@endforeach

<script>
let groupCount = 1;
document.getElementById('add-group').addEventListener('click', function(){
    let container = document.getElementById('groups-container');
    let groupDiv = document.createElement('div');
    groupDiv.classList.add('group');

    let groupIndex = groupCount;
    groupCount++;

    let html = `<input type="text" name="group_name[]" placeholder="Nama Grup" required>`;

    html += `<table style="border-collapse: collapse; width:100%; margin-top: 10px;">
        <thead>
            <tr>
                <th></th>
                <th>Nama Barang</th>
                <th>Volume</th>
                <th>KRO/Kode Akun</th>
                <th>Harga Satuan</th>
                <th>Ongkos Kirim</th>
                <th>Jumlah Dana</th>
                <th>Foto/Ket</th>
                <th>Link</th>
            </tr>
        </thead>
        <tbody>
    `;

    @foreach($pengajuan->items as $item)
        html += `<tr>
            <td><input type="checkbox" name="groups[${groupIndex}][]" value="{{ $item->id }}"></td>
            <td>{{ $item->nama_barang }}</td>
            <td>{{ $item->volume }}</td>
            <td>{{ $item->kro }}</td>
            <td>{{ number_format($item->harga_satuan,0,',','.') }}</td>
            <td>{{ number_format($item->ongkos_kirim,0,',','.') }}</td>
            <td>{{ number_format($item->jumlah_dana,0,',','.') }}</td>
            <td>@if($item->foto) <a href='{{ asset('storage/'.$item->foto) }}' target='_blank'>Lihat</a> @endif {{ $item->keterangan }}</td>
            <td>@if($item->link) <a href='{{ $item->link }}' target='_blank'>Link</a> @endif</td>
        </tr>`;
    @endforeach

    html += `</tbody></table>`;
    groupDiv.innerHTML = html;
    container.appendChild(groupDiv);
});
</script>

@endsection
