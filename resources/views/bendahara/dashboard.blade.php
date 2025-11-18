@extends('layouts.app')

@section('title', 'Dashboard Bendahara')
@section('header', 'Dashboard Bendahara')

@section('content')
<div style="max-width:1200px; margin:auto;">

    <!-- Ucapan Selamat Datang -->
    <div style="background:#eaf3ea; padding:1.5rem; border-radius:10px; margin-bottom:20px; border-left:6px solid #2e7d32;">
        <h2 style="margin:0; color:#1b5e20;">Selamat Datang, Bendahara 👋</h2>
        <p style="margin:5px 0 0 0; color:#333;">
            Kelola arsip dan pantau status laporan keuangan dengan mudah dan efisien.
        </p>
    </div>

    <!-- Card Ringkasan -->
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(250px, 1fr)); gap:20px; margin-bottom:30px;">

        <!-- Card Pengadaan -->
        <div style="background:#fff; color:#333; padding:1.5rem; border-radius:10px; 
                    box-shadow:0 2px 6px rgba(0,0,0,0.1); border:1px solid #e0e0e0;">
            <h4 style="margin-bottom:8px;">Pengadaan & Pembelian Barang</h4>
            <p style="font-size:24px; font-weight:bold; margin:0;">{{ $totalPengadaan ?? 0 }}</p>
            <p style="margin:5px 0 0 0; color:#777;">Laporan pengadaan yang telah diproses</p>
        </div>

        <!-- Card Honor -->
        <div style="background:#fff; color:#333; padding:1.5rem; border-radius:10px; 
                    box-shadow:0 2px 6px rgba(0,0,0,0.1); border:1px solid #e0e0e0;">
            <h4 style="margin-bottom:8px;">Laporan Honor</h4>
            <p style="font-size:24px; font-weight:bold; margin:0;">{{ $totalHonor ?? 0 }}</p>
            <p style="margin:5px 0 0 0; color:#777;">Pengajuan honor yang masuk</p>
        </div>

        <!-- Card Arsip -->
        <div style="background:#fff; color:#333; padding:1.5rem; border-radius:10px; 
                    box-shadow:0 2px 6px rgba(0,0,0,0.1); border:1px solid #e0e0e0;">
            <h4 style="margin-bottom:8px;">Total Arsip</h4>
            <p style="font-size:24px; font-weight:bold; margin:0;">{{ $totalArsip ?? 0 }}</p>
            <p style="margin:5px 0 0 0; color:#777;">Laporan yang telah diarsipkan</p>
        </div>

        <!-- Card Menunggu Arsip -->
        <div style="background:#fff; color:#333; padding:1.5rem; border-radius:10px; 
                    box-shadow:0 2px 6px rgba(0,0,0,0.1); border:1px solid #e0e0e0;">
            <h4 style="margin-bottom:8px;">Menunggu Arsip</h4>
            <p style="font-size:24px; font-weight:bold; margin:0;">{{ $menungguArsip ?? 0 }}</p>
            <p style="margin:5px 0 0 0; color:#777;">Laporan yang belum diarsipkan</p>
        </div>

    </div>

    <!-- Tabel Arsip Terkini -->
    <div style="background:#fff; padding:1.5rem; border-radius:10px; box-shadow:0 2px 8px rgba(0,0,0,0.05);">
        <h3 style="margin-bottom:15px; border-bottom:2px solid #ccc; padding-bottom:8px;">Arsip Terbaru</h3>

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

                    {{-- NAMA KEGIATAN --}}
                    <td>
                        @if($group instanceof \App\Models\PpkGroup)
                            {{ $group->pengajuan?->first()?->nama_kegiatan ?? '-' }}
                        @else
                            {{ $group->nama_kegiatan }}
                        @endif
                    </td>

                    {{-- JUMLAH ITEM --}}
                    <td>
                        @if($group instanceof \App\Models\PpkGroup)
                            {{ $group->items ? $group->items->count() : 0 }} item
                        @else
                            {{ $group->details->count() }} item
                        @endif
                    </td>

                    {{-- PENGAJU --}}
                    <td>
                        @if($group instanceof \App\Models\PpkGroup)
                            {{ $group->pengajuan?->first()?->user?->name ?? '-' }}
                        @else
                            {{ $group->user?->name ?? '-' }}
                        @endif
                    </td>

                    {{-- STATUS --}}
                    <td style="font-weight:bold; color:{{ $statusColors[$group->status] ?? 'black' }};">
                        {{ ucfirst(str_replace('_', ' ', $group->status)) }}
                    </td>

                    {{-- DETAIL --}}
                    <td>
                        @if($group instanceof \App\Models\PpkGroup)
                            <a href="{{ route('bendahara.laporan.show', $group->id) }}" 
                                style="padding:5px 10px; background-color:#28a745; color:white; border-radius:3px; text-decoration:none;">
                                Lihat Detail
                            </a>
                        @else
                            <a href="{{ route('bendahara.honor.show', $group->id) }}" 
                                style="padding:5px 10px; background-color:#17a2b8; color:white; border-radius:3px; text-decoration:none;">
                                Lihat Detail
                            </a>
                        @endif
                    </td>

                    {{-- ARSIP --}}
                    <td>
                        @if ($group instanceof \App\Models\PpkGroup)

                            @if($group->adum_approved_process && $group->ppk_approved_process && $group->verifikator_approved_process && !$group->arsip)
                                <form action="{{ route('bendahara.arsip.pengadaan', $group->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" style="padding:5px 10px; background:#007bff; color:white; border:none; border-radius:3px;">
                                        Simpan Arsip
                                    </button>
                                </form>
                            @elseif($group->arsip)
                                <span style="color:green; font-weight:bold;">Sudah diarsipkan</span>
                            @else
                                <span style="color:red;">Menunggu Approval</span>
                            @endif

                        @else
                            {{-- HONOR --}}
                            @if($group->adum_approved_at && $group->ppk_approved_at && !$group->arsip)
                                <form action="{{ route('bendahara.arsip.honor', $group->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" style="padding:5px 10px; background:#007bff; color:white; border:none; border-radius:3px;">
                                        Simpan Arsip
                                    </button>
                                </form>
                            @elseif($group->arsip)
                                <span style="color:green; font-weight:bold;">Sudah diarsipkan</span>
                            @else
                                <span style="color:red;">Menunggu Approval</span>
                            @endif

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

</div>
@endsection
