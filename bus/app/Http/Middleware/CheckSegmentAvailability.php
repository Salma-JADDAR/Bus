<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Segment;
use Carbon\Carbon;

class CheckSegmentAvailability
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
        if ($request->has('segment_id')) {
            $segment = Segment::find($request->segment_id);
            
            if (!$segment) {
                return redirect()->back()->with('error', 'Segment non trouvé.');
            }
            
            // Vérifier si le programme est actif
            if (!$segment->programme || !$segment->programme->actif) {
                return redirect()->back()->with('error', 'Ce trajet n\'est plus disponible.');
            }
            
            // Vérifier si le bus est disponible
            if (!$segment->programme->bus || !$segment->programme->bus->disponible) {
                return redirect()->back()->with('error', 'Bus non disponible.');
            }
        }
        
        return $next($request);
    }
}