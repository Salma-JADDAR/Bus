<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProgrammeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'heure_arrivee' => $this->faker->time('H:i'),
            'actif' => $this->faker->boolean(85),
            'bus_id' => \App\Models\Bus::factory(),
            'route_id' => \App\Models\Route::factory(),
        ];
    }
}