@extends('layouts.app')

@section('title', 'Dashboard ADUM')
@section('content')
<div style="max-width:1000px; margin:auto;">
    <h2>Dashboard Honor ADUM</h2>

    @if($honors->count() > 0)
    <table style="width:100%; border-collapse:collapse;">
        <thead>
            <tr>
                <th style="border:1px solid #ccc; padding:0.5rem;">No</th>
                <th style="border:1px solid #ccc; padding:0.5rem;">Created At</th>
                <th style="border:1px solid #ccc; padding:0.5rem;">Nama Kegiatan</th>
                <th style="border:1px solid #ccc; padding:0.5rem;">Waktu Kegiatan</th>
                <th style="border:1px solid #ccc; padding:0.5rem;">Status</th>
                <th style="border:1px solid #ccc; padding:0.5rem;">Detail</th>
                <th style="border:1px solid #ccc; padding:0.5rem;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($honors as $index => $honor)
            <tr>
                <td style="border:1px solid #ccc; padding:0.5rem;">{{ $index + 1 }}</td>
                <td style="border:1px solid #ccc; padding:0.5rem;">{{ \Carbon\Carbon::parse($honor->created_at)->format('d-m-Y') }}</td>
                <td style="border:1px solid #ccc; padding:0.5rem;">{{ $honor->nama_kegiatan }}</td>
                <td style="border:1px solid #ccc; padding:0.5rem;">{{ \Carbon\Carbon::parse($honor->waktu)->format('d-m-Y') }}</td>
                <td style="border:1px solid #ccc; padding:0.5rem;">
                    @switch($honor->status)
                        @case('pending') <span style="color:orange;">Menunggu ADUM</span> @break
                        @case('adum_approved') <span style="color:blue;">Disetujui ADUM</span> @break
                        @case('ppk_approved') <span style="color:purple;">Disetujui PPK</span> @break
                        @case('approved') <span style="color:green;">Disetujui Semua</span> @break
                        @case('rejected') <span style="color:red;">Ditolak</span> @break
                    @endswitch
                </td>
                <td style="border:1px solid #ccc; padding:0.5rem;">
                    <a href="{{ route('keuangan.honor.detail', $honor->id) }}"
                        style="background:#007bff; color:white; padding:0.4rem 0.8rem; border-radius:4px; text-decoration:none;">
                        Lihat Detail
                    </a>
                </td>
                <td style="border:1px solid #ccc; padding:0.5rem; display:flex; gap:0.3rem;">
                    @if($honor->status == 'pending' || $honor->status == 'adum_approved')
                        <form action="{{ route('honor.approve', $honor->id) }}" method="POST">
                            @csrf
                            <button type="submit" style="background:green; color:white; padding:0.3rem 0.6rem; border:none; border-radius:3px;">Approve</button>
                        </form>
                        <form action="{{ route('honor.reject', $honor->id) }}" method="POST">
                            @csrf
                            <button type="submit" style="background:red; color:white; padding:0.3rem 0.6rem; border:none; border-radius:3px;">Reject</button>
                        </form>
                    @else
                        <span style="color:gray;">No Action</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
        <p>Tidak ada data honor untuk ditampilkan.</p>
    @endif
</div>
@endsection
