@extends('layouts.app')

@section('title', 'Detail Pengajuan Pengadaan')
@section('header', 'Detail Pengajuan Pengadaan')

@section('content')
<div style="max-width:1300px; margin:auto; padding:1rem;">
    <h3 style="margin-bottom:0.5rem; font-size:22px;">
        Detail Pengadaan: 
        <span style="color:#007bff;">{{ $group->group_name }}</span>
    </h3>
    <p style="font-size:16px;"><strong>Pengajuan:</strong> {{ $group->pengajuan->nama_kegiatan }}</p>
    <p style="font-size:16px;"><strong>Pengaju:</strong> {{ $group->pengajuan->user->name }}</p>
    <p style="font-size:16px;"><strong>Status:</strong> {{ ucfirst(str_replace('_',' ',$group->status)) }}</p>

    <!-- Tombol Edit -->
    <button type="button" id="editButton"
        style="margin:0.8rem 0; padding:8px 16px; background:#ffc107; color:black; border:none; border-radius:5px; cursor:pointer; font-size:15px;">
        ✏️ Edit
    </button>

    <div style="overflow-x:auto; background:#fff; border-radius:10px; box-shadow:0 2px 8px rgba(0,0,0,0.1); padding:1.2rem;">
        <form action="{{ route('pengadaan.updateItems', $group->id) }}" method="POST" id="editForm">
            @csrf
            <table style="width:100%; border-collapse:collapse; min-width:1000px;">
                <thead style="background:#007bff; color:white; font-size:15px;">
                    <tr>
                        <th style="padding:10px;">No</th>
                        <th style="padding:10px;">Nama Barang</th>
                        <th style="padding:10px;">Volume</th>
                        @if($group->pengajuan->jenis_pengajuan === 'pembelian')
                            <th style="padding:10px;">KRO / Kode Akun</th>
                            <th style="padding:10px;">Harga Satuan</th>
                            <th style="padding:10px;">Ongkos Kirim</th>
                            <th style="padding:10px;">Jumlah Dana</th>
                            <th style="padding:10px;">Link</th>
                        @else($group->pengajuan->jenis_pengajuan === 'kerusakan')
                            <th style="padding:10px;">Harga Satuan</th>
                            <th style="padding:10px;">Jumlah Dana</th>
                            <th style="padding:10px;">Lokasi</th>
                            <th style="padding:10px;">Jenis Kerusakan</th>
                            <th style="padding:10px;">Foto</th>
                        @endif
                    </tr>
                </thead>
                <tbody style="font-size:15px;">
                    @foreach($group->items as $index => $item)
                        <tr style="background:{{ $index % 2 == 0 ? '#f9f9f9' : '#fff' }}; transition:background 0.2s;"
                            onmouseover="this.style.background='#eaf3ff';" onmouseout="this.style.background='{{ $index % 2 == 0 ? '#f9f9f9' : '#fff' }}';">
                            <td style="border-bottom:1px solid #ddd; padding:0.6rem; text-align:center;">{{ $index + 1 }}</td>
                            <td style="border-bottom:1px solid #ddd; padding:0.6rem;">{{ $item->nama_barang }}</td>
                            <td style="border-bottom:1px solid #ddd; padding:0.6rem;">
                                <span class="text-view">{{ $item->volume }}</span>
                                <input type="number" class="form-input" name="items[{{ $item->id }}][volume]" 
                                    value="{{ $item->volume }}" min="1" style="width:100%; padding:0.3rem; display:none;">
                            </td>

                            @if($group->pengajuan->jenis_pengajuan === 'pembelian')
                                <td style="border-bottom:1px solid #ddd; padding:0.6rem;">
                                    <span class="text-view">{{ $item->kro ?? '-' }}</span>
                                    <input type="text" name="items[{{ $item->id }}][kro]" 
                                        value="{{ $item->kro }}" 
                                        style="width:100%; padding:0.3rem; display:none;">
                                </td>
                                <td style="border-bottom:1px solid #ddd; padding:0.6rem;">
                                    <span class="text-view">{{ number_format($item->harga_satuan, 0, ',', '.') }}</span>
                                    <input type="number" class="form-input harga_satuan" 
                                        name="items[{{ $item->id }}][harga_satuan]" value="{{ $item->harga_satuan }}" 
                                        style="width:100%; padding:0.3rem; display:none;">
                                </td>
                                <td style="border-bottom:1px solid #ddd; padding:0.6rem;">
                                    <span class="text-view">{{ number_format($item->ongkos_kirim, 0, ',', '.') }}</span>
                                    <input type="number" class="form-input ongkos_kirim" 
                                        name="items[{{ $item->id }}][ongkos_kirim]" value="{{ $item->ongkos_kirim }}" 
                                        style="width:100%; padding:0.3rem; display:none;">
                                </td>
                                <td style="border-bottom:1px solid #ddd; padding:0.6rem; text-align:right;">
                                    <span class="text-view">{{ number_format($item->jumlah_dana_pengajuan, 0, ',', '.') }}</span>
                                    <input type="number" class="form-input jumlah_dana" 
                                        name="items[{{ $item->id }}][jumlah_dana_pengajuan]" 
                                        value="{{ $item->jumlah_dana_pengajuan }}" readonly 
                                        style="width:100%; padding:0.3rem; display:none; background:#f0f0f0;">
                                </td>
                                <td style="border-bottom:1px solid #ddd; padding:0.6rem; text-align:center;">
                                    @if($item->link)
                                        <a href="{{ $item->link }}" target="_blank">Buka Link</a>
                                    @else
                                        -
                                    @endif
                                </td>
                            @else($group->pengajuan->jenis_pengajuan === 'kerusakan')
                                <td style="border-bottom:1px solid #ddd; padding:0.6rem;">
                                    <span class="text-view">{{ number_format($item->harga_satuan, 0, ',', '.') }}</span>
                                    <input type="number" class="form-input harga_satuan" 
                                        name="items[{{ $item->id }}][harga_satuan]" value="{{ $item->harga_satuan }}" 
                                        style="width:100%; padding:0.3rem; display:none;">
                                </td>
                                <td style="border-bottom:1px solid #ddd; padding:0.6rem; text-align:right;">
                                    <span class="text-view">{{ number_format($item->jumlah_dana_pengajuan, 0, ',', '.') }}</span>
                                    <input type="number" class="form-input jumlah_dana" 
                                        name="items[{{ $item->id }}][jumlah_dana_pengajuan]" 
                                        value="{{ $item->jumlah_dana_pengajuan }}" readonly 
                                        style="width:100%; padding:0.3rem; display:none; background:#f0f0f0;">
                                </td>
                                <td style="border-bottom:1px solid #ddd; padding:0.6rem;">
                                    <span class="text-view">{{ $item->lokasi ?? '-' }}</span>
                                    <input type="text" name="items[{{ $item->id }}][lokasi]" 
                                        value="{{ $item->lokasi }}" 
                                        style="width:100%; padding:0.3rem; display:none;">
                                </td>
                                <td style="border-bottom:1px solid #ddd; padding:0.6rem;">
                                    <span class="text-view">{{ $item->jenis_kerusakan ?? '-' }}</span>
                                    <input type="text" name="items[{{ $item->id }}][jenis_kerusakan]" 
                                        value="{{ $item->jenis_kerusakan }}" 
                                        style="width:100%; padding:0.3rem; display:none;">
                                </td>
                                <td style="border:1px solid #ccc; padding:0.5rem;">
                                    @if($item->foto)
                                        <a href="{{ asset($item->foto) }}" target="_blank">Lihat Foto</a>
                                    @else
                                        -
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <button type="submit" id="saveButton"
                style="display:none; margin-top:1rem; padding:8px 16px; background:#007bff; color:white; border:none; border-radius:5px; cursor:pointer; font-size:15px;">
                💾 Simpan Perubahan
            </button>
        </form>
    </div>

    <form action="{{ route('pengadaan.submit', $group->id) }}" method="POST" style="margin-top:1.5rem;">
        @csrf
        <button type="submit" 
            style="padding:8px 16px; background:#28a745; color:white; border:none; border-radius:5px; cursor:pointer; font-size:15px;">
            🚀 Submit
        </button>
    </form>
</div>

<script>
// Klik tombol Edit
document.getElementById('editButton').addEventListener('click', function() {
    document.querySelectorAll('.text-view').forEach(el => el.style.display = 'none');
    document.querySelectorAll('.form-input').forEach(el => el.style.display = 'inline-block');
    document.getElementById('saveButton').style.display = 'inline-block';
    this.style.display = 'none';
});

// Hitung otomatis jumlah dana
document.querySelectorAll('tr').forEach(row => {
    const volume = row.querySelector('input[name*="[volume]"]');
    const harga = row.querySelector('input[name*="[harga_satuan]"]');
    const ongkos = row.querySelector('input[name*="[ongkos_kirim]"]');
    const jumlah = row.querySelector('input[name*="[jumlah_dana_pengajuan]"]');

    if(volume && harga && jumlah){
        [volume, harga, ongkos].forEach(input => {
            if(input){
                input.addEventListener('input', () => {
                    const v = parseFloat(volume.value) || 0;
                    const h = parseFloat(harga.value) || 0;
                    const o = ongkos ? parseFloat(ongkos.value) || 0 : 0;
                    jumlah.value = v * h + o;
                });
            }
        });
    }
});
</script>
@endsection
