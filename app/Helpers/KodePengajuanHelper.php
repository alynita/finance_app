<?php

namespace App\Helpers;

use App\Models\Pengajuan;
use Carbon\Carbon;

class KodePengajuanHelper
{
    public static function generate(string $jenis_pengajuan): string
    {
        $prefix = match ($jenis_pengajuan) {
            'pembelian' => 'PEMB',
            'kerusakan' => 'PMB',
            default     => 'PGJ',
        };

        $now = Carbon::now();
        $bulan = $now->month;
        $tahun = $now->year;

        $bulanRomawi = [
            1=>'I',2=>'II',3=>'III',4=>'IV',5=>'V',6=>'VI',
            7=>'VII',8=>'VIII',9=>'IX',10=>'X',11=>'XI',12=>'XII'
        ][$bulan];

        $lastNumber = Pengajuan::whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan)
            ->where('jenis_pengajuan', $jenis_pengajuan)
            ->count() + 1;

        $nomor = str_pad($lastNumber, 3, '0', STR_PAD_LEFT);

        return "{$prefix}/{$nomor}/{$bulanRomawi}/{$tahun}";
    }
}
