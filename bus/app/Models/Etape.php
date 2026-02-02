<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Etape extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom_etape',
        'adresse',
        'ville',
        'heure_passage',
        'ordre',
        'route_id'
    ];

    protected $casts = [
        'heure_passage' => 'datetime:H:i'
    ];

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function segmentsDepart()
    {
        return $this->hasMany(Segment::class, 'depart_etape_id');
    }

    public function segmentsArrivee()
    {
        return $this->hasMany(Segment::class, 'arrivee_etape_id');
    }
}