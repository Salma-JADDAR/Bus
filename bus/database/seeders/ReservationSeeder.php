<?php

namespace Database\Seeders;

use App\Models\Reservation;
use App\Models\Segment;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReservationSeeder extends Seeder
{
    public function run(): void
    {
        // Vider la table d'abord
        DB::table('reservations')->delete();
        
        // Récupérer les segments et utilisateurs
        $segments = Segment::all();
        $users = User::where('role', 'client')->get();
        
        if ($segments->isEmpty() || $users->isEmpty()) {
            return;
        }
        
        $reservations = [];
        $usedNumbers = []; // Pour éviter les doublons
        
        for ($i = 0; $i < 50; $i++) {
            $segment = $segments->random();
            $user = $users->random();
            $dateReservation = Carbon::now()->subDays(rand(1, 30));
            $dateVoyage = $dateReservation->copy()->addDays(rand(1, 60));
            
            // Générer un numéro unique
            do {
                $numero = $this->generateUniqueReservationNumber($dateReservation, $i);
            } while (in_array($numero, $usedNumbers));
            
            $usedNumbers[] = $numero;
            
            // Calculer le prix
            $prixBase = $segment->tarifAvecTVA();
            
            $reservations[] = [
                'numero_reservation' => $numero,
                'date_reservation' => $dateReservation,
                'date_voyage' => $dateVoyage,
                'statut' => $this->getRandomStatut(),
                'seat_number' => rand(1, 60),
                'nb_passagers' => rand(1, 4),
                'prix_base' => $prixBase,
                'prix_total' => $prixBase,
                'prix_options' => 0,
                'has_assurance' => rand(0, 1),
                'has_snackbox' => rand(0, 1),
                'has_seat_premium' => rand(0, 1),
                'methode_paiement' => $this->getRandomPaiementMethod(),
                'reference_paiement' => 'PAY-' . strtoupper(uniqid()),
                'user_id' => $user->id,
                'segment_id' => $segment->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        // Insérer par lots
        foreach (array_chunk($reservations, 10) as $chunk) {
            DB::table('reservations')->insert($chunk);
        }
    }
    
    private function generateUniqueReservationNumber($date, $index): string
    {
        $dateStr = $date->format('Ymd');
        $sequence = str_pad($index + 1, 4, '0', STR_PAD_LEFT);
        
        // Ajouter un identifiant unique pour éviter les conflits
        $uniqueId = substr(uniqid(), -3);
        
        return "SATAS-{$dateStr}-{$sequence}-{$uniqueId}";
    }
    
    private function getRandomStatut(): string
    {
        $statuts = ['en attente', 'confirmée', 'annulée', 'terminée'];
        return $statuts[array_rand($statuts)];
    }
    
    private function getRandomPaiementMethod(): ?string
    {
        $methodes = ['en ligne', 'agence', null];
        return $methodes[array_rand($methodes)];
    }
}