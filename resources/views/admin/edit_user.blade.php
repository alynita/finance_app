@extends('admin.layout')

@section('title', 'Edit Role Pengguna')

@section('content')
<div class="header">
    <h1>Edit Role: {{ $user->name }}</h1>
</div>

<div class="card">
    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        <label for="role">Role:</label>
        <select name="role" id="role">
            @foreach($roles as $role)
                <option value="{{ $role }}" @if($user->role == $role) selected @endif>{{ ucfirst($role) }}</option>
            @endforeach
        </select>
        <button type="submit">Simpan</button>
    </form>
</div>
@endsection
