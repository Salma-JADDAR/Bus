<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Ville;

class RouteFactory extends Factory
{
    public function definition(): array
    {
        // Utiliser deux villes existantes différentes
        $villeDepart = Ville::inRandomOrder()->first();
        
        // Trouver une ville différente pour l'arrivée
        $villeArrivee = Ville::where('id', '!=', $villeDepart->id)
            ->inRandomOrder()
            ->first();
        
        // Si on n'a qu'une seule ville, en créer une nouvelle
        if (!$villeArrivee) {
            $villeArrivee = Ville::create([
                'nom' => $this->faker->unique()->city() . ' Destination',
                'region' => $this->faker->state(),
                'pays' => 'Maroc',
                'description' => 'Ville de destination',
            ]);
        }
        
        return [
            'nom_route' => $villeDepart->nom . ' - ' . $villeArrivee->nom,
            'ville_depart_id' => $villeDepart->id,
            'ville_arrivee_id' => $villeArrivee->id,
            'description' => 'Route entre ' . $villeDepart->nom . ' et ' . $villeArrivee->nom,
        ];
    }
}