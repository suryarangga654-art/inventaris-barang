<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🌱 Bersihkan data dan mulai seeding...');

        // 1. Buat Akun Admin
        // Menggunakan updateOrCreate agar jika email sudah ada, tidak error tapi di-update
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator Utama',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );
        $this->command->info('✅ Admin created: admin@example.com');

        // 2. Buat Akun Petugas
        User::updateOrCreate(
            ['email' => 'petugas@example.com'],
            [
                'name' => 'Petugas Lapangan',
                'password' => Hash::make('password'),
                'role' => 'petugas',
                'email_verified_at' => now(),
            ]
        );
        $this->command->info('✅ Petugas created: petugas@example.com');

        // 3. Jalankan Seeder lain jika ada (Category/Barang)
        // Pastikan file ini ada, jika tidak ada silakan beri tanda komentar (//)
        // $this->call(CategorySeeder::class);

        $this->command->newLine();
        $this->command->info('🎉 Seeding Selesai!');
    }
}