<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormPersediaan extends Model
{
    protected $table = 'form_persediaan';

    protected $fillable = [
        'pengajuan_id',
        'nomor',
        'bidang',
        'items',
    ];
}
