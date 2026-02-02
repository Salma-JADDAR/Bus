<?php

namespace Database\Seeders;

use App\Models\Bus;
use App\Models\Programme;
use App\Models\Route;
use Illuminate\Database\Seeder;

class ProgrammeSeeder extends Seeder
{
    public function run(): void
    {
        $buses = Bus::all();
        $routes = Route::all();
        
        foreach ($routes as $route) {
            foreach ($buses as $bus) {
                if (rand(0, 1)) { // 50% de chance de créer un programme
                    Programme::create([
                        'heure_arrivee' => now()->addHours(rand(1, 8))->format('H:i'),
                        'actif' => true,
                        'bus_id' => $bus->id,
                        'route_id' => $route->id,
                    ]);
                }
            }
        }
    }
}