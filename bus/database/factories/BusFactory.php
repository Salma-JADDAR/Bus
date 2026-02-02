<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BusFactory extends Factory
{
    public function definition(): array
    {
        return [
            'numero_immatriculation' => 'BUS-' . $this->faker->unique()->regexify('[A-Z]{2}-\d{3}-[A-Z]{2}'),
            'capacite' => $this->faker->numberBetween(30, 60),
            'marque' => $this->faker->randomElement(['Mercedes', 'Volvo', 'MAN', 'Scania']),
            'modele' => $this->faker->year(),
            'disponible' => $this->faker->boolean(80),
        ];
    }
}