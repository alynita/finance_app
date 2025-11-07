@extends('layouts.app')

@section('title', 'Arsip Pengadaan')
@section('header', 'Arsip Pengadaan')

@section('content')
<div style="max-width:1200px; margin:auto;">
    <h3 style="margin-bottom:1rem;">Daftar Arsip Pengadaan</h3>

    <div style="overflow-x:auto; background:#fff; border-radius:8px; box-shadow:0 2px 5px rgba(0,0,0,0.1); padding:1rem;">
        <table style="width:100%; border-collapse:collapse; min-width:900px; text-align:left;">
            <thead style="background:#007bff; color:white;">
                <tr>
                    <th style="border:1px solid #ccc; padding:0.6rem;">No</th>
                    <th style="border:1px solid #ccc; padding:0.6rem;">Nama Kegiatan</th>
                    <th style="border:1px solid #ccc; padding:0.6rem;">Grup</th>
                    <th style="border:1px solid #ccc; padding:0.6rem;">Pengaju</th>
                    <th style="border:1px solid #ccc; padding:0.6rem;">Status</th>
                    <th style="border:1px solid #ccc; padding:0.6rem;">Tanggal Dibuat</th>
                    <th style="border:1px solid #ccc; padding:0.6rem;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($groups as $index => $group)
                <tr style="background:{{ $index % 2 == 0 ? '#f9f9f9' : '#fff' }};">
                    <td style="border:1px solid #ccc; padding:0.5rem; text-align:center;">{{ $index + 1 }}</td>
                    <td style="border:1px solid #ccc; padding:0.5rem;">{{ $group->pengajuan->nama_kegiatan }}</td>
                    <td style="border:1px solid #ccc; padding:0.5rem;">{{ $group->group_name }}</td>
                    <td style="border:1px solid #ccc; padding:0.5rem;">{{ $group->pengajuan->user->name }}</td>
                    <td style="border:1px solid #ccc; padding:0.5rem;">{{ ucfirst(str_replace('_',' ',$group->status)) }}</td>
                    <td style="border:1px solid #ccc; padding:0.5rem;">{{ $group->created_at->format('d M Y H:i') }}</td>
                    <td style="border:1px solid #ccc; padding:0.5rem;">
                        <a href="{{ route('pengadaan.showArsip', $group->id) }}" 
                            style="padding:6px 12px; background:#28a745; color:white; border-radius:4px; text-decoration:none;">
                            Lihat Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="border:1px solid #ccc; padding:0.5rem; text-align:center;">
                        Tidak ada arsip pengadaan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
