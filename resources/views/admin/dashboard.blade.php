@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
<div class="header">
    <h1>Dashboard Admin</h1>
</div>

<div class="card">
    <h3>Statistik Sistem</h3>
    <p><strong>Total Pengguna:</strong> {{ $totalUsers }}</p>
    <p><strong>Total Pengajuan:</strong> {{ $totalPengajuan }}</p>
    <p><strong>Pending:</strong> {{ $pending }}</p>
    <p><strong>Approved:</strong> {{ $approved }}</p>
</div>
@endsection
