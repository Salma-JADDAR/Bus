<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Route extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom_route',
        'ville_depart_id',
        'ville_arrivee_id',
        'description',
    ];

    /**
     * Une route part d'une ville
     */
    public function villeDepart(): BelongsTo
    {
        return $this->belongsTo(Ville::class, 'ville_depart_id');
    }

    /**
     * Une route arrive dans une ville
     */
    public function villeArrivee(): BelongsTo
    {
        return $this->belongsTo(Ville::class, 'ville_arrivee_id');
    }

    /**
     * Une route a plusieurs étapes
     */
    public function etapes(): HasMany
    {
        return $this->hasMany(Etape::class);
    }

    /**
     * Une route a plusieurs programmes
     */
    public function programmes(): HasMany
    {
        return $this->hasMany(Programme::class);
    }

    /**
     * Récupérer les étapes triées par ordre
     */
    public function etapesOrdonnees()
    {
        return $this->etapes()->orderBy('ordre');
    }

    /**
     * Récupérer les programmes actifs de cette route
     */
    public function programmesActifs()
    {
        return $this->programmes()->where('actif', true);
    }

    /**
     * Vérifier si la route relie deux villes spécifiques
     */
    public function relieVilles($villeDepartId, $villeArriveeId): bool
    {
        return $this->ville_depart_id == $villeDepartId && 
               $this->ville_arrivee_id == $villeArriveeId;
    }

    /**
     * Calculer la durée totale estimée de la route
     */
    public function dureeTotaleEstimee()
    {
        // Somme des durées des segments de cette route
        return $this->programmes()->with('segments')->get()
            ->flatMap->segments
            ->sum('duree_estimee');
    }
}