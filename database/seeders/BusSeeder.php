<?php

namespace Database\Seeders;

use App\Enums\BusTypeEnum;
use App\Models\Bus;
use Illuminate\Database\Seeder;

class BusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $longBus = Bus::factory()->create([
            'name' => 'Bus One',
            'type' => BusTypeEnum::LONG_ROUTE
        ]);

        for ($i = 1; $i <= floor($longBus->capacity/2); $i++) {
            $longBus->seats()->create(['seat_number' => 'A'.$i]) ;
        }

        for ($i = 1; $i <= floor($longBus->capacity/2); $i++) {
            $longBus->seats()->create(['seat_number' => 'B'.$i]) ;
        }


        $shortBus = Bus::factory()->create([
            'name' => 'Bus Two',
            'type' => BusTypeEnum::SHORT_ROUTE
        ]);

        for ($i = 1; $i <= floor($shortBus->capacity/2); $i++) {
            $shortBus->seats()->create(['seat_number' => 'A'.$i]) ;
        }

        for ($i = 1; $i <= floor($shortBus->capacity/2); $i++) {
            $shortBus->seats()->create(['seat_number' => 'B'.$i]) ;
        }
    }
}
