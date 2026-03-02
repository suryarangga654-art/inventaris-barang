<?php

namespace App\Models;

// Menggunakan Authenticatable supaya model ini bisa dipakai Login
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Petugas extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Nama tabel di database.
     * Kita set manual ke 'petugass' sesuai migration kamu.
     */
    protected $table = 'petugass';

    /**
     * Kolom yang boleh diisi (Mass Assignment).
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
        'google_id',
        'phone',
        'address',
    ];

    /**
     * Kolom yang disembunyikan.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting tipe data.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // Otomatis meng-hash password
    ];

    // ==================== LOGIKA ROLE ====================

    /**
     * Cek apakah user adalah admin.
     * Digunakan di Controller: if(auth()->user()->isAdmin())
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Cek apakah user adalah petugas biasa.
     */
    public function isPetugas(): bool
    {
        return $this->role === 'petugas';
    }
}