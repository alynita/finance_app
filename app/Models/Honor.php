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
        'adum_id',
        'adum_approved_at',
        'ppk_id',
        'ppk_approved_at',
        'user_id', 
        'status',
    ];

    public function details()
    {
        return $this->hasMany(HonorDetail::class);
    }

    public function kro()
    {
        return $this->belongsTo(KroAccount::class, 'kro_account_id');
    }
    

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

    // Relasi ke Bendahara
    public function bendahara()
    {
        return $this->belongsTo(User::class, 'bendahara_id');
    }
}
