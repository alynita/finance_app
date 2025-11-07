@extends('layouts.app')

@section('content')
<div style="max-width:1100px; margin:auto;">
    <h2 style="margin-bottom:20px;">Detail Honor</h2>

    <div style="margin-bottom:15px; padding:15px; background: rgba(255,255,255,0.85); border-radius:8px; box-shadow:0 1px 4px rgba(0,0,0,0.1);">
        <strong>Nama Kegiatan:</strong> {{ $honor->nama_kegiatan }}<br>
        <strong>Waktu Penyelenggaraan:</strong> {{ \Carbon\Carbon::parse($honor->waktu)->format('d-m-Y') }}<br>
        <strong>Alokasi Anggaran:</strong> Rp {{ number_format($honor->alokasi_anggaran, 0, ',', '.') }}
    </div>

    <table style="width:100%; border-collapse:collapse; margin-bottom:30px;">
        <thead style="background:#f8f9fa;">
            <tr>
                <th style="border:1px solid #ccc; padding:8px;">Nama</th>
                <th style="border:1px solid #ccc; padding:8px;">Jabatan</th>
                <th style="border:1px solid #ccc; padding:8px;">Tujuan</th>
                <th style="border:1px solid #ccc; padding:8px;">Uang Harian</th>
                <th style="border:1px solid #ccc; padding:8px;">PPH 21 (%)</th>
                <th style="border:1px solid #ccc; padding:8px;">Jumlah Dibayar</th>
                <th style="border:1px solid #ccc; padding:8px;">Nomor Rekening</th>
                <th style="border:1px solid #ccc; padding:8px;">Atas Nama</th>
                <th style="border:1px solid #ccc; padding:8px;">Bank</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="border:1px solid #ccc; padding:8px;">{{ $honor->nama }}</td>
                <td style="border:1px solid #ccc; padding:8px;">{{ $honor->jabatan }}</td>
                <td style="border:1px solid #ccc; padding:8px;">{{ $honor->tujuan }}</td>
                <td style="border:1px solid #ccc; padding:8px;">Rp {{ number_format($honor->uang_harian, 0, ',', '.') }}</td>
                <td style="border:1px solid #ccc; padding:8px;">{{ $honor->pph21 }}%</td>
                <td style="border:1px solid #ccc; padding:8px;">Rp {{ number_format($honor->jumlah_dibayar, 0, ',', '.') }}</td>
                <td style="border:1px solid #ccc; padding:8px;">{{ $honor->nomor_rekening }}</td>
                <td style="border:1px solid #ccc; padding:8px;">{{ $honor->atas_nama }}</td>
                <td style="border:1px solid #ccc; padding:8px;">{{ $honor->bank }}</td>
            </tr>
            <!-- BARIS TOTAL -->
            <tr style="font-weight:bold; background:#f1f1f1;">
                <td colspan="3" style="border:1px solid #ccc; padding:8px; text-align:right;">TOTAL</td>
                <td style="border:1px solid #ccc; padding:8px;">
                    Rp {{ number_format($honor->uang_harian, 0, ',', '.') }}
                </td>
                <td style="border:1px solid #ccc; padding:8px;"></td>
                <td style="border:1px solid #ccc; padding:8px;">
                    Rp {{ number_format($honor->jumlah_dibayar, 0, ',', '.') }}
                </td>
                <td colspan="3" style="border:1px solid #ccc; padding:8px;"></td>
            </tr>
        </tbody>
    </table>

    <!-- Tanda Tangan -->
    <div style="display:flex; justify-content:space-between; margin-top:50px;">
        <!-- MENGETAHUI (ADUM) -->
        <div style="flex:1; text-align:center; display:flex; flex-direction:column; align-items:center;">
            <div>MENGETAHUI</div>
            <div>ADUM</div>
            <div style="margin-top:60px;">
                @if($honor->adum_id)
                    <div style="opacity:0.5; font-weight:bold;">APPROVED</div>
                    {{ $honor->adum->name ?? '-' }}<br>
                    NIP. {{ $honor->adum->nip ?? '-' }}<br>
                    <small>{{ $honor->adum_approved_at ? \Carbon\Carbon::parse($honor->adum_approved_at)->format('d M Y H:i') : '' }}</small>
                @else
                    <em style="color:red;">Menunggu approve</em>
                @endif
            </div>
        </div>

        <!-- MENYETUJUI (PPK) -->
        <div style="flex:1; text-align:center; display:flex; flex-direction:column; align-items:center;">
            <div>MENYETUJUI</div>
            <div>Pejabat Pembuat Komitmen</div>
            <div style="margin-top:60px;">
                @if($honor->ppk_id)
                    <div style="opacity:0.5; font-weight:bold;">APPROVED</div>
                    {{ $honor->ppk->name ?? '-' }}<br>
                    NIP. {{ $honor->ppk->nip ?? '-' }}<br>
                    <small>{{ $honor->ppk_approved_at ? \Carbon\Carbon::parse($honor->ppk_approved_at)->format('d M Y H:i') : '' }}</small>
                @else
                    <em style="color:red;">Menunggu approve</em>
                @endif
            </div>
        </div>

        <!-- PENANGGUNG JAWAB (Pengaju) -->
        <div style="flex:1; text-align:center; display:flex; flex-direction:column; align-items:center;">
            <div>PENANGGUNG JAWAB</div>
            <div style="margin-top:60px;">
                {{ $honor->user->name ?? '-' }}<br>
                NIP. {{ $honor->user->nip ?? '-' }}
            </div>
        </div>
    </div>

    <a href="{{ route('keuangan.honor.detail', $honor->id) }}" 
        style="padding:4px 8px; background:#007bff; color:white; border-radius:4px; text-decoration:none;">
        Kembali
    </a>
</div>
@endsection
