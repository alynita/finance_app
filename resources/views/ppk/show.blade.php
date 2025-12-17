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
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pengajuan->items as $index => $item)
                <tr data-item-id="{{ $item->id }}">
                    <td>{{ $index+1 }}</td>
                    <td>{{ $item->nama_barang }}</td>
                    <td>{{ $item->volume }}</td>
                    <td>{{ number_format($item->harga_satuan,0,',','.') }}</td>
                    <td>{{ number_format($item->jumlah_dana_pengajuan,0,',','.') }}</td>
                    <td>{{ $item->lokasi ?? '-' }}</td>
                    <td>{{ $item->jenis_kerusakan ?? '-' }}</td>
                    <td>@if($item->foto) <a href="{{ $item->foto }}" target="_blank">Lihat</a> @else - @endif</td>
                    <td>
                        <input type="text" name="catatan_ppk[{{ $item->id }}]" class="form-control catatan-input" value="{{ $item->catatan_ppk ?? '' }}" placeholder="Isi catatan" data-item-id="{{ $item->id }}">
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
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
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pengajuan->items as $index => $item)
                <tr data-item-id="{{ $item->id }}">
                    <td>{{ $index+1 }}</td>
                    <td>{{ $item->nama_barang }}</td>
                    <td>{{ number_format($item->volume_tampil) }}</td>
                    <td>
                        <div class="kro-dropdown-wrapper" style="position:relative;">
                            <input type="text" class="kro-input" readonly value="{{ $item->kro ?? '' }}" placeholder="Pilih KRO →">
                            <input type="hidden" class="kro-hidden" value="{{ $item->kro ?? '' }}">
                            <div class="kro-menu" style="display:none; position:absolute; background:#fff; border:1px solid #ccc; max-height:200px; overflow:auto;"></div>
                        </div>
                        <button type="button" class="edit-kro-btn">Edit</button>
                        <button type="button" class="save-kro-btn" style="display:none;">Simpan</button>
                    </td>
                    <td>{{ number_format($item->harga_satuan,0,',','.') }}</td>
                    <td>{{ number_format($item->ongkos_kirim,0,',','.') }}</td>
                    <td>{{ number_format($item->jumlah_dana_tampil) }}</td>
                    <td>@if($item->link) <a href="{{ $item->link }}" target="_blank">Lihat</a> @else - @endif</td>
                    <td>
                        <input type="text" name="catatan_ppk[{{ $item->id }}]" class="form-control catatan-input" value="{{ $item->catatan_ppk ?? '' }}" placeholder="Isi catatan" data-item-id="{{ $item->id }}">
                    </td>
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
                        <th>Harga Satuan</th>
                    @endif
                    <th>Jumlah Dana</th>
                    <th>Foto / Link</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($group->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->nama_barang }}</td>
                    <td>{{ $item->volume }}</td>
                    @if($pengajuan->jenis_pengajuan === 'kerusakan')
                        <td>{{ $item->lokasi ?? '-' }}</td>
                        <td>{{ $item->jenis_kerusakan ?? '-' }}</td>
                    @else
                        <td>{{ $item->kro ?? '-' }}</td>
                        <td>{{ number_format($item->ongkos_kirim,0,',','.') }}</td>
                        <td>{{ number_format($item->harga_satuan,0,',','.') }}</td>
                    @endif
                    <td>{{ number_format($item->jumlah_dana_pengajuan,0,',','.') }}</td>
                    <td>
                        @if($pengajuan->jenis_pengajuan === 'kerusakan')
                            @if($item->foto) <a href="{{ $item->foto }}" target="_blank">Lihat</a> @else - @endif
                        @else
                            @if($item->link) <a href="{{ $item->link }}" target="_blank">Lihat</a> @else - @endif
                        @endif
                    </td>
                    <td>{{ $item->catatan_ppk }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <hr>
    @empty
        <p><em>Belum ada grup. Klik “Buat Grup” jika ingin memecah item pengajuan.</em></p>
    @endforelse

    {{-- ✅ Tombol approve semua grup --}}
    @if($pengajuan->ppkGroups->where('status','pending_ppk')->count() > 0)
    <form action="{{ route('ppk.approveAllGroups', $pengajuan->id) }}" method="POST" style="margin-top:10px;">
        @csrf
        <button type="submit" style="padding:8px 15px; background:#4CAF50; color:white; border:none; border-radius:4px;">
            ✅ Approve Semua Grup
        </button>
    </form>
    @endif

</div>

<script>
const pengajuanType = "{{ $pengajuan->jenis_pengajuan }}";
let groupCount = 0;
let selectedItems = new Set();

document.getElementById('show-group-form').addEventListener('click', function(){
    document.getElementById('group-form').style.display = 'block';
});

function renderGroup() {
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

    if (pengajuanType === 'kerusakan') {
        html += '<th>Lokasi</th><th>Jenis Kerusakan</th>';
    } else {
        html += '<th>KRO / Kode Akun</th><th>Ongkos Kirim</th><th>Harga Satuan</th><th>Link</th>';
    }

    html += '<th>Jumlah Dana</th><th>Catatan</th></tr></thead><tbody>';

    const items = document.querySelectorAll('tr[data-item-id]');
    items.forEach((row, index) => {
        const itemId = parseInt(row.dataset.itemId);
        if (selectedItems.has(itemId)) return;

        const namaBarang = row.children[1].textContent.trim();
        const volume = row.children[2].textContent.trim();
        const jumlahDana = row.children[pengajuanType === 'kerusakan' ? 6 : 6].textContent.trim();
        const catatanPpk = row.querySelector('input[name^="catatan_ppk"]')?.value || '';

        let lokasi = '', jenisKerusakan = '', kro = '', ongkir = '', hargaSatuan = '', link = '';

        if (pengajuanType === 'kerusakan') {
            lokasi = row.children[4].textContent.trim();
            jenisKerusakan = row.children[5].textContent.trim();
        } else {
            kro = row.querySelector('.kro-hidden')?.value || '';
            ongkir = row.children[5].textContent.trim();
            hargaSatuan = row.children[4].textContent.trim();
            link = row.children[7].innerHTML.trim();
        }

        html += `<tr>
                    <td style="text-align:center;">
                        <input type="checkbox" class="item-checkbox" value="${itemId}" name="groups[${groupCount}][]">
                    </td>
                    <td>${index + 1}</td>
                    <td>${namaBarang}</td>
                    <td>${volume}</td>
                    ${pengajuanType === 'kerusakan'
                        ? `<td>${lokasi}</td><td>${jenisKerusakan}</td><td>${jumlahDana}</td><td>${catatanPpk}</td>`
                        : `<td>${kro}</td><td>${ongkir}</td><td>${hargaSatuan}</td><td>${link}</td><td>${jumlahDana}</td><td>${catatanPpk}</td>`}
                </tr>`;
    });

    html += '</tbody></table>';
    groupDiv.innerHTML = html;
    container.appendChild(groupDiv);

    groupDiv.querySelectorAll('.item-checkbox').forEach(cb => {
        cb.addEventListener('change', updateSelectedItems);
    });

    groupCount++;
}

function updateSelectedItems(){
    selectedItems.clear();
    document.querySelectorAll('input[type="checkbox"][name^="groups"]').forEach(cb=>{
        if(cb.checked) selectedItems.add(parseInt(cb.value));
    });
}

document.getElementById('add-group').addEventListener('click', function(e){
    e.preventDefault();
    renderGroup();
});

// 🔹 Catatan auto-save
document.querySelectorAll('.catatan-input').forEach(input => {
    input.addEventListener('input', function() {
        const itemId = this.dataset.itemId;
        const value = this.value;
        if (this.timer) clearTimeout(this.timer);
        this.timer = setTimeout(() => {
            fetch(`/ppk/update-catatan/${itemId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ catatan_ppk: value })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) console.log('✅ Catatan tersimpan');
                else console.error('❌ Gagal simpan catatan');
            })
            .catch(err => console.error('Fetch error:', err));
        }, 500);
    });
});
</script>
@endsection
