<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_reservation',
        'date_reservation',
        'date_voyage',
        'statut',
        'nb_passagers',
        'prix_total',
        'prix_base',
        'prix_options',
        'has_assurance',
        'has_snackbox',
        'has_seat_premium',
        'methode_paiement',
        'reference_paiement',
        'qr_code_path',
        'email_envoye',
        'notes',
        'user_id',
        'segment_id',
    ];

    protected $casts = [
        'date_reservation' => 'datetime',
        'date_voyage' => 'date',
        'prix_total' => 'decimal:2',
        'prix_base' => 'decimal:2',
        'prix_options' => 'decimal:2',
        'has_assurance' => 'boolean',
        'has_snackbox' => 'boolean',
        'has_seat_premium' => 'boolean',
        'email_envoye' => 'boolean',
    ];

    /**
     * Générer un numéro de réservation unique
     */
    public static function generateReservationNumber()
    {
        $date = now()->format('Ymd');
        $lastReservation = self::where('numero_reservation', 'like', "SATAS-$date-%")->latest()->first();
        
        if ($lastReservation) {
            $lastNumber = intval(substr($lastReservation->numero_reservation, -4));
            $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '0001';
        }
        
        return "SATAS-$date-$nextNumber";
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($reservation) {
            if (empty($reservation->numero_reservation)) {
                $reservation->numero_reservation = self::generateReservationNumber();
            }
            
            if (empty($reservation->date_reservation)) {
                $reservation->date_reservation = now();
            }
            
            if (empty($reservation->date_voyage)) {
                $reservation->date_voyage = $reservation->date_reservation;
            }
            
            // Calculer le prix si nécessaire
            if (empty($reservation->prix_total) && $reservation->segment_id) {
                $segment = Segment::find($reservation->segment_id);
                if ($segment) {
                    $reservation->prix_base = $segment->tarifAvecTVA() * $reservation->nb_passagers;
                    $reservation->prix_total = $reservation->prix_base;
                }
            }
        });
    }

    /**
     * Une réservation appartient à un utilisateur
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Une réservation appartient à un segment
     */
    public function segment(): BelongsTo
    {
        return $this->belongsTo(Segment::class);
    }

    /**
     * Une réservation a plusieurs passagers
     */
    public function passagers(): HasMany
    {
        return $this->hasMany(Passager::class);
    }

    /**
     * Une réservation a plusieurs options
     */
    public function options(): HasMany
    {
        return $this->hasMany(ReservationOption::class);
    }

    /**
     * Récupérer le programme via le segment
     */
    public function programme()
    {
        return $this->segment->programme;
    }

    /**
     * Récupérer le bus via le programme
     */
    public function bus()
    {
        return $this->programme->bus;
    }

    /**
     * Récupérer la route via le programme
     */
    public function route()
    {
        return $this->programme->route;
    }

    /**
     * Calculer le prix total avec TVA et options
     */
    public function prixTotalAvecOptions(): float
    {
        $prixBase = $this->segment->tarifAvecTVA() * $this->nb_passagers;
        $prixOptions = 0;
        
        if ($this->has_assurance) {
            $prixOptions += 25 * $this->nb_passagers;
        }
        
        if ($this->has_snackbox) {
            $prixOptions += 15 * $this->nb_passagers;
        }
        
        if ($this->has_seat_premium) {
            $prixOptions += 30 * $this->nb_passagers;
        }
        
        return $prixBase + $prixOptions;
    }

    /**
     * Vérifier si la réservation est confirmée
     */
    public function estConfirmee(): bool
    {
        return $this->statut === 'confirmée';
    }

    /**
     * Vérifier si la réservation est annulable
     */
    public function estAnnulable(): bool
    {
        return in_array($this->statut, ['confirmée', 'en attente']) &&
               now()->diffInHours($this->date_voyage) > 24;
    }

    /**
     * Annuler la réservation
     */
    public function annuler(): bool
    {
        if ($this->estAnnulable()) {
            $this->statut = 'annulée';
            return $this->save();
        }
        return false;
    }

    /**
     * Confirmer la réservation
     */
    public function confirmer($methodePaiement = 'en ligne', $reference = null): bool
    {
        if ($this->statut === 'en attente') {
            $this->statut = 'confirmée';
            $this->methode_paiement = $methodePaiement;
            $this->reference_paiement = $reference;
            return $this->save();
        }
        return false;
    }

    /**
     * Attribuer des sièges automatiquement
     */
    public function attribuerSieges()
    {
        $siegesDisponibles = $this->segment->siegeDisponibles();
        $siegesAttribues = [];
        
        foreach ($this->passagers as $index => $passager) {
            if (isset($siegesDisponibles[$index])) {
                $passager->seat_number = $siegesDisponibles[$index];
                $passager->save();
                $siegesAttribues[] = $siegesDisponibles[$index];
            }
        }
        
        return $siegesAttribues;
    }
}