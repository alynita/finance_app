<?php

// app/Models/PpkGroup.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PpkGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'pengajuan_id', 
        'group_name', 
        'status', 
        'kode_akun', 
        'approved_by', 
        'approved_at'
    ];

    // Relasi ke pengajuan
    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class);
    }

    // Relasi many-to-many ke items
    public function items()
    {
        return $this->belongsToMany(PengajuanItem::class, 'ppk_group_items', 'ppk_group_id', 'item_id');
    }

    public function adum()
    {
        return $this->belongsTo(User::class, 'adum_id');
    }

    public function ppk()
    {
        return $this->belongsTo(User::class, 'ppk_id');
    }
    
    public function verifikator()
    {
        return $this->belongsTo(User::class, 'verifikator_id');
    }

    // Relasi ke user yang approve grup
    public function approvedByUser()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
