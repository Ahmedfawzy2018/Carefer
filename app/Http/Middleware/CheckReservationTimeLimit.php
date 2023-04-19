<?php

namespace App\Http\Middleware;

use App\Models\Bus;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CheckReservationTimeLimit
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
        // Check if the bus is available for reservation
        $bus = Bus::findOrFail($request->get('bus_id'));
        $lockKey = 'bus_'.$bus->id.'_lock';
        $expiresAt = now()->addMinutes(2);

        if (!Cache::add($lockKey, true, $expiresAt)) {
            // The bus is not available for reservation at this time
            return response()->json(['error' => 'The bus is currently unavailable for reservation. Please try again later.'], 400);
        }

        if (Cache::has($lockKey) && now() > $expiresAt) {
            // Release the lock
            Cache::forget($lockKey);
        }

        return $next($request);
    }
}
