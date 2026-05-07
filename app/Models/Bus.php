<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    protected $fillable = ['plate_number', 'name', 'capacity', 'type', 'status'];

    public function driver() {
        return $this->hasOne(Driver::class);
    }

    public function schedules() {
        return $this->hasMany(Schedule::class);
    }
}