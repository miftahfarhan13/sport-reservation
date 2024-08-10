<?php

namespace Database\Seeders;

use App\Models\SportCategory;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Dibimbing Sport',
            'email' => 'dibimbingsport@gmail.com',
            'role' => 'admin',
            'phone_number' => '0811223344',
            'password' => Hash::make('dibimbingsport2024')
        ]);

        User::factory()->create([
            'name' => 'Marvin D. Morgan',
            'email' => 'marvin@gmail.com',
            'role' => 'user',
            'phone_number' => '0811223345',
            'password' => Hash::make('marvin2024')
        ]);

        SportCategory::create(['name'=>'Sepak Bola']);
        SportCategory::create(['name'=>'Futsal']);
        SportCategory::create(['name'=>'Mini Soccer']);
        SportCategory::create(['name'=>'Badminton']);
        SportCategory::create(['name'=>'Basketball']);
        SportCategory::create(['name'=>'Tenis']);
        SportCategory::create(['name'=>'Tenis Meja']);
        SportCategory::create(['name'=>'Billiard']);
        SportCategory::create(['name'=>'Golf']);
        SportCategory::create(['name'=>'Padel']);
        SportCategory::create(['name'=>'Squash']);
        SportCategory::create(['name'=>'Hockey']);
        SportCategory::create(['name'=>'Volley']);
        SportCategory::create(['name'=>'Running']);
        SportCategory::create(['name'=>'Fitness']);
        SportCategory::create(['name'=>'Pilates']);
        SportCategory::create(['name'=>'Poundfit']);
        SportCategory::create(['name'=>'Yoga']);
    }
}
