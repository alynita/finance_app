@extends('layouts.app')

@section('content')
<div style="max-width:1100px; margin:auto;">
    <h2 style="margin-bottom:20px;">Detail Honor</h2>

    <!-- INFO KEGIATAN -->
    <div style="margin-bottom:15px; padding:15px; background: rgba(255,255,255,0.85); border-radius:8px; box-shadow:0 1px 4px rgba(0,0,0,0.1);">
        <p><strong>Nama Kegiatan:</strong> {{ $honor->nama_kegiatan }}</p>
        <p><strong>Waktu Penyelenggaraan:</strong> {{ \Carbon\Carbon::parse($honor->waktu)->format('d-m-Y') }}</p>
        <p><strong>Alokasi Anggaran:</strong> {{ $honor->alokasi_anggaran }}</p>
    </div>

    <!-- Tentukan jenis uang berdasarkan pengajuan -->
    @php
        // Tentukan jenis uang dari baris pertama yang ada nominalnya
        $detailPertama = $honor->details->first();
        if ($detailPertama->uang_transport > 0) {
            $jenisUang = 'transport';
            $labelUang = 'Uang Transport';
        } else {
            $jenisUang = 'harian';
            $labelUang = 'Uang Harian';
        }
    @endphp

    <!-- TABEL DETAIL ORANG -->
    <table style="width:100%; border-collapse:collapse; margin-bottom:30px;">
        <thead style="background:#f8f9fa;">
            <tr>
                <th style="border:1px solid #ccc; padding:8px;">Nama</th>
                <th style="border:1px solid #ccc; padding:8px;">NIP</th>
                <th style="border:1px solid #ccc; padding:8px;">Jabatan</th>
                <th style="border:1px solid #ccc; padding:8px;">Tujuan</th>
                <th style="border:1px solid #ccc; padding:8px;">Jumlah Hari</th>
                <th style="border:1px solid #ccc; padding:8px;">{{ $labelUang }}</th>
                <th style="border:1px solid #ccc; padding:8px;">PPH 21 (%)</th>
                <th style="border:1px solid #ccc; padding:8px;">Potongan Lain (%)</th>
                <th style="border:1px solid #ccc; padding:8px;">Jumlah Dibayar</th>
                <th style="border:1px solid #ccc; padding:8px;">Nomor Rekening</th>
                <th style="border:1px solid #ccc; padding:8px;">Atas Nama</th>
                <th style="border:1px solid #ccc; padding:8px;">Bank</th>
            </tr>
        </thead>
        <tbody>
            @php $totalKeseluruhan = 0; @endphp

            @foreach ($honor->details as $detail)
                @php
                    // Ambil nominal sesuai jenis uang
                    $nominal = $jenisUang === 'transport'
                        ? ($detail->uang_transport ?? 0)
                        : ($detail->uang_harian ?? 0);

                    $totalKeseluruhan += $detail->jumlah_dibayar;
                @endphp

                <tr>
                    <td style="border:1px solid #ccc; padding:8px;">{{ $detail->nama }}</td>
                    <td style="border:1px solid #ccc; padding:8px;">{{ $detail->nip }}</td>
                    <td style="border:1px solid #ccc; padding:8px;">{{ $detail->jabatan }}</td>
                    <td style="border:1px solid #ccc; padding:8px;">{{ $detail->tujuan }}</td>
                    <td style="border:1px solid #ccc; padding:8px;">{{ $detail->jumlah_hari }}</td>

                    <td style="border:1px solid #ccc; padding:8px;">
                        Rp {{ number_format($nominal, 0, ',', '.') }}
                    </td>
                    
                    <td style="border:1px solid #ccc; padding:8px;">{{ $detail->pph21 }}%</td>
                    <td style="border:1px solid #ccc; padding:8px;">{{ $detail->potongan_lain ?? 0 }}%</td>

                    <td style="border:1px solid #ccc; padding:8px;">
                        Rp {{ number_format($detail->jumlah_dibayar, 0, ',', '.') }}
                    </td>

                    <td style="border:1px solid #ccc; padding:8px;">{{ $detail->nomor_rekening }}</td>
                    <td style="border:1px solid #ccc; padding:8px;">{{ $detail->atas_nama }}</td>
                    <td style="border:1px solid #ccc; padding:8px;">{{ $detail->bank }}</td>
                </tr>
            @endforeach

            <!-- BARIS TOTAL -->
            <tr style="font-weight:bold; background:#f1f1f1;">
                <td colspan="8" style="border:1px solid #ccc; padding:8px; text-align:right;">TOTAL</td>
                <td style="border:1px solid #ccc; padding:8px;">Rp {{ number_format($totalKeseluruhan, 0, ',', '.') }}</td>
                <td colspan="3" style="border:1px solid #ccc; padding:8px;"></td>
            </tr>
        </tbody>
    </table>

    <!-- BAGIAN TANDA TANGAN -->
    <div style="display:flex; justify-content:space-between; margin-top:50px;">

        <!-- ADUM -->
        <div style="flex:1; text-align:center;">
            <div>MENGETAHUI</div>
            <div>ADUM</div>
            <div style="margin-top:60px;">
                @if($honor->adum_id)
                    <div style="opacity:0.6; font-weight:bold;">APPROVED</div>
                    {{ $honor->adum->name ?? '-' }}<br>
                    NIP. {{ $honor->adum->nip ?? '-' }}<br>
                    <small>{{ $honor->adum_approved_at ? \Carbon\Carbon::parse($honor->adum_approved_at)->format('d M Y H:i') : '' }}</small>
                @else
                    <em style="color:red;">Menunggu approve</em>
                @endif
            </div>
        </div>

        <!-- PPK -->
        <div style="flex:1; text-align:center;">
            <div>MENYETUJUI</div>
            <div>Pejabat Pembuat Komitmen</div>
            <div style="margin-top:60px;">
                @if($honor->ppk_id)
                    <div style="opacity:0.6; font-weight:bold;">APPROVED</div>
                    {{ $honor->ppk->name ?? '-' }}<br>
                    NIP. {{ $honor->ppk->nip ?? '-' }}<br>
                    <small>{{ $honor->ppk_approved_at ? \Carbon\Carbon::parse($honor->ppk_approved_at)->format('d M Y H:i') : '' }}</small>
                @else
                    <em style="color:red;">Menunggu approve</em>
                @endif
            </div>
        </div>

        <!-- PENANGGUNG JAWAB -->
        <div style="flex:1; text-align:center;">
            <div>PENANGGUNG JAWAB</div>
            <div style="margin-top:60px;">
                {{ $honor->user->name ?? '-' }}<br>
                NIP. {{ $honor->user->nip ?? '-' }}
            </div>
        </div>
    </div>

    <a href="{{ route('keuangan.honor.data') }}" 
        style="padding:6px 12px; background:#0E7C3A; color:white; border-radius:4px; text-decoration:none; display:inline-block; margin-top:30px;">
        Kembali
    </a>
</div>
@endsection
