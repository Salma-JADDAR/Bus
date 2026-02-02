<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EtapeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nom_etape' => $this->faker->randomElement(['Gare Routière', 'Station Centrale', 'Arrêt Principal']) . ' ' . $this->faker->city(),
            'adresse' => $this->faker->address(),
            'ville' => $this->faker->city(),
            'heure_passage' => $this->faker->time('H:i'),
            'ordre' => $this->faker->numberBetween(1, 10),
            'route_id' => \App\Models\Route::factory(),
        ];
    }
}