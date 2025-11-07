@extends('layouts.app')

@section('title', 'Dashboard Bendahara')
@section('header', 'Daftar Laporan Keuangan')

@section('content')
<div style="max-width:1000px; margin:auto;">
    <h3 style="margin-bottom:20px;">Laporan yang Sudah Diproses</h3>

    <table border="1" cellpadding="10" cellspacing="0" style="width:100%; border-collapse:collapse;">
        <thead style="background:#f2f2f2;">
            <tr>
                <th>No</th>
                <th>Nama Kegiatan</th>
                <th>Jumlah Item</th>
                <th>Pengaju</th>
                <th>Status</th>
                <th>Aksi Detail</th>
                <th>Aksi Arsip</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($laporans as $i => $group)
                @php
                    // ambil pengajuan pertama untuk nama kegiatan & waktu
                    $firstPengajuan = $group->pengajuan->first();
                    $statusColors = [
                        'processed' => 'orange',
                        'adum_approved' => 'blue',
                        'ppk_approved' => 'purple',
                        'approved' => 'green',
                        'completed' => 'gray',
                    ];
                @endphp

                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $firstPengajuan->nama_kegiatan ?? '-' }}</td>
                    <td>{{ $group->items->count() }} item</td>
                    <td>{{ $firstPengajuan->user->name ?? '-' }}</td>
                    <td style="font-weight:bold; color:{{ $statusColors[$group->status] ?? 'black' }};">
                        {{ ucfirst(str_replace('_', ' ', $group->status)) }}
                    </td>

                    <!-- Kolom Aksi Detail -->
                    <td>
                        <a href="{{ route('bendahara.laporan.show', $group->id) }}" 
                            style="padding:5px 10px; background-color:#28a745; color:white; border-radius:3px; text-decoration:none;">
                            Lihat Detail
                        </a>
                    </td>

                    <!-- Kolom Aksi Arsip -->
                    <td>
                        @if($group->adum_approved_process && $group->ppk_approved_process && $group->verifikator_approved_process && !$group->arsip)
                            <form action="{{ route('bendahara.simpan-arsip', $group->id) }}" method="POST">
                                @csrf
                                <button type="submit" onclick="return confirm('Yakin ingin mengarsipkan laporan ini?')" 
                                        style="padding:5px 10px; background-color:#007bff; color:white; border:none; border-radius:3px;">
                                    Simpan Arsip
                                </button>
                            </form>
                        @elseif($group->arsip)
                            <span style="color:green; font-weight:bold;">Sudah diarsipkan</span>
                        @else
                            <span style="color:red;">Menunggu semua approval</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align:center;">Belum ada laporan yang diproses.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
