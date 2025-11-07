@extends('layouts.app')

@section('title', 'Dashboard PPK')
@section('header', 'Daftar Pengajuan Masuk PPK')

@section('content')
<div style="max-width:900px; margin:auto;">
    <h3>Daftar Pengajuan Masuk PPK</h3>

    @if($pengajuans->isEmpty())
        <p>Tidak ada pengajuan yang menunggu persetujuan.</p>
    @else
        <table style="width:100%; border-collapse:collapse; margin-top:1rem;">
            <thead>
                <tr style="background:#f2f2f2;">
                    <th style="border:1px solid #ccc; padding:0.5rem;">No</th>
                    <th style="border:1px solid #ccc; padding:0.5rem;">Nama Kegiatan</th>
                    <th style="border:1px solid #ccc; padding:0.5rem;">Diajukan Oleh</th>
                    <th style="border:1px solid #ccc; padding:0.5rem;">Tanggal Pengajuan</th>
                    <th style="border:1px solid #ccc; padding:0.5rem;">Status</th>
                    <th style="border:1px solid #ccc; padding:0.5rem;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pengajuans as $key => $pengajuan)
                    <tr>
                        <td style="border:1px solid #ccc; padding:0.5rem;">{{ $key + 1 }}</td>
                        <td style="border:1px solid #ccc; padding:0.5rem;">
                            {{ $pengajuan->nama_kegiatan ?? '-' }}
                        </td>
                        <td style="border:1px solid #ccc; padding:0.5rem;">
                            {{ $pengajuan->user->name ?? 'Tidak diketahui' }}
                        </td>
                        <td style="border:1px solid #ccc; padding:0.5rem;">
                            {{ $pengajuan->created_at ? $pengajuan->created_at->format('d M Y') : '-' }}
                        </td>
                        <td style="border:1px solid #ccc; padding:0.5rem;">
                            {{ ucfirst(str_replace('_', ' ', $pengajuan->status)) }}
                        </td>
                        <td style="border:1px solid #ccc; padding:0.5rem;">
                            <a href="{{ route('ppk.show', $pengajuan->id) }}" 
                                style="background:#007bff; color:white; padding:0.4rem 0.8rem; border-radius:4px; text-decoration:none;">
                                Lihat Detail
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
