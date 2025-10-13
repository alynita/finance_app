<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProsesKeuangan extends Model
{
    use HasFactory;

    protected $table = 'proses_keuangan';

    protected $fillable = [
        'pengajuan_id',
        'nomor_invoice',
        'kode_akun',
        'uraian',
        'jumlah_pengajuan',
        'pph_21',
        'pph_22',
        'pph_23',
        'ppn',
        'dibayarkan',
        'no_rekening',
        'bank',
    ];

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class);
    }
}
