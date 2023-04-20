<?php

use App\Models\User;
use Database\Seeders\{
    BusSeeder,
    RouteSeeder,
};

if (! function_exists("seedBus")) {
    function seedBus()
    {
        (new BusSeeder)->run();
    }
}

if (! function_exists("seedRoute")) {
    function seedRoute()
    {
        (new RouteSeeder)->run();
    }
}

if (! function_exists("newUser")) {
    function newUser($params = [], $count = NULL)
    {
        return User::factory($count)->create($params);
    }
}

if (! function_exists("newBus")) {
    function newBus($params = [], $count = NULL)
    {
        $bus = \App\Models\Bus::factory($count)->create($params);

        for ($i = 1; $i <= floor($bus->capacity/2); $i++) {
            $bus->seats()->create(['seat_number' => 'A'.$i]) ;
        }

        for ($i = 1; $i <= floor($bus->capacity/2); $i++) {
            $bus->seats()->create(['seat_number' => 'B'.$i]) ;
        }

        return $bus;
    }
}

if (! function_exists("newRoute")) {
    function newRoute($params = [], $count = NULL)
    {
        return \App\Models\Route::factory($count)->create($params);
    }
}
