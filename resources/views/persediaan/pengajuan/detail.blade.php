@extends('layouts.app')

@section('content')
<h2 style="margin-bottom:10px;">Detail Pengajuan & Pengecekan Barang</h2>

<p style="margin-bottom:20px;">
    <b>Pengaju:</b> {{ $pengajuan->user->name ?? '-' }}
</p>

<style>
.status-btn {
    border:1px solid #ccc;
    padding:6px 12px;
    border-radius:20px;
    cursor:pointer;
    background:#f5f5f5;
    font-weight:600;
    font-size:13px;
}

.status-btn.active {
    color:white;
    border:none;
}

.status-ada { background:#2e7d32; }
.status-tidak { background:#c62828; }
.status-sebagian { background:#ef6c00; }

.btn-simpan {
    background:#1565c0;
    color:white;
    padding:6px 14px;
    border:none;
    border-radius:8px;
    cursor:pointer;
    font-weight:600;
}
</style>

<div style="
    background:#fff;
    padding:20px;
    border-radius:16px;
    box-shadow:0 10px 24px rgba(0,0,0,0.06);
">

    <div style="overflow-x:auto;">
        <table style="
            width:100%;
            border-collapse:separate;
            border-spacing:0 10px;
            min-width:600px;
        ">
            <thead>
                <tr>
                    <th style="padding:12px; text-align:left; color:#666;">No</th>
                    <th style="padding:12px; text-align:left; color:#666;">Barang</th>
                    <th style="padding:12px; text-align:left; color:#666;">Jumlah</th>
                    <th style="padding:12px; text-align:left; color:#666;">Status</th>
                    <th style="padding:12px; text-align:left; color:#666;">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @foreach($pengajuan->items as $item)
                <tr style="
                    background:#f9fafb;
                    border-radius:12px;
                    transition:0.2s;
                "
                onmouseover="this.style.background='#eef5ee'"
                onmouseout="this.style.background='#f9fafb'"
                >
                    <td style="padding:14px 12px; border-radius:12px 0 0 12px;">
                        {{ $loop->iteration }}
                    </td>

                    <td style="padding:14px 12px;">
                        {{ $item->nama_barang }}
                    </td>

                    <td style="padding:14px 12px;">
                        {{ $item->volume }}
                    </td>

                    <td style="padding:14px 12px;">
                        @if($item->status_persediaan === 'ADA')
                            <span style="
                                background:#e8f5e9;
                                color:#2e7d32;
                                padding:4px 10px;
                                border-radius:20px;
                                font-size:13px;
                                font-weight:600;
                            ">ADA</span>
                        @elseif($item->status_persediaan === 'TIDAK_ADA')
                            <span style="
                                background:#fdecea;
                                color:#c62828;
                                padding:4px 10px;
                                border-radius:20px;
                                font-size:13px;
                                font-weight:600;
                            ">TIDAK ADA</span>
                        @elseif($item->status_persediaan === 'SEBAGIAN')
                            <span style="
                                background:#fdecea;
                                color:#c62828;
                                padding:4px 10px;
                                border-radius:20px;
                                font-size:13px;
                                font-weight:600;
                            ">SEBAGIAN</span>
                        @else
                            <span style="
                                background:#eee;
                                color:#666;
                                padding:4px 10px;
                                border-radius:20px;
                                font-size:13px;
                            ">BELUM DICEK</span>
                        @endif
                    </td>

                    <td style="padding:14px 12px; border-radius:0 12px 12px 0;">
                        @if(is_null($item->status_persediaan))

                        <form action="{{ route('persediaan.item.updateStatus', $item->id) }}" method="POST">
                        @csrf

                        <input type="hidden" name="status" id="status-{{ $item->id }}">

                        <div style="display:flex; gap:6px; flex-wrap:wrap;">
                            <button type="button"
                                class="status-btn"
                                id="btn-ada-{{ $item->id }}"
                                onclick="pilihStatus({{ $item->id }}, 'ADA')">
                                ADA
                            </button>

                            <button type="button"
                                class="status-btn"
                                id="btn-tidak-{{ $item->id }}"
                                onclick="pilihStatus({{ $item->id }}, 'TIDAK_ADA')">
                                TIDAK ADA
                            </button>

                            <button type="button"
                                class="status-btn"
                                id="btn-sebagian-{{ $item->id }}"
                                onclick="pilihSebagian({{ $item->id }})">
                                SEBAGIAN
                            </button>
                        </div>

                        <div id="sebagian-box-{{ $item->id }}" style="display:none; margin-top:8px;">
                            <input
                                type="number"
                                name="jumlah_tersedia"
                                min="1"
                                max="{{ $item->volume }}"
                                placeholder="Jumlah tersedia"
                                style="
                                    width:120px;
                                    padding:6px 8px;
                                    border-radius:6px;
                                    border:1px solid #ccc;
                                "
                            >
                        </div>

                        <button type="submit" class="btn-simpan" style="margin-top:10px;">
                            Simpan
                        </button>

                        </form>

                        @else
                            <em style="color:#777;">Sudah dicek</em>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <hr style="margin:20px 0;">

    <form action="{{ route('persediaan.finalize', $pengajuan->id) }}" method="POST">
        @csrf
        <button type="submit" style="
            background:#1565c0;
            color:white;
            padding:10px 18px;
            border:none;
            border-radius:10px;
            cursor:pointer;
            font-weight:600;
        ">
            ✓ Selesai Cek Pengajuan
        </button>
    </form>

<script>
function resetButtons(id) {
    ['ada','tidak','sebagian'].forEach(t => {
        const btn = document.getElementById(`btn-${t}-${id}`);
        if (btn) btn.classList.remove(
            'active',
            'status-ada',
            'status-tidak',
            'status-sebagian'
        );
    });
}

function pilihStatus(id, status) {
    resetButtons(id);

    document.getElementById('status-' + id).value = status;
    document.getElementById('sebagian-box-' + id).style.display = 'none';

    const btn = document.getElementById(
        `btn-${status === 'ADA' ? 'ada' : 'tidak'}-${id}`
    );

    btn.classList.add(
        'active',
        status === 'ADA' ? 'status-ada' : 'status-tidak'
    );
}

function pilihSebagian(id) {
    resetButtons(id);

    document.getElementById('status-' + id).value = 'SEBAGIAN';
    document.getElementById('sebagian-box-' + id).style.display = 'block';

    const btn = document.getElementById(`btn-sebagian-${id}`);
    btn.classList.add('active','status-sebagian');
}
</script>



</div>
@endsection
