@extends('layouts.app')

@section('title', 'Dashboard Verifikator')
@section('header', 'Dashboard Verifikator')

@section('content')
<div style="max-width:1200px; margin:auto;">

    <div style="display:flex; gap:20px; margin-bottom:20px;">
        <div style="flex:1; padding:20px; background:#f1f1f1; border-radius:5px; text-align:center;">
            <h3>Pengajuan Proses Keuangan</h3>
            <p style="font-size:2rem; font-weight:bold;">{{ $pendingCount }}</p>
            <a href="{{ route('proses.dashboard') }}" style="text-decoration:none; color:#007bff;">Lihat Detail</a>
        </div>
        <div style="flex:1; padding:20px; background:#f1f1f1; border-radius:5px; text-align:center;">
            <h3>Arsip Honor</h3>
            <p style="font-size:2rem; font-weight:bold;">{{ $arsipCount }}</p>
            <a href="{{ route('verifikator.arsip') }}" style="text-decoration:none; color:#007bff;">Lihat Detail</a>
        </div>
    </div>

</div>
@endsection
