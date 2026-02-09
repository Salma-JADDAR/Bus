<?php

namespace Database\Seeders;

use App\Models\Etape;
use App\Models\Programme;
use App\Models\Segment;
use Illuminate\Database\Seeder;

class SegmentSeeder extends Seeder
{
    public function run(): void
    {
        $programmes = Programme::all();
        
        foreach ($programmes as $programme) {
            $etapes = Etape::where('route_id', $programme->route_id)
                ->orderBy('ordre')
                ->get();
            
            if ($etapes->count() >= 2) {
                // Créer un segment entre chaque paire d'étapes consécutives
                for ($i = 0; $i < $etapes->count() - 1; $i++) {
                    $depart = $etapes[$i];
                    $arrivee = $etapes[$i + 1];
                    
                    Segment::create([
                        'tarif' => rand(50, 300) + (rand(0, 99) / 100),
                        'duree_estimee' => now()->addHours($i + 1)->format('H:i'),
                        'distance_km' => rand(50, 300) + (rand(0, 99) / 100),
                        'ville_depart_id' => $depart->gare->ville_id,
                        'ville_arrivee_id' => $arrivee->gare->ville_id,
                        'programme_id' => $programme->id,
                        'depart_etape_id' => $depart->id,
                        'arrivee_etape_id' => $arrivee->id,
                    ]);
                }
            }
        }
    }
}