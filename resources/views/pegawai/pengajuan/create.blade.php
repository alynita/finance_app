@extends('layouts.app')

@section('title', 'Buat Pengajuan')
@section('header', 'Buat Pengajuan')

@section('content')
<div style="max-width:800px; margin:auto;">

    <form action="{{ route('pegawai.pengajuan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Form Utama -->
        <div style="margin-bottom:1rem; padding:1rem; border:1px solid #ccc; border-radius:5px;">
            <h3>Informasi Pengajuan</h3>

            <label>Nama Kegiatan</label>
            <input type="text" name="nama_kegiatan" value="{{ old('nama_kegiatan') }}" style="width:100%; padding:0.5rem; margin-bottom:0.5rem;" required>

            <label>Waktu Kegiatan</label>
            <input type="datetime-local" name="waktu_kegiatan" value="{{ old('waktu_kegiatan') }}" style="width:100%; padding:0.5rem; margin-bottom:0.5rem;" required>

            <label>Jenis Pengajuan</label>
            <select name="jenis_pengajuan" id="jenis_pengajuan" style="width:100%; padding:0.5rem; margin-bottom:0.5rem;" required onchange="resetItems()">
                <option value="">-- Pilih Jenis --</option>
                <option value="kerusakan">Pengajuan Kerusakan Barang</option>
                <option value="pembelian">Pengajuan Pembelian Barang</option>
                <option value="honor">Honorarium</option>
            </select>

            <label>Penanggung Jawab</label>
            <select name="pj_id" style="width:100%; padding:0.5rem; margin-bottom:0.5rem;" required>
                <option value="">-- Pilih Penanggung Jawab --</option>
                @foreach($penanggungJawabs as $pj)
                    <option value="{{ $pj->id }}">{{ $pj->nama }} - {{ $pj->jabatan }}</option>
                @endforeach
            </select>
        </div>

        <!-- Detail Item -->
        <div id="detail-items-container"></div>

        <button type="button" onclick="tambahItem()" style="margin-bottom:1rem; padding:0.5rem 1rem;">+ Tambah Item</button>
        <button type="submit" style="padding:0.5rem 1rem; background:#3490dc; color:white; border:none; border-radius:4px;">Simpan Pengajuan</button>
    </form>
</div>

<script>
let itemCount = 0;
let currentJenis = '';

function resetItems() {
    const jenis = document.getElementById('jenis_pengajuan').value;
    const container = document.getElementById('detail-items-container');
    container.innerHTML = '';
    itemCount = 0;
    currentJenis = jenis;

    if(jenis) tambahItem();
}

function tambahItem() {
    if(!currentJenis) {
        alert('Pilih jenis pengajuan dulu!');
        return;
    }

    const container = document.getElementById('detail-items-container');
    const div = document.createElement('div');
    div.style.border = '1px solid #ccc';
    div.style.padding = '1rem';
    div.style.marginBottom = '1rem';
    div.style.borderRadius = '5px';
    div.id = `item-${itemCount}`;

    let html = '';

    if(currentJenis === 'kerusakan') {
        html += `
            <h4>Item Kerusakan #${itemCount+1}</h4>
            <label>Nama Barang</label>
            <input type="text" name="items[${itemCount}][nama_barang]" style="width:100%; padding:0.5rem; margin-bottom:0.5rem;" required>
            <label>Lokasi</label>
            <input type="text" name="items[${itemCount}][lokasi]" style="width:100%; padding:0.5rem; margin-bottom:0.5rem;" required>
            <label>Jenis Kerusakan</label>
            <input type="text" name="items[${itemCount}][jenis_kerusakan]" style="width:100%; padding:0.5rem; margin-bottom:0.5rem;" required>
            <label>Volume</label>
            <input type="number" class="volume" name="items[${itemCount}][volume]" style="width:100%; padding:0.5rem; margin-bottom:0.5rem;" required>
            <label>Harga Satuan</label>
            <input type="number" class="harga_satuan" name="items[${itemCount}][harga_satuan]" style="width:100%; padding:0.5rem; margin-bottom:0.5rem;" required>
            <label>Jumlah Dana</label>
            <input type="number" class="jumlah_dana_pengajuan" name="items[${itemCount}][jumlah_dana_pengajuan]" style="width:100%; padding:0.5rem; margin-bottom:0.5rem;" readonly required>
            <label>Foto (opsional)</label>
            <input type="file" name="items[${itemCount}][foto]" style="width:100%; padding:0.5rem; margin-bottom:0.5rem;">
        `;
    } else if(currentJenis === 'pembelian') {
        html += `
            <h4>Item Pembelian #${itemCount+1}</h4>
            <label>Nama Barang</label>
            <input type="text" name="items[${itemCount}][nama_barang]" style="width:100%; padding:0.5rem; margin-bottom:0.5rem;" required>
            <label>KRO/Kode Akun</label>
            <input type="text" name="items[${itemCount}][kro]" style="width:100%; padding:0.5rem; margin-bottom:0.5rem;" required>
            <label>Harga Satuan</label>
            <input type="number" class="harga_satuan" name="items[${itemCount}][harga_satuan]" style="width:100%; padding:0.5rem; margin-bottom:0.5rem;" required>
            <label>Volume</label>
            <input type="number" class="volume" name="items[${itemCount}][volume]" style="width:100%; padding:0.5rem; margin-bottom:0.5rem;" required>
            <label>Ongkos Kirim</label>
            <input type="number" name="items[${itemCount}][ongkos_kirim]" style="width:100%; padding:0.5rem; margin-bottom:0.5rem;" required>
            <label>Jumlah Dana</label>
            <input type="number" class="jumlah_dana_pengajuan" name="items[${itemCount}][jumlah_dana_pengajuan]" style="width:100%; padding:0.5rem; margin-bottom:0.5rem;" readonly required>
        `;
    } else if(currentJenis === 'honor') {
        html += `
            <h4>Honorarium #${itemCount+1}</h4>
            <label>Tanggal</label>
            <input type="date" name="items[${itemCount}][tanggal]" style="width:100%; padding:0.5rem; margin-bottom:0.5rem;" required>
            <label>Nama</label>
            <input type="text" name="items[${itemCount}][nama]" style="width:100%; padding:0.5rem; margin-bottom:0.5rem;" required>
            <label>Jabatan</label>
            <input type="text" name="items[${itemCount}][jabatan]" style="width:100%; padding:0.5rem; margin-bottom:0.5rem;" required>
        `;
    }

    html += `<button type="button" onclick="hapusItem(${itemCount})" style="background:#ff5c5c; color:white; border:none; padding:0.3rem 0.6rem; border-radius:4px;">Hapus Item</button>`;
    div.innerHTML = html;
    container.appendChild(div);

    // Hitung Jumlah Dana otomatis
    if(currentJenis === 'kerusakan' || currentJenis === 'pembelian') {
        const volInput = div.querySelector('.volume');
        const hargaInput = div.querySelector('.harga_satuan');
        const ongkirInput = div.querySelector('input[name$="[ongkos_kirim]"]'); // cuma ada di pembelian
        const jumlahDanaInput = div.querySelector('.jumlah_dana_pengajuan');

        function updateJumlahDana() {
            const vol = parseFloat(volInput.value) || 0;
            const harga = parseFloat(hargaInput.value) || 0;
            let jumlah = vol * harga;

            if(currentJenis === 'pembelian') {
                const ongkos_kirim = parseFloat(ongkirInput.value) || 0;
                jumlah += ongkos_kirim;
            }

            jumlahDanaInput.value = jumlah;
        }

        volInput.addEventListener('input', updateJumlahDana);
        hargaInput.addEventListener('input', updateJumlahDana);
        if(currentJenis === 'pembelian') ongkirInput.addEventListener('input', updateJumlahDana);
    }

    itemCount++;
}

function hapusItem(id){
    const div = document.getElementById(`item-${id}`);
    div.remove();
}
</script>

@endsection
