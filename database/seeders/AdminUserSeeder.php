<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon; // Import Carbon

class AdminUserSeeder extends Seeder
{
    /**
     * Jalankan seed database.
     */
    public function run(): void
    {
        // Buat user admin jika belum ada
        if (!User::where('email', 'admin@example.com')->exists()) {
            User::create([
                'name' => 'Admin Berita',
                'email' => 'admin@example.com',
                'email_verified_at' => Carbon::now(), // Langsung verifikasi email admin
                'password' => Hash::make('password'), // Ganti dengan password yang lebih kuat di produksi
                'role' => User::ADMIN_ROLE,
            ]);
            $this->command->info('User admin berhasil dibuat!');
        } else {
            $this->command->info('User admin sudah ada.');
        }

        // Buat user biasa jika belum ada
        if (!User::where('email', 'user@example.com')->exists()) {
            User::create([
                'name' => 'User Biasa',
                'email' => 'user@example.com',
                'email_verified_at' => Carbon::now(), // Verifikasi user biasa juga
                'password' => Hash::make('password'),
                'role' => User::USER_ROLE,
            ]);
            $this->command->info('User biasa berhasil dibuat!');
        } else {
            $this->command->info('User biasa sudah ada.');
        }
    }
}
