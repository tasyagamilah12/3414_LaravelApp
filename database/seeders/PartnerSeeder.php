<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Partner;

class PartnerSeeder extends Seeder
{
    public function run(): void
    {
        $partners = [
            ['name' => 'Direktorat Kemahasiswaan Amikom'],
            ['name' => 'Himpunan Mahasiswa Sistem Informasi'],
            ['name' => 'Kadin Yogyakarta'],
        ];

        foreach ($partners as $partner) {
            Partner::firstOrCreate(
                ['name' => $partner['name']]
            );
        }
    }
}