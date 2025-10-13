@extends('layouts.app')

@section('title', 'Daftar Penanggung Jawab')
@section('header', 'Daftar Penanggung Jawab')

@section('content')
<div style="max-width:800px; margin:auto;">
    <a href="{{ route('pegawai.penanggung_jawab.create') }}" 
    style="display:inline-block; margin-bottom:1rem; padding:0.5rem 1rem; background:#3490dc; color:white; border-radius:4px; text-decoration:none;">
    + Tambah Penanggung Jawab
</a>


    <table style="width:100%; border-collapse: collapse;">
        <thead>
            <tr style="background:#f2f2f2;">
                <th style="border:1px solid #ccc; padding:0.5rem;">Nama</th>
                <th style="border:1px solid #ccc; padding:0.5rem;">NIP</th>
                <th style="border:1px solid #ccc; padding:0.5rem;">Jabatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pj as $item)
                <tr>
                    <td style="border:1px solid #ccc; padding:0.5rem;">{{ $item->nama }}</td>
                    <td style="border:1px solid #ccc; padding:0.5rem;">{{ $item->nip }}</td>
                    <td style="border:1px solid #ccc; padding:0.5rem;">{{ $item->jabatan }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
