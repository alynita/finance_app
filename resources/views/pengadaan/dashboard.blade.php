@extends('layouts.app')

@section('title', 'Dashboard Pengadaan')
@section('header', 'Dashboard Pengadaan')

@section('content')
<div style="max-width:1200px; margin:auto;">
    <h3>Daftar Grup Pengajuan Pending Pengadaan</h3>

    <table style="width:100%; border-collapse:collapse; margin-top:1rem;">
        <thead>
            <tr>
                <th style="border:1px solid #ccc; padding:0.5rem;">No</th>
                <th style="border:1px solid #ccc; padding:0.5rem;">Nama Kegiatan</th>
                <th style="border:1px solid #ccc; padding:0.5rem;">Pengaju</th>
                <th style="border:1px solid #ccc; padding:0.5rem;">Status</th>
                <th style="border:1px solid #ccc; padding:0.5rem;">Dibuat</th>
                <th style="border:1px solid #ccc; padding:0.5rem;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($groups as $index => $group)
            <tr>
                <td style="border:1px solid #ccc; padding:0.5rem;">{{ $index + 1 }}</td>
                <td style="border:1px solid #ccc; padding:0.5rem;">
                    {{ $group->pengajuan->nama_kegiatan }}
                </td>
                <td style="border:1px solid #ccc; padding:0.5rem;">{{ $group->pengajuan->user->name }}</td>
                <td style="border:1px solid #ccc; padding:0.5rem;">
                    {{ ucfirst(str_replace('_',' ',$group->status)) }}
                </td>
                <td style="border:1px solid #ccc; padding:0.5rem;">
                    {{ $group->created_at->format('d M Y H:i') }}
                </td>
                <td style="border:1px solid #ccc; padding:0.5rem;">
                    <a href="{{ route('pengadaan.showGroup', $group->id) }}"
                    style="padding:6px 12px; background:#008CBA; color:white; border-radius:4px; text-decoration:none;">
                    Lihat Detail
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="border:1px solid #ccc; padding:0.5rem; text-align:center;">
                    Tidak ada grup pending pengadaan.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
