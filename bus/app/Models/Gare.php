<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Gare extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom_gare',
        'adresse',
        'telephone',
        'email',
        'principale',
        'services',
        'heure_ouverture',
        'heure_fermeture',
        'ville_id',
    ];

    protected $casts = [
        'principale' => 'boolean',
        'services' => 'array',
    ];

    /**
     * Une gare appartient à une ville
     */
    public function ville(): BelongsTo
    {
        return $this->belongsTo(Ville::class);
    }

    /**
     * Une gare a plusieurs étapes
     */
    public function etapes(): HasMany
    {
        return $this->hasMany(Etape::class);
    }

    /**
     * Une gare est le départ de plusieurs segments
     */
    public function segmentsDepart(): HasMany
    {
        return $this->hasMany(Segment::class, 'depart_etape_id');
    }

    /**
     * Une gare est l'arrivée de plusieurs segments
     */
    public function segmentsArrivee(): HasMany
    {
        return $this->hasMany(Segment::class, 'arrivee_etape_id');
    }

    /**
     * Accessor pour le nom complet de la gare
     */
    public function getNomCompletAttribute(): string
    {
        return $this->nom_gare . ' - ' . $this->ville->nom;
    }

    /**
     * Vérifier si la gare est ouverte à une heure donnée
     */
    public function estOuverte($heure = null): bool
    {
        $heure = $heure ?? now()->format('H:i');
        
        return $heure >= $this->heure_ouverture && $heure <= $this->heure_fermeture;
    }
}