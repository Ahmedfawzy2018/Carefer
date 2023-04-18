<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "buses";
    protected $fillable = ['name', 'capacity', 'type', 'status'];

    public function seats()
    {
        return $this->hasMany(Seat::class, 'bus_id') ;
    }
}
