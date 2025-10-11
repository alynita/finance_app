@extends('layouts.app')

@section('title', 'Daftar Pengajuan')
@section('header', 'Daftar Pengajuan')

@section('content')
@if(session('success'))
    <div style="background-color: #d4edda; color: #155724; padding: 10px; margin-bottom: 10px; border-radius: 5px;">
        {{ session('success') }}
    </div>
@endif

<h2>Daftar Pengajuan</h2>

<table border="1" cellpadding="10" cellspacing="0" style="width:100%; border-collapse: collapse;">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Pegawai</th>
            <th>Judul Pengajuan</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($pengajuans as $index => $pengajuan)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $pengajuan->user->name }}</td>
                <td>{{ $pengajuan->nama_kegiatan ?? '-' }}</td>
                <td>{{ ucfirst($pengajuan->status) }}</td>
                <td>
                    @if(auth()->user()->role == 'adum' && $pengajuan->status == 'pending' ||
                        auth()->user()->role == 'ppk' && $pengajuan->status == 'approved_adum')

                        <form method="POST" action="{{ route('approve.approve', $pengajuan->id) }}" style="display:inline;" onsubmit="return confirmApprove(event)">
                            @csrf
                            <button type="submit" style="background: green; color: white; padding: 5px 10px; border:none; border-radius:3px;">Approve</button>
                        </form>

                        <form method="POST" action="{{ route('approve.reject', $pengajuan->id) }}" style="display:inline;" onsubmit="return confirmReject(event)">
                            @csrf
                            <button type="submit" style="background: red; color: white; padding: 5px 10px; border:none; border-radius:3px;">Reject</button>
                        </form>

                    @else
                        sudah approve
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" style="text-align:center;">Belum ada pengajuan</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection
