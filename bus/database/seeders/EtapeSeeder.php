<?php

namespace Database\Seeders;

use App\Models\Etape;
use App\Models\Gare;
use App\Models\Route;
use Illuminate\Database\Seeder;

class EtapeSeeder extends Seeder
{
    public function run(): void
    {
        $routes = Route::all();
        
        foreach ($routes as $route) {
            // Récupérer les gares des villes de départ et d'arrivée
            $garesDepart = Gare::where('ville_id', $route->ville_depart_id)->get();
            $garesArrivee = Gare::where('ville_id', $route->ville_arrivee_id)->get();
            
            // Créer 3-5 étapes pour chaque route
            $nombreEtapes = rand(3, 5);
            
            for ($i = 1; $i <= $nombreEtapes; $i++) {
                // Alterner entre gares de départ et d'arrivée
                if ($i === 1) {
                    // Première étape : gare de départ
                    $gare = $garesDepart->first();
                } elseif ($i === $nombreEtapes) {
                    // Dernière étape : gare d'arrivée
                    $gare = $garesArrivee->first();
                } else {
                    // Étapes intermédiaires : gares aléatoires
                    $gare = Gare::inRandomOrder()->first();
                }
                
                Etape::create([
                    'nom_etape' => 'Étape ' . $i . ' - ' . ($gare ? $gare->nom_gare : 'Arrêt ' . $i),
                    'heure_passage' => now()->addHours($i)->format('H:i'),
                    'ordre' => $i,
                    'route_id' => $route->id,
                    'gare_id' => $gare->id ?? Gare::factory()->create()->id,
                ]);
            }
        }
    }
}