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
        'mengetahui_id',
        'mengetahui_jabatan'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'mengetahui_approved_at' => 'datetime'
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

    //relasi ke timker1-6
    public function mengetahui()
    {
        return $this->belongsTo(User::class, 'mengetahui_id');
    }

    // relasi ke PPK (user yang approve sebagai PPK)
    public function ppk()
    {
        return $this->belongsTo(User::class, 'ppk_id');
    }

    public function ppkGroups()
    {
        return $this->hasMany(PpkGroup::class);
    }

    public function verifikator()
    {
        return $this->belongsTo(User::class, 'verifikator_id');
    }

    public function ppkApproval()
    {
        return $this->hasOne(\App\Models\PpkGroup::class, 'pengajuan_id')
                    ->where('status', 'pending_pengadaan');
    }

    public function persediaan()
    {
        return $this->belongsTo(User::class, 'persediaan_by');
    }

    public function pengeluaran()
    {
        return $this->hasOne(PengeluaranBarang::class, 'pengajuan_id');
    }

}
