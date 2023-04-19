<?php

namespace Database\Seeders;

use App\Models\Route;
use Illuminate\Database\Seeder;

class RouteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Route::factory()->create([
            'pickup_station' => 'Cairo',
            'destination_station' => 'Alexandria',
            'distance' => 90,
        ]);

        Route::factory()->create([
            'pickup_station' => 'Cairo',
            'destination_station' => 'Aswan',
            'distance' => 150,
        ]);
    }
}
