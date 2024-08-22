<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate([
            'name' => 'Admin Horse',
            'email' => 'admin@admin.com',
            'email_verified_at' => now(),
            'phone' => fake()->phoneNumber(),
            'password' => Hash::make('password123'),
            'remember_token' => Str::random(10),
            'profile_picture' => 'https://ui-avatars.com/api/?background=random&name=AdminHorse',
            'is_admin' => true,
        ]);
    }
}
