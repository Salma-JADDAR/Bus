<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Passager extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_id',
        'nom_complet',
        'cin',
        'date_naissance',
        'type_passager',
        'seat_number',
        'has_assurance',
        'has_snackbox',
        'has_seat_premium',
        'besoins_speciaux',
        'email',
        'telephone',
    ];

    protected $casts = [
        'date_naissance' => 'date',
        'has_assurance' => 'boolean',
        'has_snackbox' => 'boolean',
        'has_seat_premium' => 'boolean',
    ];

    /**
     * Un passager appartient à une réservation
     */
    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    /**
     * Vérifier si le passager est un enfant
     */
    public function estEnfant(): bool
    {
        return $this->type_passager === 'enfant';
    }

    /**
     * Calculer l'âge du passager
     */
    public function getAgeAttribute(): int
    {
        return $this->date_naissance ? now()->diffInYears($this->date_naissance) : 0;
    }

    /**
     * Calculer le prix des options pour ce passager
     */
    public function prixOptions(): float
    {
        $prix = 0;
        
        if ($this->has_assurance) {
            $prix += 25;
        }
        
        if ($this->has_snackbox) {
            $prix += 15;
        }
        
        if ($this->has_seat_premium) {
            $prix += 30;
        }
        
        return $prix;
    }
}