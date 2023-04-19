<?php

namespace App\Actions;

use App\Enums\SeatStatusEnum;
use App\Http\Requests\updateReserveBusRequest;
use App\Models\{
    Reservation,
    Seat
};

class updateReservationAction
{
    protected $request;
    protected $reservation;

    public function __construct(Reservation $reservation, updateReserveBusRequest $request)
    {
        $this->request = $request->validated() ;
        $this->reservation = $reservation;
    }

    public function execute()
    {
        if($this->request['status'] == Reservation::CANCELLED) {
            $this->request['cancelled_date'] = now();

            $seats = $this->reservation->details()->pluck('seat_id')->toArray();
        }

        $this->reservation->update($this->request);

        Seat::whereIn('id', $seats)->update(['status' => SeatStatusEnum::AVAILABLE]);
    }


}
