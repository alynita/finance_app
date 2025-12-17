<?php

if (!function_exists('statusPegawai')) {
    function statusPegawai($pengajuan)
    {
        // masih dicek persediaan
        if ($pengajuan->status === 'MENUNGGU_PERSEDIAAN') {
            return [
                'text'  => '🟡 Dicek Persediaan',
                'color' => '#facc15' // kuning
            ];
        }

        // sebagian ada
        if ($pengajuan->status === 'SEBAGIAN_PERSEDIAAN') {
            return [
                'text'  => '🟠 Sebagian Ada (Lanjut ADUM)',
                'color' => '#fb923c' // oranye
            ];
        }

        // tidak ada
        if ($pengajuan->status === 'TIDAK_ADA_PERSEDIAAN') {
            return [
                'text'  => '🔴 Tidak Ada (Lanjut PPK)',
                'color' => '#ef4444' // merah
            ];
        }

        // sudah approve
        if ($pengajuan->status === 'DISETUJUI') {
            return [
                'text'  => '🟢 Disetujui',
                'color' => '#22c55e'
            ];
        }

        return [
            'text'  => '⏳ Diproses',
            'color' => '#94a3b8'
        ];
    }
}
