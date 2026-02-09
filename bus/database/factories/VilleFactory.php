<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Ville;

class VilleFactory extends Factory
{
    private $villesMaroc = [
        ['nom' => 'Casablanca', 'region' => 'Casablanca-Settat'],
        ['nom' => 'Rabat', 'region' => 'Rabat-Salé-Kénitra'],
        ['nom' => 'Marrakech', 'region' => 'Marrakech-Safi'],
        ['nom' => 'Fès', 'region' => 'Fès-Meknès'],
        ['nom' => 'Tanger', 'region' => 'Tanger-Tétouan-Al Hoceïma'],
        ['nom' => 'Agadir', 'region' => 'Souss-Massa'],
        ['nom' => 'Meknès', 'region' => 'Fès-Meknès'],
        ['nom' => 'Oujda', 'region' => 'Oriental'],
        ['nom' => 'Safi', 'region' => 'Marrakech-Safi'],
        ['nom' => 'El Jadida', 'region' => 'Casablanca-Settat'],
        ['nom' => 'Kenitra', 'region' => 'Rabat-Salé-Kénitra'],
        ['nom' => 'Nador', 'region' => 'Oriental'],
        ['nom' => 'Témara', 'region' => 'Rabat-Salé-Kénitra'],
        ['nom' => 'Mohammedia', 'region' => 'Casablanca-Settat'],
        ['nom' => 'Khouribga', 'region' => 'Béni Mellal-Khénifra'],
    ];

    public function definition(): array
    {
        static $index = 0;
        
        // Si on a épuisé la liste des villes marocaines, créer une ville fictive
        if ($index >= count($this->villesMaroc)) {
            return [
                'nom' => $this->faker->unique()->city() . ' Ville',
                'region' => $this->faker->state(),
                'pays' => 'Maroc',
                'description' => $this->faker->sentence(10),
            ];
        }
        
        $ville = $this->villesMaroc[$index];
        $index++;
        
        // Utiliser firstOrCreate pour éviter les doublons
        $villeExistante = Ville::where('nom', $ville['nom'])->first();
        
        if ($villeExistante) {
            // La ville existe déjà, retourner ses attributs
            return [
                'nom' => $villeExistante->nom,
                'region' => $villeExistante->region,
                'pays' => $villeExistante->pays,
                'description' => $villeExistante->description,
            ];
        }
        
        return [
            'nom' => $ville['nom'],
            'region' => $ville['region'],
            'pays' => 'Maroc',
            'description' => $this->faker->sentence(10),
        ];
    }
}