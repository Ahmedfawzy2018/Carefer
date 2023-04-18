<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "reservations";
    protected $fillable = ['user_id', 'bus_id', 'route_id', 'total', 'discount', 'email', 'status'];

    public function user() {
        return $this->belongsTo(User::class, 'user_id') ;
    }

    public function bus() {
        return $this->belongsTo(Bus::class, 'bus_id') ;
    }

    public function route() {
        return $this->belongsTo(Route::class, 'route_id') ;
    }
}
