@extends('admin.layout')

@section('title', 'Edit Value KRO')

@section('content')
<div class="header mb-4">
    <h1>Edit Value KRO</h1>
</div>

<form action="{{ route('admin.kro.update', $kro->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div style="display:flex; gap:1rem;">
        <input type="text" name="value" value="{{ $kro->value }}" required>
        <button type="submit" class="btn">Simpan</button>
        <a href="{{ route('admin.kro.index') }}" class="btn" style="background:#ccc;">Batal</a>
    </div>
</form>
@endsection
