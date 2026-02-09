<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Programme extends Model
{
    use HasFactory;

    protected $fillable = [
        'heure_arrivee',
        'actif',
        'bus_id',
        'route_id',
    ];

    protected $casts = [
        'actif' => 'boolean',
        'heure_arrivee' => 'datetime:H:i',
    ];

    /**
     * Un programme appartient à un bus
     */
    public function bus(): BelongsTo
    {
        return $this->belongsTo(Bus::class);
    }

    /**
     * Un programme appartient à une route
     */
    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class);
    }

    /**
     * Un programme a plusieurs segments
     */
    public function segments(): HasMany
    {
        return $this->hasMany(Segment::class);
    }

    /**
     * Récupérer les réservations via les segments
     */
    public function reservations()
    {
        return $this->hasManyThrough(Reservation::class, Segment::class);
    }

    /**
     * Calculer le nombre de places disponibles
     */
    public function placesDisponibles(): int
    {
        $placesOccupees = $this->reservations()
            ->where('statut', 'confirmée')
            ->count();
        
        return max(0, $this->bus->capacite - $placesOccupees);
    }

    /**
     * Vérifier si le programme est complet
     */
    public function estComplet(): bool
    {
        return $this->placesDisponibles() === 0;
    }

    /**
     * Récupérer l'heure de départ estimée
     */
    public function heureDepartEstimee()
    {
        // Heure d'arrivée moins la durée totale des segments
        $dureeTotale = $this->segments()->sum('duree_estimee');
        return $this->heure_arrivee->subMinutes($dureeTotale);
    }

    /**
     * Accessor pour le statut du programme
     */
    public function getStatutAttribute(): string
    {
        if (!$this->actif) return 'inactif';
        if ($this->estComplet()) return 'complet';
        return 'disponible';
    }

    /**
     * Scope pour les programmes actifs
     */
    public function scopeActive(Builder $query)
    {
        return $query->where('actif', true);
    }

    /**
     * Scope pour les programmes avec bus disponible
     */
    public function scopeWithAvailableBus(Builder $query)
    {
        return $query->whereHas('bus', function ($q) {
            $q->where('disponible', true);
        });
    }

    /**
     * Scope pour les programmes à une date donnée
     */
    public function scopeForDate(Builder $query, $date)
    {
        // Ici vous pourriez ajouter de la logique si nécessaire
        return $query;
    }



}