<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HonorDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'honor_id',
        'nama',
        'nip',
        'jabatan',
        'tujuan',
        'jumlah_hari',
        'uang_harian',
        'uang_transport',
        'pph21',
        'potongan_lain',
        'jumlah_dibayar',
        'nomor_rekening',
        'atas_nama',
        'bank',
    ];

    // Relasi ke Honor
    public function honor()
    {
        return $this->belongsTo(Honor::class);
    }
}
