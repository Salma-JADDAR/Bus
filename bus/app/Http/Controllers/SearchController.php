<?php

namespace App\Http\Controllers;

use App\Models\Ville;
use App\Models\Segment;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SearchController extends Controller
{
    public function showForm()
    {
        $villes = Ville::orderBy('nom')->get();
        return view('search.form', compact('villes'));
    }
    
    public function search(Request $request)
    {
        $request->validate([
            'ville_depart_id' => 'required|exists:villes,id',
            'ville_arrivee_id' => 'required|exists:villes,id|different:ville_depart_id',
            'date_voyage' => 'required|date|after_or_equal:today',
            'passagers' => 'required|integer|min:1|max:10',
        ]);
        
        // Récupérer les segments disponibles
        $segments = Segment::with(['programme.bus', 'villeDepart', 'villeArrivee'])
            ->where('ville_depart_id', $request->ville_depart_id)
            ->where('ville_arrivee_id', $request->ville_arrivee_id)
            ->whereHas('programme', function($q) {
                $q->where('actif', true);
            })
            ->get();
        
        $results = [];
        $dateVoyage = Carbon::parse($request->date_voyage);
        $nbPassagers = $request->passagers;
        
        foreach ($segments as $segment) {
            // Vérifier places disponibles
            $placesOccupees = Reservation::where('segment_id', $segment->id)
                ->whereDate('date_voyage', $dateVoyage)
                ->whereIn('statut', ['en attente', 'confirmée'])
                ->sum('nb_passagers');
            
            $placesDisponibles = $segment->programme->bus->capacite - $placesOccupees;
            
            if ($placesDisponibles >= $nbPassagers) {
                $heureDepart = Carbon::parse($segment->programme->heure_arrivee)
                    ->subMinutes($this->durationToMinutes($segment->duree_estimee));
                
                $results[] = [
                    'segment' => $segment,
                    'places_disponibles' => $placesDisponibles,
                    'prix_unitaire' => $segment->tarif,
                    'prix_total' => $segment->tarif * $nbPassagers,
                    'heure_depart' => $heureDepart,
                    'heure_arrivee' => $segment->programme->heure_arrivee,
                    'duree' => $segment->duree_estimee,
                    'bus' => $segment->programme->bus,
                ];
            }
        }
        
        return view('search.results', [
            'results' => $results,
            'villeDepart' => Ville::find($request->ville_depart_id),
            'villeArrivee' => Ville::find($request->ville_arrivee_id),
            'searchParams' => $request->all(),
        ]);
    }
    
    private function durationToMinutes($duration)
    {
        if (is_string($duration)) {
            $parts = explode(':', $duration);
            return ($parts[0] * 60) + ($parts[1] ?? 0);
        }
        return 0;
    }
}