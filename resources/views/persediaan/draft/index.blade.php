@extends('layouts.app')

@section('content')
<h2>Draft Formulir Pengeluaran</h2>

<div style="
    background:#fff;
    padding:20px;
    border-radius:16px;
    box-shadow:0 10px 24px rgba(0,0,0,0.06);
">

    <div style="overflow-x:auto;">
        <table style="
            width:100%;
            border-collapse:separate;
            border-spacing:0 10px;
            min-width:600px;
    ">

            <thead>
                <tr>
                    <th style="padding:12px; text-align:left; color:#666;">No</th>
                    <th style="padding:12px; text-align:left; color:#666;">Nomor</th>
                    <th style="padding:12px; text-align:left; color:#666;">Tanggal</th>
                    <th style="padding:12px; text-align:left; color:#666;">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @foreach($drafts as $d)
                <tr style="
                    background:#f9fafb;
                    border-radius:12px;
                    transition:0.2s;
                "
                onmouseover="this.style.background='#eef5ee'"
                onmouseout="this.style.background='#f9fafb'"
                >
                    <td style="padding:14px 12px; border-radius:12px 0 0 12px;">
                        {{ $loop->iteration }}
                    </td>

                    <td style="padding:14px 12px;">
                        {{ $d->kode_pengeluaran }}
                    </td>

                    <td style="padding:14px 12px;">
                        {{ $d->created_at->format('d-m-Y') }}
                    </td>

                    <td style="padding:14px 12px; border-radius:0 12px 12px 0;">
                        <a href="{{ route('persediaan.draft.detail', $d->id) }}"
                            style="
                                background:#2e7d32;
                                color:#fff;
                                padding:6px 14px;
                                border-radius:8px;
                                text-decoration:none;
                                font-size:14px;
                            ">
                            Detail
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
