<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengeluaranBarangItem extends Model
{
    protected $table = 'pengeluaran_barang_items';

    protected $fillable = [
        'pengeluaran_id',
        'pengajuan_item_id',
        'nama_barang',
        'jumlah',
        'harga_satuan',
        'total',
        'keterangan'
    ];

    // Relasi ke header pengeluaran
    public function pengeluaran()
    {
        return $this->belongsTo(PengeluaranBarang::class, 'pengeluaran_id');
    }

    // Relasi ke item pengajuan
    public function pengajuanItem()
    {
        return $this->belongsTo(PengajuanItem::class, 'pengajuan_item_id');
    }
}
