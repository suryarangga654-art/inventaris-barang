<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;
    protected $fillable = ['id','name_barang','stock'];

    public function masuk()
    {
        return $this->hasMany(Masuk::class, 'id_barang');
    }   

    public function keluar()
    {
        return $this->hasMany(Keluar::class, 'id_barang');
    }
    public function peminjam()
    {
        return $this->hasMany(BarangPeminjam::class, 'id_barang');
    }
    public function pengembalian()
    {
        return $this->hasMany(BarangPengembalian::class, 'id_barang');
    }
    public function user() {
        return $this->belongsTo(User::class);
    }
}
