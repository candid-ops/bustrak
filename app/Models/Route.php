<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    protected $fillable = [
        'origin', 'destination', 'stops',
        'distance_km', 'fare', 'type', 'is_active'
    ];

    protected $casts = [
        'stops' => 'array',
    ];

    public function schedules() {
        return $this->hasMany(Schedule::class);
    }
}