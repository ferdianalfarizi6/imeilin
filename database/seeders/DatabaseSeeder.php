<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Service;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin User
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@gmail.com', // Actually user requested dynamic in admin panel but standard seed is good
            'password' => Hash::make('password'),
            'role' => 'admin',
            'whatsapp' => '088706553307',
            'referral_code' => 'ADMIN-123'
        ]);

        // Default Service
        Service::create([
            'name' => 'Unblock IMEI Aktif 3 Bulan',
            'price' => 150000,
            'description' => 'Layanan unblock IMEI permanen selama 3 bulan. Proses cepat 1-3 hari kerja.',
            'is_active' => true,
        ]);
    }
}
