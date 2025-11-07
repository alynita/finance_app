@extends('admin.layout')

@section('title', 'Manajemen KRO')

@section('content')
<div class="header mb-4">
    <h1>Manajemen KRO</h1>
</div>

@if(session('success'))
    <div class="alert">{{ session('success') }}</div>
@endif

<!-- Form tambah KRO -->
<form id="form-tambah" action="{{ route('admin.kro.store') }}" method="POST" style="margin-bottom:1rem;">
    @csrf
    <div style="display:flex; gap:1rem;">
        <input type="text" name="value" placeholder="kro/kode akun" required>
        <button type="submit" class="btn">+ Tambah KRO</button>
    </div>
</form>

<!-- Form search -->
<input type="text" id="search" placeholder="Cari KRO..." value="{{ request('search') }}" style="margin-bottom:1rem;">

<!-- Tabel KRO -->
<table id="kro-table">
    <thead>
        <tr>
            <th>No</th>
            <th>KRO/Kode Akun</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($kros as $index => $kro)
        <tr data-id="{{ $kro->id }}">
            <td>{{ $index + 1 }}</td>
            <td>
                <input type="text" class="value-input" value="{{ $kro->value }}">
                <button class="btn btn-update">Simpan</button>
            </td>
            <td>
                <button class="btn btn-delete" style="background:#ff5c5c;">Hapus</button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrf = '{{ csrf_token() }}';

    // AJAX Hapus
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function() {
            if (!confirm('Yakin ingin hapus KRO ini?')) return;

            const tr = this.closest('tr');
            const id = tr.getAttribute('data-id');

            fetch(`/admin/kro/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
            }).then(res => res.json())
                .then(() => tr.remove())
                .catch(err => alert('Gagal hapus KRO'));
        });
    });

    // AJAX Update
    document.querySelectorAll('.btn-update').forEach(btn => {
        btn.addEventListener('click', function() {
            const tr = this.closest('tr');
            const id = tr.getAttribute('data-id');
            const value = tr.querySelector('.value-input').value;

            fetch(`/admin/kro/${id}`, {
                method: 'PUT',
                headers: { 'X-CSRF-TOKEN': csrf, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ value })
            }).then(res => res.json())
                .then(data => alert('KRO berhasil diperbarui!'))
                .catch(err => alert('Gagal update KRO'));
        });
    });

    // AJAX Search
    const searchInput = document.getElementById('search');
    searchInput.addEventListener('input', function() {
        const keyword = this.value.toLowerCase();
        document.querySelectorAll('#kro-table tbody tr').forEach(tr => {
            const value = tr.querySelector('.value-input').value.toLowerCase();
            tr.style.display = value.includes(keyword) ? '' : 'none';
        });
    });
});
</script>
@endsection
