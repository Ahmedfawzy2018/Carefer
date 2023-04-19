<?php

namespace App\Http\Middleware;

use App\Enums\BusStatusEnum;
use App\Enums\BusTypeEnum;
use App\Models\Bus;
use App\Models\Reservation;
use App\Models\Route;
use Closure;
use Illuminate\Http\Request;

class CheckBusAvailability
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $bus = Bus::findOrFail($request->get('bus_id'));

        //1. Check if the Bus is Open for booking
        if ($bus->status != BusStatusEnum::OPEN) {
            return response()->json(['error' => 'The bus is not available for reservation'], 400);
        }

        //2. Check the Bus capacity
        $seats = $bus->seats()->whereIn('id', $request->get('seats'))->groupBy('id')->pluck('id')->toArray();

        if ($bus->capacity < count($seats)) {
            return response()->json(['error' => 'Sorry, your reservations seats is more that bus capacity.'], 400);
        }

        //3. Check the Bus total reservations
        $todayReservationsCount = Reservation::where('bus_id', $bus->id)->where('status', 1)->whereDate('reservation_date', today())->sum('seats_count');
        if ($bus->capacity < (count($seats) + $todayReservationsCount) ) {
            $exceedString = $todayReservationsCount == $bus->capacity ?  "Bus Seats all Reserved." : "Only ".($bus->capacity -  $todayReservationsCount)." seats Available.";
            return response()->json(['error' => 'Sorry, your required seats will exceed the capacity of the Bus. '.$exceedString], 400);
        }

        //4. Check the Bus type and Route Type are compatible or not.
        $route = Route::findOrFail($request->get('route_id'));
        if(($bus->type == BusTypeEnum::LONG_ROUTE && $route->distance < 100) || ($bus->type == BusTypeEnum::SHORT_ROUTE && $route->distance > 100))
        {
            return response()->json(['error' => 'The bus is not compatible with the Route.'], 400);
        }

        return $next($request);
    }
}
