<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kro extends Model
{
    protected $table = 'kro';
    protected $fillable = ['kode', 'nama', 'parent_id', 'kode_akun'];

    public function children()
    {
        return $this->hasMany(Kro::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Kro::class, 'parent_id');
    }
}
