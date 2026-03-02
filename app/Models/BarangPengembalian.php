<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangPengembalian extends Model
{
    use HasFactory;
    protected $fillable = ['id_barang','nama_pengembali','merek', 'jumlah', 'keterangan', 'tanggal_pengembalian'];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }
}
