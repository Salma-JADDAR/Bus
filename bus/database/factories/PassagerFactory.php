<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PassagerFactory extends Factory
{
    public function definition(): array
    {
        $isEnfant = $this->faker->boolean(20);
        
        return [
            'reservation_id' => \App\Models\Reservation::factory(),
            'nom_complet' => $this->faker->name(),
            'cin' => $this->faker->regexify('[A-Z]{2}\d{6}'),
            'date_naissance' => $isEnfant 
                ? $this->faker->dateTimeBetween('-12 years', '-2 years')
                : $this->faker->dateTimeBetween('-60 years', '-18 years'),
            'type_passager' => $isEnfant ? 'enfant' : 'adulte',
            'seat_number' => $this->faker->numberBetween(1, 60),
            'has_assurance' => $this->faker->boolean(60),
            'has_snackbox' => $this->faker->boolean(40),
            'has_seat_premium' => $this->faker->boolean(30),
            'email' => $this->faker->safeEmail(),
            'telephone' => '+2126' . $this->faker->numerify('########'),
        ];
    }
}