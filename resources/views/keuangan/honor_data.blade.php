@extends('layouts.app')

@section('content')
<div style="max-width:1100px; margin:auto;">
    <h2 style="margin-bottom:20px;">Data Honor</h2>

    <table style="width:100%; border-collapse:collapse;">
        <thead style="background:#f8f9fa;">
            <tr>
                <th style="border:1px solid #ccc; padding:8px;">No</th>
                <th style="border:1px solid #ccc; padding:8px;">Created At</th>
                <th style="border:1px solid #ccc; padding:8px;">Nama Kegiatan</th>
                <th style="border:1px solid #ccc; padding:8px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($honors as $key => $honor)
                <tr>
                    <td style="border:1px solid #ccc; padding:8px;">{{ $key + 1 }}</td>
                    <td style="border:1px solid #ccc; padding:8px;">{{ $honor->created_at->format('d-m-Y H:i') }}</td>
                    <td style="border:1px solid #ccc; padding:8px;">{{ $honor->nama_kegiatan }}</td>
                    <td style="border:1px solid #ccc; padding:8px;">
                        <a href="{{ route('keuangan.honor.detail', $honor->id) }}" 
                            style="padding:4px 8px; background:#007bff; color:white; border-radius:4px; text-decoration:none;">
                            Lihat Detail
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
