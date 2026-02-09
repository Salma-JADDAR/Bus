<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReservationOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_id',
        'passager_id',
        'option_type',
        'option_name',
        'option_description',
        'prix',
        'quantite',
    ];

    protected $casts = [
        'prix' => 'decimal:2',
        'quantite' => 'integer',
    ];

    // Ajoutez cette constante
    const OPTIONS = [
        'assurance' => [
            'name' => 'Assurance Annulation',
            'description' => 'Remboursement de 80% en cas d\'annulation jusqu\'à 2h avant le départ',
            'prix' => 25,
            'unite' => 'par passager',
        ],
        'snackbox' => [
            'name' => 'Snack-box SATAS',
            'description' => 'Eau minérale + Sandwich + Fruit + Chocolat',
            'prix' => 15,
            'unite' => 'par passager',
        ],
        'seat_premium' => [
            'name' => 'Siège Premium',
            'description' => 'Sièges première rangée avec espace jambes supplémentaire',
            'prix' => 30,
            'unite' => 'par passager',
        ],
    ];

    /**
     * Une option appartient à une réservation
     */
    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    /**
     * Une option peut appartenir à un passager
     */
    public function passager(): BelongsTo
    {
        return $this->belongsTo(Passager::class);
    }

    /**
     * Calculer le sous-total
     */
    public function getSubtotalAttribute(): float
    {
        return $this->prix * $this->quantite;
    }

    /**
     * Récupérer les informations de l'option
     */
    public function getOptionInfoAttribute(): array
    {
        return self::OPTIONS[$this->option_type] ?? [
            'name' => $this->option_name,
            'description' => $this->option_description,
            'prix' => $this->prix,
            'unite' => 'unité',
        ];
    }
}