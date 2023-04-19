<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReservationDetails extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "reservation_details";

    protected $fillable = ['reservation_id', 'seat_id'];

    public function reservation() {
        return $this->belongsTo(Reservation::class, "reservation_id");
    }

    public function seat() {
        return $this->belongsTo(Seat::class, "seat_id");
    }
}
