<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SegmentFactory extends Factory
{
    public function definition(): array
    {
        $programme = \App\Models\Programme::factory()->create();
        $etapes = \App\Models\Etape::where('route_id', $programme->route_id)->get();
        
        if ($etapes->count() < 2) {
            $depart = \App\Models\Etape::factory()->create(['route_id' => $programme->route_id]);
            $arrivee = \App\Models\Etape::factory()->create(['route_id' => $programme->route_id]);
        } else {
            $depart = $etapes->random();
            $arrivee = $etapes->where('id', '!=', $depart->id)->random();
        }
        
        // Récupérer les villes depuis les gares
        $villeDepart = $depart->gare->ville;
        $villeArrivee = $arrivee->gare->ville;
        
        return [
            'tarif' => $this->faker->randomFloat(2, 50, 500),
            'duree_estimee' => $this->faker->time('H:i', '04:00'),
            'distance_km' => $this->faker->randomFloat(2, 100, 500),
            'ville_depart_id' => $villeDepart->id,
            'ville_arrivee_id' => $villeArrivee->id,
            'programme_id' => $programme->id,
            'depart_etape_id' => $depart->id,
            'arrivee_etape_id' => $arrivee->id,
        ];
    }
}