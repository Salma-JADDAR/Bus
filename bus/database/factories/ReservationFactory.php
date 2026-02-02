<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationFactory extends Factory
{
    public function definition(): array
    {
        $segment = \App\Models\Segment::factory()->create();
        $capacite = $segment->programme->bus->capacite;
        
        return [
            'date_reservation' => $this->faker->dateBetween('-1 month', '+1 month'),
            'statut' => $this->faker->randomElement(['confirmée', 'en attente', 'annulée', 'terminée']),
            'seat_number' => $this->faker->numberBetween(1, $capacite),
            'user_id' => \App\Models\User::factory(),
            'segment_id' => $segment->id,
        ];
    }
}