@extends('layouts.app')

@section('title', 'Proses Honorarium')
@section('header', 'Proses Honorarium')

@section('content')
<div style="max-width:600px; margin:auto;">

    {{-- Notifikasi sukses atau error --}}
    @if(session('success'))
        <div style="background-color:#d4edda; color:#155724; padding:10px; margin-bottom:10px; border-radius:5px;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background-color:#f8d7da; color:#721c24; padding:10px; margin-bottom:10px; border-radius:5px;">
            {{ session('error') }}
        </div>
    @endif

    {{-- Informasi pengajuan --}}
    <h2 style="margin-bottom:10px;">{{ $pengajuan->nama_kegiatan }}</h2>
    <p><strong>Waktu Kegiatan:</strong> {{ $pengajuan->waktu_kegiatan }}</p>
    <p><strong>Jenis Pengajuan:</strong> {{ ucfirst($pengajuan->jenis_pengajuan) }}</p>

    {{-- Input No Akun (di luar form honorarium) --}}
    <div style="margin: 20px 0;">
        <label for="no_akun"><strong>No Akun:</strong></label>
        <input type="text" id="no_akun" name="no_akun"
            style="padding: 8px; width: 200px; margin-left: 10px;"
            placeholder="Masukkan No Akun" required>
    </div>

    {{-- Form Proses Honorarium --}}
    <form action="{{ route('keuangan.storeProses', $pengajuan->id) }}" method="POST">
        @csrf

        @foreach($pengajuan->items as $index => $item)
        <div style="border:1px solid #ccc; padding:15px; border-radius:8px; margin-bottom:1rem;">
            <h4>Honorarium #{{ $index + 1 }}</h4>

            <div style="margin-bottom:10px;">
                <label><strong>Nama</strong></label>
                <input type="text" name="items[{{ $index }}][nama]" 
                    value="{{ $item->nama }}" readonly
                    style="width:100%; padding:8px; border:1px solid #ccc; border-radius:5px; background:#f8f9fa;">
            </div>

            <div style="margin-bottom:10px;">
                <label><strong>Jabatan</strong></label>
                <input type="text" name="items[{{ $index }}][jabatan]" 
                    value="{{ $item->jabatan }}" readonly
                    style="width:100%; padding:8px; border:1px solid #ccc; border-radius:5px; background:#f8f9fa;">
            </div>

            <div style="margin-bottom:15px;">
                <label><strong>Uraian</strong></label>
                <input type="text" name="items[{{ $index }}][uraian]" placeholder="Masukkan uraian honorarium..." required
                    style="width:100%; padding:8px; border:1px solid #ccc; border-radius:5px;">
            </div>

            <div style="margin-bottom:15px;">
                <label><strong>Jumlah Honor</strong></label>
                <input type="number" class="jumlah_honor" name="items[{{ $index }}][jumlah_honor]" value="0" required 
                    style="width:100%; padding:8px; border:1px solid #ccc; border-radius:5px;">
            </div>

            <div style="margin-bottom:15px;">
                <label><strong>Bulan</strong></label>
                <input type="number" class="bulan" name="items[{{ $index }}][bulan]" value="1" required 
                    style="width:100%; padding:8px; border:1px solid #ccc; border-radius:5px;">
            </div>

            <div style="margin-bottom:15px;">
                <label><strong>PPh 21 (15%)</strong></label>
                <input type="text" class="pph" name="items[{{ $index }}][pph]" readonly 
                    style="width:100%; padding:8px; border:1px solid #ccc; border-radius:5px; background:#f8f9fa;">
            </div>

            <div style="margin-bottom:15px;">
                <label><strong>Jumlah Akhir</strong></label>
                <input type="text" class="jumlah_akhir" name="items[{{ $index }}][jumlah_akhir]" readonly 
                    style="width:100%; padding:8px; border:1px solid #ccc; border-radius:5px; background:#f8f9fa;">
            </div>

            <div style="margin-bottom:15px;">
                <label><strong>No Rekening</strong></label>
                <input type="text" name="items[{{ $index }}][no_rekening]" required 
                    style="width:100%; padding:8px; border:1px solid #ccc; border-radius:5px;">
            </div>

            <div style="margin-bottom:20px;">
                <label><strong>Bank</strong></label>
                <input type="text" name="items[{{ $index }}][bank]" required 
                    style="width:100%; padding:8px; border:1px solid #ccc; border-radius:5px;">
            </div>
        </div>
        @endforeach

        <button type="submit" 
            style="padding:10px 20px; background:#4CAF50; color:white; border:none; border-radius:5px;">
            Simpan
        </button>
    </form>
</div>

{{-- Script hitung otomatis --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.jumlah_honor, .bulan').forEach(function(input) {
        input.addEventListener('input', function() {
            const container = this.closest('div').parentNode;
            const honor = parseFloat(container.querySelector('.jumlah_honor').value) || 0;
            const bulan = parseFloat(container.querySelector('.bulan').value) || 0;
            const total = honor * bulan;
            const pph = total * 0.15;
            const akhir = total - pph;

            container.querySelector('.pph').value = pph.toFixed(2);
            container.querySelector('.jumlah_akhir').value = akhir.toFixed(2);
        });
    });
});
</script>
@endsection
