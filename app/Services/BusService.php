<?php

namespace App\Services;

use App\Enums\BusStatusEnum;
use App\Http\Resources\BusResource;
use App\Models\Bus;
use App\Traits\ApiResponseTrait;

class BusService
{
    use ApiResponseTrait;

    public function list($request)
    {
        try {
            $query = Bus::where('status', BusStatusEnum::OPEN)->paginate($request->limit ?? 20) ;
            return $this->respond(
                [
                    'records' => BusResource::collection($query),
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
            $query = Bus::findOrFail($id) ;

            return $this->respond(
                new BusResource($query)
            );
        } catch(\Exception $e) {
            return $this->respondBadRequest($e->getMessage());
        }
    }
}
