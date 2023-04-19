<?php

namespace App\Services;

use App\Http\Resources\frequentlyReservationResource;
use App\Actions\{
    ReservationAction,
    updateReservationAction
};

use App\Http\Resources\ReservationResource;
use App\Models\Reservation;
use App\Traits\ApiResponseTrait;

class ReservationService
{
    use ApiResponseTrait;

    public function reserve($request)
    {
        try {
            (new ReservationAction($request))->execute();

            return $this->respondCreated();
        } catch(\Exception $e) {
            return $this->respondBadRequest($e->getMessage());
        }
    }

    public function list($request)
    {
        try {
            $query = Reservation::filter($request)->paginate($request->limit ?? 20) ;
            return $this->respond(
                [
                    'records' => ReservationResource::collection($query),
                    'total' => $query->total()
                ]
            );
        } catch(\Exception $e) {
            return $this->respondBadRequest($e->getMessage());
        }
    }

    public function show($reservation)
    {
        try {

            return $this->respond(
                new ReservationResource($reservation)
            );
        } catch(\Exception $e) {
            return $this->respondBadRequest($e->getMessage());
        }
    }

    public function update($reservation, $request)
    {
        try {

            (new updateReservationAction($reservation, $request))->execute();

            return $this->respondCreated();
        } catch(\Exception $e) {
            return $this->respondBadRequest($e->getMessage());
        }
    }

    public function destroy($reservation)
    {
        try {
            $reservation->delete() ;

            return $this->respondDeleted();
        } catch(\Exception $e) {
            return $this->respondBadRequest($e->getMessage());
        }
    }

    public function mostFrequentTrip()
    {
        try {
            $reservation = Reservation::with('user', 'route')
                ->whereNull('deleted_at')
                ->select('id', 'user_id', 'route_id')
                ->groupBy('id', 'user_id', 'route_id')
                ->get();

            return $this->respond(
                frequentlyReservationResource::collection($reservation)
            );
        } catch(\Exception $e) {
            return $this->respondBadRequest($e->getMessage());
        }
    }

}
