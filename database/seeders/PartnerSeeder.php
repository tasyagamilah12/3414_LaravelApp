<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Partner;

class PartnerSeeder extends Seeder
{
    public function run(): void
    {
        // Data partner statis yang aman tanpa fungsi fake()
        $partners = [
            [
                'name' => 'Direktorat Kemahasiswaan Amikom',
                'logo' => 'partners/amikom.png',
            ],
            [
                'name' => 'Himpunan Mahasiswa Sistem Informasi',
                'logo' => 'partners/himasi.png',
            ],
            [
                'name' => 'Kadin Yogyakarta',
                'logo' => 'partners/kadin.png',
            ],
        ];

        foreach ($partners as $partner) {
            Partner::firstOrCreate(
                ['name' => $partner['name']],
                ['logo' => $partner['logo']]
            );
        }
    }
}