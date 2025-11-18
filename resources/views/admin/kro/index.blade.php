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

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Kode</th>
            <th>Kode Akun</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($kro as $kro)
        <tr data-id="{{ $kro->id }}">
            <td class="kode-text">{{ $kro->kode }}</td>
            <td class="kode-akun-text">{{ $kro->kode_akun }}</td>
            <td>
                <button class="btn-edit-kode btn btn-sm btn-warning">Edit Kode</button>
                <button class="btn-edit-akun btn btn-sm btn-info">Edit Kode Akun</button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrf = '{{ csrf_token() }}';

    // Edit Kode
    document.querySelectorAll('.btn-edit-kode').forEach(btn => {
        btn.addEventListener('click', function() {
            const tr = this.closest('tr');
            const td = tr.querySelector('.kode-text');
            const currentValue = td.textContent.trim();

            // Ganti ke input
            td.innerHTML = `<input type="text" value="${currentValue}" class="form-control kode-input" style="width:120px; display:inline-block;">
                            <button class="btn-save btn btn-sm btn-success">Save</button>
                            <button class="btn-cancel btn btn-sm btn-secondary">Cancel</button>`;

            // Save
            td.querySelector('.btn-save').addEventListener('click', function() {
                const newValue = td.querySelector('.kode-input').value;
                const id = tr.dataset.id;

                fetch(`/admin/kro/${id}`, {
                    method: 'PUT',
                    headers: { 
                        'X-CSRF-TOKEN': csrf,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ kode: newValue })
                }).then(res => res.json())
                  .then(data => {
                      td.textContent = newValue;
                  }).catch(err => alert('Gagal update Kode'));
            });

            // Cancel
            td.querySelector('.btn-cancel').addEventListener('click', function() {
                td.textContent = currentValue;
            });
        });
    });

    // Edit Kode Akun
    document.querySelectorAll('.btn-edit-akun').forEach(btn => {
        btn.addEventListener('click', function() {
            const tr = this.closest('tr');
            const td = tr.querySelector('.kode-akun-text');
            const currentValue = td.textContent.trim();

            td.innerHTML = `<input type="text" value="${currentValue}" class="form-control kode-akun-input" style="width:120px; display:inline-block;">
                            <button class="btn-save btn btn-sm btn-success">Save</button>
                            <button class="btn-cancel btn btn-sm btn-secondary">Cancel</button>`;

            td.querySelector('.btn-save').addEventListener('click', function() {
                const newValue = td.querySelector('.kode-akun-input').value;
                const id = tr.dataset.id;

                fetch(`/admin/kro/${id}`, {
                    method: 'PUT',
                    headers: { 
                        'X-CSRF-TOKEN': csrf,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ kode_akun: newValue })
                }).then(res => res.json())
                    .then(data => {
                        td.textContent = newValue;
                    }).catch(err => alert('Gagal update Kode Akun'));
            });

            td.querySelector('.btn-cancel').addEventListener('click', function() {
                td.textContent = currentValue;
            });
        });
    });
});
</script>

@endsection
