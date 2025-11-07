@extends('layouts.app')

@section('title', 'Pengajuan Pembelian Barang')
@section('header', 'Form Pengajuan Pembelian Barang')

@section('content')
<div style="max-width:800px; margin:auto;">
    <form action="{{ route('pegawai.pengajuan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div style="margin-bottom:1rem; padding:1rem; border:1px solid #ccc; border-radius:5px;">
            <h3>Informasi Pengajuan</h3>

            <label>Nama Kegiatan</label>
            <input type="text" name="nama_kegiatan" style="width:100%; padding:0.5rem; margin-bottom:0.5rem;" required>

            <label>Waktu Kegiatan</label>
            <input type="datetime-local" name="waktu_kegiatan" style="width:100%; padding:0.5rem; margin-bottom:0.5rem;" required>

            <input type="hidden" name="jenis_pengajuan" value="pembelian">
        </div>

        <div style="margin-bottom:1rem;">
            <label for="mengetahui">Mengetahui</label>
            <select name="mengetahui" id="mengetahui" style="width:100%; padding:0.5rem; margin-top:0.3rem; border:1px solid #ccc; border-radius:5px; font-size:1rem;" required>
                <option value="">-- Pilih --</option>
                <option value="adum">ADUM</option>
                <option value="timker_1">Timker 1</option>
                <option value="timker_2">Timker 2</option>
                <option value="timker_3">Timker 3</option>
                <option value="timker_4">Timker 4</option>
                <option value="timker_5">Timker 5</option>
                <option value="timker_6">Timker 6</option>
            </select>
        </div>

        <div id="detail-items-container"></div>

        <button type="button" onclick="tambahItem()" style="margin-bottom:1rem; padding:0.5rem 1rem;">+ Tambah Item</button>
        <button type="submit" style="padding:0.5rem 1rem; background:#3490dc; color:white; border:none; border-radius:4px;">Simpan Pengajuan</button>
    </form>
</div>

<script>
let itemCount = 0;

// Ambil data KRO dari PHP
let kroAccounts = @json($kroAccounts);

function tambahItem() {
    const container = document.getElementById('detail-items-container');
    const div = document.createElement('div');
    div.style.border = '1px solid #ccc';
    div.style.padding = '1rem';
    div.style.marginBottom = '1rem';
    div.style.borderRadius = '5px';
    div.id = `item-${itemCount}`;

    // Opsi dropdown KRO
    let options = `<option value="">-- Pilih KRO/Kode Akun --</option>`;
    kroAccounts.forEach(kro => {
        options += `<option value="${kro.value}">${kro.value}</option>`;
    });

    div.innerHTML = `
        <h4>Item Pembelian #${itemCount+1}</h4>

        <label>Nama Barang</label>
        <input type="text" name="items[${itemCount}][nama_barang]" style="width:100%; padding:0.5rem; margin-bottom:0.5rem;" required>

        <label>KRO/Kode Akun</label>
        <select name="items[${itemCount}][kro]" style="width:100%; padding:0.5rem; margin-bottom:0.5rem;" required>
            ${options}
        </select>

        <label>Volume</label>
        <input type="number" class="volume" name="items[${itemCount}][volume]" style="width:100%; padding:0.5rem; margin-bottom:0.5rem;" required>

        <label>Harga Satuan</label>
        <input type="number" class="harga_satuan" name="items[${itemCount}][harga_satuan]" style="width:100%; padding:0.5rem; margin-bottom:0.5rem;" required>

        <label>Ongkos Kirim</label>
        <input type="number" class="ongkos_kirim" name="items[${itemCount}][ongkos_kirim]" style="width:100%; padding:0.5rem; margin-bottom:0.5rem;" required>

        <label>Total Dana</label>
        <input type="number" class="jumlah_dana_pengajuan" name="items[${itemCount}][jumlah_dana_pengajuan]" style="width:100%; padding:0.5rem; margin-bottom:0.5rem;" readonly required>

        <label>Foto/Ket</label>
        <input type="file" class="foto" name="items[${itemCount}][foto]" style="width:100%; padding:0.5rem; margin-bottom:0.5rem;">

        <label>Link</label>
        <input type="url" class="link" name="items[${itemCount}][link]" style="width:100%; padding:0.5rem; margin-bottom:0.5rem;" placeholder="https://example.com">

        <button type="button" onclick="hapusItem(${itemCount})" style="background:#ff5c5c; color:white; border:none; padding:0.3rem 0.6rem; border-radius:4px;">Hapus Item</button>
    `;

    container.appendChild(div);

    // Total otomatis
    const volume = div.querySelector('.volume');
    const harga = div.querySelector('.harga_satuan');
    const ongkir = div.querySelector('.ongkos_kirim');
    const total = div.querySelector('.jumlah_dana_pengajuan');

    function updateTotal() {
        const v = parseFloat(volume.value) || 0;
        const h = parseFloat(harga.value) || 0;
        const o = parseFloat(ongkir.value) || 0;
        total.value = (v * h) + o;
    }

    volume.addEventListener('input', updateTotal);
    harga.addEventListener('input', updateTotal);
    ongkir.addEventListener('input', updateTotal);

    itemCount++;
}

function hapusItem(id){
    document.getElementById(`item-${id}`).remove();
}
</script>
@endsection
