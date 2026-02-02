<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_immatriculation',
        'capacite',
        'marque',
        'modele',
        'disponible'
    ];

    public function programmes()
    {
        return $this->hasMany(Programme::class);
    }

    public function reservations()
    {
        return $this->hasManyThrough(Reservation::class, Programme::class);
    }
}