@extends('layouts.app')

@section('content')
<div style="max-width:1100px; margin:auto;">
    <h2 style="margin-bottom:20px;">Data Honor</h2>

    <!-- Pilih jumlah entri per halaman -->
    <form method="GET" action="{{ route('bendahara.arsip.honor.list') }}" style="margin-bottom:15px;">
        <label for="perPage" style="font-weight:bold; margin-right:10px;">Tampilkan:</label>
        <select name="perPage" id="perPage" onchange="this.form.submit()" style="padding:4px;">
            @foreach([10, 25, 50, 100] as $size)
                <option value="{{ $size }}" {{ request('perPage', 10) == $size ? 'selected' : '' }}>
                    {{ $size }}
                </option>
            @endforeach
        </select>
        <span>entri</span>
    </form>

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
                        <a href="{{ route('bendahara.honor.show', $honor->id) }}" 
                            style="padding:4px 8px; background:#007bff; color:white; border-radius:4px; text-decoration:none; margin-right:5px;">
                            Lihat Detail
                        </a>

                        <a href="{{ route('bendahara.honor.download.pdf', $honor->id ) }}"
                            style="background:green; color:white; padding:0.3rem 0.6rem; border-radius:4px; text-decoration:none;">
                            Download PDF
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
