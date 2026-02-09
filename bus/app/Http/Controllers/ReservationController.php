<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Passager;
use App\Models\Segment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReservationController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'segment_id' => 'required|exists:segments,id',
            'date_voyage' => 'required|date|after_or_equal:today',
            'passagers' => 'required|integer|min:1|max:10',
        ]);
        
        $segment = Segment::with(['programme.bus', 'villeDepart', 'villeArrivee'])->find($request->segment_id);
        
        if (!$segment) {
            return back()->with('error', 'Trajet non trouvé.');
        }
        
        return view('reservation.create', [
            'segment' => $segment,
            'dateVoyage' => Carbon::parse($request->date_voyage),
            'nbPassagers' => $request->passagers,
            'prixBase' => $segment->tarif * $request->passagers,
        ]);
    }
    
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $request->validate([
            'segment_id' => 'required|exists:segments,id',
            'date_voyage' => 'required|date',
            'nb_passagers' => 'required|integer|min:1|max:10',
            'passagers' => 'required|array|min:1',
            'passagers.*.nom' => 'required|string|max:100',
            'passagers.*.cin' => 'required|string|max:20',
        ]);
        
        try {
            DB::beginTransaction();
            
            $segment = Segment::find($request->segment_id);
            
            // Créer réservation
            $reservation = Reservation::create([
                'numero_reservation' => 'SATAS-' . date('Ymd') . '-' . strtoupper(Str::random(6)),
                'segment_id' => $segment->id,
                'user_id' => Auth::id(),
                'date_voyage' => $request->date_voyage,
                'date_reservation' => now(),
                'nb_passagers' => $request->nb_passagers,
                'prix_total' => $segment->tarif * $request->nb_passagers,
                'prix_base' => $segment->tarif * $request->nb_passagers,
                'statut' => 'en attente',
            ]);
            
            // Créer passagers
            foreach ($request->passagers as $passagerData) {
                Passager::create([
                    'reservation_id' => $reservation->id,
                    'nom_complet' => $passagerData['nom'],
                    'cin' => $passagerData['cin'],
                    'type_passager' => $passagerData['type'] ?? 'adulte',
                ]);
            }
            
            DB::commit();
            
            return redirect()->route('reservation.confirmation', $reservation->id)
                ->with('success', 'Réservation créée !');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }
    
    public function confirmation($id)
    {
        $reservation = Reservation::with(['segment.programme.bus', 'segment.villeDepart', 'segment.villeArrivee', 'passagers', 'user'])
            ->findOrFail($id);
            
        if (Auth::id() !== $reservation->user_id) {
            abort(403);
        }
        
        return view('reservation.confirmation', compact('reservation'));
    }
    
    public function payment(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);
        
        if (Auth::id() !== $reservation->user_id) {
            abort(403);
        }
        
        $reservation->update([
            'statut' => 'confirmée',
            'methode_paiement' => 'en ligne',
            'reference_paiement' => 'PAY-' . Str::random(10),
        ]);
        
        return redirect()->route('reservation.confirmation', $reservation->id)
            ->with('success', 'Paiement confirmé !');
    }
    
    public function downloadTicket($id)
    {
        $reservation = Reservation::with(['segment.programme.bus', 'segment.villeDepart', 'segment.villeArrivee', 'passagers'])
            ->findOrFail($id);
            
        if (Auth::id() !== $reservation->user_id) {
            abort(403);
        }
        
        return view('reservation.ticket', compact('reservation'));
    }
    
    public function cancel(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);
        
        if (Auth::id() !== $reservation->user_id) {
            abort(403);
        }
        
        $reservation->update(['statut' => 'annulée']);
        
        return back()->with('success', 'Réservation annulée.');
    }
    
    public function index()
    {
        $reservations = Reservation::with(['segment.programme.route', 'passagers'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('reservation.index', compact('reservations'));
    }
    
    public function verify($numero)
    {
        $reservation = Reservation::with(['segment.programme.bus', 'passagers'])
            ->where('numero_reservation', $numero)
            ->firstOrFail();
            
        return view('reservation.verify', compact('reservation'));
    }
    
    
}