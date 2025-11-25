@extends('layouts.app')

@section('title', 'Form Pengajuan Pembelian Barang')
@section('header', 'Form Pengajuan Pembelian Barang')

<style> 
.kro-dropdown-wrapper { 
    position: relative; /* penting */ 
    margin-bottom: 1rem; 
    } 
    .kro-input { 
        width: 100%; 
        padding: 12px; 
        font-size: 16px; 
        border: 1px solid #ccc; 
        border-radius: 5px; 
        
    } 
    .kro-menu { 
        position: absolute; 
        top: 100%; /* tepat di bawah input */ 
        left: 0; width: 100%; 
        max-height: 250px; 
        background: white; 
        border: 1px solid #ccc; 
        border-radius: 4px; 
        overflow-y: auto; 
        display: none; 
        z-index: 9999; 
        padding: 10px; 
    } 
    .kro-menu .tree-root { 
        font-size: 13px; 
    } 
    .kro-input { 
        width: 100% !important; 
        padding: 12px; 
        font-size: 20x; /* <— perbesar tulisan input */ 
    } 
    .kro-dropdown { 
        width: 100%; 
        border: 1px solid #ccc; 
        padding: 12px; 
        margin-top: 5px; 
        border-radius: 5px; 
        background: white; 
        font-size: 20px; /* <— perbesar tulisan list KRO */ 
        } 
        .kro-dropdown li, 
        .kro-dropdown span { 
            font-size: 20px; /* <— semua teks di dalam list */ 
        } 
</style>

@section('content')
<div style="max-width:900px;margin:auto;">
    <form id="pengajuanForm" action="{{ route('pegawai.pengajuan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf 

        <!-- Informasi Pengajuan -->
        <div style="margin-bottom:1rem; padding:1rem; border:1px solid #ccc; border-radius:5px;">
            <h3>Informasi Pengajuan</h3>
            <label>Nama Kegiatan</label>
            <input type="text" name="nama_kegiatan" style="width:100%;padding:0.5rem;margin-bottom:0.5rem;" required>

            <label>Waktu Kegiatan</label>
            <input type="datetime-local" name="waktu_kegiatan" style="width:100%;padding:0.5rem;margin-bottom:0.5rem;" required>

            <input type="hidden" name="jenis_pengajuan" value="pembelian">
        </div>

        @php
            $role = Auth::user()->role;
            if (str_contains($role, 'anggota_timker_')) {
                $timkerNumber = str_replace('anggota_timker_', '', $role);
                $mengetahui_jabatan = 'timker_' . $timkerNumber;
            } else {
                $mengetahui_jabatan = 'adum';
            }
        @endphp

        <input type="hidden" name="mengetahui_jabatan" value="{{ $mengetahui_jabatan }}">

        <div id="detail-items-container"></div>

        <button type="button" id="btnTambah" style="margin-bottom:1rem;padding:0.5rem 1rem;">+ Tambah Item</button>
        <button type="submit" style="padding:0.5rem 1rem;background:#3490dc;color:white;border:none;border-radius:4px;">Simpan Pengajuan</button>
    </form>
</div>

<script>
window.kroAll = @json($kroAll);
let itemCount = 0;

// Tambah item baru
document.getElementById('btnTambah').addEventListener('click', tambahItem);

function tambahItem() {
    const idx = itemCount++;
    const container = document.getElementById('detail-items-container');
    const wrapper = document.createElement('div');
    wrapper.id = `item-${idx}`;
    wrapper.style = "border:1px solid #ccc;padding:1rem;margin-bottom:1rem;border-radius:5px;";

    wrapper.innerHTML = `
        <h4>Item #${idx + 1}</h4>
        <label>Nama Barang</label>
        <input type="text" name="items[${idx}][nama_barang]" style="width:100%;padding:0.5rem;margin-bottom:0.5rem;" required>

        <div class="form-group kro-dropdown-wrapper" id="kro-wrapper-${idx}">
            <label for="kro-${idx}">KRO / Kode Akun</label>
            <input type="text" name="items[${idx}][kro]" id="kro-trigger-${idx}" class="kro-input" readonly placeholder="Pilih KRO →">
            <input type="hidden" name="items[${idx}][kode_kro]" id="kode_kro-${idx}">
            <div class="kro-menu" id="kro-menu-${idx}"></div>
        </div>

        <label>Volume</label>
        <input type="number" name="items[${idx}][volume]" id="vol-${idx}" style="width:100%;padding:0.5rem;margin-bottom:0.5rem;" required>

        <label>Harga Satuan</label>
        <input type="number" name="items[${idx}][harga_satuan]" id="hrg-${idx}" style="width:100%;padding:0.5rem;margin-bottom:0.5rem;" required>

        <label>Ongkos Kirim</label>
        <input type="number" name="items[${idx}][ongkos_kirim]" id="ong-${idx}" style="width:100%;padding:0.5rem;margin-bottom:0.5rem;" required>

        <label>Total Dana</label>
        <input type="number" name="items[${idx}][jumlah_dana_pengajuan]" id="tot-${idx}" style="width:100%;padding:0.5rem;margin-bottom:0.5rem;" readonly required>

        <label>Foto/Keterangan</label>
        <input type="file" name="items[${idx}][foto]" style="width:100%;padding:0.5rem;margin-bottom:0.5rem;">

        <label>Link</label>
        <input type="url" name="items[${idx}][link]" placeholder="https://example.com" style="width:100%;padding:0.5rem;margin-bottom:0.5rem;">

        <button type="button" onclick="hapusItem(${idx})" style="background:#ff5c5c;color:white;border:none;padding:0.4rem 0.8rem;border-radius:4px;">Hapus</button>
    `;
    container.appendChild(wrapper);

    // Hitung Total Dana
    const vol = document.getElementById(`vol-${idx}`);
    const hrg = document.getElementById(`hrg-${idx}`);
    const ong = document.getElementById(`ong-${idx}`);
    const tot = document.getElementById(`tot-${idx}`);
    function hitung() {
        const v = parseFloat(vol.value||0);
        const h = parseFloat(hrg.value||0);
        const o = parseFloat(ong.value||0);
        tot.value = (v*h)+o;
    }
    vol.addEventListener('input', hitung);
    hrg.addEventListener('input', hitung);
    ong.addEventListener('input', hitung);

    // Buat KRO dropdown
    initKroDropdown(idx);
}

// Hapus item
function hapusItem(id) {
    const el = document.getElementById(`item-${id}`);
    if(el) el.remove();
}

// Dropdown KRO
function initKroDropdown(idx) {
    const wrapper = document.getElementById(`kro-wrapper-${idx}`);
    const trigger = document.getElementById(`kro-trigger-${idx}`);
    const menu = document.getElementById(`kro-menu-${idx}`);
    const hiddenInput = document.getElementById(`kode_kro-${idx}`);

    trigger.addEventListener("click", function () {
        menu.style.display = menu.style.display === "block" ? "none" : "block";
    });

    document.addEventListener("click", function(e) {
        if (!wrapper.contains(e.target)) {
            menu.style.display = "none";
        }
    });

    menu.innerHTML = "";
    const treeRoot = document.createElement("div");
    treeRoot.classList.add("tree-root");
    menu.appendChild(treeRoot);

    buildTreeNodes(window.kroAll, treeRoot, function(selected) {
        trigger.value = selected;
        hiddenInput.value = selected;
        menu.style.display = "none";
    });
}

// Build tree KRO
function buildTreeNodes(data, parentEl, onSelect, path = []) {
    data.forEach(item => {
        const currentLabel = item.kode_akun ?? item.kode;
        const newPath = [...path, currentLabel];

        const row = document.createElement('div');
        row.style.marginLeft = "10px";

        const toggle = document.createElement('span');
        toggle.textContent = item.children?.length ? "▸" : "";
        toggle.style.cursor = "pointer";
        toggle.style.marginRight = "4px";

        const label = document.createElement('span');
        label.textContent = currentLabel;
        label.style.cursor = "pointer";

        row.appendChild(toggle);
        row.appendChild(label);
        parentEl.appendChild(row);

        let childBox = null;
        if(item.children?.length) {
            childBox = document.createElement("div");
            childBox.style.display = "none";
            childBox.style.marginLeft = "20px";
            parentEl.appendChild(childBox);
            buildTreeNodes(item.children, childBox, onSelect, newPath);
        }

        toggle.addEventListener("click", () => {
            if(!childBox) return;
            childBox.style.display = childBox.style.display === "block" ? "none" : "block";
            toggle.textContent = childBox.style.display === "block" ? "▾" : "▸";
        });

        label.addEventListener("click", () => {
            const level = newPath.length;
            if(level <= 2) {
                if(childBox) {
                    childBox.style.display = "block";
                    toggle.textContent = "▾";
                }
                return;
            }
            let finalPath = newPath.slice(2);
            const finalVal = finalPath.join('.');
            onSelect(finalVal);
        });
    });
}

// Validasi KRO wajib
document.getElementById('pengajuanForm').addEventListener('submit', function(e){
    let valid = true;

    const kodeInputs = document.querySelectorAll('input[name^="items"][name$="[kode_kro]"]');
    kodeInputs.forEach((hiddenInput, idx) => {
        const trigger = document.getElementById(`kro-trigger-${idx}`);
        if(!hiddenInput.value || hiddenInput.value.trim() === ""){
            valid = false;
            trigger.setCustomValidity("Isi bidang ini");
            trigger.reportValidity(); // tampilkan warning di input readonly
        } else {
            trigger.setCustomValidity(""); // reset valid
        }
    });

    if(!valid){
        e.preventDefault(); // blok submit
        // fokus ke field pertama yang kosong
        const firstEmpty = Array.from(kodeInputs).find(i => !i.value);
        if(firstEmpty){
            document.getElementById(`kro-trigger-${Array.from(kodeInputs).indexOf(firstEmpty)}`).focus();
        }
    }
});

// Load item pertama otomatis
document.addEventListener("DOMContentLoaded", function() {
    tambahItem();
});
</script>
@endsection
