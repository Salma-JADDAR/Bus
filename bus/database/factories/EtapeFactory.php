<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EtapeFactory extends Factory
{
    public function definition(): array
    {
        $route = \App\Models\Route::factory()->create();
        $ville = \App\Models\Ville::find($route->ville_depart_id);
        $gare = \App\Models\Gare::factory()->create(['ville_id' => $ville->id]);
        
        return [
            'nom_etape' => 'Arrêt ' . $this->faker->streetName(),
            'heure_passage' => $this->faker->time('H:i'),
            'ordre' => $this->faker->numberBetween(1, 10),
            'route_id' => $route->id,
            'gare_id' => $gare->id,
        ];
    }
}