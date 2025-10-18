@extends('layouts.app')

@section('title', 'Proses Honorarium')
@section('header', 'Proses Honorarium')

@section('content')
<div style="max-width:700px; margin:auto;">

    {{-- Notifikasi --}}
    @if(session('success'))
        <div style="background-color:#d4edda; color:#155724; padding:10px; margin-bottom:10px; border-radius:5px;">
            {{ session('success') }}
        </div>
    @endif

    <h2 style="margin-bottom:10px;">{{ $pengajuan->nama_kegiatan }}</h2>
    <p><strong>Waktu Kegiatan:</strong> {{ $pengajuan->waktu_kegiatan }}</p>
    <p><strong>Jenis Pengajuan:</strong> {{ ucfirst($pengajuan->jenis_pengajuan) }}</p>

    

    <form action="{{ route('keuangan.simpanHonorarium', $pengajuan->id) }}" method="POST">
        @csrf

        {{-- Input Kode Akun --}}
        <div style="margin: 20px 0;">
            <label for="kode_akun"><strong>Kode Akun:</strong></label>
            <input type="text" id="kode_akun" name="kode_akun"
                style="padding: 8px; width: 200px; margin-left: 10px; border:1px solid #ccc; border-radius:5px;"
                placeholder="Masukkan Kode Akun" value="{{ old('kode_akun', $pengajuan->kode_akun) }}">
        </div>

        @php
            $items = $pengajuan->items->count()
                ? $pengajuan->items
                : [ (object) [
                    'nama' => $pengajuan->nama_pengaju ?? '',
                    'jabatan' => $pengajuan->jabatan_pengaju ?? '',
                    'uraian' => '',
                    'jumlah_honor' => 0,
                    'bulan' => 1,
                    'no_rekening' => '',
                    'bank' => '',
                    'tanggal' => $pengajuan->tanggal ?? now()->format('Y-m-d')
                ] ];
        @endphp

        @foreach($items as $index => $item)
        <div style="border:1px solid #ccc; padding:15px; border-radius:8px; margin-bottom:1rem;">
            <h4>Honorarium #{{ $index + 1 }}</h4>

            {{-- Tanggal --}}
            <div style="margin-bottom:10px;">
                <label><strong>Tanggal</strong></label>
                <input type="date" name="items[{{ $index }}][tanggal]" value="{{ $item->tanggal ?? $pengajuan->tanggal }}" readonly
                    style="width:100%; padding:8px; border:1px solid #ccc; border-radius:5px; background:#f8f9fa;">
            </div>

            {{-- Nama --}}
            <div style="margin-bottom:10px;">
                <label><strong>Nama</strong></label>
                <input type="text" name="items[{{ $index }}][nama]" value="{{ $item->nama }}" readonly
                    style="width:100%; padding:8px; border:1px solid #ccc; border-radius:5px; background:#f8f9fa;">
            </div>

            {{-- Jabatan --}}
            <div style="margin-bottom:10px;">
                <label><strong>Jabatan</strong></label>
                <input type="text" name="items[{{ $index }}][jabatan]" value="{{ $item->jabatan }}" readonly
                    style="width:100%; padding:8px; border:1px solid #ccc; border-radius:5px; background:#f8f9fa;">
            </div>

            {{-- Uraian --}}
            <div style="margin-bottom:10px;">
                <label><strong>Uraian</strong></label>
                <input type="text" name="items[{{ $index }}][uraian]" value="{{ $item->uraian ?? '' }}" placeholder="Masukkan uraian..." required
                    style="width:100%; padding:8px; border:1px solid #ccc; border-radius:5px;">
            </div>

            {{-- Jumlah Honor --}}
            <div style="margin-bottom:10px;">
                <label><strong>Jumlah Honor</strong></label>
                <input type="number" class="jumlah_honor" name="items[{{ $index }}][jumlah_honor]" value="{{ $item->jumlah_honor ?? 0 }}" required
                    style="width:100%; padding:8px; border:1px solid #ccc; border-radius:5px;">
            </div>

            {{-- Bulan --}}
            <div style="margin-bottom:10px;">
                <label><strong>Bulan</strong></label>
                <input type="number" class="bulan" name="items[{{ $index }}][bulan]" value="{{ $item->bulan ?? 1 }}" required
                    style="width:100%; padding:8px; border:1px solid #ccc; border-radius:5px;">
            </div>

            {{-- PPh 21 --}}
            <div style="margin-bottom:10px;">
                <label><strong>PPh 21 (15%)</strong></label>
                <input type="text" class="pph" name="items[{{ $index }}][pph_21]" readonly
                    style="width:100%; padding:8px; border:1px solid #ccc; border-radius:5px; background:#f8f9fa;">
            </div>

            {{-- Jumlah Akhir --}}
            <div style="margin-bottom:10px;">
                <label><strong>Jumlah Akhir</strong></label>
                <input type="text" class="jumlah_akhir" name="items[{{ $index }}][jumlah]" readonly
                    style="width:100%; padding:8px; border:1px solid #ccc; border-radius:5px; background:#f8f9fa;">
            </div>

            {{-- No Rekening --}}
            <div style="margin-bottom:10px;">
                <label><strong>No Rekening</strong></label>
                <input type="text" name="items[{{ $index }}][no_rekening]" value="{{ $item->no_rekening ?? '' }}" required
                    style="width:100%; padding:8px; border:1px solid #ccc; border-radius:5px;">
            </div>

            {{-- Bank --}}
            <div style="margin-bottom:10px;">
                <label><strong>Bank</strong></label>
                <input type="text" name="items[{{ $index }}][bank]" value="{{ $item->bank ?? '' }}" required
                    style="width:100%; padding:8px; border:1px solid #ccc; border-radius:5px;">
            </div>
        </div>
        @endforeach

        <button type="submit" style="padding:10px 20px; background:#4CAF50; color:white; border:none; border-radius:5px;">
            Simpan
        </button>
    </form>
</div>

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
