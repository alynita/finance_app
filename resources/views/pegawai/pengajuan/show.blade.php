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
        <tr>
            <td><strong>Nomor Pengajuan</strong></td>
            <td>:</td>
            <td><strong>{{ $pengajuan->kode_pengajuan }}</strong></td>
        </tr>

    </table>

    <!-- Detail Item -->
    <h4>Detail Item:</h4>
    <table style="width:100%; border-collapse: collapse;">
        <thead>
            <tr style="background:#f2f2f2;">
                @if($pengajuan->jenis_pengajuan === 'pembelian')
                    <th style="border:1px solid #ccc; padding:0.5rem;">Nama Barang</th>
                    <th style="border:1px solid #ccc; padding:0.5rem;">Volume</th>
                    <th style="border:1px solid #ccc; padding:0.5rem;">KRO/Kode Akun</th>
                    <th style="border:1px solid #ccc; padding:0.5rem;">Harga Satuan</th>
                    <th style="border:1px solid #ccc; padding:0.5rem;">Jumlah Dana</th>
                    <th style="border:1px solid #ccc; padding:0.5rem;">Foto/Ket</th>
                    <th style="border:1px solid #ccc; padding:0.5rem;">Link</th>
                    <th style="border:1px solid #ccc; padding:0.5rem;">Keterangan Persediaan</th>
                @elseif($pengajuan->jenis_pengajuan === 'kerusakan')
                    <th style="border:1px solid #ccc; padding:0.5rem;">Nama Barang</th>
                    <th style="border:1px solid #ccc; padding:0.5rem;">Volume</th>
                    <th style="border:1px solid #ccc; padding:0.5rem;">Lokasi</th>
                    <th style="border:1px solid #ccc; padding:0.5rem;">Jenis Kerusakan</th>
                    <th style="border:1px solid #ccc; padding:0.5rem;">Harga Satuan</th>
                    <th style="border:1px solid #ccc; padding:0.5rem;">Jumlah Dana</th>
                    <th style="border:1px solid #ccc; padding:0.5rem;">Foto</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($pengajuan->items as $item)
                <tr>
                    @if($pengajuan->jenis_pengajuan === 'pembelian')
                        <td style="border:1px solid #ccc; padding:0.5rem;">{{ $item->nama_barang ?? '-' }}</td>
                        <td style="border:1px solid #ccc; padding:0.5rem;">
                            {{ number_format((float)($item->volume_tampil ?? $item->volume ?? 0), 2) }}
                        </td>
                        <td style="border:1px solid #ccc; padding:0.5rem;">
                            @php
                                // Ambil kro_full, hapus duplikasi jika ada
                                $kroFull = $item->kro_full ?? $item->kro ?? '-';
                                $parts = explode('.', $kroFull);
                                $cleanParts = [];
                                foreach($parts as $p){
                                    if(!in_array($p, $cleanParts)){
                                        $cleanParts[] = $p;
                                    }
                                }
                                // Format terakhir → ganti titik sebelum kode akun akhir dengan slash
                                if(count($cleanParts) > 2){
                                    $last = array_pop($cleanParts); // kode akun terakhir
                                    $secondLast = array_pop($cleanParts); // biasanya level terakhir sebelum A/524111
                                    $kroDisplay = implode('.', $cleanParts) . '.' . $secondLast . '/' . $last;
                                } else {
                                    $kroDisplay = $kroFull;
                                }
                            @endphp
                            {{ $kroDisplay }}
                        </td>
                        <td style="border:1px solid #ccc; padding:0.5rem;">{{ number_format((float)($item->harga_satuan ?? 0), 2) }}</td>
                        <td style="border:1px solid #ccc; padding:0.5rem;">
                            {{ number_format($item->jumlah_dana_tampil ?? $item->jumlah_dana_pengajuan) }}
                        </td>
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
                        <td style="border:1px solid #ccc; padding:0.5rem;">
                            @if($pengajuan->status === 'menunggu_persediaan')
                                <em style="color:#999;">Menunggu pengecekan persediaan</em>

                            @elseif($item->status_persediaan === 'ADA')
                                <span style="color:#2e7d32; font-weight:600;">
                                    ✔ Tersedia di persediaan
                                </span>

                            @elseif($item->status_persediaan === 'SEBAGIAN')
                                <span style="color:#ef6c00; font-weight:600;">
                                    ⚠ Sebagian tersedia ({{ $item->jumlah_tersedia }})
                                </span>

                            @elseif($item->status_persediaan === 'TIDAK_ADA')
                                <span style="color:#c62828; font-weight:600;">
                                    ✖ Tidak tersedia (pengadaan)
                                </span>

                            @else
                                <span style="color:#999;">-</span>
                            @endif
                        </td>
                    @elseif($pengajuan->jenis_pengajuan === 'kerusakan')
                        <td style="border:1px solid #ccc; padding:0.5rem;">{{ $item->nama_barang ?? '-' }}</td>
                        <td style="border:1px solid #ccc; padding:0.5rem;">{{ number_format((float)($item->volume ?? 0), 2) }}</td>
                        <td style="border:1px solid #ccc; padding:0.5rem;">{{ $item->lokasi ?? '-' }}</td>
                        <td style="border:1px solid #ccc; padding:0.5rem;">{{ $item->jenis_kerusakan ?? '-' }}</td>
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
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Tanda Tangan -->
    <div style="display:flex; justify-content:space-between; margin-top:100px;">
        {{-- ================= MENGETAHUI ================= --}}
        @php
            $rolePembuat = $pengajuan->user->role;
            $mengetahui_user = null;

            // 1. Jika pembuat adalah anggota timker → mengetahui = ketua timker
            if (str_starts_with($rolePembuat, 'anggota_timker_')) {
                $index = str_replace('anggota_timker_', '', $rolePembuat);
                $mengetahui_user = \App\Models\User::where('role', 'timker_' . $index)->first();
            }

            // 2. Jika pembuat adalah ketua timker → mengetahui = ketua timker itu sendiri
            elseif (str_starts_with($rolePembuat, 'timker_')) {
                $mengetahui_user = \App\Models\User::where('role', $rolePembuat)->first();
            }

            // 3. Bukan timker → mengetahui = ADUM
            else {
                $mengetahui_user = \App\Models\User::where('role', 'adum')->first();
            }
        @endphp

        <div style="flex:1; text-align:center;">
            <div>MENGETAHUI</div>
            <div>{{ strtoupper($mengetahui_user->jabatan ?? $mengetahui_user->role ?? '-') }}</div>

            <div style="margin-top:60px;">
                @if($pengajuan->mengetahui_approved_at)
                    <div style="opacity:0.5; font-weight:bold;">APPROVED</div>
                    <div>{{ $mengetahui_user->name ?? '-' }}</div>
                    <div>NIP. {{ $mengetahui_user->nip ?? '-' }}</div>
                    <small>{{ $pengajuan->mengetahui_approved_at }}</small>
                @else
                    <em style="color:red;">Tanda tangan menunggu approve</em>
                @endif
            </div>
        </div>

        {{-- ================= MENYETUJUI (PPK) ================= --}}
        <div style="flex:1; text-align:center;">
            <div>MENYETUJUI</div>
            <div>Pejabat Pembuat Komitmen</div>

            <div style="margin-top:60px;">
                @if($pengajuan->ppk_approved_at)
                    <div style="opacity:0.5; font-weight:bold;">APPROVED</div>
                    <div>{{ $pengajuan->ppk->name }}</div>
                    <div>NIP. {{ $pengajuan->ppk->nip }}</div>
                    <small>{{ $pengajuan->ppk_approved_at }}</small>
                @else
                    <em style="color:red;">Tanda tangan menunggu approve</em>
                @endif
            </div>
        </div>

        {{-- ================= PENANGGUNG JAWAB ================= --}}
        <div style="flex:1; text-align:center;">
            <div>PENANGGUNG JAWAB</div>

            <div style="margin-top:60px;">
                {{ $pengajuan->user->name ?? 'Nama Penanggung Jawab' }}<br>
                NIP. {{ $pengajuan->user->nip ?? '-' }}
            </div>
        </div>

    </div>

</div>
@endsection
