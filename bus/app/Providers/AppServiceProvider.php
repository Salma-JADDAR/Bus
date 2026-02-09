<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Définir les morph maps si nécessaire
        Relation::enforceMorphMap([
            'user' => 'App\Models\User',
            'ville' => 'App\Models\Ville',
            'gare' => 'App\Models\Gare',
            'bus' => 'App\Models\Bus',
            'route' => 'App\Models\Route',
            'etape' => 'App\Models\Etape',
            'programme' => 'App\Models\Programme',
            'segment' => 'App\Models\Segment',
            'reservation' => 'App\Models\Reservation',
        ]);
    }
}