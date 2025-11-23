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
                                
                                <!--KETUA TIMKER-->
                                <option value="timker_1" {{ $user->role == 'timker_1' ? 'selected' : '' }}>Timker 1</option>
                                <option value="timker_2" {{ $user->role == 'timker_2' ? 'selected' : '' }}>Timker 2</option>
                                <option value="timker_3" {{ $user->role == 'timker_3' ? 'selected' : '' }}>Timker 3</option>
                                <option value="timker_4" {{ $user->role == 'timker_4' ? 'selected' : '' }}>Timker 4</option>
                                <option value="timker_5" {{ $user->role == 'timker_5' ? 'selected' : '' }}>Timker 5</option>
                                <option value="timker_6" {{ $user->role == 'timker_6' ? 'selected' : '' }}>Timker 6</option>

                                <!-- ANGGOTA TIMKER -->
                                <option value="anggota_timker_1" {{ $user->role == 'anggota_timker_1' ? 'selected' : '' }}>Anggota Timker 1</option>
                                <option value="anggota_timker_2" {{ $user->role == 'anggota_timker_2' ? 'selected' : '' }}>Anggota Timker 2</option>
                                <option value="anggota_timker_3" {{ $user->role == 'anggota_timker_3' ? 'selected' : '' }}>Anggota Timker 3</option>
                                <option value="anggota_timker_4" {{ $user->role == 'anggota_timker_4' ? 'selected' : '' }}>Anggota Timker 4</option>
                                <option value="anggota_timker_5" {{ $user->role == 'anggota_timker_5' ? 'selected' : '' }}>Anggota Timker 5</option>
                                <option value="anggota_timker_6" {{ $user->role == 'anggota_timker_6' ? 'selected' : '' }}>Anggota Timker 6</option>

                                <option value="sarpras" {{ $user->role == 'sarpras' ? 'selected' : '' }}>Sarpras</option>
                                <option value="bmn" {{ $user->role == 'bmn' ? 'selected' : '' }}>BMN</option>
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
