<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangPeminjam extends Model
{
    use HasFactory;
    protected $fillable = ['id_barang','nama_peminjam', 'merek', 'jumlah', 'keterangan', 'tanggal_peminjaman', 'tanggal_pengembalian'];
     public  $timestamps  = true;

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }
}
