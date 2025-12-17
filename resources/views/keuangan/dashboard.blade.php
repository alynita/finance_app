@extends('layouts.app')

@section('title', 'Dashboard Keuangan')
@section('header', 'Dashboard Keuangan')

@section('content')
<div style="max-width:1200px; margin:auto; overflow-x:auto;">

    <!-- Ucapan Selamat Datang -->
    <div style="background:#eaf3ea; padding:1.5rem; border-radius:10px; margin-bottom:20px; border-left:6px solid #2e7d32;">
        <h2 style="margin:0; color:#1b5e20;">Selamat datang, {{ Auth::user()->name }}👋</h2>
        <p style="margin:5px 0 0 0; color:#333;">
            Proses Dana Pengajuan dengan mudah dan efisien.
        </p>
    </div>

    @if(session('success'))
        <div style="background-color:#d4edda; color:#155724; padding:10px; margin-bottom:10px; border-radius:5px;">
            {{ session('success') }}
        </div>
    @endif

    <table style="width:100%; border-collapse:collapse; text-align:left;">
        <thead style="background:#007bff; color:white;">
            <tr>
                <th style="border:1px solid #ccc; padding:0.6rem;">No</th>
                <th style="border:1px solid #ccc; padding:0.6rem;">Kode</th>
                <th style="border:1px solid #ccc; padding:0.6rem;">Nama Kegiatan</th>
                <th style="border:1px solid #ccc; padding:0.6rem;">Pengaju</th>
                <th style="border:1px solid #ccc; padding:0.6rem;">Status</th>
                <th style="border:1px solid #ccc; padding:0.6rem;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($groups as $index => $group)
            <tr style="background:{{ $index % 2 == 0 ? '#f9f9f9' : '#fff' }};">
                <td style="border:1px solid #ccc; padding:0.5rem; text-align:center;">{{ $index + 1 }}</td>
                <td style="border:1px solid #ccc; padding:0.5rem;">{{ $group->pengajuan->kode_pengajuan ?? '-' }}</td>
                <td style="border:1px solid #ccc; padding:0.5rem;">{{ $group->pengajuan->nama_kegiatan ?? '-' }}</td>
                <td style="border:1px solid #ccc; padding:0.5rem;">{{ $group->pengajuan->user->name ?? '-' }}</td>
                <td style="border:1px solid #ccc; padding:0.5rem;">{{ ucfirst(str_replace('_',' ',$group->status)) }}</td>
                <td style="border:1px solid #ccc; padding:0.5rem;">
                    <a href="{{ route('keuangan.showGroup', $group->id) }}" 
                        style="padding:5px 10px; background:#007bff; color:white; border-radius:3px; text-decoration:none;">
                        Proses
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if($groups->isEmpty())
        <p>Tidak ada grup pengajuan yang dikirim ke keuangan.</p>
    @endif

</div>
@endsection
