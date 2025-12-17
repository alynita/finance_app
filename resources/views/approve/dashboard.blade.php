@extends('layouts.app')

@section('title', 'Dashboard ' . strtoupper($user->role))
@section('header', 'Dashboard ' . strtoupper($user->role))

@section('content')
<div style="max-width:1000px; margin:auto;">

    <!-- Ucapan Selamat Datang -->
    <div style="background:#eaf3ea; padding:1.5rem; border-radius:10px; margin-bottom:20px; border-left:6px solid #2e7d32;">
        <h2 style="margin:0; color:#1b5e20;">Selamat datang, {{ Auth::user()->name }}👋</h2>
        <p style="margin:5px 0 0 0; color:#333;">
            Proses Persetujuan Pengajuan dengan mudah dan efisien.
        </p>
    </div>

    {{-- CARD SUMMARY --}}
    @if($user->role === 'adum')
    {{-- Baris 1: Kategori Pengajuan --}}
    <div style="display:flex; gap:1rem; margin-top:1rem;">
        <div style="flex:1; background:white; padding:0.8rem 1rem; border-radius:6px; box-shadow:0 2px 5px rgba(0,0,0,0.1); text-align:center; height:90px;">
            <h4 style="margin:0; font-size:0.9rem;">Pengadaan & Kerusakan Barang</h4>
            <p style="font-size:1.4rem; font-weight:600; margin:0.3rem 0 0;">{{ $pendingPembelian }}</p>
        </div>
        <div style="flex:1; background:white; padding:0.8rem 1rem; border-radius:6px; box-shadow:0 2px 5px rgba(0,0,0,0.1); text-align:center; height:90px;">
            <h4 style="margin:0; font-size:0.9rem;">Proses Keuangan</h4>
            <p style="font-size:1.4rem; font-weight:600; margin:0.3rem 0 0;">{{ $pendingProsesKeuangan }}</p>
        </div>
        <div style="flex:1; background:white; padding:0.8rem 1rem; border-radius:6px; box-shadow:0 2px 5px rgba(0,0,0,0.1); text-align:center; height:90px;">
            <h4 style="margin:0; font-size:0.9rem;">Honor</h4>
            <p style="font-size:1.4rem; font-weight:600; margin:0.3rem 0 0;">{{ $pendingHonor }}</p>
        </div>
    </div>
    @endif

    {{-- Baris 2: Status --}}
    @if(str_starts_with($user->role, 'timker_') || $user->role === 'adum')
    <div style="display:flex; gap:1rem; margin-top:0.8rem;">
        <div style="flex:1; background:white; padding:0.8rem 1rem; border-radius:6px; box-shadow:0 2px 5px rgba(0,0,0,0.1); text-align:center; height:90px;">
            <h4 style="margin:0; font-size:0.9rem;">Total Pending</h4>
            <p style="font-size:1.4rem; font-weight:600; margin:0.3rem 0 0;">{{ $totalPending }}</p>
        </div>
        <div style="flex:1; background:white; padding:0.8rem 1rem; border-radius:6px; box-shadow:0 2px 5px rgba(0,0,0,0.1); text-align:center; height:90px;">
            <h4 style="margin:0; font-size:0.9rem;">Total Approved</h4>
            <p style="font-size:1.4rem; font-weight:600; margin:0.3rem 0 0;">{{ $totalApproved }}</p>
        </div>
        <div style="flex:1; background:white; padding:0.8rem 1rem; border-radius:6px; box-shadow:0 2px 5px rgba(0,0,0,0.1); text-align:center; height:90px;">
            <h4 style="margin:0; font-size:0.9rem;">Total Rejected</h4>
            <p style="font-size:1.4rem; font-weight:600; margin:0.3rem 0 0;">{{ $totalRejected }}</p>
        </div>
    </div>
    @endif

    <h3>Daftar Pengajuan</h3>

    <table style="width:100%; border-collapse:collapse; margin-top:1rem; font-size:14px;">
        <thead>
            <tr style="background:#f5f5f5;">
                <th style="border:1px solid #ccc; padding:8px; text-align:center; width:40px;">No</th>
                <th style="border:1px solid #ccc; padding:8px; text-align:center; width:160px;">Kode</th>
                <th style="border:1px solid #ccc; padding:8px; text-align:center; width:150px;">Created At</th>
                <th style="border:1px solid #ccc; padding:8px;">Nama Kegiatan</th>
                <th style="border:1px solid #ccc; padding:8px; text-align:center; width:140px;">Jenis</th>
                <th style="border:1px solid #ccc; padding:8px; text-align:center; width:120px;">Pengaju</th>
                <th style="border:1px solid #ccc; padding:8px; text-align:center; width:140px;">Status</th>
                <th style="border:1px solid #ccc; padding:8px; text-align:center; width:110px;">Detail</th>
                <th style="border:1px solid #ccc; padding:8px; text-align:center; width:160px;">Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse($pengajuans as $index => $pengajuan)
            <tr>
                <td style="border:1px solid #ccc; padding:8px; text-align:center;">
                    {{ $index + 1 }}
                </td>

                <td style="border:1px solid #ccc; padding:8px; text-align:center; font-weight:600;">
                    {{ $pengajuan->kode_pengajuan ?? '-' }}
                </td>

                <td style="border:1px solid #ccc; padding:8px; text-align:center; white-space:nowrap;">
                    {{ $pengajuan->created_at->format('d M Y') }}<br>
                    <small style="color:#666;">{{ $pengajuan->created_at->format('H:i') }}</small>
                </td>

                <td style="border:1px solid #ccc; padding:8px;">
                    {{ $pengajuan->nama_kegiatan }}
                </td>

                <td style="border:1px solid #ccc; padding:8px; text-align:center;">
                    {{ $pengajuan->jenis_pengajuan === 'pembelian'
                        ? 'Pembelian'
                        : 'Pemeliharaan' }}
                </td>

                <td style="border:1px solid #ccc; padding:8px; text-align:center;">
                    {{ $pengajuan->user->name }}
                </td>

                <td style="border:1px solid #ccc; padding:8px; text-align:center;">
                    <span style="
                        display:inline-block;
                        padding:4px 10px;
                        border-radius:12px;
                        font-size:12px;
                        background:#e3f2fd;
                        color:#0d47a1;
                        font-weight:600;
                    ">
                        Pending ADUM
                    </span>
                </td>

                <td style="border:1px solid #ccc; padding:8px; text-align:center;">
                    <a href="{{ route('pegawai.pengajuan.show', $pengajuan->id) }}"
                    style="
                            background:#1976d2;
                            color:white;
                            padding:6px 10px;
                            border-radius:4px;
                            text-decoration:none;
                            font-size:13px;
                    ">
                        Detail
                    </a>
                </td>

                <td style="border:1px solid #ccc; padding:8px;">
                    <div style="display:flex; justify-content:center; gap:6px;">
                        <form action="{{ route('adum.approve', $pengajuan->id) }}" method="POST">
                            @csrf
                            <button type="submit"
                                style="
                                    background:#4CAF50;
                                    color:white;
                                    border:none;
                                    padding:6px 10px;
                                    border-radius:4px;
                                    font-size:13px;
                                    cursor:pointer;
                                ">
                                Approve
                            </button>
                        </form>

                        <form action="{{ route('adum.reject', $pengajuan->id) }}" method="POST">
                            @csrf
                            <button type="submit"
                                style="
                                    background:#f44336;
                                    color:white;
                                    border:none;
                                    padding:6px 10px;
                                    border-radius:4px;
                                    font-size:13px;
                                    cursor:pointer;
                                ">
                                Reject
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align:center; padding:1rem; color:#777;">
                    Tidak ada pengajuan
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
