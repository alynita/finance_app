@extends('layouts.app')

@section('content')
<div style="max-width:800px; margin:auto; padding:20px; background:#f0f2f5; border-radius:8px;">
    <h2 style="margin-bottom:25px; text-align:center; color:#333;">Tambah Pengajuan Honor</h2>

    <!-- Form utama di kotak transparan -->
    <form action="{{ route('keuangan.honor.store') }}" method="POST" style="margin-bottom:30px;">
        @csrf

        <!-- Input tambahan di atas form utama -->
        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:15px; margin-bottom:20px;">
            <div style="display:flex; flex-direction:column;">
                <label for="nama_kegiatan" style="margin-bottom:5px; font-weight:500;">Nama Kegiatan</label>
                <input type="text" id="nama_kegiatan" name="nama_kegiatan" placeholder="Masukkan nama kegiatan" required style="padding:8px; border:1px solid #ccc; border-radius:5px;">
            </div>

            <div style="display:flex; flex-direction:column;">
                <label for="waktu" style="margin-bottom:5px; font-weight:500;">Waktu Penyelenggaraan</label>
                <input type="date" id="waktu" name="waktu" required style="padding:8px; border:1px solid #ccc; border-radius:5px;">
            </div>

            <div style="display:flex; flex-direction:column;">
                <label for="alokasi_anggaran" style="margin-bottom:5px; font-weight:500;">Alokasi Anggaran</label>
                <input type="number" id="alokasi_anggaran" name="alokasi_anggaran" placeholder="Masukkan alokasi anggaran" required style="padding:8px; border:1px solid #ccc; border-radius:5px;">
            </div>
        </div>

        <div style="background: rgba(255,255,255,0.85); padding:20px; border-radius:8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:15px;">
                
                <div style="display:flex; flex-direction:column;">
                    <label for="nama" style="margin-bottom:5px; font-weight:500;">Nama</label>
                    <input type="text" id="nama" name="nama" placeholder="Masukkan nama" required style="padding:8px; border:1px solid #ccc; border-radius:5px;">
                </div>

                <div style="display:flex; flex-direction:column;">
                    <label for="jabatan" style="margin-bottom:5px; font-weight:500;">Jabatan</label>
                    <input type="text" id="jabatan" name="jabatan" placeholder="Masukkan jabatan" required style="padding:8px; border:1px solid #ccc; border-radius:5px;">
                </div>

                <div style="display:flex; flex-direction:column;">
                    <label for="tujuan" style="margin-bottom:5px; font-weight:500;">Tujuan</label>
                    <input type="text" id="tujuan" name="tujuan" placeholder="Masukkan tujuan" required style="padding:8px; border:1px solid #ccc; border-radius:5px;">
                </div>

                <div style="display:flex; flex-direction:column;">
                    <label for="uang_harian" style="margin-bottom:5px; font-weight:500;">Uang Harian</label>
                    <input type="number" id="uang_harian" name="uang_harian" placeholder="Masukkan uang harian" required style="padding:8px; border:1px solid #ccc; border-radius:5px;">
                </div>

                <div style="display:flex; flex-direction:column;">
                    <label for="pph21" style="margin-bottom:5px; font-weight:500;">PPH 21 (%)</label>
                    <select id="pph21" name="pph21" style="padding:8px; border:1px solid #ccc; border-radius:5px;">
                        <option value="0">0%</option>
                        <option value="5">5%</option>
                        <option value="15">15%</option>
                        <option value="manual">Manual</option>
                    </select>
                    <!-- Input manual PPH -->
                    <input type="number" id="pph21_manual" placeholder="Masukkan PPH 21 (%)" style="padding:8px; border:1px solid #ccc; border-radius:5px; margin-top:8px; display:none;">
                </div>

                <div style="display:flex; flex-direction:column;">
                    <label for="jumlah_dibayar" style="margin-bottom:5px; font-weight:500;">Jumlah Dibayar</label>
                    <input type="number" id="jumlah_dibayar" name="jumlah_dibayar" placeholder="Jumlah dibayar (otomatis)" readonly style="padding:8px; border:1px solid #ccc; border-radius:5px;">
                </div>

                <div style="display:flex; flex-direction:column;">
                    <label for="nomor_rekening" style="margin-bottom:5px; font-weight:500;">Nomor Rekening</label>
                    <input type="text" id="nomor_rekening" name="nomor_rekening" placeholder="Masukkan nomor rekening" required style="padding:8px; border:1px solid #ccc; border-radius:5px;">
                </div>

                <div style="display:flex; flex-direction:column;">
                    <label for="atas_nama" style="margin-bottom:5px; font-weight:500;">Atas Nama</label>
                    <input type="text" id="atas_nama" name="atas_nama" placeholder="Masukkan nama pemilik rekening" required style="padding:8px; border:1px solid #ccc; border-radius:5px;">
                </div>

                <div style="display:flex; flex-direction:column;">
                    <label for="bank" style="margin-bottom:5px; font-weight:500;">Bank</label>
                    <input type="text" id="bank" name="bank" placeholder="Masukkan nama bank" required style="padding:8px; border:1px solid #ccc; border-radius:5px;">
                </div>
            </div>
        </div>

        <div style="text-align:center; margin-top:20px;">
            <button type="submit" style="padding:10px 20px; background:#007bff; color:white; border:none; border-radius:5px; cursor:pointer; font-weight:500; transition:background 0.3s;">
                Simpan
            </button>
        </div>
    </form>

    <!-- Script PPH Manual & Hitung Jumlah Dibayar -->
    <script>
        const uangHarian = document.getElementById('uang_harian');
        const pphSelect = document.getElementById('pph21');
        const pphManual = document.getElementById('pph21_manual');
        const jumlahDibayar = document.getElementById('jumlah_dibayar');

        // Tampilkan input manual saat pilih "Manual"
        pphSelect.addEventListener('change', function() {
            if(this.value === 'manual') {
                pphManual.style.display = 'block';
            } else {
                pphManual.style.display = 'none';
            }
            hitungJumlah();
        });

        function hitungJumlah() {
            let uang = parseFloat(uangHarian.value) || 0;
            let pph = 0;

            if(pphSelect.value === 'manual') {
                pph = parseFloat(pphManual.value) || 0;
            } else {
                pph = parseFloat(pphSelect.value) || 0;
            }

            jumlahDibayar.value = uang - (uang * pph / 100);
        }

        uangHarian.addEventListener('input', hitungJumlah);
        pphManual.addEventListener('input', hitungJumlah);
    </script>
</div>
@endsection
