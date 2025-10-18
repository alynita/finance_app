<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Honorarium extends Model
{
    use HasFactory;

    protected $table = 'honorarium';

    protected $fillable = [
        'pengajuan_id',
        'nama',
        'jabatan',
        'uraian',
        'jumlah_honor',
        'bulan',
        'total_honor',
        'pph_21',
        'jumlah',
        'no_rekening',
        'bank',
        'tanggal',
    ];

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class, 'pengajuan_id', 'id');
    }
}
