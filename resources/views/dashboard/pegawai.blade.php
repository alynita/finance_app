@extends('layouts.app')

@section('title', 'Dashboard Pegawai')
@section('header', 'Dashboard Pegawai')

@section('content')
<div>
    <h1>Selamat datang, {{ $user->name }}</h1>

    <div style="display:flex; gap:1rem; margin:1rem 0;">
        <div class="card pending" style="flex:1;">
            <h3>Jumlah Pending</h3>
            <p class="count" data-count="{{ $pending ?? 0 }}">0</p>
        </div>
        <div class="card approved" style="flex:1;">
            <h3>Jumlah Approve</h3>
            <p class="count" data-count="{{ $approved ?? 0 }}">0</p>
        </div>
        <div class="card rejected" style="flex:1;">
            <h3>Jumlah Reject</h3>
            <p class="count" data-count="{{ $rejected ?? 0 }}">0</p>
        </div>
    </div>

    <style>
    .card {
        padding: 1rem;
        border-radius: 5px;
        text-align: center;
        transition: transform 0.2s, background 0.2s;
    }
    .card.pending { background: #f8f9fa; color: #333; }
    .card.approved { background: #d4edda; color: #155724; }
    .card.rejected { background: #f8d7da; color: #721c24; }
    .card:hover { transform: translateY(-5px); box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
    .count { font-size: 1.5rem; font-weight: bold; }
    </style>

    <script>
    // Animasi naik angka
    document.querySelectorAll('.count').forEach(el => {
        const target = +el.getAttribute('data-count');
        let count = 0;
        const step = Math.ceil(target / 50); // 50 frame animasi

        const interval = setInterval(() => {
            count += step;
            if(count >= target) {
                count = target;
                clearInterval(interval);
            }
            el.textContent = count;
        }, 20);
    });
    </script>

    <a href="{{ route('pegawai.pengajuan.create') }}" style="display:inline-block; margin:1rem 0; padding:0.5rem 1rem; background:#3490dc; color:white; text-decoration:none; border-radius:4px;">Buat Pengajuan</a>

    <h3>Ringkasan Pengajuan</h3>
    <table border="1" cellpadding="8" cellspacing="0" style="border-collapse:collapse; width:100%;">
        <thead style="background:#eee;">
            <tr>
                <th>No</th>
                <th>Judul Pengajuan</th>
                <th>Tanggal Pengajuan</th>
                <th>Status Akhir</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pengajuans as $index => $pengajuan)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $pengajuan->nama_kegiatan }}</td>
                    <td>{{ $pengajuan->created_at->format('d-m-Y') }}</td>
                    <td>
                        @if($pengajuan->status == 'approved')
                            Approved
                        @elseif(str_starts_with($pengajuan->status, 'rejected'))
                            Rejected
                        @else
                            Pending
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('pegawai.pengajuan.show', $pengajuan->id) }}"
                            style="background:#3498db; color:white; padding:5px 10px; 
                                    text-decoration:none; border-radius:3px;">
                            Lihat Detail
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align:center;">Belum ada pengajuan</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
