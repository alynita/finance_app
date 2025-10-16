@extends('admin.layout')

@section('title', 'Manajemen Pengguna')

@section('content')
<div class="header">
    <h1>Manajemen Pengguna</h1>
</div>

@if(session('success'))
    <div style="background:#d4edda; color:#155724; padding:10px; border-radius:5px; margin-bottom:1rem;">
        {{ session('success') }}
    </div>
@endif

<div class="card">
    <table width="100%" cellspacing="0" cellpadding="10">
        <thead style="background-color:#C19A6B; color:white;">
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $i => $user)
                <tr style="border-bottom:1px solid #ddd;">
                    <td>{{ $i+1 }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <form action="{{ route('admin.users.updateRole', $user->id) }}" method="POST" style="display:flex; align-items:center; gap:8px;">
                            @csrf
                            @method('PUT')
                            <select name="role" style="padding:5px; border-radius:5px; border:1px solid #ccc;">
                                <option value="pegawai" {{ $user->role == 'pegawai' ? 'selected' : '' }}>Pegawai</option>
                                <option value="adum" {{ $user->role == 'adum' ? 'selected' : '' }}>Subbagian Administrasi Umum</option>
                                <option value="ppk" {{ $user->role == 'ppk' ? 'selected' : '' }}>Pejabat Pembuat Komitmen</option>
                                <option value="verifikator" {{ $user->role == 'verifikator' ? 'selected' : '' }}>Verifikator</option>
                                <option value="keuangan" {{ $user->role == 'keuangan' ? 'selected' : '' }}>Keuangan</option>
                                <option value="penyelenggara_pengadaan" {{ $user->role == 'penyelenggara_pengadaan' ? 'selected' : '' }}>Penyelenggara Pengadaan</option>
                                <option value="bendahara" {{ $user->role == 'bendahara' ? 'selected' : '' }}>Bendahara</option>
                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                            <button type="submit" class="btn" style="background-color:#1B263B; color:white; border:none; padding:5px 10px; border-radius:5px;">
                                Simpan
                            </button>
                        </form>
                    </td>
                    <td>
                        <form action="{{ route('admin.users.resetPassword', $user->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn" style="background-color:#1B263B; color:white; border:none; padding:5px 10px; border-radius:5px;">
                                Reset Password
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
