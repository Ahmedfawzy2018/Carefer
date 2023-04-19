<?php

namespace App\Services;

use App\Http\Resources\RouteResource;
use App\Models\Route;
use App\Traits\ApiResponseTrait;

class RouteService
{
    use ApiResponseTrait;

    public function list($request)
    {
        try {
            $query = Route::paginate($request->limit ?? 20) ;
            return $this->respond(
                [
                    'records' => RouteResource::collection($query),
                    'total' => $query->total()
                ]
            );
        } catch(\Exception $e) {
            return $this->respondBadRequest($e->getMessage());
        }
    }
}
