@extends('layouts.app')

@section('title', 'Dashboard Pegawai')
@section('header', 'Dashboard Pegawai')

@section('content')
<div>
    <!-- Ucapan Selamat Datang -->
    <div style="background:#eaf3ea; padding:1.5rem; border-radius:10px; margin-bottom:20px; border-left:6px solid #2e7d32;">
        <h2 style="margin:0; color:#1b5e20;">Selamat datang, {{ $user->name }} 👋</h2>
        <p style="margin:5px 0 0 0; color:#333;">
            Buat Pengajuan dan pantau status pengajuan dengan mudah dan efisien.
        </p>
    </div>

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
                <th>Nomor</th>
                <th>Judul Pengajuan</th>
                <th>Tanggal Pengajuan</th>
                <th>Status Akhir</th>
                <th>Aksi</th>
                <th>Follow Up</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pengajuans as $index => $pengajuan)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $pengajuan->kode_pengajuan }}</td>
                    <td>{{ $pengajuan->nama_kegiatan }}</td>
                    <td>{{ $pengajuan->created_at->format('d-m-Y') }}</td>
                    <td>
                        @php
                            $statusLabel = '';
                            $bg = '#eee';
                            $color = '#333';
                            $icon = '⏳';

                            switch ($pengajuan->status) {
                                case 'menunggu_persediaan':
                                    $statusLabel = 'Dicek Persediaan';
                                    $bg = '#fff3cd';
                                    $color = '#856404';
                                    $icon = '📦';
                                    break;

                                case 'pending_adum':
                                    $statusLabel = 'Menunggu ADUM';
                                    $bg = '#e3f2fd';
                                    $color = '#0d47a1';
                                    $icon = '📝';
                                    break;

                                case 'pending_ppk':
                                    $statusLabel = 'Menunggu PPK';
                                    $bg = '#ede7f6';
                                    $color = '#4527a0';
                                    $icon = '✍️';
                                    break;

                                case 'pending_pengadaan':
                                    $statusLabel = 'Proses Pengadaan';
                                    $bg = '#e1f5fe';
                                    $color = '#0277bd';
                                    $icon = '🏗️';
                                    break;

                                case 'submitted_keuangan':
                                    $statusLabel = 'Diajukan ke Keuangan';
                                    $bg = '#f3e5f5';
                                    $color = '#6a1b9a';
                                    $icon = '💰';
                                    break;

                                case 'approved':
                                    $statusLabel = 'Disetujui';
                                    $bg = '#e8f5e9';
                                    $color = '#2e7d32';
                                    $icon = '✅';
                                    break;

                                case 'rejected':
                                    $statusLabel = 'Ditolak';
                                    $bg = '#fdecea';
                                    $color = '#c62828';
                                    $icon = '❌';
                                    break;

                                default:
                                    $statusLabel = ucfirst(str_replace('_', ' ', $pengajuan->status));
                            }
                        @endphp

                        <span style="
                            display:inline-block;
                            padding:6px 12px;
                            border-radius:20px;
                            font-size:13px;
                            font-weight:600;
                            background:{{ $bg }};
                            color:{{ $color }};
                        ">
                            {{ $icon }} {{ $statusLabel }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('pegawai.pengajuan.show', $pengajuan->id) }}"
                            style="background:#3498db; color:white; padding:5px 10px; 
                                    text-decoration:none; border-radius:3px;">
                            Lihat Detail
                        </a>
                    </td>
                    <td style="text-align:center;">
                    @php
                        $userTarget = null;
                        $jabatan = null;

                        switch ($pengajuan->status) {
                            case 'menunggu_persediaan':
                                $userTarget = \App\Models\User::where('role', 'persediaan')->first();
                                $jabatan = 'Petugas Persediaan';
                                break;

                            case 'pending_adum':
                                $userTarget = \App\Models\User::where('role', 'adum')->first();
                                $jabatan = 'ADUM';
                                break;

                            case 'pending_ppk':
                                $userTarget = \App\Models\User::where('role', 'ppk')->first();
                                $jabatan = 'PPK';
                                break;

                            case 'pending_pengadaan':
                                $userTarget = \App\Models\User::where('role', 'pengadaan')->first();
                                $jabatan = 'Pengadaan';
                                break;

                            case 'submitted_keuangan':
                                $userTarget = \App\Models\User::where('role', 'keuangan')->first();
                                $jabatan = 'Keuangan';
                                break;
                        }

                        $waNumber = null;
                        if ($userTarget && $userTarget->no_hp) {
                            $waNumber = preg_replace('/^0/', '62', $userTarget->no_hp);
                        }

                        $pesan = urlencode(
                            "Yth. Bapak/Ibu {$jabatan}\n\n".
                            "Mohon izin mengingatkan,\n".
                            "terdapat pengajuan dengan nomor:\n\n".
                            "{$pengajuan->kode_pengajuan}\n".
                            "{$pengajuan->nama_kegiatan}\n\n".
                            "yang saat ini berstatus:\n".
                            strtoupper(str_replace('_',' ', $pengajuan->status))."\n\n".
                            "Mohon kiranya dapat ditindaklanjuti.\n".
                            "Atas perhatian Bapak/Ibu kami ucapkan terima kasih.\n\n".
                            "Hormat kami,\n{$user->name}"
                        );
                    @endphp

                    @if($waNumber)
                        <a href="https://wa.me/{{ $waNumber }}?text={{ $pesan }}"
                        target="_blank"
                        style="background:#25D366; color:white; padding:5px 10px; border-radius:4px; text-decoration:none;">
                            💬 WA
                        </a>
                    @else
                        -
                    @endif
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
