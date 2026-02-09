<?php

namespace Database\Seeders;

use App\Models\Route;
use App\Models\Ville;
use Illuminate\Database\Seeder;

class RouteSeeder extends Seeder
{
    public function run(): void
    {
        $routesPrincipales = [
            ['Casablanca', 'Rabat'],
            ['Casablanca', 'Marrakech'],
            ['Rabat', 'Fès'],
            ['Tanger', 'Casablanca'],
            ['Agadir', 'Marrakech'],
            ['Fès', 'Oujda'],
            ['Marrakech', 'Safi'],
            ['Casablanca', 'El Jadida'],
        ];
        
        foreach ($routesPrincipales as $routeVilles) {
            $villeDepart = Ville::where('nom', $routeVilles[0])->first();
            $villeArrivee = Ville::where('nom', $routeVilles[1])->first();
            
            if ($villeDepart && $villeArrivee) {
                $nomRoute = $villeDepart->nom . ' - ' . $villeArrivee->nom;
                
                Route::firstOrCreate(
                    [
                        'ville_depart_id' => $villeDepart->id,
                        'ville_arrivee_id' => $villeArrivee->id,
                    ],
                    [
                        'nom_route' => $nomRoute,
                        'description' => 'Route directe ' . $nomRoute,
                    ]
                );
            }
        }
        
        $this->command->info('Routes principales créées/mises à jour.');
        
        // Ajouter quelques routes aléatoires si nécessaire
        $existingRoutes = Route::count();
        if ($existingRoutes < 10) {
            $routesToCreate = 10 - $existingRoutes;
            Route::factory($routesToCreate)->create();
            $this->command->info("{$routesToCreate} routes supplémentaires créées.");
        }
        
        $totalRoutes = Route::count();
        $this->command->info("Total: {$totalRoutes} routes dans la base de données.");
    }
}