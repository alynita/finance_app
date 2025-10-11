@extends('layouts.app')

@section('title', 'Detail Pengajuan')
@section('header', 'Detail Pengajuan')

@section('content')
<div style="max-width:800px; margin:auto;">

    <p><strong>Nama Kegiatan:</strong> {{ $pengajuan->nama_kegiatan }}</p>
    <p><strong>Waktu Kegiatan:</strong> {{ $pengajuan->waktu_kegiatan }}</p>
    <p><strong>Jenis Pengajuan:</strong> {{ ucfirst($pengajuan->jenis_pengajuan) }}</p>

    <h4>Detail Item:</h4>
        <table style="width:100%; border-collapse: collapse;">
            <thead>
                <tr style="background:#f2f2f2;">
                    <th style="border:1px solid #ccc; padding:0.5rem;">Nama Barang</th>

                    @if($pengajuan->jenis_pengajuan !== 'honor')
                        <th style="border:1px solid #ccc; padding:0.5rem;">Volume</th>
                    @endif

                    @if($pengajuan->jenis_pengajuan === 'pembelian')
                        <th style="border:1px solid #ccc; padding:0.5rem;">KRO/Kode Akun</th>
                        <th style="border:1px solid #ccc; padding:0.5rem;">Harga Satuan</th>
                        <th style="border:1px solid #ccc; padding:0.5rem;">Volume</th>
                        <th style="border:1px solid #ccc; padding:0.5rem;">Jumlah Dana</th>
                        <th style="border:1px solid #ccc; padding:0.5rem;">Ongkos Kirim</th>
                    @elseif($pengajuan->jenis_pengajuan === 'kerusakan')
                        <th style="border:1px solid #ccc; padding:0.5rem;">Lokasi</th>
                        <th style="border:1px solid #ccc; padding:0.5rem;">Jenis Kerusakan</th>
                        <th style="border:1px solid #ccc; padding:0.5rem;">Volume</th>
                        <th style="border:1px solid #ccc; padding:0.5rem;">Harga Satuan</th>
                        <th style="border:1px solid #ccc; padding:0.5rem;">Jumlah Dana</th>
                        <th style="border:1px solid #ccc; padding:0.5rem;">Foto</th>
                    @elseif($pengajuan->jenis_pengajuan === 'honor')
                        <th style="border:1px solid #ccc; padding:0.5rem;">Keterangan</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($pengajuan->items as $item)
                    <tr>
                        <td style="border:1px solid #ccc; padding:0.5rem;">{{ $item->nama_barang }}</td>

                        @if($pengajuan->jenis_pengajuan !== 'honor')
                            <td style="border:1px solid #ccc; padding:0.5rem;">{{ $item->volume }}</td>
                        @endif

                        @if($pengajuan->jenis_pengajuan === 'pembelian')
                            <td style="border:1px solid #ccc; padding:0.5rem;">{{ number_format($item->kro) }}</td>
                            <td style="border:1px solid #ccc; padding:0.5rem;">{{ number_format($item->harga_satuan) }}</td>
                            <td style="border:1px solid #ccc; padding:0.5rem;">{{ number_format($item->volume) }}</td>
                            <td style="border:1px solid #ccc; padding:0.5rem;">{{ number_format($item->jumlah_dana_pengajuan) }}</td>
                            <td style="border:1px solid #ccc; padding:0.5rem;">{{ number_format($item->ongkos_kirim) }}</td>
                        @elseif($pengajuan->jenis_pengajuan === 'kerusakan')
                            <td style="border:1px solid #ccc; padding:0.5rem;">{{ $item->lokasi }}</td>
                            <td style="border:1px solid #ccc; padding:0.5rem;">{{ $item->jenis_kerusakan }}</td>
                            <td style="border:1px solid #ccc; padding:0.5rem;">{{ number_format($item->volume) }}</td>
                            <td style="border:1px solid #ccc; padding:0.5rem;">{{ number_format($item->harga_satuan) }}</td>
                            <td style="border:1px solid #ccc; padding:0.5rem;">{{ number_format($item->jumlah_dana) }}</td>
                            <td style="border:1px solid #ccc; padding:0.5rem;">
                                @if($item->foto)
                                    <a href="{{ asset('storage/' . $item->foto) }}" target="_blank">Lihat Foto</a>
                                @else
                                    -
                                @endif
                            </td>
                        @elseif($pengajuan->jenis_pengajuan === 'honor')
                            <td style="border:1px solid #ccc; padding:0.5rem;">
                                Tanggal: {{ $item->tanggal }}, Nama: {{ $item->nama }}, Jabatan: {{ $item->jabatan }}
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>

    <!-- Tanda Tangan -->
    <div style="display:flex; justify-content:space-between; margin-top:100px;">
        <!-- Subbagian Administrasi Umum -->
        <div style="flex:1; text-align:center; display:flex; flex-direction:column; align-items:center;">
            <div>Mengetahui</div>
            <div>Subbagian Administrasi Umum</div>
            <div style="margin-top:60px;">
                {{ $pengajuan->adum_name ?? 'Nama ADUM' }}<br>
                NIP. {{ $pengajuan->adum_nip ?? 'NIP ADUM' }}
            </div>
        </div>

        <!-- Pejabat Pembuat Komitmen -->
        <div style="flex:1; text-align:center; display:flex; flex-direction:column; align-items:center;">
            <div>Menyetujui</div>
            <div>Pejabat Pembuat Komitmen</div>
            <div style="margin-top:120px;">
                {{ $pengajuan->ppk_name ?? 'Nama PPK' }}<br>
                NIP. {{ $pengajuan->ppk_nip ?? 'NIP PPK' }}
            </div>
        </div>

        <!-- Penanggung Jawab -->
        <div style="flex:1; text-align:center; display:flex; flex-direction:column; align-items:center;">
            <div>Penanggung Jawab</div>
            <div style="margin-top:60px;">
                {{ $pengajuan->penanggungJawab->name ?? 'Nama PJ' }}<br>
                NIP. {{ $pengajuan->penanggungJawab->nip ?? 'NIP PJ' }}
            </div>
        </div>
    </div>

    <a href="{{ route('pegawai.daftar-pengajuan') }}" 
        style="display:inline-block; margin-top:1rem; padding:0.5rem 1rem; background:#6c757d; color:white; border-radius:4px; text-decoration:none;">
        Kembali
    </a>
</div>
@endsection
