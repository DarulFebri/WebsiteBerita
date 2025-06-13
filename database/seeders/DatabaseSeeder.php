<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Jalankan seed database.
     */
    public function run(): void
    {
        // Panggil seeder AdminUserSeeder terlebih dahulu
        $this->call(AdminUserSeeder::class);
        // Kemudian panggil seeder NewsSeeder
        $this->call(NewsSeeder::class);
    }
}
