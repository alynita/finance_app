@extends('layouts.app')

@section('title', 'Detail Pengajuan')
@section('header', 'Detail Pengajuan')

@section('content')
<div style="max-width:800px; margin:auto;">

    <!-- Informasi Pengajuan -->
    <table style="width:100%; border-collapse:collapse; margin-bottom:10px;">
        <tr>
            <td style="width:200px;"><strong>Nama Kegiatan</strong></td>
            <td style="width:10px;">:</td>
            <td>{{ $pengajuan->nama_kegiatan }}</td>
        </tr>
        <tr>
            <td><strong>Waktu Kegiatan</strong></td>
            <td>:</td>
            <td>{{ $pengajuan->waktu_kegiatan }}</td>
        </tr>
        <tr>
            <td><strong>Jenis Pengajuan</strong></td>
            <td>:</td>
            <td>{{ ucfirst($pengajuan->jenis_pengajuan) }}</td>
        </tr>
    </table>

    <!-- Detail Item -->
    <h4>Detail Item:</h4>
    <table style="width:100%; border-collapse: collapse;">
        <thead>
            <tr style="background:#f2f2f2;">
                <th style="border:1px solid #ccc; padding:0.5rem;">Nama Barang</th>
                <th style="border:1px solid #ccc; padding:0.5rem;">Volume</th>
                <th style="border:1px solid #ccc; padding:0.5rem;">KRO/Kode Akun</th>
                <th style="border:1px solid #ccc; padding:0.5rem;">Harga Satuan</th>
                <th style="border:1px solid #ccc; padding:0.5rem;">Jumlah Dana</th>
                <th style="border:1px solid #ccc; padding:0.5rem;">Foto/Ket</th>
                <th style="border:1px solid #ccc; padding:0.5rem;">Link</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pengajuan->items as $item)
                <tr>
                    <td style="border:1px solid #ccc; padding:0.5rem;">{{ $item->nama_barang ?? '-' }}</td>
                    <td style="border:1px solid #ccc; padding:0.5rem;">{{ number_format((float)($item->volume ?? 0), 2) }}</td>

                    <td style="border:1px solid #ccc; padding:0.5rem;">
                        @php
                            $kroFull = $item->kro_full ?? $item->kro ?? '-';
                            $parts = explode('.', $kroFull);
                            $cleanParts = [];

                            foreach($parts as $p){
                                if(!in_array($p, $cleanParts)){
                                    $cleanParts[] = $p;
                                }
                            }

                            if(count($cleanParts) > 2){
                                $last = array_pop($cleanParts);
                                $secondLast = array_pop($cleanParts);
                                $kroDisplay = implode('.', $cleanParts) . '.' . $secondLast . '/' . $last;
                            } else {
                                $kroDisplay = $kroFull;
                            }
                        @endphp
                        {{ $kroDisplay }}
                    </td>

                    <td style="border:1px solid #ccc; padding:0.5rem;">{{ number_format((float)($item->harga_satuan ?? 0), 2) }}</td>
                    <td style="border:1px solid #ccc; padding:0.5rem;">{{ number_format((float)($item->jumlah_dana_pengajuan ?? 0), 2) }}</td>

                    <td style="border:1px solid #ccc; padding:0.5rem;">
                        @if($item->foto)
                            <img src="{{ asset('storage/'.$item->foto) }}"
                                alt="Foto"
                                style="width: 120px; height: auto; border-radius: 6px;" />
                        @else
                            -
                        @endif
                    </td>

                    <td style="border:1px solid #ccc; padding:0.5rem;">
                        @if($item->link)
                            <a href="{{ $item->link }}" target="_blank">Buka Link</a>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Tanda Tangan -->
    @php
        $mengetahui_user = $pengajuan->mengetahui ?? $pengajuan->adum;
    @endphp

    <div style="display:flex; justify-content:space-between; margin-top:100px;">

        <!-- MENGETAHUI -->
        <div style="flex:1; text-align:center;">
            <div>MENGETAHUI</div>
            <div>
                {{ $pengajuan->mengetahui_jabatan 
                    ? strtoupper(str_replace('_', ' ', $pengajuan->mengetahui_jabatan)) 
                    : ($mengetahui_user->role ? strtoupper($mengetahui_user->role) : 'Role') 
                }}
            </div>
            <div style="margin-top:60px;">
                @if($pengajuan->mengetahui_id || $pengajuan->adum_id)
                    <div style="opacity:0.5; font-weight:bold;">APPROVED</div>
                    {{ $mengetahui_user->name ?? '-' }}<br>
                    NIP. {{ $mengetahui_user->nip ?? '-' }}<br>
                    <small>{{ \Carbon\Carbon::parse($pengajuan->mengetahui_approved_at ?? $pengajuan->adum_approved_at)->format('d M Y H:i') }}</small>
                @else
                    <em style="color:red;">Tanda tangan menunggu approve</em>
                @endif
            </div>
        </div>

        <!-- PPK -->
        <div style="flex:1; text-align:center;">
            <div>MENYETUJUI</div>
            <div>Pejabat Pembuat Komitmen</div>
            <div style="margin-top:60px;">
                @if($pengajuan->ppk_id)
                    <div style="opacity:0.5; font-weight:bold;">APPROVED</div>
                    {{ $pengajuan->ppk->name ?? 'Nama PPK' }}<br>
                    NIP. {{ $pengajuan->ppk->nip ?? '-' }}<br>
                    <small>{{ $pengajuan->ppk_approved_at ? \Carbon\Carbon::parse($pengajuan->ppk_approved_at)->format('d M Y H:i') : '' }}</small>
                @else
                    <em style="color:red;">Tanda tangan menunggu approve</em>
                @endif
            </div>
        </div>

        <!-- Penanggung Jawab -->
        <div style="flex:1; text-align:center;">
            <div>PENANGGUNG JAWAB</div>
            <div style="margin-top:60px;">
                {{ $pengajuan->user->name ?? 'Nama Penanggung Jawab' }}<br>
                NIP. {{ $pengajuan->user->nip ?? '-' }}
            </div>
        </div>

    </div>

    <a href="{{ route('pegawai.daftar-pengajuan') }}" 
        style="display:inline-block; margin-top:1rem; padding:0.5rem 1rem; background:#6c757d; color:white; border-radius:4px; text-decoration:none;">
        Kembali
    </a>

</div>
@endsection
