@extends('layouts.app')
@section('title', 'Pecah Pengajuan')
@section('content')

@php
    // pastikan $pengajuan dan $kroAll tersedia dari controller
@endphp

<div style="max-width:1100px; margin:auto;">
    <h3>Pecah Pengajuan / Approve Langsung: #{{ $pengajuan->id }}</h3>
    <p>Pengaju: {{ $pengajuan->user->name ?? '-' }} | Tanggal: {{ $pengajuan->created_at->format('d M Y') }}</p>
    <p>Status: {{ ucfirst($pengajuan->status) }}</p>
    <hr>

    {{-- -------------------------
         TABEL ITEM UTAMA (editable KRO)
         ------------------------- --}}
    <h4>Item Pengajuan</h4>
    <table border="1" cellpadding="6" style="width:100%; border-collapse:collapse; margin-bottom:10px;">
        <thead style="background:#f3f3f3;">
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Volume</th>
                <th>KRO / Kode Akun</th>
                <th>Harga Satuan</th>
                <th>Ongkos Kirim</th>
                <th>Jumlah Dana</th>
                <th>Link</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pengajuan->items as $index => $item)
            <tr data-item-id="{{ $item->id }}">
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->nama_barang }}</td>
                <td>{{ $item->volume }}</td>
                <td>
                    {{-- wrapper KRO (editable) --}}
                    <div class="kro-dropdown-wrapper" style="position:relative; display:inline-block;">
                        <input type="text" class="kro-input" readonly value="{{ $item->kro ?? '' }}" placeholder="Pilih KRO →" style="min-width:210px;">
                        <input type="hidden" class="kro-hidden" value="{{ $item->kro ?? '' }}">
                        <div class="kro-menu" style="display:none; position:absolute; background:#fff; border:1px solid #ccc; max-height:200px; overflow:auto; z-index:50; width:320px;"></div>
                    </div>
                    <div style="display:inline-block; margin-left:6px;">
                        <button type="button" class="btn-edit-kro">Edit</button>
                        <button type="button" class="btn-save-kro" style="display:none;">Simpan</button>
                    </div>
                </td>
                <td>{{ number_format($item->harga_satuan,0,',','.') }}</td>
                <td>{{ number_format($item->ongkos_kirim,0,',','.') }}</td>
                <td>{{ number_format($item->jumlah_dana_pengajuan,0,',','.') }}</td>
                <td>@if($item->link) <a href="{{ $item->link }}" target="_blank">Lihat</a> @else - @endif</td>
                <td>
                    <input type="text" name="catatan_ppk[{{ $item->id }}]" class="form-control" value="{{ $item->catatan_ppk ?? '' }}" placeholder="Isi catatan">
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <hr>

    {{-- -------------------------
         FORM PEMBUATAN GRUP (dinamis)
         ------------------------- --}}
    <form action="{{ route('ppk.storeGroup', $pengajuan->id) }}" method="POST" id="group-form">
        @csrf

        <h4>Buat Grup PPK</h4>
        <div id="groups-container"></div>

        <div style="margin-top:8px;">
            <button type="button" id="add-group" style="background:#2196F3; color:white; padding:8px 12px; border:none; border-radius:4px;">Tambah Grup</button>
        </div>

        <div style="margin-top:16px;">
            <label><input type="checkbox" name="approve_all" value="1"> Approve langsung semua tanpa pecah</label>
        </div>

        <div style="margin-top:12px;">
            <button type="submit" style="padding:8px 15px; background:#008CBA; color:white; border:none; border-radius:4px;">Submit</button>
        </div>
    </form>

    <hr>

    <h4>Grup yang sudah dibuat:</h4>
    @foreach($pengajuan->ppkGroups as $group)
        <div style="border:1px solid #ccc; padding:8px; margin-bottom:10px;">
            <strong>{{ $group->group_name }}</strong> -
            @if($group->status === 'pending_pengadaan')
                <span style="color:green;">APPROVED</span>
            @else
                <span style="color:red;">Pending</span>
                <form action="{{ route('ppk.approveGroup', $group->id) }}" method="POST" style="display:inline-block;">
                    @csrf
                    <button type="submit">Approve Grup</button>
                </form>
            @endif

            <ul style="margin-top:8px;">
                @foreach($group->items as $item)
                    <li>{{ $item->nama_barang }} ({{ $item->volume }}) — KRO: {{ $item->kro ?? '-' }}</li>
                @endforeach
            </ul>
        </div>
    @endforeach
</div>

{{-- =========================
SCRIPT (dinamis + sinkron)
========================= --}}
<script>
/**
 * Strategy:
 * - Use a single itemList (JS) initialised from server-side $pengajuan->items
 * - renderGroup() generates a group table using itemList, skipping items that are already selected
 * - when KRO is updated on main table (via AJAX), we update itemList and also update any .kro-display cells in all group tables
 * - prevent duplicate selection: when checkbox checked, sync other group tables to hide/disable that row
 */

const pengajuanType = "{{ $pengajuan->jenis_pengajuan }}";
let groupCount = 0;
let selectedItems = new Set();

// itemList = single source of truth for items (id, nama_barang, volume, kro, ongkos_kirim, jumlah_dana_pengajuan, etc.)
let itemList = @json($pengajuan->items->map(function($it){
    return [
        'id' => $it->id,
        'nama_barang' => $it->nama_barang,
        'volume' => $it->volume,
        'kro' => $it->kro,
        'ongkos_kirim' => $it->ongkos_kirim,
        'harga_satuan' => $it->harga_satuan,
        'jumlah_dana_pengajuan' => $it->jumlah_dana_pengajuan,
        'link' => $it->link,
        'catatan' => $it->catatan_ppk ?? '',
    ];
}));

// show initial one group by default
document.addEventListener('DOMContentLoaded', () => {
    renderGroup();           // render first empty group
    initKroDropdowns();      // init KRO dropdown/editing on main table
});

// show group form already visible by default in this page
// but keep a button if you want to show/hide externally
document.getElementById('add-group').addEventListener('click', function(){
    const newGroup = renderGroup();
    // after injecting group, sync UI so selected items removed from available list
    syncGroupRows();
    return newGroup;
});

function renderGroup(){
    const container = document.getElementById('groups-container');
    const groupDiv = document.createElement('div');
    groupDiv.classList.add('group');
    groupDiv.style.marginBottom = '15px';

    const idx = groupCount;
    groupCount++;

    let html = `
        <div style="display:flex; align-items:center; gap:10px;">
            <input type="text" name="group_name[]" placeholder="Nama Grup" required style="flex:1; padding:6px;">
            <button type="button" class="remove-group" style="background:#e74c3c;color:white;padding:6px 8px;border:none;border-radius:4px;">Hapus Grup</button>
        </div>

        <p style="font-weight:600; margin:8px 0;">Pilih item untuk grup ini:</p>

        <table border="1" cellpadding="6" style="width:100%; border-collapse:collapse; margin-bottom:10px;">
            <thead style="background:#f3f3f3;">
                <tr>
                    <th>Pilih</th>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Volume</th>
    `;

    if(pengajuanType === 'kerusakan'){
        html += '<th>Lokasi</th><th>Jenis Kerusakan</th>';
    } else {
        html += '<th>KRO / Kode Akun</th><th>Ongkos Kirim</th>';
    }

    html += '<th>Jumlah Dana</th></tr></thead><tbody>';

    // render rows from itemList, skip those already selected
    itemList.forEach((item, index) => {
        if(selectedItems.has(item.id)) return; // skip already chosen items
        html += `
            <tr data-item-id="${item.id}">
                <td style="text-align:center;">
                    <input type="checkbox" class="item-checkbox" value="${item.id}" name="groups[${idx}][]">
                </td>
                <td>${index + 1}</td>
                <td>${escapeHtml(item.nama_barang)}</td>
                <td>${escapeHtml(item.volume)}</td>
                ${
                    pengajuanType === 'kerusakan'
                    ? `<td>-</td><td>-</td>` // if you have lokasi/jns, you can map them in itemList
                    : `<td class="kro-display">${escapeHtml(item.kro ?? '-')}</td><td>${numberFormat(item.ongkos_kirim)}</td>`
                }
                <td>${numberFormat(item.jumlah_dana_pengajuan)}</td>
            </tr>
        `;
    });

    html += '</tbody></table>';

    groupDiv.innerHTML = html;
    container.appendChild(groupDiv);

    // attach events for checkboxes in this group
    groupDiv.querySelectorAll('.item-checkbox').forEach(cb => {
        cb.addEventListener('change', (e) => {
            const id = parseInt(cb.value);
            if(cb.checked){
                selectedItems.add(id);
            } else {
                selectedItems.delete(id);
            }
            // sync rows in all groups so selected items are hidden/disabled elsewhere
            syncGroupRows();
        });
    });

    // attach remove group
    groupDiv.querySelectorAll('.remove-group').forEach(btn => {
        btn.addEventListener('click', () => {
            // before remove, free any selected items inside this group
            groupDiv.querySelectorAll('.item-checkbox:checked').forEach(cb => {
                selectedItems.delete(parseInt(cb.value));
            });
            groupDiv.remove();
            syncGroupRows();
        });
    });

    return groupDiv;
}

function syncGroupRows(){
    // For each group, show only rows for items not selected OR kept in that group's checked box
    const allGroups = document.querySelectorAll('#groups-container .group');
    // collect currently selected items
    const selected = new Set([...selectedItems]);

    allGroups.forEach(group => {
        // for each row
        group.querySelectorAll('tr[data-item-id]').forEach(tr => {
            const itemId = parseInt(tr.dataset.itemId);
            const checkbox = tr.querySelector('.item-checkbox');
            if(!checkbox) return;

            // if this checkbox is checked, keep visible
            if(checkbox.checked) {
                tr.style.display = '';
                checkbox.disabled = false;
                return;
            }

            // if some other group selected the item, hide this row (so single-selection across groups)
            if(selected.has(itemId)) {
                tr.style.display = 'none';
            } else {
                tr.style.display = '';
                checkbox.disabled = false;
            }
        });
    });
}

/* ---------------------------
   KRO Dropdown & update logic
   --------------------------- */

/**
 * Initialize KRO dropdown controls on the page (main item table).
 * When user saves, we call endpoint to update DB and also update itemList in JS &
 * update any displayed .kro-display cells in group tables.
 */
const kroAllData = @json($kroAll);

function initKroDropdowns(context = document){
    // For each wrapper found under context
    context.querySelectorAll('.kro-dropdown-wrapper').forEach(wrapper => {
        // avoid double-binding: remove existing marker
        if(wrapper.dataset.kroInit === '1') return;
        wrapper.dataset.kroInit = '1';

        const input = wrapper.querySelector('.kro-input');
        const hiddenInput = wrapper.querySelector('.kro-hidden');
        const menu = wrapper.querySelector('.kro-menu');

        // buttons are siblings in DOM structure we created
        const row = wrapper.closest('tr');
        const editBtn = row.querySelector('.btn-edit-kro');
        const saveBtn = row.querySelector('.btn-save-kro');

        // Build tree inside menu (clear first)
        menu.innerHTML = '';

        function buildTreeNodes(data, parentEl, path = []) {
            data.forEach(node => {
                const currentLabel = node.kode_akun ?? node.kode;
                const newPath = [...path, currentLabel];

                const div = document.createElement('div');
                div.style.marginLeft = "10px";

                const toggle = document.createElement('span');
                toggle.textContent = node.children?.length ? "▸" : "";
                toggle.style.cursor = "pointer";
                toggle.style.marginRight = "6px";

                const label = document.createElement('span');
                label.textContent = currentLabel;
                label.style.cursor = "pointer";

                div.appendChild(toggle);
                div.appendChild(label);
                parentEl.appendChild(div);

                let childBox = null;
                if(node.children?.length) {
                    childBox = document.createElement('div');
                    childBox.style.display = "none";
                    childBox.style.marginLeft = "18px";
                    parentEl.appendChild(childBox);
                    buildTreeNodes(node.children, childBox, newPath);
                }

                toggle.addEventListener('click', (ev) => {
                    ev.stopPropagation();
                    if(!childBox) return;
                    childBox.style.display = childBox.style.display === "block" ? "none" : "block";
                    toggle.textContent = childBox.style.display === "block" ? "▾" : "▸";
                });

                label.addEventListener('click', (ev) => {
                    ev.stopPropagation();
                    if(newPath.length < 3){
                        if(childBox){
                            childBox.style.display = "block";
                            toggle.textContent = "▾";
                        }
                        return;
                    }
                    const finalVal = newPath.slice(2).join('/');
                    input.value = finalVal;
                    hiddenInput.value = finalVal;
                    menu.style.display = 'none';
                });
            });
        }

        buildTreeNodes(kroAllData, menu);

        // open edit
        editBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            input.readOnly = false;
            menu.style.display = 'block';
            editBtn.style.display = 'none';
            saveBtn.style.display = 'inline-block';
        });

        // click outside to close menu
        document.addEventListener('click', (e) => {
            if(!wrapper.contains(e.target) && !editBtn.contains(e.target)) menu.style.display = 'none';
        });

        // save KRO
        saveBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            input.readOnly = true;
            editBtn.style.display = 'inline-block';
            saveBtn.style.display = 'none';

            const itemId = row.dataset.itemId;
            const newValue = hiddenInput.value;

            fetch(`/ppk/update-kro/${itemId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ kro: newValue })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success){
                    // 1) update itemList (single source)
                    const idx = itemList.findIndex(i => parseInt(i.id) == parseInt(itemId));
                    if(idx !== -1){
                        itemList[idx].kro = newValue;
                    }

                    // 2) update any visible kro-display cells in groups (so future render/use shows latest)
                    document.querySelectorAll(`.kro-display`).forEach(el => {
                        // find parent row to determine which item
                        const tr = el.closest('tr[data-item-id]');
                        if(!tr) return;
                        const id = parseInt(tr.dataset.itemId);
                        if(id == itemId) {
                            el.textContent = newValue;
                        }
                    });

                    // 3) also update the main table's input (already set) — no reload needed
                    console.log('KRO updated:', newValue);
                } else {
                    console.error('Gagal update KRO', data);
                }
            })
            .catch(err => {
                console.error('Fetch error:', err);
            });
        });
    });
}

// helper: basic escape
function escapeHtml(text){
    if(text === null || text === undefined) return '';
    return String(text)
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

function numberFormat(val){
    if(val === null || val === undefined) return '-';
    const n = Number(val);
    if(isNaN(n)) return val;
    return n.toLocaleString('id-ID');
}
</script>

@endsection
