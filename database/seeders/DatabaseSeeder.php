<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Buat akun organizer default (HM SPI UINSA)
        User::firstOrCreate(
            ['email' => 'admin@musabaqahtarikhislam.com'],
            [
                'name' => 'Admin HM SPI UINSA',
                'password' => Hash::make('password'),
                'role' => 'organizer',
                'is_active' => true,
            ]
        );

        // Buat peserta contoh untuk testing
        User::firstOrCreate(
            ['participant_id' => 'PESERTA-001'],
            [
                'name' => 'Peserta Test',
                'email' => 'peserta@test.com',
                'password' => Hash::make('AKSES123'),
                'role' => 'participant',
                'is_active' => true,
            ]
        );

        $this->call([
            DummyEventSeeder::class,
        ]);
    }
}
