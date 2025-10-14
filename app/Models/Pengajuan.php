<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
    use HasFactory;

    protected $table = 'pengajuan';

    protected $fillable = [
        'user_id',
        'nama_kegiatan',
        'waktu_kegiatan',
        'jenis_pengajuan',
        'status',
        'adum_id',
        'ppk_id',
    ];

    // relasi ke item pengajuan
    public function items()
    {
        return $this->hasMany(PengajuanItem::class, 'pengajuan_id');
    }

    // relasi ke honorarium
    public function honorariums()
    {
        return $this->hasMany(Honorarium::class, 'pengajuan_id');
    }

    // relasi ke user pengaju
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // relasi ke ADUM (user yang approve sebagai ADUM)
    public function adum()
    {
        return $this->belongsTo(User::class, 'adum_id');
    }

    // relasi ke PPK (user yang approve sebagai PPK)
    public function ppk()
    {
        return $this->belongsTo(User::class, 'ppk_id');
    }
}
