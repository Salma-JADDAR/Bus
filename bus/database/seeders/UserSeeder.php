<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Ville;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $casablanca = Ville::where('nom', 'Casablanca')->first();
        
        if (!$casablanca) {
            $casablanca = Ville::first();
        }
        
        // Créer ou mettre à jour l'admin
        User::firstOrCreate(
            ['email' => 'admin@buscompany.ma'], // Condition de recherche
            [                                   // Données à créer si non existant
                'name' => 'Admin System',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'phone' => '+212612345678',
                'ville_id' => $casablanca->id ?? Ville::first()->id,
            ]
        );
        
        $this->command->info('Admin utilisateur créé/mis à jour.');

        // Compter combien de clients existent déjà
        $existingClients = User::where('role', 'client')->count();
        $clientsToCreate = max(0, 10 - $existingClients);
        
        if ($clientsToCreate > 0) {
            // Créer les clients manquants
            User::factory($clientsToCreate)->client()->create();
            $this->command->info("{$clientsToCreate} nouveaux clients créés.");
        } else {
            $this->command->info('Déjà 10 clients existent.');
        }
        
        $totalUsers = User::count();
        $this->command->info("Total: {$totalUsers} utilisateurs dans la base de données.");
    }
}