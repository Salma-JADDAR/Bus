<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ville extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'region',
        'pays',
        'description',
    ];

    /**
     * Une ville a plusieurs gares
     */
    public function gares(): HasMany
    {
        return $this->hasMany(Gare::class);
    }

    /**
     * Une ville a plusieurs utilisateurs
     */
    public function utilisateurs(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Une ville a plusieurs bus stationnés
     */
    public function buses(): HasMany
    {
        return $this->hasMany(Bus::class);
    }

    /**
     * Une ville est le départ de plusieurs routes
     */
    public function routesDepart(): HasMany
    {
        return $this->hasMany(Route::class, 'ville_depart_id');
    }

    /**
     * Une ville est l'arrivée de plusieurs routes
     */
    public function routesArrivee(): HasMany
    {
        return $this->hasMany(Route::class, 'ville_arrivee_id');
    }

    /**
     * Une ville est le départ de plusieurs segments
     */
    public function segmentsDepart(): HasMany
    {
        return $this->hasMany(Segment::class, 'ville_depart_id');
    }

    /**
     * Une ville est l'arrivée de plusieurs segments
     */
    public function segmentsArrivee(): HasMany
    {
        return $this->hasMany(Segment::class, 'ville_arrivee_id');
    }

    /**
     * Récupérer la gare principale de la ville
     */
    public function garePrincipale()
    {
        return $this->gares()->where('principale', true)->first();
    }
}