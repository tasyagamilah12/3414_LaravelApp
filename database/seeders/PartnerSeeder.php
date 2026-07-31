<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Partner;
use Faker\Factory as Faker;

class PartnerSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // Contoh perbaikan isi seeder menggunakan $faker
        Partner::create([
            'name' => $faker->company,
            'logo' => $faker->imageUrl(),
        ]);
    }
}