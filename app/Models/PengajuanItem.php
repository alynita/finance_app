<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanItem extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_items';

    protected $fillable = [
        'pengajuan_id',
        'nama_barang',
        'lokasi',
        'jenis_kerusakan',
        'volume',
        'harga_satuan',
        'ongkos_kirim',
        'jumlah_dana_pengajuan',
        'kro',
        'foto',
        'tanggal',
        'nama',
        'jabatan',
        'tipe_item',
        'updated_at',
        'created_at'
    ];

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class);
    }
}
