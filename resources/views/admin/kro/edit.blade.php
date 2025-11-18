@extends('admin.layout')

@section('title', 'Edit Value KRO')

@section('content')
<div class="header mb-4">
    <h1>Edit Value KRO</h1>
</div>

<form action="{{ isset($kro) ? route('admin.kro.update', $kro->id) : route('admin.kro.store') }}" method="POST">
    @csrf
    @if(isset($kro)) @method('PUT') @endif

    <div class="mb-2">
        <label>KRO</label> 
        <input type="text" name="kode" class="form-control" value="{{ $kro->kode ?? old('kode') }}">
    </div>

    <div class="mb-2">
        <label>Kode Akun</label>
        <input type="text" name="kode_akun" class="form-control" value="{{ $kro->kode_akun ?? old('kode_akun') }}">
    </div>

    <button type="submit" class="btn btn-success">{{ isset($kro) ? 'Update' : 'Simpan' }}</button>
</form>

@endsection
