@extends('layouts.app')

@section('title', 'Dashboard PJ')
@section('header', 'Pengajuan Menunggu Tanda Tangan PJ')

@section('content')
<div style="max-width:900px; margin:auto;">

    @if(session('success'))
        <div style="color:green;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div style="color:red;">{{ session('error') }}</div>
    @endif

    <table style="width:100%; border-collapse: collapse;">
        <thead>
            <tr style="background:#f2f2f2;">
                <th style="border:1px solid #ccc; padding:0.5rem;">Nama Kegiatan</th>
                <th style="border:1px solid #ccc; padding:0.5rem;">Jenis</th>
                <th style="border:1px solid #ccc; padding:0.5rem;">Pengaju</th>
                <th style="border:1px solid #ccc; padding:0.5rem;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pengajuans as $pengajuan)
                <tr>
                    <td style="border:1px solid #ccc; padding:0.5rem;">{{ $pengajuan->nama_kegiatan }}</td>
                    <td style="border:1px solid #ccc; padding:0.5rem;">{{ ucfirst($pengajuan->jenis_pengajuan) }}</td>
                    <td style="border:1px solid #ccc; padding:0.5rem;">{{ $pengajuan->user->name }}</td>
                    <td style="border:1px solid #ccc; padding:0.5rem;">
                        <form action="{{ route('pj.approve', $pengajuan->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" style="background:green; color:white; padding:0.3rem 0.5rem; border:none;">Approve</button>
                        </form>
                        <form action="{{ route('pj.reject', $pengajuan->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" style="background:red; color:white; padding:0.3rem 0.5rem; border:none;">Reject</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="border:1px solid #ccc; padding:0.5rem; text-align:center;">Tidak ada pengajuan menunggu PJ</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
