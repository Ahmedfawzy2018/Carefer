<?php

namespace App\Http\Resources;

use App\Enums\ReservationStatusEnum;
use App\Models\Reservation;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
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
            'user' => new UserResource($this->user),
            'bus' => new BusResource($this->bus),
            'route' => new RouteResource($this->route),
            'seats_count' => $this->seats_count,
            'seats_price' => $this->seats_price,
            'total' => $this->total,
            'discount' => $this->discount,
            'email' => $this->email,
            'reservation_date' => $this->reservation_date,
            'cancelled_date' => $this->cancelled_date,
            'status' => Reservation::STATUS_MAPPING[$this->status],
        ];
    }
}
