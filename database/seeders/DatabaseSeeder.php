<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Province;
use App\Models\SportCategory;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
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

        Province::truncate();
        City::truncate();

        $jsonProvinces = File::get(database_path('json/provinces.json'));
        $dataProvinces = json_decode($jsonProvinces);
        $dataProvinces = collect($dataProvinces);

        foreach ($dataProvinces as $d) {
            $d = collect($d)->toArray();
            $p = new Province();
            $p->fill($d);
            $p->save();
        }

        $jsonCities = File::get(database_path('json/cities.json'));
        $dataCities = json_decode($jsonCities);
        $dataCities = collect($dataCities);

        foreach ($dataCities as $d) {
            $d = collect($d)->toArray();
            $p = new City();
            $p->fill($d);
            $p->save();
        }
    }
}
