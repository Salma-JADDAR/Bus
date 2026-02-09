<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Ville;

class GareFactory extends Factory
{
    public function definition(): array
    {
        // Utiliser une ville existante
        $ville = Ville::inRandomOrder()->first();
        
        if (!$ville) {
            $ville = Ville::first();
        }
        
        return [
            'nom_gare' => $this->faker->randomElement(['Gare Routière', 'Station CTM', 'Gare Principale', 'Terminal Bus', 'Station Supratours']) 
                         . ' ' . $ville->nom,
            'adresse' => $this->faker->address(),
            'telephone' => '+2125' . $this->faker->numerify('########'),
            'email' => 'contact@gare-' . strtolower($ville->nom) . '.ma',
            'principale' => $this->faker->boolean(30),
            'services' => implode(',', $this->faker->randomElements(['WiFi', 'Café', 'Boutique', 'WC', 'Parking', 'Guichet', 'Salle d\'attente'], 3)),
            'heure_ouverture' => '06:00',
            'heure_fermeture' => '22:00',
            'ville_id' => $ville->id,
        ];
    }
}