<?php

namespace App\Actions;

use App\Enums\SeatStatusEnum;
use App\Http\Requests\ReserveBusRequest;
use App\Models\Bus;
use App\Models\Seat;
use Illuminate\Support\Facades\Cache;

class ReservationAction
{
    protected $request;
    protected $user;
    protected array $seats;

    public function __construct(ReserveBusRequest $request)
    {
        $this->request = $request->validated() ;
        $this->user = auth()->user();
    }

    public function execute()
    {
         $booking = $this->user->bookings()->create($this->prePrepareData());
         Cache::forget('bus_'.$this->request['bus_id'].'_lock');

        foreach ($this->seats as $seat){
            $booking->details()->create(['seat_id' => $seat]);

            Seat::whereId($seat)->update(['status' => SeatStatusEnum::BUSY]);
        }

         return $booking;
    }

    private function prePrepareData()
    {
        $row = [];
        $bus = Bus::find($this->request['bus_id']) ;
        $this->seats = $bus->seats()->whereIn('id', $this->request['seats'])->groupBy('id')->pluck('id')->toArray();

        $total = $bus->seat_price * count( $this->seats);
        $discount = 0;
        if(count( $this->seats) > 5) {
            $discount = $total * 10 / 100 ; //Discount 10%
            $total -= $discount ;
        }
        $row['bus_id'] = $this->request['bus_id'] ;
        $row['route_id'] = $this->request['route_id'] ;
        $row['seats_count'] = count( $this->seats) ;
        $row['seats_price'] = $bus->seat_price ;
        $row['total'] = $total ;
        $row['discount'] = $discount ;
        $row['email'] = $this->user->email ;
        $row['reservation_date'] = today();

        return $row;
    }
}
