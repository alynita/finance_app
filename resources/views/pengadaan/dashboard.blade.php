@extends('layouts.app')

@section('title','Dashboard Pengadaan')
@section('header','Dashboard Pengadaan')

@section('content')
<h2>Daftar Pengajuan</h2>

@foreach($pengajuans as $p)
    @php
        // Tentukan warna status
        $statusColor = 'gray';
        switch(strtolower($p->status)) {
            case 'pending':
            case 'pending_pj':
            case 'pending_adum':
            case 'pending_ppk':
                $statusColor = '#facc15'; // kuning
                break;
            case 'approved':
                $statusColor = '#4ade80'; // hijau
                break;
            case 'rejected':
            case 'rejected_adum':
            case 'rejected_ppk':
                $statusColor = '#f87171'; // merah
                break;
        }
    @endphp

    <h3>{{ $p->nama_kegiatan }} ({{ ucfirst($p->jenis_pengajuan) }}) - 
        <span style="background: {{ $statusColor }}; color: #000; padding:0.2rem 0.5rem; border-radius:4px; font-weight:bold;">
            {{ ucfirst($p->status) }}
        </span>
    </h3>
    <p>Pengaju: {{ $p->user->name }} | Tanggal: {{ $p->created_at->format('d-m-Y') }}</p>

    <table border="1" cellpadding="8" cellspacing="0" style="border-collapse:collapse; width:100%; margin-bottom:1rem;">
        <thead>
            <tr style="background:#f2f2f2;">
                <th>Nama Barang</th>
                @if($p->jenis_pengajuan !== 'honor')
                    <th>Volume</th>
                @endif

                @if($p->jenis_pengajuan === 'pembelian')
                    <th>KRO/Kode Akun</th>
                    <th>Harga Satuan</th>
                    <th>Jumlah Dana</th>
                    <th>Ongkos Kirim</th>
                @elseif($p->jenis_pengajuan === 'kerusakan')
                    <th>Lokasi</th>
                    <th>Jenis Kerusakan</th>
                    <th>Harga Satuan</th>
                    <th>Jumlah Dana</th>
                    <th>Foto</th>
                @elseif($p->jenis_pengajuan === 'honor')
                    <th>Keterangan</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($p->items as $item)
                <tr>
                    <td>{{ $item->nama_barang ?? $item->nama ?? '-' }}</td>
                    @if($p->jenis_pengajuan !== 'honor')
                        <td>{{ $item->volume ?? '-' }}</td>
                    @endif

                    @if($p->jenis_pengajuan === 'pembelian')
                        <td>{{ $item->kro ?? '-' }}</td>
                        <td>{{ $item->harga_satuan ?? '-' }}</td>
                        <td>{{ $item->jumlah_dana_pengajuan ?? '-' }}</td>
                        <td>{{ $item->ongkos_kirim ?? '-' }}</td>
                    @elseif($p->jenis_pengajuan === 'kerusakan')
                        <td>{{ $item->lokasi ?? '-' }}</td>
                        <td>{{ $item->jenis_kerusakan ?? '-' }}</td>
                        <td>{{ $item->harga_satuan ?? '-' }}</td>
                        <td>{{ $item->jumlah_dana_pengajuan ?? '-' }}</td>
                        <td>
                            @if($item->foto)
                                <a href="{{ asset('storage/' . $item->foto) }}" target="_blank">Lihat</a>
                            @else
                                -
                            @endif
                        </td>
                    @elseif($p->jenis_pengajuan === 'honor')
                        <td>{{ $item->jabatan ?? $item->nama ?? '-' }}</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>

    @if($p->status === 'approved')
    <form method="POST" action="{{ route('pengadaan.arsip', $p->id) }}">
        @csrf
        <button type="submit" style="
            background-color: #4CAF50; 
            color: white; 
            padding: 0.5rem 1rem; 
            border: none; 
            border-radius: 4px; 
            cursor: pointer;
            transition: background 0.2s;
        " onmouseover="this.style.backgroundColor='#45a049'" onmouseout="this.style.backgroundColor='#4CAF50'">
            Simpan Arsip
        </button>
    </form>
    @else
        <button style="
            background-color: #ccc; 
            color: #666; 
            padding: 0.5rem 1rem; 
            border: none; 
            border-radius: 4px; 
            cursor: not-allowed;
        " disabled>
            Menunggu Approve
        </button>
    @endif

    </form>
    <hr>
@endforeach

@endsection
