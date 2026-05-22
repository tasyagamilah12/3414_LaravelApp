<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin
        User::create([
            'name' => 'Admin Amikom',
            'email' => 'admin@amikom.ac.id',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // 2. Categories
        $seminar = Category::create([
            'name' => 'Seminar IT',
            'slug' => 'seminar-it',
        ]);

        $entertainment = Category::create([
            'name' => 'Entertainment',
            'slug' => 'entertainment',
        ]);

        $workshop = Category::create([
            'name' => 'Workshop',
            'slug' => 'workshop',
        ]);

        // 3. Events (6 data)

        Event::create([
            'category_id' => $entertainment->id,
            'title' => 'Jazz Night 2025',
            'description' => 'Nikmati malam dengan musik jazz.',
            'date' => '2026-05-10 19:00:00',
            'location' => 'Amikom Baru',
            'price' => 50000,
            'stock' => 100,
            'poster_path' => 'posters/event-1.png',
        ]);

        Event::create([
            'category_id' => $seminar->id,
            'title' => 'AI Summit 2026',
            'description' => 'Tren terbaru AI.',
            'date' => '2026-05-01 13:00:00',
            'location' => 'Ruang Cinema',
            'price' => 45000,
            'stock' => 150,
            'poster_path' => 'posters/event-2.png',
        ]);

        Event::create([
            'category_id' => $workshop->id,
            'title' => 'UI/UX Masterclass',
            'description' => 'Belajar desain UI/UX.',
            'date' => '2026-06-01 10:00:00',
            'location' => 'Lab 1',
            'price' => 75000,
            'stock' => 50,
            'poster_path' => 'posters/event-3.png',
        ]);

        Event::create([
            'category_id' => $workshop->id,
            'title' => 'Web Development Bootcamp',
            'description' => 'Belajar Laravel dari dasar.',
            'date' => '2026-06-05 09:00:00',
            'location' => 'Lab 2',
            'price' => 100000,
            'stock' => 80,
            'poster_path' => 'posters/event-4.png',
        ]);

        Event::create([
            'category_id' => $entertainment->id,
            'title' => 'E-Sport Tournament',
            'description' => 'Turnamen game terbesar.',
            'date' => '2026-07-01 15:00:00',
            'location' => 'Auditorium',
            'price' => 30000,
            'stock' => 200,
            'poster_path' => 'posters/event-5.png',
        ]);

        Event::create([
            'category_id' => $seminar->id,
            'title' => 'Cyber Security Talk',
            'description' => 'Belajar keamanan digital.',
            'date' => '2026-07-10 13:00:00',
            'location' => 'Ruang 101',
            'price' => 40000,
            'stock' => 120,
            'poster_path' => 'posters/event-6.png',
        ]);
        
        $this->call([
    PartnerSeeder::class
    ]);
    }
}