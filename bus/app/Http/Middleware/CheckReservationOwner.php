<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservation;

class CheckReservationOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $reservationId = $request->route('reservation') ?? $request->route('id');
        
        if (is_numeric($reservationId)) {
            $reservation = Reservation::findOrFail($reservationId);
            
            // Vérifier si l'utilisateur est le propriétaire ou un admin
            if (Auth::id() !== $reservation->user_id && Auth::user()->role !== 'admin') {
                abort(403, 'Vous n\'êtes pas autorisé à accéder à cette réservation.');
            }
        }
        
        return $next($request);
    }
}