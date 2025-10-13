@extends('layouts.app')

@section('title', 'Tambah Penanggung Jawab')
@section('header', 'Tambah Penanggung Jawab')

@section('content')
<div style="max-width:600px; margin:auto;">
    <form action="{{ route('pegawai.penanggung_jawab.store') }}" method="POST">
        @csrf
        <label>Nama</label>
        <input type="text" name="nama" style="width:100%; padding:0.5rem; margin-bottom:0.5rem;" required>

        <label>NIP</label>
        <input type="text" name="nip" style="width:100%; padding:0.5rem; margin-bottom:0.5rem;" required>

        <label>Jabatan</label>
        <input type="text" name="jabatan" style="width:100%; padding:0.5rem; margin-bottom:0.5rem;" required>

        <button type="submit" style="padding:0.5rem 1rem; background:#3490dc; color:white; border:none; border-radius:4px;">Simpan</button>
    </form>
</div>
@endsection
