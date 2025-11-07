<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KroAccount extends Model
{
    use HasFactory;

    protected $fillable = ['value', 'kro', 'kode_akun'];
}
