<?php

namespace App\Services;

use App\Actions\ReservationAction;
use App\Enums\ReservationStatusEnum;
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
            $query = Reservation::paginate($request->limit ?? 20) ;
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

    public function show($id)
    {
        try {
            $query = Reservation::findOrFail($id) ;

            return $this->respond(
                new ReservationResource($query)
            );
        } catch(\Exception $e) {
            return $this->respondBadRequest($e->getMessage());
        }
    }
}
