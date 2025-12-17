@extends('layouts.app')

@section('content')
<h2>Dashboard Persediaan</h2>

<!-- HEADER CARD -->
<div style="
    background:#eaf3ea;
    padding:1.5rem;
    border-radius:10px;
    margin-bottom:20px;
    border-left:6px solid #2e7d32;
">
    <h2 style="margin:0; color:#1b5e20;">
        Selamat datang, {{ Auth::user()->name }} 👋
    </h2>
    <p style="margin:5px 0 0 0; color:#333;">
        Dashboard Persediaan
    </p>
</div>

<!-- STAT CARD -->
<div style="margin-top:30px;">
    <div style="
        display:flex;
        gap:24px;
        flex-wrap:wrap;
    ">

        <!-- Card -->
        <div style="
            background:#fff;
            padding:24px;
            border-radius:14px;
            flex:1 1 260px;
            max-width:320px;
            box-shadow:0 6px 16px rgba(0,0,0,0.05);
            transition:0.2s;
        "
        onmouseover="this.style.transform='translateY(-4px)'"
        onmouseout="this.style.transform='translateY(0)'"
        >
            <p style="margin:0; color:#777; font-size:14px;">
                Total Pengajuan Masuk
            </p>
            <h2 style="margin:10px 0 0; font-size:36px; color:#2e7d32;">
                {{ \App\Models\Pengajuan::count() }}
            </h2>
        </div>

        <!-- Card -->
        <div style="
            background:#fff;
            padding:24px;
            border-radius:14px;
            flex:1 1 260px;
            max-width:320px;
            box-shadow:0 6px 16px rgba(0,0,0,0.05);
            transition:0.2s;
        "
        onmouseover="this.style.transform='translateY(-4px)'"
        onmouseout="this.style.transform='translateY(0)'"
        >
            <p style="margin:0; color:#777; font-size:14px;">
                Draft Pengeluaran
            </p>
            <h2 style="margin:10px 0 0; font-size:36px; color:#1565c0;">
                {{ \App\Models\PengeluaranBarang::count() }}
            </h2>
        </div>

    </div>
</div>

@endsection
