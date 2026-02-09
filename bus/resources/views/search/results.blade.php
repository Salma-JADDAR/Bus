@extends('layouts.app')

@section('title', 'Résultats - SATAS')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Résultats de recherche</h4>
    <a href="{{ route('search.form') }}" class="btn btn-outline-primary">
        <i class="fas fa-arrow-left"></i> Nouvelle recherche
    </a>
</div>

<p class="text-muted mb-4">
    {{ $villeDepart->nom }} → {{ $villeArrivee->nom }} | 
    {{ \Carbon\Carbon::parse($searchParams['date_voyage'])->format('d/m/Y') }} | 
    {{ $searchParams['passagers'] }} passager{{ $searchParams['passagers'] > 1 ? 's' : '' }}
</p>

@if(count($results) > 0)
    @foreach($results as $result)
        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h5>{{ $result['segment']->villeDepart->nom }} → {{ $result['segment']->villeArrivee->nom }}</h5>
                        <p class="mb-1">
                            <i class="fas fa-clock"></i> 
                            {{ $result['heure_depart']->format('H:i') }} - {{ $result['heure_arrivee']->format('H:i') }}
                            ({{ $result['duree'] }})
                        </p>
                        <p class="mb-1">
                            <i class="fas fa-bus"></i> 
                            {{ $result['bus']->marque }} ({{ $result['bus']->capacite }} places)
                        </p>
                        <p class="mb-0">
                            <span class="badge bg-success">
                                {{ $result['places_disponibles'] }} places disponibles
                            </span>
                        </p>
                    </div>
                    
                    <div class="col-md-4 text-end">
                        <h4 class="text-primary">{{ number_format($result['prix_unitaire'], 2) }} MAD</h4>
                        <small class="text-muted">par personne</small>
                        
                        <div class="mt-3">
                            @auth
                                <a href="{{ route('reservation.create', [
                                    'segment_id' => $result['segment']->id,
                                    'date_voyage' => $searchParams['date_voyage'],
                                    'passagers' => $searchParams['passagers']
                                ]) }}" class="btn btn-primary">
                                    <i class="fas fa-ticket-alt"></i> Réserver
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-warning">
                                    <i class="fas fa-sign-in-alt"></i> Connectez-vous
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@else
    <div class="alert alert-warning">
        <i class="fas fa-exclamation-triangle"></i>
        Aucun trajet disponible pour ces critères.
    </div>
@endif
@endsection