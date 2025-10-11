@extends('layouts.app') <!-- pastikan ini nama layout sidebar/header kamu -->

@section('title', 'Dashboard Pegawai')

@section('header', 'Dashboard Pegawai')

@section('content')
    <div>
        <h1>Selamat datang, {{ $user->name }}</h1>

        <div style="display:flex; gap:1rem; margin:1rem 0;">
            <div style="background:#f8f9fa; padding:1rem; border-radius:5px; flex:1;">
                <h3>Jumlah Pending</h3>
                <p style="font-size:1.5rem; font-weight:bold;">{{ $pending ?? 0 }}</p>
            </div>
            <div style="background:#d4edda; padding:1rem; border-radius:5px; flex:1;">
                <h3>Jumlah Approve</h3>
                <p style="font-size:1.5rem; font-weight:bold;">{{ $approved ?? 0 }}</p>
            </div>
            <div style="background:#f8d7da; padding:1rem; border-radius:5px; flex:1;">
                <h3>Jumlah Reject</h3>
                <p style="font-size:1.5rem; font-weight:bold;">{{ $rejected ?? 0 }}</p>
            </div>
        </div>

        <a href="/pegawai/pengajuan" style="display:inline-block; margin:1rem 0; padding:0.5rem 1rem; background:#3490dc; color:white; text-decoration:none; border-radius:4px;">Buat Pengajuan</a>

        <h3>Ringkasan Pengajuan</h3>
            <table border="1" cellpadding="8" cellspacing="0" style="border-collapse:collapse; width:100%;">
                <thead style="background:#eee;">
                    <tr>
                        <th>No</th>
                        <th>Judul Pengajuan</th>
                        <th>Status Adum</th>
                        <th>Status PPK</th>
                        <th>Status Akhir</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengajuans as $index => $pengajuan)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $pengajuan->nama_kegiatan }}</td>
                            <td>{{ ucfirst($pengajuan->adum_status ?? 'pending') }}</td>
                            <td>{{ ucfirst($pengajuan->ppk_status ?? 'pending') }}</td>
                            <td>
                                @if($pengajuan->adum_status == 'approved' && $pengajuan->ppk_status == 'approved')
                                    Approved
                                @elseif($pengajuan->adum_status == 'rejected' || $pengajuan->ppk_status == 'rejected')
                                    Rejected
                                @else
                                    Pending
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
    </div>
@endsection
