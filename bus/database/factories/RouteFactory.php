<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RouteFactory extends Factory
{
    public function definition(): array
    {
        $villes = ['Casablanca', 'Rabat', 'Marrakech', 'Fès','safi' ,'Tanger', 'Agadir', 'Meknès', 'Oujda'];
        
        $depart = $this->faker->randomElement($villes);
        $arrivee = $this->faker->randomElement(array_diff($villes, [$depart]));
        
        return [
            'nom_route' => $depart . ' - ' . $arrivee,
            'ville_depart' => $depart,
            'ville_arrivee' => $arrivee,
            'description' => $this->faker->sentence(),
        ];
    }
}