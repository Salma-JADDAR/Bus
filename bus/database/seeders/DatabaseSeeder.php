<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. D'abord créer toutes les villes
        $this->call(VilleSeeder::class);
        
        // 2. Ensuite créer les gares (qui utilisent les villes existantes)
        $this->call(GareSeeder::class);
        
        // 3. Ensuite créer les utilisateurs (qui utilisent les villes existantes)
        $this->call(UserSeeder::class);
        
        // 4. Ensuite créer les buses (qui utilisent les villes existantes)
        $this->call(BusSeeder::class);
        
        // 5. Ensuite créer les routes (qui utilisent les villes existantes)
        $this->call(RouteSeeder::class);
        
        // 6. Ensuite créer les étapes
        $this->call(EtapeSeeder::class);
        
        // 7. Ensuite créer les programmes
        $this->call(ProgrammeSeeder::class);
        
        // 8. Ensuite créer les segments
        $this->call(SegmentSeeder::class);
        
        // 9. Enfin créer les réservations
        $this->call(ReservationSeeder::class);
    }
}