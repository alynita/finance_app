@extends('layouts.app')

@section('title', 'Detail Pengajuan')
@section('header', 'Detail Pengajuan')

@section('content')
<div style="max-width:800px; margin:auto;">

    <table style="width:100%; border-collapse:collapse; margin-bottom:10px;">
        <tr>
            <td style="width:200px;"><strong>Nama Kegiatan</strong></td>
            <td style="width:10px;">:</td>
            <td>{{ $pengajuan->nama_kegiatan }}</td>
        </tr>
        <tr>
            <td><strong>Waktu Kegiatan</strong></td>
            <td>:</td>
            <td>{{ $pengajuan->waktu_kegiatan }}</td>
        </tr>
        <tr>
            <td><strong>Jenis Pengajuan</strong></td>
            <td>:</td>
            <td>{{ ucfirst($pengajuan->jenis_pengajuan) }}</td>
        </tr>
        <tr>
    </table>

    <h4>Detail Item:</h4>
    <table style="width:100%; border-collapse: collapse;">
        <thead>
            <tr style="background:#f2f2f2;">

                @if($pengajuan->jenis_pengajuan === 'pembelian')
                    <th style="border:1px solid #ccc; padding:0.5rem;">Nama Barang</th>
                    <th style="border:1px solid #ccc; padding:0.5rem;">Volume</th>
                    <th style="border:1px solid #ccc; padding:0.5rem;">KRO/Kode Akun</th>
                    <th style="border:1px solid #ccc; padding:0.5rem;">Harga Satuan</th>
                    <th style="border:1px solid #ccc; padding:0.5rem;">Jumlah Dana</th>
                    <th style="border:1px solid #ccc; padding:0.5rem;">Ongkos Kirim</th>
                @elseif($pengajuan->jenis_pengajuan === 'kerusakan')
                    <th style="border:1px solid #ccc; padding:0.5rem;">Nama Barang</th>
                    <th style="border:1px solid #ccc; padding:0.5rem;">Volume</th>
                    <th style="border:1px solid #ccc; padding:0.5rem;">Lokasi</th>
                    <th style="border:1px solid #ccc; padding:0.5rem;">Jenis Kerusakan</th>
                    <th style="border:1px solid #ccc; padding:0.5rem;">Harga Satuan</th>
                    <th style="border:1px solid #ccc; padding:0.5rem;">Jumlah Dana</th>
                    <th style="border:1px solid #ccc; padding:0.5rem;">Foto</th>
                @elseif($pengajuan->jenis_pengajuan === 'honor')
                    <th style="border:1px solid #ccc; padding:0.5rem;">Tanggal</th>
                    <th style="border:1px solid #ccc; padding:0.5rem;">Nama</th>
                    <th style="border:1px solid #ccc; padding:0.5rem;">Jabatan</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($pengajuan->items as $item)
                <tr>
                    @if($pengajuan->jenis_pengajuan === 'pembelian')
                        <td style="border:1px solid #ccc; padding:0.5rem;">{{ $item->nama_barang ?? '-' }}</td>
                        <td style="border:1px solid #ccc; padding:0.5rem;">{{ number_format((float)($item->volume ?? 0)) }}</td>
                        <td style="border:1px solid #ccc; padding:0.5rem;">{{ $item->kro ?? '-' }}</td>
                        <td style="border:1px solid #ccc; padding:0.5rem;">{{ number_format((float)($item->harga_satuan ?? 0)) }}</td>
                        <td style="border:1px solid #ccc; padding:0.5rem;">{{ number_format((float)($item->jumlah_dana_pengajuan ?? 0)) }}</td>
                        <td style="border:1px solid #ccc; padding:0.5rem;">{{ number_format((float)($item->ongkos_kirim ?? 0)) }}</td>
                    @elseif($pengajuan->jenis_pengajuan === 'kerusakan')
                        <td style="border:1px solid #ccc; padding:0.5rem;">{{ $item->nama_barang ?? '-' }}</td>
                        <td style="border:1px solid #ccc; padding:0.5rem;">{{ number_format((float)($item->volume ?? 0)) }}</td>
                        <td style="border:1px solid #ccc; padding:0.5rem;">{{ $item->lokasi ?? '-' }}</td>
                        <td style="border:1px solid #ccc; padding:0.5rem;">{{ $item->jenis_kerusakan ?? '-' }}</td>
                        <td style="border:1px solid #ccc; padding:0.5rem;">{{ number_format((float)($item->harga_satuan ?? 0)) }}</td>
                        <td style="border:1px solid #ccc; padding:0.5rem;">{{ number_format((float)($item->jumlah_dana_pengajuan ?? 0)) }}</td>
                        <td style="border:1px solid #ccc; padding:0.5rem;">
                            @if($item->foto)
                                <a href="{{ asset('storage/' . $item->foto) }}" target="_blank">Lihat Foto</a>
                            @else
                                -
                            @endif
                        </td>
                    @elseif($pengajuan->jenis_pengajuan === 'honor')
                        <td style="border:1px solid #ccc; padding:0.5rem;">{{ $item->tanggal ?? '-' }}</td>
                        <td style="border:1px solid #ccc; padding:0.5rem;">{{ $item->nama ?? '-' }}</td>
                        <td style="border:1px solid #ccc; padding:0.5rem;">{{ $item->jabatan ?? '-' }}</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>


    <div style="display:flex; justify-content:space-between; margin-top:100px;">

    <!-- ADUM -->
    <div style="flex:1; text-align:center; display:flex; flex-direction:column; align-items:center;">
        <div>MENGETAHUI</div>
        <div>Subbagian Administrasi Umum</div>
        <div style="margin-top:60px;">
            @if($pengajuan->adum_id)
                <div style="opacity:0.5; font-weight:bold;">APPROVED</div>
                {{ $pengajuan->adum->name ?? 'Nama ADUM' }}<br>
                NIP. {{ $pengajuan->adum->nip ?? '-' }}
            @else
                Tanda tangan menunggu approve
            @endif
        </div>
    </div>

    <!-- PPK -->
    <div style="flex:1; text-align:center; display:flex; flex-direction:column; align-items:center;">
        <div>MENYETUJUI</div>
        <div>Pejabat Pembuat Komitmen</div>
        <div style="margin-top:60px;">
            @if($pengajuan->ppk_id)
                <div style="opacity:0.5; font-weight:bold;">APPROVED</div>
                {{ $pengajuan->ppk->name ?? 'Nama PPK' }}<br>
                NIP. {{ $pengajuan->ppk->nip ?? '-' }}
            @else
                Tanda tangan menunggu approve
            @endif
        </div>
    </div>

    <!-- PJ -->
    <div style="flex:1; text-align:center; display:flex; flex-direction:column; align-items:center;">
        <div>PENANGGUNG JAWAB</div>
        <div style="margin-top:60px;">
            {{ $pengajuan->user->name ?? 'Nama Penanggung Jawab' }}<br>
            NIP. {{ $pengajuan->user->nip ?? '-' }}
        </div>
    </div>

</div>


    <a href="{{ route('pegawai.daftar-pengajuan') }}" 
        style="display:inline-block; margin-top:1rem; padding:0.5rem 1rem; background:#6c757d; color:white; border-radius:4px; text-decoration:none;">
        Kembali
    </a>
</div>
@endsection
