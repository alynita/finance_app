@extends('admin.layout')

@section('title', 'Manajemen KRO')

@section('content')
<div class="header mb-4">
    <h1>Manajemen KRO</h1>
</div>

@if(session('success'))
    <div class="alert">{{ session('success') }}</div>
@endif

<!-- Tombol tambah KRO Top Level -->
<button id="btn-show-form" class="btn btn-primary">+ Tambah KRO Top Level</button>

<form id="form-tambah" style="display:none; margin-top:1rem; gap:0.5rem;">
    @csrf
    <input type="text" name="kode" placeholder="Kode KRO" required style="width:150px;">
    <input type="text" name="kode_akun" placeholder="Kode Akun (opsional)" style="width:150px;">
    <button type="submit" class="btn btn-success">Simpan</button>
    <button type="button" id="btn-cancel-form" class="btn btn-secondary">Batal</button>
</form>

<!-- Tree List -->
<ul class="tree" id="kro-list" style="margin-top:1rem;">
    @foreach($kro as $kroItem)
        @include('admin.kro.partials.kro-children', ['children' => [$kroItem]])
    @endforeach
</ul>

<style>
.tree, .nested { list-style-type: none; padding-left: 0; }
.nested { display: none; margin-left: 20px; }
.caret { cursor: pointer; user-select: none; }
.caret::before { content: "▶ "; display: inline-block; margin-right: 5px; transition: transform 0.2s; }
.caret-down::before { transform: rotate(90deg); }
li { margin-bottom: 10px; }
.kro-item { display: flex; align-items: center; gap: 10px; padding: 5px 0; }
.kro-buttons { margin-left: auto; display: flex; gap: 8px; }
.form-add-child { margin-top: 5px; display: flex; gap: 5px; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrf = '{{ csrf_token() }}';

    // Delegate click events
    document.getElementById('kro-list').addEventListener('click', function(e) {
        const li = e.target.closest('li');
        if(!li) return;

        // Dropdown
        if(e.target.classList.contains('caret')) {
            const nested = li.querySelector(".nested");
            if(nested) nested.style.display = nested.style.display === 'block' ? 'none' : 'block';
            e.target.classList.toggle('caret-down');
        }

        // Tambah child
        if(e.target.classList.contains('btn-add-child')) {
            if(li.querySelector('.form-add-child')) return;
            const formHtml = `
                <div class="form-add-child">
                    <input type="text" placeholder="Kode KRO" class="kode-input" required style="width:120px;">
                    <input type="text" placeholder="Kode Akun (opsional)" class="kode-akun-input" style="width:120px;">
                    <button class="btn-save-child btn btn-sm btn-success">Save</button>
                    <button class="btn-cancel-child btn btn-sm btn-secondary">Cancel</button>
                </div>
            `;
            li.insertAdjacentHTML('beforeend', formHtml);
        }

        // Cancel tambah child
        if(e.target.classList.contains('btn-cancel-child')) {
            e.target.closest('.form-add-child').remove();
        }

        // Save tambah child
        if(e.target.classList.contains('btn-save-child')) {
            const formDiv = e.target.closest('.form-add-child');
            const kode = formDiv.querySelector('.kode-input').value;
            const kode_akun = formDiv.querySelector('.kode-akun-input').value;

            fetch("{{ route('admin.kro.store') }}", {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                body: JSON.stringify({ kode, kode_akun, parent_id: li.dataset.id })
            }).then(res => res.json())
              .then(data => { 
                  if(data.success){ 
                      alert(data.message); 
                      location.reload(); 
                  } else alert('Gagal menambah KRO'); 
              })
              .catch(err => alert('Terjadi kesalahan'));
        }

        // Edit Kode
        if(e.target.classList.contains('btn-edit-kode')) {
            const kodeSpan = li.querySelector('.kode-span');
            const current = kodeSpan.dataset.original;
            kodeSpan.innerHTML = `<input type="text" value="${current}" style="width:100px;">
                <button class="btn-save btn btn-sm btn-success">Save</button>
                <button class="btn-cancel btn btn-sm btn-secondary">Cancel</button>`;
            kodeSpan.querySelector('.btn-cancel').addEventListener('click', () => kodeSpan.textContent = current);
            kodeSpan.querySelector('.btn-save').addEventListener('click', () => {
                const newKode = kodeSpan.querySelector('input').value;
                fetch(`/admin/kro/${li.dataset.id}`, {
                    method: 'PUT',
                    headers: { 'X-CSRF-TOKEN': csrf, 'Content-Type': 'application/json' },
                    body: JSON.stringify({ kode: newKode })
                }).then(res=>res.json()).then(()=> location.reload());
            });
        }

        // Edit Kode Akun
        if(e.target.classList.contains('btn-edit-akun')) {
            const akunSpan = li.querySelector('.akun-span');
            const current = akunSpan.dataset.original ?? '';
            akunSpan.innerHTML = `<input type="text" value="${current}" style="width:100px;">
                <button class="btn-save btn btn-sm btn-success">Save</button>
                <button class="btn-cancel btn btn-sm btn-secondary">Cancel</button>`;
            akunSpan.querySelector('.btn-cancel').addEventListener('click', () => akunSpan.textContent = current);
            akunSpan.querySelector('.btn-save').addEventListener('click', () => {
                const newAkun = akunSpan.querySelector('input').value;
                fetch(`/admin/kro/${li.dataset.id}`, {
                    method: 'PUT',
                    headers: { 'X-CSRF-TOKEN': csrf, 'Content-Type': 'application/json' },
                    body: JSON.stringify({ kode_akun: newAkun })
                }).then(res=>res.json()).then(()=> location.reload());
            });
        }

        // Hapus KRO
        if(e.target.classList.contains('btn-delete')) {
            if(confirm('Yakin ingin menghapus KRO ini?')) {
                fetch(`/admin/kro/${li.dataset.id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': csrf }
                }).then(res => res.json())
                    .then(data => {
                        if(data.success){
                            alert(data.message);
                            li.remove();
                        } else alert('Gagal menghapus KRO');
                    })
                    .catch(err => alert('Terjadi kesalahan'));
                }
        }
    });

    // Show top-level form
    const form = document.getElementById('form-tambah');
    const btnShow = document.getElementById('btn-show-form');
    const btnCancel = document.getElementById('btn-cancel-form');

    btnShow.addEventListener('click', () => {
        form.style.display = 'flex';
        btnShow.style.display = 'none';
    });
    btnCancel.addEventListener('click', () => {
        form.style.display = 'none';
        btnShow.style.display = 'inline-block';
    });

    // Save top-level KRO
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const data = { kode: this.kode.value, kode_akun: this.kode_akun.value, parent_id: null };
        fetch("{{ route('admin.kro.store') }}", {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json', 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        }).then(res => res.json())
            .then(data => { if(data.success){ alert(data.message); location.reload(); } })
            .catch(err => alert('Terjadi kesalahan'));
    });
});
</script>
@endsection
