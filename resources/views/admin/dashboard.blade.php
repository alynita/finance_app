@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
<div class="header mb-4">
    <h1>Dashboard Admin</h1>
</div>

<h3 style="margin-bottom: 1rem;">Statistik Sistem</h3>

<div class="stat-grid">
    <div class="stat-card">
        <div class="icon">👥</div>
        <div class="details">
            <h4>Total Pengguna</h4>
            <p>{{ $totalUsers }}</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="icon">📄</div>
        <div class="details">
            <h4>Total Pengajuan</h4>
            <p>{{ $totalPengajuan }}</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="icon">📊</div>
        <div class="details">
            <h4>Total KRO</h4>
            <p>{{ $totalKro }}</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="icon">⏳</div>
        <div class="details">
            <h4>Pending</h4>
            <p>{{ $pending }}</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="icon">✅</div>
        <div class="details">
            <h4>Approved</h4>
            <p>{{ $approved }}</p>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .stat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.5rem;
    }
    .stat-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.08);
        padding: 1.5rem;
        display: flex;
        align-items: center;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 14px rgba(0,0,0,0.12);
    }
    .icon {
        font-size: 32px;
        background-color: #C19A6B;
        color: white;
        border-radius: 50%;
        width: 56px;
        height: 56px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
    }
    .details h4 {
        margin: 0;
        font-size: 18px;
        color: #0D1B2A;
        font-weight: 600;
    }
    .details p {
        margin: 0.2rem 0 0 0;
        font-size: 20px;
        font-weight: 700;
        color: #C19A6B;
    }
</style>
@endpush
