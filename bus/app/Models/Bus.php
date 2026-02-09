<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bus extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_immatriculation',
        'capacite',
        'marque',
        'modele',
        'ville_id',
        'disponible',
    ];

    protected $casts = [
        'disponible' => 'boolean',
    ];

    /**
     * Un bus appartient à une ville (où il est stationné)
     */
    public function ville(): BelongsTo
    {
        return $this->belongsTo(Ville::class);
    }

    /**
     * Un bus a plusieurs programmes
     */
    public function programmes(): HasMany
    {
        return $this->hasMany(Programme::class);
    }

    /**
     * Récupérer les programmes actifs du bus
     */
    public function programmesActifs()
    {
        return $this->programmes()->where('actif', true);
    }

    /**
     * Récupérer les programmes disponibles (actifs et bus disponible)
     */
    public function programmesDisponibles()
    {
        return $this->programmesActifs()->whereHas('bus', function ($query) {
            $query->where('disponible', true);
        });
    }

    /**
     * Vérifier si le bus est disponible pour une date/heure
     */
    public function estDisponiblePourProgramme($dateHeure)
    {
        // Logique de vérification de disponibilité
        return $this->disponible && 
               !$this->programmes()->where('heure_arrivee', '<=', $dateHeure)
                    ->where('actif', true)->exists();
    }

    /**
     * Accessor pour le nom complet du bus
     */
    public function getNomCompletAttribute(): string
    {
        return $this->marque . ' ' . $this->modele . ' (' . $this->numero_immatriculation . ')';
    }
}