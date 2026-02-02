<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Programme extends Model
{
    use HasFactory;

    protected $fillable = [
        'heure_arrivee',
        'actif',
        'bus_id',
        'route_id'
    ];

    protected $casts = [
        'heure_arrivee' => 'datetime:H:i',
        'actif' => 'boolean'
    ];

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function segments()
    {
        return $this->hasMany(Segment::class);
    }

    public function reservations()
    {
        return $this->hasManyThrough(Reservation::class, Segment::class);
    }
}