@extends('layouts.app')

@section('title', 'Proses Keuangan')
@section('header', 'Proses Keuangan')

@section('content')
<div style="max-width:900px; margin:auto; background:#fff; padding:25px; border-radius:10px; box-shadow:0 2px 10px rgba(0,0,0,0.1); font-family:'Segoe UI', sans-serif;">

    @if(session('success'))
        <div style="background-color:#d4edda; color:#155724; padding:12px 15px; margin-bottom:20px; border-radius:6px; border:1px solid #c3e6cb;">
            {{ session('success') }}
        </div>
    @endif

    <div style="margin-bottom:20px;">
        <p style="font-size:16px; margin-bottom:6px;"><strong>Pengajuan:</strong> {{ $group->pengajuan->nama_kegiatan }}</p>
        <p style="font-size:16px;"><strong>Pengaju:</strong> {{ $group->pengajuan->user->name }}</p>
    </div>

    <form action="{{ route('keuangan.storeProses', $group->id) }}" method="POST">
        @csrf

        <div style="margin-bottom:25px;">
            <label style="font-weight:600; display:block; margin-bottom:6px;">Kode Akun</label>
            <input type="text" name="kode_akun" value="{{ $group->kode_akun ?? '' }}" 
                style="width:300px; padding:10px; border:1px solid #ccc; border-radius:6px;">
        </div>

        @foreach($group->items as $index => $item)
        @php
            $jumlahDecimal = $item->jumlah_dana_pengajuan ?? 0;
            $pph21Decimal = $item->pph21 ?? 0;
            $pph22Decimal = $item->pph22 ?? 0;
            $pph23Decimal = $item->pph23 ?? 0;
            $ppnDecimal   = $item->ppn ?? 0;
        @endphp

        <div style="border:1px solid #dee2e6; border-radius:8px; padding:20px; margin-bottom:25px; background:#f8f9fa;">
            <h4 style="margin-bottom:15px; color:#333; border-bottom:2px solid #007bff; display:inline-block; padding-bottom:4px;">Item #{{ $index + 1 }}</h4>

            <input type="hidden" name="items[{{ $item->id }}][id]" value="{{ $item->id }}">

            <div style="margin-bottom:10px;">
                <label>Nama/Nomor Invoice</label>
                <input type="text" name="items[{{ $item->id }}][invoice]" value="{{ $item->invoice ?? '' }}" 
                    style="width:100%; padding:8px; border:1px solid #ccc; border-radius:5px;">
            </div>

            <div style="margin-bottom:10px;">
                <label>Nama Barang</label>
                <input type="text" name="items[{{ $item->id }}][nama_barang]" value="{{ $item->nama_barang }}" readonly 
                    style="width:100%; padding:8px; border:1px solid #ccc; border-radius:5px; background:#e9ecef;">
            </div>

            <div style="margin-bottom:10px;">
                <label>Detail Akun</label>
                <input type="text" name="items[{{ $item->id }}][detail_akun]" value="{{ $item->detail_akun ?? '' }}" 
                    style="width:100%; padding:8px; border:1px solid #ccc; border-radius:5px;">
            </div>

            <div style="margin-bottom:10px;">
                <label>Uraian</label>
                <textarea name="items[{{ $item->id }}][uraian]" rows="2" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:5px;">{{ $item->uraian ?? '' }}</textarea>
            </div>

            <div style="margin-bottom:15px;">
                <label>Jumlah Pengajuan</label><br>
                <input type="text" class="jumlah" data-index="{{ $index }}" 
                    name="items[{{ $item->id }}][jumlah_dana_pengajuan]" 
                    value="{{ number_format($jumlahDecimal,0,',','.') }}" 
                    style="width:220px; padding:8px; border:1px solid #ccc; border-radius:5px;">
            </div>

            <div style="display:flex; gap:20px; align-items:center; margin-bottom:10px;">
                <div>
                    <label>PPH 21 (%)</label><br>
                    <select class="pph21-select" data-index="{{ $index }}" style="padding:6px; border:1px solid #ccc; border-radius:5px;">
                        <option value="0" {{ $pph21Decimal==0?'selected':'' }}>0%</option>
                        <option value="0.05" {{ $pph21Decimal>0 && round($pph21Decimal/$jumlahDecimal,2)==0.05?'selected':'' }}>5%</option>
                        <option value="0.15" {{ $pph21Decimal>0 && round($pph21Decimal/$jumlahDecimal,2)==0.15?'selected':'' }}>15%</option>
                        <option value="manual" {{ $pph21Decimal>0 && !in_array(round($pph21Decimal/$jumlahDecimal,2), [0.05,0.15])?'selected':'' }}>Manual</option>
                    </select>
                    <input type="number" min="0" max="100" class="pph21-manual" data-index="{{ $index }}" 
                        style="width:80px; padding:4px; border:1px solid #ccc; border-radius:5px; margin-top:4px; display:none;" 
                        placeholder="%" value="{{ $pph21Decimal>0 && !in_array(round($pph21Decimal/$jumlahDecimal,2), [0.05,0.15])?round($pph21Decimal/$jumlahDecimal*100,2):'' }}">
                </div>
                <div>
                    <label><input type="checkbox" class="pph22-check" data-index="{{ $index }}" {{ $pph22Decimal>0?'checked':'' }}> PPH 22</label><br>
                    <label><input type="checkbox" class="pph23-check" data-index="{{ $index }}" {{ $pph23Decimal>0?'checked':'' }}> PPH 23</label><br>
                    <label><input type="checkbox" class="ppn-check" data-index="{{ $index }}" {{ $ppnDecimal>0?'checked':'' }}> PPN</label><br>
                    <label><input type="checkbox" class="npwp-check" data-index="{{ $index }}" {{ $pph22Decimal>0 || $pph23Decimal>0?'checked':'' }}> NPWP</label>
                </div>
            </div>

            <div style="display:grid; grid-template-columns: repeat(auto-fit,minmax(150px,1fr)); gap:15px;">
                <div>
                    <label>PPH 21 (Rp)</label>
                    <input type="text" name="items[{{ $item->id }}][hasil_pph21]" class="hasil_pph21" data-index="{{ $index }}" readonly style="width:100%; padding:6px; border:1px solid #ccc; border-radius:5px; background:#f1f3f5;">
                </div>
                <div>
                    <label>PPH 22 (Rp)</label>
                    <input type="text" name="items[{{ $item->id }}][hasil_pph22]" class="hasil_pph22" data-index="{{ $index }}" readonly style="width:100%; padding:6px; border:1px solid #ccc; border-radius:5px; background:#f1f3f5;">
                </div>
                <div>
                    <label>PPH 23 (Rp)</label>
                    <input type="text" name="items[{{ $item->id }}][hasil_pph23]" class="hasil_pph23" data-index="{{ $index }}" readonly style="width:100%; padding:6px; border:1px solid #ccc; border-radius:5px; background:#f1f3f5;">
                </div>
                <div>
                    <label>PPN (Rp)</label>
                    <input type="text" name="items[{{ $item->id }}][hasil_ppn]" class="hasil_ppn" data-index="{{ $index }}" readonly style="width:100%; padding:6px; border:1px solid #ccc; border-radius:5px; background:#f1f3f5;">
                </div>
                <div>
                    <label>Dibayarkan (Rp)</label>
                    <input type="text" name="items[{{ $item->id }}][dibayarkan]" class="dibayarkan" data-index="{{ $index }}" readonly style="width:100%; padding:6px; border:1px solid #ccc; border-radius:5px; background:#e6f4ea; font-weight:600; color:#155724;">
                </div>
            </div>

            <div style="margin-top:15px; display:flex; gap:15px;">
                <div style="flex:1;">
                    <label>No Rekening</label>
                    <input type="text" name="items[{{ $item->id }}][no_rekening]" value="{{ $item->no_rekening ?? '' }}" 
                        style="width:100%; padding:8px; border:1px solid #ccc; border-radius:5px;">
                </div>
                <div style="flex:1;">
                    <label>Bank</label>
                    <input type="text" name="items[{{ $item->id }}][bank]" value="{{ $item->bank ?? '' }}" 
                        style="width:100%; padding:8px; border:1px solid #ccc; border-radius:5px;">
                </div>
            </div>
        </div>
        @endforeach

        <div style="text-align:right;">
            <button type="submit" style="padding:10px 20px; background-color:#007bff; color:white; border:none; border-radius:6px; cursor:pointer; font-weight:500;">
                💾 Simpan
            </button>
        </div>
    </form>
</div>

<script>
window.onload = function() {
    const itemCount = {{ $group->items->count() }};

    function parseCurrency(value) {
        if(!value) return 0;
        return parseFloat(value.replace(/\./g,'').replace(',', '.')) || 0;
    }

    function formatCurrency(value) {
        return value.toLocaleString('id-ID', {minimumFractionDigits:0});
    }

    function updateTotals() {
        for (let i = 0; i < itemCount; i++) {
            const jumlah = parseCurrency(document.querySelector(`.jumlah[data-index="${i}"]`)?.value || '0');
            const punyaNPWP = document.querySelector(`.npwp-check[data-index="${i}"]`)?.checked || false;

            // ==== PPN ====
            const isPPN = document.querySelector(`.ppn-check[data-index="${i}"]`)?.checked;
            const ppn = isPPN && jumlah >= 2000000 ? jumlah * 0.12 : 0;

            // ==== PPH 21 ====
            const sel = document.querySelector(`.pph21-select[data-index="${i}"]`);
            let pph21Rate = parseFloat(sel.value);
            if(sel.value === 'manual') {
                pph21Rate = parseFloat(document.querySelector(`.pph21-manual[data-index="${i}"]`)?.value || 0)/100;
            }
            const pph21 = jumlah * pph21Rate;

            // ==== PPH 22 ====
            const isPPH22 = document.querySelector(`.pph22-check[data-index="${i}"]`)?.checked;
            const pph22 = isPPH22 ? jumlah * (punyaNPWP ? 0.015 : 0.03) : 0;

            // ==== PPH 23 ====
            const isPPH23 = document.querySelector(`.pph23-check[data-index="${i}"]`)?.checked;
            const pph23 = isPPH23 ? jumlah * (punyaNPWP ? 0.02 : 0.04) : 0;

            // ==== TOTAL DIBAYARKAN ====
            const dibayarkan = jumlah - (pph21 + pph22 + pph23);

            // ==== UPDATE INPUT ====
            document.querySelector(`.hasil_pph21[data-index="${i}"]`).value = formatCurrency(pph21);
            document.querySelector(`.hasil_pph22[data-index="${i}"]`).value = formatCurrency(pph22);
            document.querySelector(`.hasil_pph23[data-index="${i}"]`).value = formatCurrency(pph23);
            document.querySelector(`.hasil_ppn[data-index="${i}"]`).value = formatCurrency(ppn);
            document.querySelector(`.dibayarkan[data-index="${i}"]`).value = formatCurrency(dibayarkan);

            // ==== SHOW/HIDE MANUAL INPUT ====
            const manualInput = document.querySelector(`.pph21-manual[data-index="${i}"]`);
            manualInput.style.display = sel.value === 'manual' ? 'inline-block' : 'none';
        }
    }

    document.querySelectorAll('.jumlah, .pph21-select, .pph21-manual, .pph22-check, .pph23-check, .ppn-check, .npwp-check').forEach(el => {
        el.addEventListener('input', updateTotals);
        el.addEventListener('change', updateTotals);
    });

    updateTotals();
}
</script>

@endsection
