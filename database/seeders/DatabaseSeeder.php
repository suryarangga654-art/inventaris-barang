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
        $this->command->info('🌱 Menghapus duplikasi dan seeding...');

        // 1. Buat Akun Admin
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator Utama',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

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

        $this->command->info('✅ Seeding sukses! Login password: password');
    }
}