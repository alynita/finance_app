<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenanggungJawab extends Model
{
    use HasFactory;

    protected $table = 'penanggung_jawab';

    protected $fillable = [
        'nama',
        'nip',
        'jabatan',
        'user_id',
    ];

    public function pengajuans()
    {
        return $this->hasMany(\App\Models\Pengajuan::class, 'pj_id');
    }
}
