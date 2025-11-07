<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Finance App')</title>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
/* Basic reset */
body { font-family: Arial, sans-serif; margin:0; padding:0; }

/* Header */
.header {
    background: #3490dc;
    color: white;
    padding: 0.5rem 1rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: fixed;
    width: 100%;
    top: 0;
    z-index: 1000;
    box-sizing: border-box;
}
.header-left { display:flex; align-items:center; gap:0.5rem; }
.header-left h1 { margin:0; font-size:1.2rem; white-space:nowrap; }
.header form button {
    background: #ff5c5c; border:none; color:white; padding:0.5rem 1rem; border-radius:4px;
    cursor:pointer; font-size:0.9rem; white-space:nowrap; min-width:80px;
}
.header form button:hover { background: #ff3b3b; }

/* Sidebar */
.sidebar {
    background: #f1f1f1;
    width: 200px;
    height: 100vh;
    position: fixed;
    top: 50px;
    left: 0;
    padding-top:1rem;
    overflow-y:auto;
    transition: width 0.3s ease, transform 0.3s ease;
    border-right:1px solid rgba(0,0,0,0.1);
    box-sizing:border-box;
}
.sidebar.collapsed { width:60px; }
.sidebar a { display:flex; align-items:center; padding:0.5rem 1rem; color:#333; text-decoration:none; margin-bottom:0.2rem; white-space:nowrap; }
.sidebar a:hover { background:#ddd; }
.sidebar.collapsed a span { display:none; }

.dropdown {
    position: relative;
}

.dropdown-btn {
    background: none;
    border: none;
    color: black;
    cursor: pointer;
    width: 100%;
    text-align: left;
    padding: 8px 16px;
}

.dropdown-content {
    display: none;
    flex-direction: column;
    background-color: #50C878;
    margin-left: 10px;
}

.dropdown-content a {
    padding: 6px 20px;
    color: #ddd;
    text-decoration: none;
}

.dropdown-content a:hover {
    color: #fff;
}

.dropdown:hover .dropdown-content {
    display: flex;
}


/* Content */
.content {
    margin-left:200px;
    padding:2rem;
    padding-top:70px;
    transition: margin-left 0.3s ease;
    box-sizing:border-box;
}
.sidebar.collapsed ~ .content { margin-left:60px; }

/* Responsive */
@media (max-width:768px){
    .sidebar { transform:translateX(-100%); width:200px; }
    .sidebar.active { transform:translateX(0); }
    .content { margin-left:0; }
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

    @elseif(str_starts_with($user->role, 'timker_')) {{-- timker1–timker6 --}}
        <a href="{{ route($user->role . '.dashboard') }}"><span>Dashboard</span></a>
        <a href="{{ route($user->role . '.pengajuan') }}"><span>Pengajuan</span></a>

    @elseif($user->role == 'adum')
        <a href="{{ route('adum.dashboard') }}"><span>Dashboard</span></a>
        <a href="{{ route('adum.pengajuan') }}"><span>Pengajuan</span></a>
        <a href="{{ route('proses.dashboard') }}"><span>Proses Keuangan</span></a>
        <a href="{{ route('honor.dashboard') }}"><span>Approval Honor</span></a>

    @elseif($user->role == 'ppk')
        <a href="{{ route('ppk.dashboard') }}"><span>Dashboard</span></a>
        <a href="{{ route('ppk.approve') }}"><span>Draf</span></a>
        <a href="{{ route('proses.dashboard') }}"><span>Proses Keuangan</span></a>
        <a href="{{ route('honor.dashboard') }}"><span>Approval Honor</span></a>

    @elseif($user->role == 'keuangan')
    <a href="{{ route('keuangan.dashboard') }}"><span>Dashboard</span></a>
    <a href="{{ route('keuangan.laporan') }}"><span>Laporan</span></a>

    <!-- Dropdown untuk menu Honor -->
    <div class="dropdown">
        <button class="dropdown-btn"><span>Honor</span></button>
        <div class="dropdown-content">
            <a href="{{ route('keuangan.honor.form') }}">Pengajuan Honor</a>
            <a href="{{ route('keuangan.honor.data') }}">Data Honor</a>
        </div>
    </div>

    @elseif($user->role == 'verifikator')
        <a href="{{ route('verifikator.dashboard') }}"><span>Dashboard</span></a>

    @elseif($user->role == 'bendahara')
        <a href="{{ route('bendahara.dashboard') }}"><span>Dashboard</span></a>
        <a href="{{ route('bendahara.arsip') }}"><span>Arsip</span></a>
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
