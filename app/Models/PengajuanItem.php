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
        'link',
        'ppk_group_id',
        'status_ppk',
        'catatan_ppk',
        'tanggal',
        'nama',
        'jabatan',
        'tipe_item',
        'updated_at',
        'created_at',
        'uraian', 
        'jumlah_dana_pengajuan',
        'pph21', 
        'pph22', 
        'pph23', 
        'ppn', 
        'dibayarkan',
        'no_rekening',
        'bank',
        'invoice'
    ];

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class);
    }

    public function ppkGroup()
    {
        return $this->belongsTo(PpkGroup::class, 'ppk_group_id');
    }

    public function pengeluaranItem()
    {
        return $this->hasOne(PengeluaranBarangItem::class, 'pengajuan_item_id');
    }

    // 💡 otomatis hitung jumlah_dana_pengajuan saat membuat item
    protected static function booted()
    {
        static::creating(function ($item) {
            if ($item->tipe_item === 'barang') { // pembelian
                $volume = $item->volume ?? 0;
                $harga_satuan = $item->harga_satuan ?? 0;
                $ongkos_kirim = $item->ongkos_kirim ?? 0;
                $item->jumlah_dana_pengajuan = ($volume * $harga_satuan) + $ongkos_kirim;
            } elseif ($item->tipe_item === 'kerusakan') {
                $volume = $item->volume ?? 0;
                $harga_satuan = $item->harga_satuan ?? 0;
                $item->jumlah_dana_pengajuan = $volume * $harga_satuan;
            }
        });
    }

}
