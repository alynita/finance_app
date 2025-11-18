@extends('layouts.app')

@section('content')
<div style="max-width:900px; margin:auto; padding:20px;">

    <h2 style="text-align:center; color:#2c3e50; margin-bottom:30px;">Form Pengajuan Honor</h2>

    <form action="{{ route('keuangan.honor.store') }}" method="POST">
        @csrf

        <!-- Informasi Umum Kegiatan -->
        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom:25px;">
            <div style="display:flex; flex-direction:column;">
                <label for="nama_kegiatan" style="margin-bottom:5px; font-weight:600;">Nama Kegiatan</label>
                <input type="text" name="nama_kegiatan" id="nama_kegiatan" placeholder="Masukkan nama kegiatan"
                    required style="padding:10px; border:1px solid #ccc; border-radius:5px; font-size:1rem;">
            </div>

            <div style="display:flex; flex-direction:column;">
                <label for="waktu" style="margin-bottom:5px; font-weight:600;">Tanggal Kegiatan</label>
                <input type="date" name="waktu" id="waktu" required
                    style="padding:10px; border:1px solid #ccc; border-radius:5px; font-size:1rem;">
            </div>
        </div>

        <!-- Alokasi Anggaran -->
        <div style="margin-bottom:30px;">
            <label for="alokasi_anggaran" style="font-weight:600; margin-bottom:5px; display:block;">Alokasi Anggaran</label>
            
            <div class="alokasi-dropdown-wrapper" style="position:relative; display:inline-block; width:100%;">
                <input type="text" class="alokasi-input" name="alokasi_anggaran" placeholder="Pilih Alokasi Anggaran →"
                    style="width:100%; padding:10px; font-size:1rem; border:1px solid #ccc; border-radius:5px;">
                <div class="alokasi-menu" style="display:none; position:absolute; background:#fff; border:1px solid #ccc; max-height:250px; overflow:auto; width:100%; z-index:1000;"></div>
            </div>
        </div>

        <!-- Container Item Honor -->
        <div id="honor-container">
            <div class="honor-row" style="background:#ffffff; padding:20px; border-radius:8px; box-shadow:0 1px 4px rgba(0,0,0,0.1); margin-bottom:20px;">
                
                <!-- Baris Nama dan Jabatan -->
                <div style="display:flex; gap:15px; margin-bottom:20px;">
                    <div style="flex:1; display:flex; flex-direction:column;">
                        <label style="margin-bottom:5px;">Nama</label>
                        <input type="text" name="nama[]" placeholder="Masukkan nama" required
                            style="padding:10px; border:1px solid #ccc; border-radius:5px; margin-bottom:5px; box-sizing:border-box;">
                    </div>
                    <div style="flex:1; display:flex; flex-direction:column;">
                        <label style="margin-bottom:5px;">Jabatan</label>
                        <input type="text" name="jabatan[]" placeholder="Masukkan jabatan" required
                            style="padding:10px; border:1px solid #ccc; border-radius:5px; margin-bottom:5px; box-sizing:border-box;">
                    </div>
                </div>

                <!-- Baris Tujuan dan Jumlah Hari -->
                <div style="display:flex; gap:15px; margin-bottom:20px;">
                    <div style="flex:1; display:flex; flex-direction:column;">
                        <label style="margin-bottom:5px;">Tujuan</label>
                        <input type="text" name="tujuan[]" placeholder="Masukkan tujuan" required
                            style="padding:10px; border:1px solid #ccc; border-radius:5px; margin-bottom:5px; box-sizing:border-box;">
                    </div>
                    <div style="flex:1; display:flex; flex-direction:column;">
                        <label style="margin-bottom:5px;">Jumlah Hari</label>
                        <input type="number" name="jumlah_hari[]" value="0" required
                            style="padding:10px; border:1px solid #ccc; border-radius:5px; margin-bottom:5px; box-sizing:border-box;">
                    </div>
                </div>

                <!-- Baris Uang Harian, PPH 21, Potongan Lain -->
                <div style="display:flex; gap:15px; margin-bottom:20px;">

                    <!-- Kolom Jenis Honor + Uang Harian/Transport -->
                    <div style="flex:1; display:flex; flex-direction:column;">
                        
                        <label style="margin-bottom:5px;">Jenis Honor</label>
                        <select name="jenis_uang[]" class="jenisUang"
                            style="padding:10px; border:1px solid #ccc; border-radius:5px; margin-bottom:10px;">
                            <option value="harian">Uang Harian</option>
                            <option value="transport">Uang Transport</option>
                        </select>

                        <!-- INPUT UANG HARIAN -->
                        <label class="labelHarian" style="margin-bottom:5px;">Nominal Uang Harian</label>
                        <input type="number" name="uang_harian[]" class="inputHarian"
                            style="padding:10px; border:1px solid #ccc; border-radius:5px;">

                        <!-- INPUT UANG TRANSPORT -->
                        <label class="labelTransport" style="margin-bottom:5px; display:none;">Nominal Uang Transport</label>
                        <input type="number" name="uang_transport[]" class="inputTransport" style="display:none;
                            padding:10px; border:1px solid #ccc; border-radius:5px;">
                    </div>

                    <!-- Kolom PPH -->
                    <div style="flex:1; display:flex; flex-direction:column;">
                        <label style="margin-bottom:5px;">PPH 21 (%)</label>
                        <select name="pph21[]" class="pphSelect"
                                style="width:100%; padding:10px; border:1px solid #ccc; border-radius:5px;">
                            <option value="0">0%</option>
                            <option value="5">5%</option>
                            <option value="15">15%</option>
                            <option value="manual">Manual</option>
                        </select>

                        <input type="number" name="pph21_manual[]" class="pphManual" 
                            placeholder="Masukkan PPH 21 (%)" 
                            style="padding:10px; border:1px solid #ccc; border-radius:5px; margin-top:8px; display:none;">
                    </div>

                    <!-- Kolom Potongan lain -->
                    <div style="flex:1; display:flex; flex-direction:column;">
                        <label style="margin-bottom:5px;">Potongan Lain (%)</label>
                        <input type="number" name="potongan_lain[]" value=""
                            style="padding:10px; border:1px solid #ccc; border-radius:5px;">
                    </div>

                </div>

                <!-- Baris Jumlah Dibayar -->
                <div style="margin-bottom:15px;">
                    <label>Jumlah Dibayar</label>
                    <input type="number" name="jumlah_dibayar[]" placeholder="Otomatis terhitung" readonly
                        style="width:100%; padding:8px; border:1px solid #ccc; border-radius:5px; background:#f8f9fa;">
                </div>

                <!-- Baris Rekening, Atas Nama, Bank -->
                <div style="display:flex; gap:15px; margin-bottom:20px;">
                    <div style="flex:1; display:flex; flex-direction:column;">
                        <label style="margin-bottom:5px;">Nomor Rekening</label>
                        <input type="text" name="nomor_rekening[]" required
                            style="padding:10px; border:1px solid #ccc; border-radius:5px; box-sizing:border-box;">
                    </div>
                    <div style="flex:1; display:flex; flex-direction:column;">
                        <label style="margin-bottom:5px;">Atas Nama</label>
                        <input type="text" name="atas_nama[]" required
                            style="padding:10px; border:1px solid #ccc; border-radius:5px; box-sizing:border-box;">
                    </div>
                    <div style="flex:1; display:flex; flex-direction:column;">
                        <label style="margin-bottom:5px;">Bank</label>
                        <input type="text" name="bank[]" required
                            style="padding:10px; border:1px solid #ccc; border-radius:5px; box-sizing:border-box;">
                    </div>
                </div>

            </div>
        </div>

        <!-- Tombol Tambah Baris -->
        <div style="text-align:center; margin-bottom:20px;">
            <button type="button" id="addRow" style="padding:10px 20px; background:#2c3e50; color:white; border:none; border-radius:5px; cursor:pointer;">
                + Tambah Baris
            </button>
        </div>

        <!-- Tombol Simpan -->
        <div style="text-align:center;">
            <button type="submit" style="padding:10px 25px; background:#1abc9c; color:white; border:none; border-radius:5px; cursor:pointer; font-weight:500;">
                Simpan Pengajuan
            </button>
        </div>
    </form>

    <script>
    let index = 1;

    function attachListeners(row) {
        // cari control di dalam row (scoped)
        const jenisSelect = row.querySelector('[name="jenis_uang[]"]');
        const inputHarian = row.querySelector('[name="uang_harian[]"]');
        const inputTransport = row.querySelector('[name="uang_transport[]"]');
        const jumlahHari = row.querySelector('[name="jumlah_hari[]"]');
        const pphSelect = row.querySelector('[name="pph21[]"]');
        const pphManual = row.querySelector('[name="pph21_manual[]"]');
        const potonganInput = row.querySelector('[name="potongan_lain[]"]');
        const jumlahDibayar = row.querySelector('[name="jumlah_dibayar[]"]');

        // safety: jika elemen penting hilang, stop
        if (!jenisSelect || !inputHarian || !inputTransport || !jumlahDibayar) return;

        // fungsi hitung per row
        function hitungJumlah() {
            const jenis = jenisSelect.value;
            const uangHarianVal = parseFloat(inputHarian.value) || 0;
            const uangTransportVal = parseFloat(inputTransport.value) || 0;
            const hariVal = parseFloat(jumlahHari.value) || 0;

            let total = 0;
            if (jenis === 'harian') {
                total = uangHarianVal * hariVal;
            } else {
                total = uangTransportVal; // transport fixed
            }

            // potongan (jika kosong dianggap 100%)
            let potonganPercent = parseFloat(potonganInput?.value);
            if (isNaN(potonganPercent) || potonganPercent === 0) potonganPercent = 100;

            const setelahPotongan = total * (potonganPercent / 100);

            // PPH
            let pphPercent = 0;
            if (pphSelect) {
                if (pphSelect.value === 'manual') {
                    pphPercent = parseFloat(pphManual?.value) || 0;
                } else {
                    pphPercent = parseFloat(pphSelect.value) || 0;
                }
            }

            const nilaiPph = setelahPotongan * (pphPercent / 100);
            const dibayar = Math.round(setelahPotongan - nilaiPph);

            jumlahDibayar.value = dibayar >= 0 ? dibayar : 0;
        }

        // show/hide input sesuai jenis selected
        function updateVisibleInputs() {
            if (jenisSelect.value === 'harian') {
                inputHarian.style.display = 'block';
                inputTransport.style.display = 'none';
            } else {
                inputHarian.style.display = 'none';
                inputTransport.style.display = 'block';
            }
            // hitung ulang
            hitungJumlah();
        }

        // pasang event listener yang diperlukan
        ['input','change'].forEach(evt => {
            jumlahHari?.addEventListener(evt, hitungJumlah);
            inputHarian?.addEventListener(evt, hitungJumlah);
            inputTransport?.addEventListener(evt, hitungJumlah);
            potonganInput?.addEventListener(evt, hitungJumlah);
            pphManual?.addEventListener(evt, hitungJumlah);
            pphSelect?.addEventListener(evt, () => {
                // tampilkan/manual handling
                if (pphSelect.value === 'manual' && pphManual) {
                    pphManual.style.display = 'block';
                } else if (pphManual) {
                    pphManual.style.display = 'none';
                    pphManual.value = '';
                }
                hitungJumlah();
            });
            if (jenisSelect) jenisSelect.addEventListener(evt, updateVisibleInputs);
        });

        // run sekali saat attach supaya nilai awal terhitung
        // ensure manual PPH hidden initially if present
        if (pphManual) pphManual.style.display = (pphSelect && pphSelect.value === 'manual') ? 'block' : 'none';
        updateVisibleInputs();
    }

    // addRow handler (cloning)
    document.getElementById('addRow').addEventListener('click', () => {
        const container = document.getElementById('honor-container');
        const template = container.firstElementChild;
        const newRow = template.cloneNode(true);

        // reset values inside the cloned row
        newRow.querySelectorAll('input, select').forEach(el => {
            if (el.type === 'number' || el.type === 'text' || el.tagName === 'INPUT') el.value = '';
            if (el.tagName === 'SELECT') el.selectedIndex = 0;
        });

        // ensure hidden/visible inputs set consistent initial state
        const inputHarian = newRow.querySelector('[name="uang_harian[]"]');
        const inputTransport = newRow.querySelector('[name="uang_transport[]"]');
        if (inputHarian) inputHarian.style.display = 'block';
        if (inputTransport) inputTransport.style.display = 'none';

        container.appendChild(newRow);
        attachListeners(newRow);
        index++;
    });

    // attach to existing rows on page load
    document.querySelectorAll('#honor-container .honor-row').forEach(row => attachListeners(row));

    //Dropdown Alokasi Anggaran
    const kroAllData = @json($kroAll); // dari controller, buildTree($kroData)

    const wrapper = document.querySelector('.alokasi-dropdown-wrapper');
    const input = wrapper.querySelector('.alokasi-input');
    const menu = wrapper.querySelector('.alokasi-menu');

    // klik input → toggle menu
    input.addEventListener('click', e => {
        e.stopPropagation();
        menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
    });

    // klik di luar → tutup menu
    document.addEventListener('click', e => {
        if(!wrapper.contains(e.target)) menu.style.display = 'none';
    });

    // build tree nodes
    function buildTreeNodes(data, parentEl, path = []) {
        data.forEach(item => {
            const currentLabel = item.kode_akun ?? item.kode ?? item.kro;
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
                buildTreeNodes(item.children, childBox, newPath);
            }

            toggle.addEventListener("click", () => {
                if(!childBox) return;
                childBox.style.display = childBox.style.display === "block" ? "none" : "block";
                toggle.textContent = childBox.style.display === "block" ? "▾" : "▸";
            });

            label.addEventListener("click", () => {
                if(newPath.length < 3){
                    if(childBox){
                        childBox.style.display = "block";
                        toggle.textContent = "▾";
                    }
                    return;
                }
                const finalVal = newPath.slice(2).join('/');
                input.value = finalVal;
                menu.style.display = 'none';
            });
        });
    }

    // render menu
    menu.innerHTML = '';
    buildTreeNodes(kroAllData, menu);
    </script>


</div>
@endsection
