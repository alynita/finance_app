@extends('layouts.app')

@section('title', 'Detail Pengajuan Pengadaan')
@section('header', 'Detail Pengajuan Pengadaan')

@section('content')
<div style="max-width:1200px; margin:auto;">
    <h3 style="margin-bottom:0.5rem;">Detail Pengadaan: <span style="color:#007bff;">{{ $group->group_name }}</span></h3>
    <p><strong>Pengajuan:</strong> {{ $group->pengajuan->nama_kegiatan }}</p>
    <p><strong>Pengaju:</strong> {{ $group->pengajuan->user->name }}</p>
    <p><strong>Status:</strong> {{ ucfirst(str_replace('_',' ',$group->status)) }}</p>

    <div style="overflow-x:auto; margin-top:1rem; background:#fff; border-radius:8px; box-shadow:0 2px 5px rgba(0,0,0,0.1); padding:1rem;">
        <form action="{{ route('pengadaan.updateItems', $group->id) }}" method="POST" id="editForm">
            @csrf
            <table style="width:100%; border-collapse:collapse; min-width:900px; text-align:left;">
                <thead style="background:#007bff; color:white;">
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Volume</th>
                        @if($group->pengajuan->jenis_pengajuan === 'pembelian')
                            <th>KRO / Kode Akun</th>
                            <th>Harga Satuan</th>
                            <th>Ongkos Kirim</th>
                            <th>Jumlah Dana</th>
                            <th>Link</th>
                        @else
                            <th>Harga Satuan</th>
                            <th>Jumlah Dana</th>
                            <th>Lokasi</th>
                            <th>Jenis Kerusakan</th>
                            <th>Foto</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($group->items as $index => $item)
                        <tr style="background:{{ $index % 2 == 0 ? '#f9f9f9' : '#fff' }};">
                            <td style="border:1px solid #ccc; padding:0.5rem; text-align:center;">{{ $index + 1 }}</td>
                            <td style="border:1px solid #ccc; padding:0.5rem;">{{ $item->nama_barang }}</td>
                            <td style="border:1px solid #ccc; padding:0.8rem;">
                                <input type="number" class="volume" name="items[{{ $item->id }}][volume]" value="{{ $item->volume }}" min="1" style="width:100%; padding:0.1rem;">
                            </td>

                            @if($group->pengajuan->jenis_pengajuan === 'pembelian')
                                <td style="border:1px solid #ccc; padding:0.8rem;">
                                    <input type="text" name="items[{{ $item->id }}][kro]" value="{{ $item->kro }}" style="width:100%; padding:0.1rem;">
                                </td>
                                <td style="border:1px solid #ccc; padding:0.8rem;">
                                    <input type="number" class="harga_satuan" name="items[{{ $item->id }}][harga_satuan]" value="{{ $item->harga_satuan }}" style="width:100%; padding:0.1rem;">
                                </td>
                                <td style="border:1px solid #ccc; padding:0.8rem;">
                                    <input type="number" class="ongkos_kirim" name="items[{{ $item->id }}][ongkos_kirim]" value="{{ $item->ongkos_kirim }}" style="width:100%; padding:0.1rem;">
                                </td>
                                <td style="border:1px solid #ccc; padding:0.8rem; text-align:right;">
                                    <input type="number" class="jumlah_dana" name="items[{{ $item->id }}][jumlah_dana_pengajuan]" value="{{ $item->jumlah_dana_pengajuan }}" readonly style="width:100%; padding:0.1rem; background:#f0f0f0;">
                                </td>
                                <td style="border:1px solid #ccc; padding:0.8rem;">
                                    <input type="text" name="items[{{ $item->id }}][link]" value="{{ $item->link }}" style="width:100%; padding:0.1rem;">
                                </td>
                            @else
                                <td style="border:1px solid #ccc; padding:0.8rem;">
                                    <input type="number" class="harga_satuan" name="items[{{ $item->id }}][harga_satuan]" value="{{ $item->harga_satuan }}" style="width:100%; padding:0.1rem;">
                                </td>
                                <td style="border:1px solid #ccc; padding:0.8rem; text-align:right;">
                                    <input type="number" class="jumlah_dana" name="items[{{ $item->id }}][jumlah_dana_pengajuan]" value="{{ $item->jumlah_dana_pengajuan }}" readonly style="width:100%; padding:0.1rem; background:#f0f0f0;">
                                </td>
                                <td style="border:1px solid #ccc; padding:0.8rem;">
                                    <input type="text" name="items[{{ $item->id }}][lokasi]" value="{{ $item->lokasi }}" style="width:100%; padding:0.1rem;">
                                </td>
                                <td style="border:1px solid #ccc; padding:0.8rem;">
                                    <input type="text" name="items[{{ $item->id }}][jenis_kerusakan]" value="{{ $item->jenis_kerusakan }}" style="width:100%; padding:0.1rem;">
                                </td>
                                <td style="border:1px solid #ccc; padding:0.8rem;">
                                    <input type="text" name="items[{{ $item->id }}][foto]" value="{{ $item->foto }}" style="width:100%; padding:0.1rem;">
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <button type="submit" style="margin-top:1rem; padding:8px 16px; background:#007bff; color:white; border:none; border-radius:5px; cursor:pointer;">
                💾 Simpan Perubahan
            </button>
        </form>
    </div>

    <form action="{{ route('pengadaan.submit', $group->id) }}" method="POST" style="margin-top:1.5rem;">
        @csrf
        <button type="submit" style="padding:8px 16px; background:#28a745; color:white; border:none; border-radius:5px; cursor:pointer;">
            🚀 Submit
        </button>
    </form>
</div>

<script>
document.querySelectorAll('tr').forEach(row => {
    const volume = row.querySelector('.volume');
    const harga = row.querySelector('.harga_satuan');
    const ongkos = row.querySelector('.ongkos_kirim');
    const jumlah = row.querySelector('.jumlah_dana');

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
