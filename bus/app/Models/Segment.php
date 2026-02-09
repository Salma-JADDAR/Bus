<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Segment extends Model
{
    use HasFactory;

    protected $fillable = [
        'tarif',
        'duree_estimee',
        'distance_km',
        'ville_depart_id',
        'ville_arrivee_id',
        'programme_id',
        'depart_etape_id',
        'arrivee_etape_id',
    ];

    protected $casts = [
        'tarif' => 'decimal:2',
        'distance_km' => 'float',
        'duree_estimee' => 'datetime:H:i',
    ];

    /**
     * Un segment appartient à un programme
     */
    public function programme(): BelongsTo
    {
        return $this->belongsTo(Programme::class);
    }

    /**
     * Un segment part d'une ville
     */
    public function villeDepart(): BelongsTo
    {
        return $this->belongsTo(Ville::class, 'ville_depart_id');
    }

    /**
     * Un segment arrive dans une ville
     */
    public function villeArrivee(): BelongsTo
    {
        return $this->belongsTo(Ville::class, 'ville_arrivee_id');
    }

    /**
     * Un segment part d'une étape
     */
    public function departEtape(): BelongsTo
    {
        return $this->belongsTo(Etape::class, 'depart_etape_id');
    }

    /**
     * Un segment arrive à une étape
     */
    public function arriveeEtape(): BelongsTo
    {
        return $this->belongsTo(Etape::class, 'arrivee_etape_id');
    }

    /**
     * Un segment a plusieurs réservations
     */
  /**
 * Un segment a plusieurs réservations
 */
public function reservations(): HasMany
{
    return $this->hasMany(Reservation::class);
}
  /**
 * Calculer le tarif avec TVA (20%)
 */
public function tarifAvecTVA(): float
{
    return round($this->tarif * 1.20, 2);
}

    /**
     * Vérifier si le segment est disponible pour réservation
     */
    public function estDisponible(): bool
    {
        return $this->programme->actif && 
               !$this->programme->estComplet();
    }

    /**
     * Accessor pour la description du segment
     */
    public function getDescriptionAttribute(): string
    {
        return "De {$this->villeDepart->nom} à {$this->villeArrivee->nom} " .
               "({$this->distance_km} km, {$this->duree_estimee->format('H:i')} h)";
    }

    /**
     * Récupérer les sièges occupés sur ce segment
     */
    public function siegeOccupes()
    {
        return $this->reservations()
            ->where('statut', 'confirmée')
            ->pluck('seat_number');
    }

    /**
     * Récupérer les sièges disponibles
     */
  public function siegeDisponibles()
{
    $occupes = $this->reservations()
        ->whereIn('statut', ['en attente', 'confirmée'])
        ->pluck('seat_number')
        ->filter()
        ->values();
    
    $capacite = $this->programme->bus->capacite;
    
    return collect(range(1, $capacite))
        ->reject(fn($siege) => $occupes->contains($siege))
        ->values();
}

      /**
     * Scope pour les segments entre deux villes
     */
    public function scopeBetweenCities(Builder $query, $villeDepartId, $villeArriveeId)
    {
        return $query->where('ville_depart_id', $villeDepartId)
                     ->where('ville_arrivee_id', $villeArriveeId);
    }

    /**
     * Scope pour les segments actifs (avec programme actif)
     */
    public function scopeActive(Builder $query)
    {
        return $query->whereHas('programme', function ($q) {
            $q->where('actif', true);
        });
    }

    /**
     * Scope pour les segments avec des places disponibles
     */
    public function scopeWithAvailableSeats(Builder $query, $date, $passagers)
    {
        return $query->whereHas('programme.bus', function ($q) use ($date, $passagers) {
            $q->where('capacite', '>=', function ($sub) use ($date) {
                $sub->selectRaw('COUNT(*) + ?')
                    ->from('reservations')
                    ->whereColumn('segment_id', 'segments.id')
                    ->whereDate('date_reservation', $date)
                    ->where('statut', 'confirmée');
            }, $passagers);
        });
    }

    /**
 * Récupérer les sièges disponibles
 */
 
}