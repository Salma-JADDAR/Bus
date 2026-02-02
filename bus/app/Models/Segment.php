<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Segment extends Model
{
    use HasFactory;

    protected $fillable = [
        'tarif',
        'duree_estimee',
        'distance_km',
        'programme_id',
        'depart_etape_id',
        'arrivee_etape_id'
    ];

    protected $casts = [
        'duree_estimee' => 'datetime:H:i',
        'tarif' => 'decimal:2',
        'distance_km' => 'float'
    ];

    public function programme()
    {
        return $this->belongsTo(Programme::class);
    }

    public function etapeDepart()
    {
        return $this->belongsTo(Etape::class, 'depart_etape_id');
    }

    public function etapeArrivee()
    {
        return $this->belongsTo(Etape::class, 'arrivee_etape_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}