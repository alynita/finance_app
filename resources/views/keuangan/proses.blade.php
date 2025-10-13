@extends('layouts.app')

@section('title', 'Proses Pengajuan')
@section('header', 'Proses Pengajuan')

@section('content')
<div style="max-width:1200px; margin:auto;">

    {{-- Notifikasi sukses atau error --}}
    @if(session('success'))
        <div style="background-color: #d4edda; color: #155724; padding: 10px; margin-bottom: 10px; border-radius: 5px;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background-color: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 10px; border-radius: 5px;">
            {{ session('error') }}
        </div>
    @endif

    {{-- Informasi dasar pengajuan --}}
    <p><strong>Nama Kegiatan:</strong> {{ $pengajuan->nama_kegiatan }}</p>
    <p><strong>Waktu Kegiatan:</strong> {{ $pengajuan->waktu_kegiatan }}</p>
    <p><strong>Jenis Pengajuan:</strong> {{ ucfirst($pengajuan->jenis_pengajuan) }}</p>

    {{-- Form Proses Keuangan --}}
    <form action="{{ route('keuangan.storeProses', $pengajuan->id) }}" method="POST">
    @csrf

    {{-- Input Kode Akun --}}
    <div style="margin-bottom:15px;">
        <label><strong>Kode Akun:</strong></label>
        <input type="text" name="kode_akun" value="{{ $pengajuan->kode_akun ?? '' }}" style="width:200px; padding:0.5rem;">
    </div>

    @foreach($pengajuan->items as $index => $item)
    <div style="border:1px solid #ccc; padding:15px; margin-bottom:15px; border-radius:5px;">
        <h4>Item #{{ $index + 1 }}</h4>

        <label>Nama / Nomor Invoice</label>
        <input type="text" name="invoice[{{ $index }}]" value="{{ $item->invoice ?? '' }}" style="width:100%; padding:0.5rem; margin-bottom:0.5rem;">

        <label>Detail Akun / Nama Barang</label>
        <input type="text" name="detail_akun[{{ $index }}]" value="{{ $item->nama_barang ?? $item->nama }}" style="width:100%; padding:0.5rem; margin-bottom:0.5rem;">

        <label>Uraian</label>
        <input type="text" name="uraian[{{ $index }}]" value="{{ $item->uraian ?? '' }}" style="width:100%; padding:0.5rem; margin-bottom:0.5rem;">

        <div style="display:flex; align-items:center; gap:10px; margin-bottom:0.5rem;">
            <div style="display:flex; flex-direction:column;">
                <label>Jumlah Pengajuan</label>
                <input type="number" name="jumlah_pengajuan[{{ $index }}]" value="{{ $item->jumlah_dana_pengajuan ?? 0 }}" class="jumlah" data-index="{{ $index }}" style="width:220px; padding:0.5rem;">
            </div>

            <div style="display:flex; flex-direction:column; justify-content:flex-end;">
                <label style="margin-bottom:0;">PPH 21 (%)</label>
                <select name="pph21[{{ $index }}]" class="pph21" data-index="{{ $index }}" style="width:80px; padding:0.3rem;">
                    <option value="0" {{ ($item->pph21 ?? 0) == 0 ? 'selected' : '' }}>0%</option>
                    <option value="0.05" {{ ($item->pph21 ?? 0) == 0.05 ? 'selected' : '' }}>5%</option>
                    <option value="0.15" {{ ($item->pph21 ?? 0) == 0.15 ? 'selected' : '' }}>15%</option>
                </select>
            </div>
        </div>

        <div style="display:flex; flex-wrap:wrap; gap:10px; margin-bottom:0.5rem;">
            <div style="flex:1;">
                <label>PPH 21 (Rp)</label>
                <input type="text" name="hasil_pph21[{{ $index }}]" class="hasil-pph21" data-index="{{ $index }}" readonly style="width:100%; padding:0.5rem;">
            </div>
            <div style="flex:1;">
                <label>PPH 22 (1,5%)</label>
                <input type="text" name="hasil_pph22[{{ $index }}]" class="hasil-pph22" data-index="{{ $index }}" readonly style="width:100%; padding:0.5rem;">
            </div>
            <div style="flex:1;">
                <label>PPH 23 (2%)</label>
                <input type="text" name="hasil_pph23[{{ $index }}]" class="hasil-pph23" data-index="{{ $index }}" readonly style="width:100%; padding:0.5rem;">
            </div>
            <div style="flex:1;">
                <label>PPN (19%)</label>
                <input type="text" name="hasil_ppn[{{ $index }}]" class="hasil-ppn" data-index="{{ $index }}" readonly style="width:100%; padding:0.5rem;">
            </div>
            <div style="flex:1;">
                <label>Dibayarkan</label>
                <input type="text" name="dibayarkan[{{ $index }}]" class="dibayarkan" data-index="{{ $index }}" readonly style="width:100%; padding:0.5rem;">
            </div>
        </div>

        <div style="display:flex; flex-wrap:wrap; gap:10px;">
            <div style="flex:1;">
                <label>No Rekening</label>
                <input type="text" name="no_rekening[{{ $index }}]" value="{{ $item->no_rekening ?? '' }}" style="width:100%; padding:0.5rem;">
            </div>
            <div style="flex:1;">
                <label>Bank</label>
                <input type="text" name="bank[{{ $index }}]" value="{{ $item->bank ?? '' }}" style="width:100%; padding:0.5rem;">
            </div>
        </div>
    </div>
    @endforeach

    {{-- Total Pajak & Total Dibayarkan --}}
    <div style="border:1px solid #000; padding:15px; border-radius:5px; margin-bottom:15px; width:350px;">
        <p><strong>Total Pajak: </strong> <span id="totalPajak">0</span></p>
        <p><strong>Total Dibayarkan: </strong> <span id="totalDiterima">0</span></p>
    </div>

    <button type="button" id="btnSimpan" style="padding:0.5rem 1rem; background-color: #28a745; color: white; border: none; border-radius:5px; cursor:pointer;">
    Simpan
    </button>

    <script>
    document.getElementById('btnSimpan').addEventListener('click', function() {
        if(confirm("Apakah kamu yakin ingin menyimpan data ini?")) {
            this.closest('form').submit();
        }
    });
    </script>
</form>

<script>
function updateTotals() {
    const itemCount = {{ $pengajuan->items->count() }};
    let totalPajak = 0;
    let totalDiterima = 0;

    for(let i=0;i<itemCount;i++){
        const jumlah = parseFloat(document.querySelector(`[name="jumlah_pengajuan[${i}]"]`).value) || 0;
        const pph21 = parseFloat(document.querySelector(`[name="pph21[${i}]"]`).value) || 0;

        const pph22 = 0.015;
        const pph23 = 0.02;
        const ppn = 0.19;

        const hasilPPH21 = jumlah * pph21;
        const hasilPPH22 = jumlah * pph22;
        const hasilPPH23 = jumlah * pph23;
        const hasilPPN = jumlah * ppn;

        const totalPajakItem = hasilPPH21 + hasilPPH22 + hasilPPH23 + hasilPPN;
        const dibayarkan = jumlah - totalPajakItem;

        totalPajak += totalPajakItem;
        totalDiterima += dibayarkan;

        document.querySelector(`.hasil-pph21[data-index="${i}"]`).value = hasilPPH21.toLocaleString('id-ID', {minimumFractionDigits:2});
        document.querySelector(`.hasil-pph22[data-index="${i}"]`).value = hasilPPH22.toLocaleString('id-ID', {minimumFractionDigits:2});
        document.querySelector(`.hasil-pph23[data-index="${i}"]`).value = hasilPPH23.toLocaleString('id-ID', {minimumFractionDigits:2});
        document.querySelector(`.hasil-ppn[data-index="${i}"]`).value = hasilPPN.toLocaleString('id-ID', {minimumFractionDigits:2});
        document.querySelector(`.dibayarkan[data-index="${i}"]`).value = dibayarkan.toLocaleString('id-ID', {minimumFractionDigits:2});
    }

    document.getElementById('totalPajak').innerText = totalPajak.toLocaleString('id-ID', {minimumFractionDigits:2});
    document.getElementById('totalDiterima').innerText = totalDiterima.toLocaleString('id-ID', {minimumFractionDigits:2});
}

// Listener input
document.querySelectorAll('.jumlah, .pph21').forEach(input=>{
    input.addEventListener('input', updateTotals);
});

// Hitung awal
updateTotals();
</script>

</div>
@endsection
