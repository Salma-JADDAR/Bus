<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom_route',
        'ville_depart',
        'ville_arrivee',
        'description'
    ];

    public function etapes()
    {
        return $this->hasMany(Etape::class);
    }

    public function programmes()
    {
        return $this->hasMany(Programme::class);
    }

    public function segments()
    {
        return $this->hasManyThrough(Segment::class, Programme::class);
    }
}