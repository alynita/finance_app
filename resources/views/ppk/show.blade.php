@extends('layouts.app')

@section('title', 'Detail Pengajuan')
@section('header', 'Detail Pengajuan')

@section('content')
<div style="max-width:1100px; margin:auto;">
    <h3>{{ $pengajuan->nama_kegiatan }}</h3>
    <p>Pengaju: {{ $pengajuan->user->name }} | Tanggal: {{ $pengajuan->created_at->format('d M Y') }}</p>
    <p>Status: {{ ucfirst($pengajuan->status) }}</p>

    <hr>

    {{-- 🔹 Item Pengajuan --}}
    <h4>Item Pengajuan</h4>

    @if($pengajuan->jenis_pengajuan === 'kerusakan')
        <table border="1" cellpadding="6" style="width:100%; border-collapse:collapse;">
            <thead style="background:#f3f3f3;">
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Volume</th>
                    <th>Harga Satuan</th>
                    <th>Jumlah Dana</th>
                    <th>Lokasi</th>
                    <th>Jenis Kerusakan</th>
                    <th>Foto</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pengajuan->items as $index => $item)
                <tr>
                    <td>{{ $index+1 }}</td>
                    <td>{{ $item->nama_barang }}</td>
                    <td>{{ $item->volume }}</td>
                    <td>{{ number_format($item->harga_satuan,0,',','.') }}</td>
                    <td>{{ number_format($item->jumlah_dana_pengajuan,0,',','.') }}</td>
                    <td>{{ $item->lokasi ?? '-' }}</td>
                    <td>{{ $item->jenis_kerusakan ?? '-' }}</td>
                    <td>@if($item->foto) <a href="{{ $item->foto }}" target="_blank">Lihat</a> @else - @endif</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @elseif($pengajuan->jenis_pengajuan === 'pembelian')
        <table border="1" cellpadding="6" style="width:100%; border-collapse:collapse;">
            <thead style="background:#f3f3f3;">
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Volume</th>
                    <th>KRO / Kode Akun</th>
                    <th>Harga Satuan</th>
                    <th>Ongkos Kirim</th>
                    <th>Jumlah Dana</th>
                    <th>Link</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pengajuan->items as $index => $item)
                <tr>
                    <td>{{ $index+1 }}</td>
                    <td>{{ $item->nama_barang }}</td>
                    <td>{{ $item->volume }}</td>
                    <td>{{ $item->kro ?? '-' }}</td>
                    <td>{{ number_format($item->harga_satuan,0,',','.') }}</td>
                    <td>{{ number_format($item->ongkos_kirim,0,',','.') }}</td>
                    <td>{{ number_format($item->jumlah_dana_pengajuan,0,',','.') }}</td>
                    <td>@if($item->link) <a href="{{ $item->link }}" target="_blank">Lihat</a> @else - @endif</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <hr>

    {{-- 🔹 Pilihan tindakan --}}
    <div style="display:flex; gap:10px; margin-top:10px;">
        <button id="show-group-form" style="padding:8px 15px; background:#FFC107; color:black; border:none; border-radius:4px; cursor:pointer;">
            ➕ Buat Grup
        </button>

        <form action="{{ route('ppk.approveAll', $pengajuan->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menyetujui semua item tanpa membuat grup?')">
            @csrf
            <button type="submit" style="padding:8px 15px; background:#4CAF50; color:white; border:none; border-radius:4px; cursor:pointer;">
                ✅ Approve Langsung
            </button>
        </form>
    </div>

    {{-- 🔹 Form buat grup --}}
    <form id="group-form" action="{{ route('ppk.storeGroup', $pengajuan->id) }}" method="POST" style="display:none; margin-top:20px;">
        @csrf
        <h4>Buat Grup PPK</h4>
        <div id="groups-container"></div>
        <button type="button" id="add-group" style="background:#2196F3; color:white; padding:6px 12px; border:none; border-radius:4px;">Tambah Grup</button>
        <br><br>
        <button type="submit" style="padding:8px 15px; background:#008CBA; color:white; border:none; border-radius:4px;">💾 Simpan Grup</button>
    </form>

    <hr>

    {{-- 🔹 Grup yang sudah dibuat --}}
    <h4>Grup PPK yang Sudah Dibuat</h4>
    @forelse($pengajuan->ppkGroups as $group)
        <p>Grup: <strong>{{ $group->group_name }}</strong> | Status: <strong>{{ ucfirst($group->status) }}</strong></p>
        <table border="1" cellpadding="6" style="width:100%; border-collapse:collapse; margin-bottom:10px;">
            <thead style="background:#f3f3f3;">
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Volume</th>
                    @if($pengajuan->jenis_pengajuan === 'kerusakan')
                        <th>Lokasi</th>
                        <th>Jenis Kerusakan</th>
                    @else
                        <th>KRO / Kode Akun</th>
                        <th>Ongkos Kirim</th>
                    @endif
                    <th>Jumlah Dana</th>
                    <th>Foto / Link</th>
                </tr>
            </thead>
            <tbody>
                @foreach($group->items as $index => $item)
                <tr>
                    <td>{{ $index+1 }}</td>
                    <td>{{ $item->nama_barang }}</td>
                    <td>{{ $item->volume }}</td>
                    @if($pengajuan->jenis_pengajuan === 'kerusakan')
                        <td>{{ $item->lokasi ?? '-' }}</td>
                        <td>{{ $item->jenis_kerusakan ?? '-' }}</td>
                    @else
                        <td>{{ $item->kro ?? '-' }}</td>
                        <td>{{ number_format($item->ongkos_kirim,0,',','.') }}</td>
                    @endif
                    <td>{{ number_format($item->jumlah_dana_pengajuan,0,',','.') }}</td>
                    <td>
                        @if($pengajuan->jenis_pengajuan === 'kerusakan')
                            @if($item->foto) <a href="{{ $item->foto }}" target="_blank">Lihat</a> @else - @endif
                        @else
                            @if($item->link) <a href="{{ $item->link }}" target="_blank">Lihat</a> @else - @endif
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if($group->status === 'pending_ppk')
            <form action="{{ route('ppk.approveGroup', $group->id) }}" method="POST" style="margin-bottom:10px;">
                @csrf
                <button type="submit" style="padding:6px 12px; background:#4CAF50; color:white; border:none; border-radius:4px;">✅ Approve Grup Ini</button>
            </form>
        @endif
        <hr>
    @empty
        <p><em>Belum ada grup. Klik “Buat Grup” jika ingin memecah item pengajuan.</em></p>
    @endforelse
</div>

<script>
const pengajuanType = "{{ $pengajuan->jenis_pengajuan }}";
let groupCount = 0;
let selectedItems = new Set();

document.getElementById('show-group-form').addEventListener('click', function(){
    document.getElementById('group-form').style.display = 'block';
});

// fungsi buat render grup baru
function renderGroup(){
    const container = document.getElementById('groups-container');
    const groupDiv = document.createElement('div');
    groupDiv.classList.add('group');
    groupDiv.style.marginBottom = '15px';

    let html = `
        <input type="text" name="group_name[]" placeholder="Nama Grup" required>
        <p style="font-weight:600;">Pilih item untuk grup ini:</p>
        <table border="1" cellpadding="6" style="width:100%; border-collapse:collapse; margin-bottom:10px;">
            <thead style="background:#f3f3f3;">
                <tr>
                    <th>Pilih</th>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Volume</th>
    `;

    if(pengajuanType === 'kerusakan'){
        html += '<th>Lokasi</th><th>Jenis Kerusakan</th>';
    } else {
        html += '<th>KRO / Kode Akun</th><th>Ongkos Kirim</th>';
    }

    html += '<th>Jumlah Dana</th></tr></thead><tbody>';

    @foreach($pengajuan->items as $index => $item)
        html += selectedItems.has({{ $item->id }}) ? '' : `
            <tr>
                <td style="text-align:center;">
                    <input type="checkbox" class="item-checkbox" value="{{ $item->id }}" name="groups[${groupCount}][]">
                </td>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->nama_barang }}</td>
                <td>{{ $item->volume }}</td>
                ${pengajuanType === 'kerusakan'
                    ? `<td>{{ $item->lokasi ?? '-' }}</td><td>{{ $item->jenis_kerusakan ?? '-' }}</td>`
                    : `<td>{{ $item->kro ?? '-' }}</td><td>{{ number_format($item->ongkos_kirim,0,',','.') }}</td>`}
                <td>{{ number_format($item->jumlah_dana_pengajuan,0,',','.') }}</td>
            </tr>
        `;
    @endforeach

    html += '</tbody></table>';
    groupDiv.innerHTML = html;
    container.appendChild(groupDiv);

    // tambah event listener untuk checkbox
    groupDiv.querySelectorAll('.item-checkbox').forEach(cb=>{
        cb.addEventListener('change', function(){
            updateSelectedItems();
        });
    });

    groupCount++;
}

function updateSelectedItems(){
    selectedItems.clear();
    document.querySelectorAll('input[type="checkbox"][name^="groups"]').forEach(cb=>{
        if(cb.checked) selectedItems.add(parseInt(cb.value));
    });
}

document.getElementById('add-group').addEventListener('click', function(){
    renderGroup();
});
</script>
@endsection
