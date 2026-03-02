<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangMasuk extends Model
{
    protected $fillable = ['id_barang','merek','jumlah','keterangan','tanggal_barang_masuk'];
    public  $timestamps  = true;

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }
}
