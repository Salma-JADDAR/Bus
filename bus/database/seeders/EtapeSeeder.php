<?php

namespace Database\Seeders;

use App\Models\Etape;
use App\Models\Route;
use Illuminate\Database\Seeder;

class EtapeSeeder extends Seeder
{
    public function run(): void
    {
        $routes = Route::all();
        
        foreach ($routes as $route) {
            // Créer 3-5 étapes pour chaque route
            $nombreEtapes = rand(3, 5);
            
            for ($i = 1; $i <= $nombreEtapes; $i++) {
                Etape::create([
                    'nom_etape' => 'Etape ' . $i . ' - ' . $route->ville_depart,
                    'adresse' => 'Adresse ' . $i . ', ' . $route->ville_depart,
                    'ville' => $route->ville_depart,
                    'heure_passage' => now()->addHours($i)->format('H:i'),
                    'ordre' => $i,
                    'route_id' => $route->id,
                ]);
            }
        }
    }
}