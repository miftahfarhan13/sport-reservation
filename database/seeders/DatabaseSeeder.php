<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\PaymentMethod;
use App\Models\Province;
use App\Models\SportActivity;
use App\Models\SportActivityParticipant;
use App\Models\SportCategory;
use App\Models\User;
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

        SportActivity::create([
            'sport_category_id' => 4,
            'city_id' => 3171,
            'user_id' => 1,
            'title' => 'Happytepokbulu! @vinduss',
            'description' => '<p><strong>BARENG HAPPYTEBOX YUK!</strong><br>2024!</p><ul><li>Male & Female very welcome</li><li>All Level Beginner - Intermediate</li><li>3 lap, sudah ada member diluar rovo</li><li>Sistem rally 25 point</li><li>Include shuttlecock (kita pakai NINE/setara)</li></ul><p><strong>Fee:</strong> 60.000</p><p>PM buat join Grup kita biar rutin 😉</p><p>Thank you 🙏</p>',
            'price' => 60000,
            'price_discount' => 70000,
            'slot' => 30,
            'address' => 'Jl Pioner No. 50a, Penjaringan, Kec. Penjaringan, Jakarta 14440',
            'map_url' => 'https://maps.app.goo.gl/vmByccAmBx7xQXNL9',
            'activity_date' => '2024-08-11',
            'start_time' => '09:00',
            'end_time' => '12:00',
        ]);

        SportActivityParticipant::create([
            'sport_activity_id' => 1,
            'user_id' => 2,
        ]);

        PaymentMethod::create([
            'name' => 'BCA',
            'virtual_account_number' => '1234-5678-0001234567',
            'virtual_account_name' => 'dibimbing',
            'image_url' => 'https://dibimbing-cdn.sgp1.cdn.digitaloceanspaces.com/bca-logo.svg'
        ]);

        PaymentMethod::create([
            'name' => 'Bank BRI',
            'virtual_account_number' => '9101-1121-0023456789',
            'virtual_account_name' => 'dibimbing',
            'image_url' => 'https://dibimbing-cdn.sgp1.cdn.digitaloceanspaces.com/bri-logo.svg'
        ]);

        PaymentMethod::create([
            'name' => 'Bank Mandiri',
            'virtual_account_number' => '2718-1223-0045678901',
            'virtual_account_name' => 'dibimbing',
            'image_url' => 'https://dibimbing-cdn.sgp1.cdn.digitaloceanspaces.com/mandiri-logo.svg'
        ]);

        PaymentMethod::create([
            'name' => 'Bank BNI',
            'virtual_account_number' => '5678-1234-0012345678',
            'virtual_account_name' => 'dibimbing',
            'image_url' => 'https://dibimbing-cdn.sgp1.cdn.digitaloceanspaces.com/bni-logo.svg'
        ]);
    }
}
