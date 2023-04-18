<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Seat extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "seats";
    protected $fillable = ['bus_id', 'seat_number', 'status'];

    public function bus()
    {
        return $this->belongsTo(Bus::class, 'bus_id') ;
    }
}
