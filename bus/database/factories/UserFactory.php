<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use App\Models\Ville;

class UserFactory extends Factory
{
    public function definition(): array
    {
        // Toujours utiliser une ville existante
        $ville = Ville::inRandomOrder()->first();
        
        if (!$ville) {
            // Si aucune ville n'existe, en créer une
            $ville = Ville::create([
                'nom' => 'Casablanca',
                'region' => 'Casablanca-Settat',
                'pays' => 'Maroc',
                'description' => 'Ville par défaut',
            ]);
        }
        
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password123'),
            'role' => $this->faker->randomElement(['client', 'admin']),
            'phone' => '+2126' . $this->faker->numerify('########'),
            'ville_id' => $ville->id,
        ];
    }

    public function client()
    {
        return $this->state([
            'role' => 'client',
        ]);
    }

    public function admin()
    {
        // Trouver Casablanca ou prendre la première ville
        $ville = Ville::where('nom', 'Casablanca')->first() ?? Ville::first();
        
        return $this->state([
            'role' => 'admin',
            'email' => 'admin@buscompany.ma',
            'ville_id' => $ville->id,
        ]);
    }
}