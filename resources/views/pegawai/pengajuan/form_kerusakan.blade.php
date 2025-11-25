@extends('layouts.app')

@section('title', 'Pengajuan Kerusakan Barang')
@section('header', 'Form Pengajuan Kerusakan Barang')

@section('content')
<div style="max-width:800px; margin:auto;">
    <form action="{{ route('sarpras.pengajuan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div style="margin-bottom:1rem; padding:1rem; border:1px solid #ccc; border-radius:5px;">
            <h3>Informasi Pengajuan</h3>

            <label>Nama Kegiatan</label>
            <input type="text" name="nama_kegiatan" style="width:100%; padding:0.5rem; margin-bottom:0.5rem;" required>

            <label>Waktu Kegiatan</label>
            <input type="datetime-local" name="waktu_kegiatan" style="width:100%; padding:0.5rem; margin-bottom:0.5rem;" required>

            <input type="hidden" name="jenis_pengajuan" value="kerusakan">
        </div>

        <div id="detail-items-container"></div>

        <button type="button" onclick="tambahItem()" style="margin-bottom:1rem; padding:0.5rem 1rem;">+ Tambah Item</button>
        <button type="submit" style="padding:0.5rem 1rem; background:#3490dc; color:white; border:none; border-radius:4px;">Simpan Pengajuan</button>
    </form>
</div>

<script>
let itemCount = 0;

function tambahItem() {
    const container = document.getElementById('detail-items-container');

    const div = document.createElement('div');
    div.style.border = '1px solid #ccc';
    div.style.padding = '1rem';
    div.style.marginBottom = '1rem';
    div.style.borderRadius = '5px';
    div.id = `item-${itemCount}`;

    div.innerHTML = `
        <h4>Item Kerusakan #${itemCount+1}</h4>

        <label>Nama Barang</label>
        <input type="text" name="items[${itemCount}][nama_barang]" style="width:100%; padding:0.5rem; margin-bottom:0.5rem;" required>

        <label>Lokasi Barang</label>
        <input type="text" name="items[${itemCount}][lokasi]" style="width:100%; padding:0.5rem; margin-bottom:0.5rem;" required>

        <label>Jenis Kerusakan</label>
        <input type="text" name="items[${itemCount}][jenis_kerusakan]" style="width:100%; padding:0.5rem; margin-bottom:0.5rem;" required>

        <label>Volume</label>
        <input type="number" class="volume" name="items[${itemCount}][volume]" style="width:100%; padding:0.5rem; margin-bottom:0.5rem;" required>

        <label>Harga Satuan</label>
        <input type="number" class="harga_satuan" name="items[${itemCount}][harga_satuan]" style="width:100%; padding:0.5rem; margin-bottom:0.5rem;" required>

        <label>Jumlah Dana</label>
        <input type="number" class="jumlah_dana_pengajuan" name="items[${itemCount}][jumlah_dana_pengajuan]" style="width:100%; padding:0.5rem; margin-bottom:0.5rem;" readonly required>

        <label>Foto Barang (opsional)</label>
        <input type="file" name="items[${itemCount}][foto]" style="width:100%; padding:0.5rem; margin-bottom:0.5rem;">

        <button type="button" onclick="hapusItem(${itemCount})" style="background:#ff5c5c; color:white; border:none; padding:0.3rem 0.6rem; border-radius:4px;">Hapus Item</button>
    `;

    container.appendChild(div);

    const volume = div.querySelector('.volume');
    const harga = div.querySelector('.harga_satuan');
    const total = div.querySelector('.jumlah_dana_pengajuan');

    function updateTotal() {
        const v = parseFloat(volume.value) || 0;
        const h = parseFloat(harga.value) || 0;
        total.value = v * h;
    }

    volume.addEventListener('input', updateTotal);
    harga.addEventListener('input', updateTotal);

    itemCount++;
}

function hapusItem(id) {
    const div = document.getElementById(`item-${id}`);
    if (div) div.remove();
}
</script>

@endsection
