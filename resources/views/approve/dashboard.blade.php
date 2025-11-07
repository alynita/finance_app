@extends('layouts.app')

@section('title', 'Dashboard ' . strtoupper($user->role))
@section('header', 'Dashboard ' . strtoupper($user->role))

@section('content')
<div style="max-width:1000px; margin:auto;">

    <h2>Selamat datang, {{ $user->name }}</h2>

    <h3>Daftar Pengajuan</h3>

    <table style="width:100%; border-collapse:collapse; margin-top:1rem;">
        <thead>
            <tr>
                <th style="border:1px solid #ccc; padding:0.5rem;">No</th>
                <th style="border:1px solid #ccc; padding:0.5rem;">Created At</th>
                <th style="border:1px solid #ccc; padding:0.5rem;">Nama Kegiatan</th>
                <th style="border:1px solid #ccc; padding:0.5rem;">Jenis Pengajuan</th>
                <th style="border:1px solid #ccc; padding:0.5rem;">Pengaju</th>
                <th style="border:1px solid #ccc; padding:0.5rem;">Status</th>
                <th style="border:1px solid #ccc; padding:0.5rem;">Detail</th>
                <th style="border:1px solid #ccc; padding:0.5rem;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pengajuans as $index => $pengajuan)
            <tr>
                <td style="border:1px solid #ccc; padding:0.5rem;">{{ $index + 1 }}</td>
                <td style="border:1px solid #ccc; padding:0.5rem;">{{ $pengajuan->created_at->format('d M Y H:i') }}</td> {{-- Format tanggal --}}
                <td style="border:1px solid #ccc; padding:0.5rem;">{{ ucfirst($pengajuan->nama_kegiatan) }}</td>
                <td style="border:1px solid #ccc; padding:0.5rem;">{{ ucfirst($pengajuan->jenis_pengajuan) }}</td>
                <td style="border:1px solid #ccc; padding:0.5rem;">{{ $pengajuan->user->name }}</td>
                <td style="border:1px solid #ccc; padding:0.5rem;">{{ ucfirst(str_replace('_', ' ', $pengajuan->status)) }}</td>
                <td style="border:1px solid #ccc; padding:0.5rem;">
                    <a href="{{ route('pegawai.pengajuan.show', $pengajuan->id) }}"
                        style="background:#007bff; color:white; padding:0.4rem 0.8rem; border-radius:4px; text-decoration:none;">
                        Lihat Detail
                    </a>
                </td>
                <td style="border:1px solid #ccc; padding:0.5rem; display:flex; gap:0.5rem;">

                    {{-- Timker --}}
                    @if(str_starts_with($user->role, 'timker') && $pengajuan->status === 'pending_' . $user->role)
                        <form action="{{ route($user->role . '.approve', $pengajuan->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="keterangan" value="Approve by {{ $user->name }}">
                            <button type="submit" style="padding:0.3rem 0.6rem; background:#4CAF50; color:white; border:none; border-radius:4px;">Approve</button>
                        </form>
                        <form action="{{ route($user->role . '.reject', $pengajuan->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="keterangan" value="Reject by {{ $user->name }}">
                            <button type="submit" style="padding:0.3rem 0.6rem; background:#f44336; color:white; border:none; border-radius:4px;">Reject</button>
                        </form>

                    {{-- ADUM --}}
                    @elseif($user->role === 'adum' && $pengajuan->status === 'pending_adum')
                        <form action="{{ route('adum.approve', $pengajuan->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="keterangan" value="Approve by {{ $user->name }}">
                            <button type="submit" style="padding:0.3rem 0.6rem; background:#4CAF50; color:white; border:none; border-radius:4px;">Approve</button>
                        </form>
                        <form action="{{ route('adum.reject', $pengajuan->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="keterangan" value="Reject by {{ $user->name }}">
                            <button type="submit" style="padding:0.3rem 0.6rem; background:#f44336; color:white; border:none; border-radius:4px;">Reject</button>
                        </form>

                    {{-- PPK --}}
                    @elseif($user->role === 'ppk' && $pengajuan->status === 'pending_ppk')
                        <form action="{{ route('ppk.approve', $pengajuan->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="keterangan" value="Approve by {{ $user->name }}">
                            <button type="submit" style="padding:0.3rem 0.6rem; background:#4CAF50; color:white; border:none; border-radius:4px;">Approve</button>
                        </form>
                        <form action="{{ route('ppk.reject', $pengajuan->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="keterangan" value="Reject by {{ $user->name }}">
                            <button type="submit" style="padding:0.3rem 0.6rem; background:#f44336; color:white; border:none; border-radius:4px;">Reject</button>
                        </form>
                    @else
                        <span style="color:gray;">No Action</span>
                    @endif

                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center; padding:1rem;">Tidak ada pengajuan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
