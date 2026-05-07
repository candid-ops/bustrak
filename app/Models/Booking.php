<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id', 'schedule_id', 'seat_number',
        'travel_date', 'status', 'booking_reference'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function schedule() {
        return $this->belongsTo(Schedule::class);
    }

    public function payment() {
        return $this->hasOne(Payment::class);
    }
}