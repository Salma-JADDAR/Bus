<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Segment;
use App\Models\User;

class ReservationFactory extends Factory
{
    public function definition(): array
    {
        $segment = Segment::factory()->create();
        $user = User::factory()->create();
        $dateVoyage = $this->faker->dateTimeBetween('+1 week', '+1 month');
        
        $nbPassagers = $this->faker->numberBetween(1, 4);
        $prixBase = $segment->tarifAvecTVA() * $nbPassagers;
        $prixOptions = 0;
        
        // Options aléatoires
        $hasAssurance = $this->faker->boolean(50);
        $hasSnackbox = $this->faker->boolean(40);
        $hasSeatPremium = $this->faker->boolean(30);
        
        if ($hasAssurance) $prixOptions += 25 * $nbPassagers;
        if ($hasSnackbox) $prixOptions += 15 * $nbPassagers;
        if ($hasSeatPremium) $prixOptions += 30 * $nbPassagers;
        
        return [
            'numero_reservation' => \App\Models\Reservation::generateReservationNumber(),
            'segment_id' => $segment->id,
            'user_id' => $user->id,
            'date_voyage' => $dateVoyage,
            'date_reservation' => now(),
            'nb_passagers' => $nbPassagers,
            'prix_total' => $prixBase + $prixOptions,
            'prix_base' => $prixBase,
            'prix_options' => $prixOptions,
            'has_assurance' => $hasAssurance,
            'has_snackbox' => $hasSnackbox,
            'has_seat_premium' => $hasSeatPremium,
            'statut' => $this->faker->randomElement(['en attente', 'confirmée', 'annulée']),
            'methode_paiement' => $this->faker->randomElement(['en ligne', 'agence', null]),
            'reference_paiement' => $this->faker->regexify('[A-Z0-9]{10}'),
        ];
    }
}