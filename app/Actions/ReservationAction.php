<?php

namespace App\Actions;

use App\Http\Requests\ReserveBusRequest;
use App\Models\Bus;
use App\Models\Seat;

class ReservationAction
{
    protected $request;
    protected $user;

    public function __construct(ReserveBusRequest $request)
    {
        $this->request = $request->validated() ;
        $this->user = auth()->user();
    }

    public function execute()
    {
        return $this->user->bookings()->create($this->prePrepareData());
    }

    private function prePrepareData()
    {
        $row = [];
        $bus = Bus::find($this->request['bus_id']) ;
        $seats = $bus->seats()->whereIn('id', $this->request['seats'])->groupBy('id')->pluck('id')->toArray();

        $total = $bus->seat_price * count($seats);
        $discount = 0;
        if(count($seats) > 5) {
            $discount = $total * 10 / 100 ; //Discount 10%
            $total -= $discount ;
        }
        $row['bus_id'] = $this->request['bus_id'] ;
        $row['route_id'] = $this->request['route_id'] ;
        $row['seats_count'] = count($seats) ;
        $row['seats_price'] = $bus->seat_price ;
        $row['total'] = $total ;
        $row['discount'] = $discount ;
        $row['email'] = $this->user->email ;
        $row['reservation_date'] = today();

        return $row;
    }
}
