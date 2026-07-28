<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class OrganizerSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            [
                'email' => 'organizer@amikom.ac.id'
            ],
            [
                'name' => 'Organizer Event',
                'password' => Hash::make('password'),
                'role' => 'user'
            ]
        );
    }
}