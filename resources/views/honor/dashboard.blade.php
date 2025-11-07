@extends('layouts.app')

@section('content')
<div style="max-width:1100px; margin:auto;">
    <h2 style="margin-bottom:20px;">Dashboard Honor</h2>

    @if(session('success'))
        <div style="background:#d4edda; color:#155724; padding:10px; margin-bottom:15px;">
            {{ session('success') }}
        </div>
    @endif

    @if($honors->count() > 0)
        <table style="width:100%; border-collapse:collapse;">
            <thead style="background:#f8f9fa;">
                <tr>
                    <th style="border:1px solid #ccc; padding:8px;">Nama Kegiatan</th>
                    <th style="border:1px solid #ccc; padding:8px;">Waktu</th>
                    <th style="border:1px solid #ccc; padding:8px;">Alokasi Anggaran</th>
                    <th style="border:1px solid #ccc; padding:8px;">Nama</th>
                    <th style="border:1px solid #ccc; padding:8px;">Jabatan</th>
                    <th style="border:1px solid #ccc; padding:8px;">Tujuan</th>
                    <th style="border:1px solid #ccc; padding:8px;">Uang Harian</th>
                    <th style="border:1px solid #ccc; padding:8px;">PPH 21 (%)</th>
                    <th style="border:1px solid #ccc; padding:8px;">Jumlah Dibayar</th>
                    <th style="border:1px solid #ccc; padding:8px;">No. Rekening</th>
                    <th style="border:1px solid #ccc; padding:8px;">Atas Nama</th>
                    <th style="border:1px solid #ccc; padding:8px;">Bank</th>
                    <th style="border:1px solid #ccc; padding:8px;">Status</th>
                    <th style="border:1px solid #ccc; padding:8px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($honors as $honor)
                    <tr>
                        <td style="border:1px solid #ccc; padding:8px;">
                            <a href="{{ route('keuangan.honor.detail', $honor->id) }}" style="color:#007bff; text-decoration:none;">
                                {{ $honor->nama_kegiatan }}
                            </a>
                        </td>
                        <td style="border:1px solid #ccc; padding:8px;">{{ \Carbon\Carbon::parse($honor->waktu)->format('d-m-Y') }}</td>
                        <td style="border:1px solid #ccc; padding:8px;">Rp {{ number_format($honor->alokasi_anggaran,0,',','.') }}</td>
                        <td style="border:1px solid #ccc; padding:8px;">{{ $honor->nama }}</td>
                        <td style="border:1px solid #ccc; padding:8px;">{{ $honor->jabatan }}</td>
                        <td style="border:1px solid #ccc; padding:8px;">{{ $honor->tujuan }}</td>
                        <td style="border:1px solid #ccc; padding:8px;">Rp {{ number_format($honor->uang_harian,0,',','.') }}</td>
                        <td style="border:1px solid #ccc; padding:8px;">{{ $honor->pph21 }}%</td>
                        <td style="border:1px solid #ccc; padding:8px;">Rp {{ number_format($honor->jumlah_dibayar,0,',','.') }}</td>
                        <td style="border:1px solid #ccc; padding:8px;">{{ $honor->nomor_rekening }}</td>
                        <td style="border:1px solid #ccc; padding:8px;">{{ $honor->atas_nama }}</td>
                        <td style="border:1px solid #ccc; padding:8px;">{{ $honor->bank }}</td>
                        <td style="border:1px solid #ccc; padding:8px;">
                            @switch($honor->status)
                                @case('pending') <span style="color:orange;">Menunggu ADUM</span> @break
                                @case('adum_approved') <span style="color:blue;">Disetujui ADUM</span> @break
                                @case('ppk_approved') <span style="color:purple;">Disetujui PPK</span> @break
                                @case('approved') <span style="color:green;">Disetujui Semua</span> @break
                                @case('rejected') <span style="color:red;">Ditolak</span> @break
                            @endswitch
                        </td>
                        <td style="border:1px solid #ccc; padding:8px; text-align:center;">
                            @if(($user->role == 'adum' && $honor->status == 'pending') ||
                                ($user->role == 'ppk' && $honor->status == 'adum_approved'))
                                <form action="{{ route('honor.approve', $honor->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    <button type="submit" style="background:green; color:white; padding:4px 8px; border:none; border-radius:3px;">Approve</button>
                                </form>
                                <form action="{{ route('honor.reject', $honor->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    <button type="submit" style="background:red; color:white; padding:4px 8px; border:none; border-radius:3px;">Reject</button>
                                </form>
                            @else
                                <span style="color:gray;">-</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Tidak ada data honor untuk ditampilkan.</p>
    @endif
</div>
@endsection
