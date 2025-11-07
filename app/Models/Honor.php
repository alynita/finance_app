<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Honor extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kegiatan',
        'waktu',
        'alokasi_anggaran',
        'nama',
        'jabatan',
        'tujuan',
        'uang_harian',
        'pph21',
        'jumlah_dibayar',
        'nomor_rekening',
        'atas_nama',
        'bank',
        'adum_id',
        'adum_approved_at',
        'ppk_id',
        'ppk_approved_at',
        'user_id', // penanggung jawab / pengaju
        'status',
    ];

    // Relasi ke ADUM
    public function adum()
    {
        return $this->belongsTo(User::class, 'adum_id');
    }

    // Relasi ke PPK
    public function ppk()
    {
        return $this->belongsTo(User::class, 'ppk_id');
    }

    // Relasi ke pengaju / penanggung jawab
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
