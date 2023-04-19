<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\{
    ReserveBusRequest,
    updateReserveBusRequest
};

use App\Models\Reservation;
use App\Services\ReservationService;
use Illuminate\Http\Request;

class ReservationController extends Controller
{

    public function __construct()
    {
        $this->middleware(['check.bus.availability', 'check.reservation.time.limit'])->only('store');
    }

    /**
     * List Reservations
     * @queryParam limit  Number of records per page .

     * @apiResourceCollection  App\Http\Resources\ReservationResource
     * @apiResourceModel App\Models\Reservation
     */
    public function index(Request $request, ReservationService $reservationService)
    {
        return $reservationService->list($request);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Reserve Bus
     * @bodyParam bus_id integer required Bus ID which we need to reserve.
     * @bodyParam route_id integer required Route ID which we need to Take.
     * @bodyParam seats required array Seats IDs.

     * @apiResourceCollection  App\Http\Resources\ReservationResource
     * @apiResourceModel App\Models\Reservation
     */
    public function store(ReserveBusRequest $request, ReservationService $reservationService)
    {
        return $reservationService->reserve($request);
    }

    /**
     * Read Reservation
     * @urlParam reservation integer required Reservation ID.

     * @apiResourceCollection  App\Http\Resources\ReservationResource
     * @apiResourceModel App\Models\Reservation
     */
    public function show(Reservation $reservation, ReservationService $reservationService)
    {
        return $reservationService->show($reservation);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update Reservation
     * @bodyParam status integer required Status of Reservation need to chnage.

     * @apiResourceCollection  App\Http\Resources\ReservationResource
     * @apiResourceModel App\Models\Reservation
     */
    public function update(Reservation $reservation, updateReserveBusRequest $request, ReservationService $reservationService)
    {
        return $reservationService->update($reservation, $request);
    }

    /**
     * Update Reservation
     * @urlParam reservation integer required Which one we need to delete.
     */
    public function destroy(Reservation $reservation, ReservationService $reservationService)
    {
        return $reservationService->destroy($reservation);
    }

    public function mostFrequentTrip(ReservationService $reservationService)
    {
        return $reservationService->mostFrequentTrip();
    }
}
