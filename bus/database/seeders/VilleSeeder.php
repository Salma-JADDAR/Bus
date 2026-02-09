<?php

namespace Database\Seeders;

use App\Models\Ville;
use Illuminate\Database\Seeder;

class VilleSeeder extends Seeder
{
    public function run(): void
    {
        $villes = [
            ['nom' => 'Casablanca', 'region' => 'Casablanca-Settat', 'description' => 'Capital économique du Maroc'],
            ['nom' => 'Rabat', 'region' => 'Rabat-Salé-Kénitra', 'description' => 'Capitale administrative du Maroc'],
            ['nom' => 'Marrakech', 'region' => 'Marrakech-Safi', 'description' => 'Ville touristique célèbre'],
            ['nom' => 'Fès', 'region' => 'Fès-Meknès', 'description' => 'Capitale spirituelle et culturelle'],
            ['nom' => 'Tanger', 'region' => 'Tanger-Tétouan-Al Hoceïma', 'description' => 'Port important du détroit de Gibraltar'],
            ['nom' => 'Agadir', 'region' => 'Souss-Massa', 'description' => 'Station balnéaire réputée'],
            ['nom' => 'Meknès', 'region' => 'Fès-Meknès', 'description' => 'Ville impériale historique'],
            ['nom' => 'Oujda', 'region' => 'Oriental', 'description' => 'Ville de l\'est marocain'],
            ['nom' => 'Safi', 'region' => 'Marrakech-Safi', 'description' => 'Port et ville industrielle'],
            ['nom' => 'El Jadida', 'region' => 'Casablanca-Settat', 'description' => 'Ville côtière historique'],
        ];

        foreach ($villes as $ville) {
            // Utiliser firstOrCreate pour éviter les doublons
            Ville::firstOrCreate(
                ['nom' => $ville['nom']], // Condition de recherche
                [                          // Données à créer si non existant
                    'region' => $ville['region'],
                    'pays' => 'Maroc',
                    'description' => $ville['description'],
                ]
            );
        }
        
        // Vérifier combien de villes ont été créées
        $count = Ville::count();
        $this->command->info("{$count} villes dans la base de données.");
    }
}