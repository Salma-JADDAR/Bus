@extends('layouts.app')

@section('title', 'Réserver - SATAS')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Récapitulatif</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Départ</h6>
                        <h4>{{ $segment->villeDepart->nom }}</h4>
                        <p>{{ $dateVoyage->format('d/m/Y') }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Arrivée</h6>
                        <h4>{{ $segment->villeArrivee->nom }}</h4>
                        <p>{{ $segment->programme->heure_arrivee->format('H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <form method="POST" action="{{ route('reservation.store') }}">
            @csrf
            <input type="hidden" name="segment_id" value="{{ $segment->id }}">
            <input type="hidden" name="date_voyage" value="{{ $dateVoyage->format('Y-m-d') }}">
            <input type="hidden" name="nb_passagers" value="{{ $nbPassagers }}">
            
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Passagers ({{ $nbPassagers }})</h5>
                </div>
                <div class="card-body">
                    @for($i = 0; $i < $nbPassagers; $i++)
                        <div class="border-bottom pb-3 mb-3">
                            <h6>Passager {{ $i + 1 }}</h6>
                            
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">Nom complet *</label>
                                    <input type="text" name="passagers[{{ $i }}][nom]" 
                                           class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">CIN *</label>
                                    <input type="text" name="passagers[{{ $i }}][cin]" 
                                           class="form-control" required>
                                </div>
                            </div>
                            
                            <div class="mb-2">
                                <label class="form-label">Type</label>
                                <select name="passagers[{{ $i }}][type]" class="form-control">
                                    <option value="adulte">Adulte</option>
                                    <option value="enfant">Enfant</option>
                                </select>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
            
            <button type="submit" class="btn btn-success btn-lg w-100">
                <i class="fas fa-check-circle"></i> Confirmer la réservation
            </button>
        </form>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Détails du prix</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Prix de base</span>
                    <span>{{ number_format($prixBase, 2) }} MAD</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <strong>Total</strong>
                    <strong>{{ number_format($prixBase, 2) }} MAD</strong>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection