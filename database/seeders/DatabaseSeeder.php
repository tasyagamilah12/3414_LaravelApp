<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Event;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Superadmin
        $admin = User::create([
            'name'              => 'Superadmin Amikom',
            'email'             => 'admin@amikom.ac.id',
            'password'          => Hash::make('p'),
            'role'              => 'admin',
            'organization_name' => 'Direktorat Kemahasiswaan',
        ]);

        // 2. Akun Organizer (HIMA SI)
        $hima = User::create([
            'name'              => 'HIMA SI Amikom',
            'email'             => 'himasi@amikom.ac.id',
            'password'          => Hash::make('password123'),
            'role'              => 'organizer',
            'organization_name' => 'Himpunan Mahasiswa Sistem Informasi',
        ]);

        // 3. Kategori Event
        $seminar = Category::create([
            'name' => 'Seminar IT',
            'slug' => 'seminar-it',
        ]);

        $entertainment = Category::create([
            'name' => 'Entertainment',
            'slug' => 'entertainment',
        ]);

        $workshop = Category::create([
            'name' => 'Workshop & Tech',
            'slug' => 'workshop-tech',
        ]);

        // 4. Daftar Event
        Event::create([
            'user_id'     => $hima->id,
            'category_id' => $entertainment->id,
            'title'       => 'Jazz Night 2026',
            'description' => 'Nikmati malam dengan musik jazz.',
            'date'        => '2026-08-10 19:00:00',
            'location'    => 'Amikom Baru',
            'price'       => 50000,
            'stock'       => 100,
            'poster_path' => 'posters/event-1.png',
        ]);

        $eventPaid = Event::create([
            'user_id'     => $hima->id,
            'category_id' => $seminar->id,
            'title'       => 'AI Summit 2026',
            'description' => 'Tren terbaru kecerdasan buatan dan implikasinya.',
            'date'        => '2026-08-15 13:00:00',
            'location'    => 'Ruang Cinema Amikom',
            'price'       => 45000,
            'stock'       => 150,
            'poster_path' => 'posters/event-2.png',
        ]);

        Event::create([
            'user_id'     => $hima->id,
            'category_id' => $workshop->id,
            'title'       => 'UI/UX Masterclass',
            'description' => 'Belajar desain UI/UX modern dari dasar.',
            'date'        => '2026-08-20 10:00:00',
            'location'    => 'Lab 1',
            'price'       => 75000,
            'stock'       => 50,
            'poster_path' => 'posters/event-3.png',
        ]);

        Event::create([
            'user_id'     => $hima->id,
            'category_id' => $workshop->id,
            'title'       => 'Web Development Bootcamp: Laravel 12',
            'description' => 'Pelatihan gratis belajar Laravel dari dasar untuk mahasiswa.',
            'date'        => '2026-08-25 09:00:00',
            'location'    => 'Lab 2 Amikom',
            'price'       => 0,
            'stock'       => 80,
            'poster_path' => 'posters/event-4.png',
        ]);

        Event::create([
            'user_id'     => $hima->id,
            'category_id' => $entertainment->id,
            'title'       => 'E-Sport Tournament 2026',
            'description' => 'Turnamen game terbesar antarmahasiswa.',
            'date'        => '2026-09-01 15:00:00',
            'location'    => 'Auditorium Amikom',
            'price'       => 30000,
            'stock'       => 200,
            'poster_path' => 'posters/event-5.png',
        ]);

        Event::create([
            'user_id'     => $hima->id,
            'category_id' => $seminar->id,
            'title'       => 'Cyber Security Talk',
            'description' => 'Belajar dasar-dasar keamanan digital dan cyber defense.',
            'date'        => '2026-09-10 13:00:00',
            'location'    => 'Ruang 101 Amikom',
            'price'       => 40000,
            'stock'       => 120,
            'poster_path' => 'posters/event-6.png',
        ]);

        // 5. Sampel Transaksi Sukses
        Transaction::create([
            'event_id'       => $eventPaid->id,
            'user_id'        => $admin->id,
            'order_id'       => 'TRX-DEMO-' . strtoupper(Str::random(6)),
            'customer_name'  => 'Mahasiswa Budi',
            'customer_email' => 'budi@students.amikom.ac.id',
            'customer_phone' => '081234567890',
            'total_price'    => 45000,
            'payment_type'   => 'bank_transfer',
            'status'         => 'settlement',
            'is_used'        => false,
        ]);

        // 6. Pemanggilan Seeder Pendukung yang Aman
        $this->call([
            PartnerSeeder::class,
        ]);
    }
}