<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Ville;

class BusFactory extends Factory
{
    public function definition(): array
    {
        // Utiliser une ville existante
        $ville = Ville::inRandomOrder()->first();
        
        if (!$ville) {
            $ville = Ville::first();
        }
        
        return [
            'numero_immatriculation' => 'BUS-' . $this->faker->unique()->regexify('[A-Z]{2}-\d{3}-[A-Z]{2}'),
            'capacite' => $this->faker->numberBetween(30, 60),
            'marque' => $this->faker->randomElement(['Mercedes', 'Volvo', 'MAN', 'Scania', 'Iveco']),
            'modele' => (string) $this->faker->numberBetween(2015, 2023),
            'ville_id' => $ville->id,
            'disponible' => $this->faker->boolean(80),
        ];
    }
}