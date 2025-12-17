@extends('layouts.app')

@section('content')
<h2>Form Pengeluaran Barang</h2>

<form action="{{ route('persediaan.pengeluaran.store') }}" method="POST">
@csrf

<input type="hidden" name="pengajuan_id" value="{{ $pengajuan->id }}">

<p>
    Kode Pengeluaran <br>
    <input type="text" name="kode_pengeluaran" value="{{ $kodePengeluaran }}" readonly>
</p>

<p>
    Bidang / Bagian <br>
    <input type="text" name="bidang_bagian">
</p>

<p>
    Nama Penyerah <br>
    <input type="text" name="nama_penyerah">
</p>

<hr>

<h3>Daftar Barang Dikeluarkan</h3>

<table border="1" width="100%" cellpadding="6">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Barang</th>
            <th>Jumlah</th>
            <th>Harga Satuan</th>
            <th>Total</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($pengajuan->items as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>

                <td>
                    {{ $item->nama_barang }}
                    <input type="hidden" name="items[{{ $index }}][pengajuan_item_id]" value="{{ $item->id }}">
                    <input type="hidden" name="items[{{ $index }}][nama_barang]" value="{{ $item->nama_barang }}">
                </td>

                <td>
                    <input 
                        type="number" 
                        class="jumlah-input"
                        name="items[{{ $index }}][jumlah]" 
                        value="{{ $item->volume }}" 
                        min="1"
                    >
                </td>

                <td>
                    <input 
                        type="number" 
                        class="harga-input"
                        name="items[{{ $index }}][harga_satuan]" 
                        value="{{ $item->harga_satuan ?? 0 }}"
                    >
                </td>

                <td>
                    <input 
                        type="number" 
                        class="total-input"
                        name="items[{{ $index }}][total]" 
                        value="{{ $item->jumlah_dana_pengajuan ?? 0 }}"
                        readonly
                    >
                </td>

                <td>
                    <input
                        type="number"
                        name="items[{{ $index }}][jumlah_tersedia]"
                        value="{{ $item->jumlah_tersedia }}"
                        readonly
                    >
                </td>

                <td>
                    <input 
                        type="text" 
                        name="items[{{ $index }}][keterangan]"
                    >
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" align="center">
                    Tidak ada barang yang dikeluarkan
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

<br>
<button type="submit" style="
    background:#1565c0;
    color:white;
    padding:10px 16px;
    border:none;
    border-radius:8px;
    font-weight:600;
">
    💾 Simpan Draft Pengeluaran
</button>

</form>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('tbody tr').forEach(row => {
        const jumlahInput = row.querySelector('.jumlah-input');
        const hargaInput  = row.querySelector('.harga-input');
        const totalInput  = row.querySelector('.total-input');

        if (!jumlahInput || !hargaInput || !totalInput) return;

        function hitungTotal() {
            const jumlah = parseFloat(jumlahInput.value) || 0;
            const harga  = parseFloat(hargaInput.value) || 0;
            totalInput.value = jumlah * harga;
        }

        jumlahInput.addEventListener('input', hitungTotal);
        hargaInput.addEventListener('input', hitungTotal);
    });
});
</script>
@endsection
