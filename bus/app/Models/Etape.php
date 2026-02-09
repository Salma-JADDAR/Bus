<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Etape extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom_etape',
        'heure_passage',
        'ordre',
        'route_id',
        'gare_id',
    ];

    protected $casts = [
        'heure_passage' => 'datetime:H:i',
    ];

    /**
     * Une étape appartient à une route
     */
    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class);
    }

    /**
     * Une étape appartient à une gare
     */
    public function gare(): BelongsTo
    {
        return $this->belongsTo(Gare::class);
    }

    /**
     * Une étape est le départ de plusieurs segments
     */
    public function segmentsDepart(): HasMany
    {
        return $this->hasMany(Segment::class, 'depart_etape_id');
    }

    /**
     * Une étape est l'arrivée de plusieurs segments
     */
    public function segmentsArrivee(): HasMany
    {
        return $this->hasMany(Segment::class, 'arrivee_etape_id');
    }

    /**
     * Récupérer la ville de l'étape via la gare
     */
    public function ville()
    {
        return $this->gare ? $this->gare->ville : null;
    }

    /**
     * Accessor pour le nom complet de l'étape
     */
    public function getNomCompletAttribute(): string
    {
        return $this->nom_etape . ' (Ordre: ' . $this->ordre . ')';
    }

    /**
     * Vérifier si c'est la première étape de la route
     */
    public function estPremiereEtape(): bool
    {
        return $this->ordre === 1;
    }

    /**
     * Vérifier si c'est la dernière étape de la route
     */
    public function estDerniereEtape(): bool
    {
        $maxOrdre = $this->route->etapes()->max('ordre');
        return $this->ordre === $maxOrdre;
    }
}