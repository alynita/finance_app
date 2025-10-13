@extends('layouts.app')

@section('title', 'Proses Honorarium')
@section('header', 'Proses Honorarium')

@section('content')
<div style="max-width:1000px; margin:auto;">

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

    {{-- Informasi pengajuan --}}
    <h2>Pengajuan: {{ $pengajuan->nama_kegiatan }}</h2>
    <p><strong>Waktu Kegiatan:</strong> {{ $pengajuan->waktu_kegiatan }}</p>
    <p><strong>Jenis Pengajuan:</strong> {{ ucfirst($pengajuan->jenis_pengajuan) }}</p>

    <form action="{{ route('keuangan.storeProses', $pengajuan->id) }}" method="POST">
        @csrf
        <table border="1" cellpadding="10" cellspacing="0" style="width:100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Jabatan</th>
                    <th>Uraian</th>
                    <th>Jumlah Honor (Bulan × Honor)</th>
                    <th>Total Honor</th>
                    <th>PPh 21 (15%)</th>
                    <th>Jumlah Akhir</th>
                    <th>No Rekening</th>
                    <th>Bank</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td><input type="text" name="nama" value="{{ $pengajuan->user->name }}" required></td>
                    <td><input type="text" name="jabatan" value="{{ $pengajuan->jabatan ?? '' }}" required></td>
                    <td>
                        Honorarium
                        <div style="margin-top:5px;">
                            <small>
                                Bulan × Jumlah Honor
                            </small>
                        </div>
                    </td>
                    <td><input type="number" name="jumlah_honor" value="0" required></td>
                    <td><input type="number" name="bulan" value="1" required></td>
                    <td>-- otomatis --</td>
                    <td>-- otomatis --</td>
                    <td><input type="text" name="no_rekening" value="" required></td>
                    <td><input type="text" name="bank" value="" required></td>
                </tr>
            </tbody>
        </table>

        {{-- Kotak total pajak & diterima --}}
        <div style="margin-top:1rem; padding:10px; border:1px solid #ccc; border-radius:5px; width:300px;">
            <p><strong>Total Pajak:</strong> -- otomatis --</p>
            <p><strong>Diterima:</strong> -- otomatis --</p>
        </div>

        {{-- Tombol simpan --}}
        <button type="submit" style="margin-top:1rem; padding:0.5rem 1rem; background:#4CAF50; color:white; border:none; border-radius:5px;">
            Simpan
        </button>
    </form>
</div>
@endsection
