<?php

namespace App\Models;

use App\Enums\ReservationStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "reservations";

    const RESERVED = 1;
    const CANCELLED = 2;
    const COMPLETED = 3;

    CONST STATUS_MAPPING = [
        1 => ReservationStatusEnum::RESERVED,
        2 => ReservationStatusEnum::CANCELLED,
        3 => ReservationStatusEnum::COMPLETED,
    ];
    protected $fillable = [
        'user_id',
        'bus_id',
        'route_id',
        'seats_count',
        'seats_price',
        'total',
        'discount',
        'email',
        'status',
        'reservation_date',
        'cancelled_date',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id') ;
    }

    public function bus() {
        return $this->belongsTo(Bus::class, 'bus_id') ;
    }

    public function route() {
        return $this->belongsTo(Route::class, 'route_id') ;
    }

    public function details() {
        return $this->hasMany(ReservationDetails::class, "reservation_id");
    }

    public function scopeFilter($query, $request)
    {
        $query = $query->where('user_id', auth()->user()->id);
        // If we need to Add some filter for reservations (user, bus , route, etc)
        return $query;
    }
}
