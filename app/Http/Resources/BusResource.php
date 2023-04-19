<?php

namespace App\Http\Resources;

use App\Enums\SeatStatusEnum;
use Illuminate\Http\Resources\Json\JsonResource;

class BusResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'capacity' => $this->capacity,
            'type' => $this->type,
            'status' => $this->status,
            'seats' => SeatResource::collection(
                $this->seats()->where('status', SeatStatusEnum::AVAILABLE)->select('id', 'seat_number')->get()
            )
        ];
    }
}
