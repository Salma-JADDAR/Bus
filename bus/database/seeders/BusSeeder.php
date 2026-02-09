<?php

namespace Database\Seeders;

use App\Models\Bus;
use Illuminate\Database\Seeder;

class BusSeeder extends Seeder
{
    public function run(): void
    {
        // Compter combien de bus existent déjà
        $existingBuses = Bus::count();
        $busesToCreate = max(0, 8 - $existingBuses);
        
        if ($busesToCreate > 0) {
            Bus::factory($busesToCreate)->create();
            $this->command->info("{$busesToCreate} nouveaux bus créés.");
        } else {
            $this->command->info('Déjà 8 bus existent.');
        }
        
        $totalBuses = Bus::count();
        $this->command->info("Total: {$totalBuses} bus dans la base de données.");
    }
}