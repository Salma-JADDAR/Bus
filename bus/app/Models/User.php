<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'ville_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Un utilisateur appartient à une ville
     */
    public function ville(): BelongsTo
    {
        return $this->belongsTo(Ville::class);
    }

    /**
     * Un utilisateur a plusieurs réservations
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Vérifier si l'utilisateur est un admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Vérifier si l'utilisateur est un client
     */
    public function isClient(): bool
    {
        return $this->role === 'client';
    }

    /**
     * Récupérer les réservations confirmées de l'utilisateur
     */
    public function reservationsConfirmees()
    {
        return $this->reservations()->where('statut', 'confirmée');
    }

    /**
     * Récupérer les réservations en attente de l'utilisateur
     */
    public function reservationsEnAttente()
    {
        return $this->reservations()->where('statut', 'en attente');
    }
}