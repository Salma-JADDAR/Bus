<?php

namespace Database\Seeders;

use App\Models\Reservation;
use App\Models\Segment;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder
{
    public function run(): void
    {
        $clients = User::where('role', 'client')->get();
        $segments = Segment::all();
        
        foreach ($clients as $client) {
            // Chaque client a 1-3 réservations
            $nbReservations = rand(1, 3);
            
            for ($i = 0; $i < $nbReservations; $i++) {
                $segment = $segments->random();
                $capacite = $segment->programme->bus->capacite;
                
                Reservation::create([
                    'date_reservation' => now()->addDays(rand(1, 30))->format('Y-m-d'),
                    'statut' => rand(0, 1) ? 'confirmée' : 'en attente',
                    'seat_number' => rand(1, $capacite),
                    'user_id' => $client->id,
                    'segment_id' => $segment->id,
                ]);
            }
        }
    }
}