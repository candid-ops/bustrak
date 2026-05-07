<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'route_id', 'bus_id', 'driver_id',
        'departure_time', 'days', 'status'
    ];

    protected $casts = [
        'days' => 'array',
    ];

    public function route() {
        return $this->belongsTo(Route::class);
    }

    public function bus() {
        return $this->belongsTo(Bus::class);
    }

    public function driver() {
        return $this->belongsTo(Driver::class);
    }

    public function bookings() {
        return $this->hasMany(Booking::class);
    }
}