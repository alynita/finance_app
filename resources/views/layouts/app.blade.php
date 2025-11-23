<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Finance App')</title>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
/* === WARNA UTAMA KEMENKES === */
:root {
    --green-main: #009B4C;     /* Hijau khas Kemenkes */
    --green-dark: #007B39;
    --green-light: #00B262;
    --white: #ffffff;
    --gray-bg: #f5f7f5;
    --gray-text: #333;
}

/* Reset dan font */
body {
    font-family: "Segoe UI", Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: var(--gray-bg);
}

/* Header */
.header {
    background: var(--green-main);
    color: var(--white);
    padding: 0.7rem 1.2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: fixed;
    width: 100%;
    top: 0;
    z-index: 1000;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    box-sizing: border-box;
}
.header-left { display: flex; align-items: center; gap: 0.6rem; }
.header-left h1 { margin: 0; font-size: 1.2rem; white-space: nowrap; font-weight: 600; }
.header form button {
    background: var(--green-dark);
    border: none;
    color: var(--white);
    padding: 0.5rem 1.2rem;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.9rem;
    transition: background 0.3s ease;
}
.header form button:hover {
    background: #005f2c;
}

/* Sidebar */
.sidebar {
    background: var(--white);
    width: 220px;
    height: 100vh;
    position: fixed;
    top: 60px;
    left: 0;
    padding-top: 1rem;
    overflow-y: auto;
    transition: width 0.3s ease, transform 0.3s ease;
    border-right: 1px solid rgba(0,0,0,0.1);
    box-sizing: border-box;
}
.sidebar.collapsed { width: 60px; }
.sidebar a {
    display: flex;
    align-items: center;
    padding: 0.6rem 1rem;
    color: var(--gray-text);
    text-decoration: none;
    margin-bottom: 0.2rem;
    border-left: 4px solid transparent;
    transition: all 0.3s ease;
}
.sidebar a:hover {
    background: var(--green-light);
    color: var(--white);
    border-left: 4px solid var(--green-dark);
}
.sidebar.collapsed a span { display: none; }

/* Container dropdown */
.dropdown {
    position: relative;
    width: 100%;
}

/* Tombol dropdown */
.dropdown-btn {
    background: none;
    border: none;
    color: var(--gray-text);
    cursor: pointer;
    width: 100%;
    padding: 10px 20px;
    font-size: 1rem;
    text-align: left;
    border-radius: 6px;
    transition: all 0.2s ease;
}

/* Hover tombol */
.dropdown-btn:hover {
    background-color: var(--green-light);
    color: var(--white);
}

/* Isi dropdown */
.dropdown-content {
    display: none;
    position: relative;
    background-color: var(--white);
    border-left: 3px solid var(--green-main);
    margin-left: 10px;
    padding-left: 10px;
    flex-direction: column;
}

/* Link di dropdown */
.dropdown-content a {
    padding: 8px 12px;
    font-size: 0.95rem;
    color: var(--gray-text);
    display: block;
    text-decoration: none;
    border-radius: 4px;
    transition: 0.2s ease;
}

/* Hover link */
.dropdown-content a:hover {
    background: var(--green-light);
    color: var(--white);
}

/* Saat hover container, tampilkan menu */
.dropdown:hover .dropdown-content {
    display: flex;
}

/* Konten */
.content {
    margin-left: 220px;
    padding: 2rem;
    padding-top: 80px;
    transition: margin-left 0.3s ease;
    box-sizing: border-box;
}
.sidebar.collapsed ~ .content {
    margin-left: 60px;
}

/* Tombol sidebar */
.sidebar-toggle {
    background: var(--white);
    color: var(--green-main);
    border: none;
    font-size: 1.2rem;
    padding: 0.3rem 0.6rem;
    border-radius: 4px;
    cursor: pointer;
    transition: background 0.3s ease;
}
.sidebar-toggle:hover {
    background: rgba(255,255,255,0.2);
    color: var(--white);
}

/* Responsive */
@media (max-width: 768px) {
    .sidebar { transform: translateX(-100%); width: 200px; }
    .sidebar.active { transform: translateX(0); }
    .content { margin-left: 0; }
}
</style>
</head>
<body>

<!-- Header -->
<div class="header">
    <div class="header-left">
        <button class="sidebar-toggle" onclick="toggleSidebar()">☰</button>
        <h1>@yield('header', 'Finance App')</h1>
    </div>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>
</div>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    @php $user = auth()->user(); @endphp

    @if($user->role == 'pegawai')
        <a href="{{ route('pegawai.dashboard') }}"><span>Dashboard</span></a>
        <a href="{{ route('pegawai.pengajuan.create') }}"><span>Buat Pengajuan</span></a>
        <a href="{{ route('pegawai.daftar-pengajuan') }}"><span>Daftar Pengajuan</span></a>

    @elseif(str_starts_with($user->role, 'anggota_timker_'))
        <a href="{{route('anggota_timker.dashboard') }}"><span>Dashboard</span></a>
        <a href="{{route('anggota_timker.create') }}"><span>Buat Pengajuan</span></a>
        <a href="{{route('anggota_timker.index') }}">Daftar Pengajuan<span></span></a>
    @elseif($user->role == 'sarpras')
        <a href="{{ route('sarpras.dashboard') }}"><span>Dashboard</span></a>
        <a href="{{ route('pegawai.pengajuan.create') }}"><span>Buat Pengajuan</span></a>
        <a href="{{ route('pegawai.daftar-pengajuan') }}"><span>Daftar Pengajuan</span></a>

    @elseif($user->role == 'bmn')
        <a href="{{ route('bmn.dashboard') }}"><span>Dashboard</span></a>
        <a href="{{ route('pegawai.pengajuan.create') }}"><span>Buat Pengajuan</span></a>
        <a href="{{ route('pegawai.daftar-pengajuan') }}"><span>Daftar Pengajuan</span></a>

    @elseif($user->role == 'pengadaan')
        <a href="{{ route('pengadaan.dashboard') }}"><span>Dashboard</span></a>
        <a href="{{ route('pengadaan.arsip') }}"><span>Arsip</span></a>

    @elseif(str_starts_with($user->role, 'timker_'))
        <a href="{{ route($user->role . '.dashboard') }}"><span>Dashboard</span></a>
        <a href="{{ route($user->role . '.pengajuan') }}"><span>Pengajuan</span></a>

    @elseif($user->role == 'adum')
        <a href="{{ route('adum.dashboard') }}"><span>Dashboard</span></a>
        <a href="{{ route('adum.pengajuan') }}"><span>Pengajuan</span></a>
        <a href="{{ route('proses.dashboard') }}"><span>Proses Keuangan</span></a>
        <a href="{{ route('honor.dashboard') }}"><span>Approval Honor</span></a>

    @elseif($user->role == 'ppk')
        <a href="{{ route('ppk.dashboard') }}"><span>Dashboard</span></a>
        <a href="{{ route('proses.dashboard') }}"><span>Proses Keuangan</span></a>
        <a href="{{ route('honor.dashboard') }}"><span>Approval Honor</span></a>
        <a href="{{ route('ppk.approve') }}"><span>Draf</span></a>

    @elseif($user->role == 'keuangan')
        <a href="{{ route('keuangan.dashboard') }}"><span>Dashboard</span></a>
        <a href="{{ route('keuangan.laporan') }}"><span>Laporan</span></a>

        <!-- Dropdown Honor -->
        <div class="dropdown">
            <button class="dropdown-btn">Honor ▾</button>
            <div class="dropdown-content">
                <a href="{{ route('keuangan.honor.form') }}">Pengajuan Honor</a>
                <a href="{{ route('keuangan.honor.data') }}">Data Honor</a>
                <a href="{{ route('keuangan.honor.index.laporan') }}">Laporan SPD Rampung</a>
            </div>
        </div>

    @elseif($user->role == 'verifikator')
        <a href="{{ route('verifikator.dashboard') }}"><span>Dashboard</span></a>
        <a href="{{ route('verifikator.proses') }}"><span>Proses Keuangan</span></a>
        <a href="{{ route('verifikator.arsip') }}"><span>Arsip Honor</span></a>


    @elseif($user->role == 'bendahara')
        <a href="{{ route('bendahara.dashboard') }}"><span>Dashboard</span></a>

        {{-- Dropdown Arsip --}}
        <div class="dropdown">
            <button class="dropdown-btn">Arsip ▾</button>
            <div class="dropdown-content">
                <a href="{{ route('bendahara.arsip.pengadaan.list') }}">Pengadaan Barang</a>
                <a href="{{ route('bendahara.arsip.kerusakan.list') }}">Kerusakan Barang</a>
                <a href="{{ route('bendahara.arsip.honor.list') }}">Honor</a>
            </div>
        </div>
    @endif

    <a href="{{ route('profile.edit') }}"><span>Profile</span></a>

</div>

<!-- Content -->
<div class="content">
    @yield('content')
</div>

<script>
function toggleSidebar(){
    document.getElementById('sidebar').classList.toggle('collapsed');
}
</script>

</body>
</html>
