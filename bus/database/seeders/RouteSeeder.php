<?php

namespace Database\Seeders;

use App\Models\Route;
use Illuminate\Database\Seeder;

class RouteSeeder extends Seeder
{
    public function run(): void
    {
        Route::create([
            'nom_route' => 'Casablanca - Marrakech',
            'ville_depart' => 'Casablanca',
            'ville_arrivee' => 'Marrakech',
            'description' => 'Route principale entre les deux grandes villes',
        ]);

        Route::create([
            'nom_route' => 'Rabat - Fès',
            'ville_depart' => 'Rabat',
            'ville_arrivee' => 'Fès',
            'description' => 'Route vers la capitale spirituelle',
        ]);

        Route::create([
            'nom_route' => 'Tanger - Agadir',
            'ville_depart' => 'Tanger',
            'ville_arrivee' => 'Agadir',
            'description' => 'Route côtière nord-sud',
        ]);

        // Ajouter 2 routes supplémentaires
        Route::factory(2)->create();
    }
}