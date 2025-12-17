<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengeluaranBarang extends Model
{
    protected $table = 'pengeluaran_barang';

    protected $fillable = [
        'pengajuan_id',
        'kode_pengeluaran',
        'bidang_bagian',
        'nama_penerima',
        'nama_petugas_persediaan',
        'nama_penyerah',
        'persediaan_id',
        'tanggal_pengeluaran',
    ];

    // Relasi ke pengajuan (1 pengeluaran = 1 pengajuan)
    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class, 'pengajuan_id');
    }

    public function persediaan()
    {
        return $this->belongsTo(User::class, 'persediaan_id');
    }

    // Relasi ke item pengeluaran (1 pengeluaran bisa banyak item)
    public function items()
    {
        return $this->hasMany(PengeluaranBarangItem::class, 'pengeluaran_id');
    }
}
