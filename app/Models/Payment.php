<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'booking_id', 'phone', 'amount',
        'mpesa_checkout_id', 'mpesa_receipt',
        'status', 'paid_at'
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function booking() {
        return $this->belongsTo(Booking::class);
    }
}