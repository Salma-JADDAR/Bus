<?php

namespace Database\Seeders;

use App\Models\Gare;
use App\Models\Ville;
use Illuminate\Database\Seeder;

class GareSeeder extends Seeder
{
    public function run(): void
    {
        $villes = Ville::all();
        
        foreach ($villes as $ville) {
            // Chaque ville a 1-3 gares
            $nbGares = rand(1, 3);
            
            for ($i = 1; $i <= $nbGares; $i++) {
                $nomGare = ($i === 1 ? 'Gare Principale' : 'Gare ' . $i) . ' ' . $ville->nom;
                
                Gare::firstOrCreate(
                    ['nom_gare' => $nomGare], // Condition de recherche
                    [                           // Données à créer si non existant
                        'adresse' => 'Quartier Centre, ' . $ville->nom,
                        'telephone' => '+2125' . rand(10000000, 99999999),
                        'email' => 'contact@gare' . $i . '-' . strtolower($ville->nom) . '.ma',
                        'principale' => $i === 1,
                        'services' => 'WiFi, Café, Parking, WC',
                        'heure_ouverture' => '06:00',
                        'heure_fermeture' => '22:00',
                        'ville_id' => $ville->id,
                    ]
                );
            }
        }
        
        $count = Gare::count();
        $this->command->info("{$count} gares dans la base de données.");
    }
}