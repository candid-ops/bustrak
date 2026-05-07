<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $fillable = ['user_id', 'bus_id', 'licence_number', 'status'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function bus() {
        return $this->belongsTo(Bus::class);
    }

    public function schedules() {
        return $this->hasMany(Schedule::class);
    }
}