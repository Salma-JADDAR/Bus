@extends('layouts.app')

@section('title', 'Confirmation - SATAS')

@section('content')
<div class="text-center mb-5">
    @if($reservation->statut === 'confirmée')
        <div class="alert alert-success">
            <i class="fas fa-check-circle fa-3x"></i>
            <h2 class="mt-3">Réservation confirmée !</h2>
            <p>Votre réservation a été confirmée.</p>
        </div>
    @else
        <div class="alert alert-warning">
            <i class="fas fa-clock fa-3x"></i>
            <h2 class="mt-3">Réservation en attente</h2>
            <p>Votre réservation est enregistrée.</p>
        </div>
    @endif
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Réservation {{ $reservation->numero_reservation }}</h5>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6>Départ</h6>
                        <h4>{{ optional(optional($reservation->segment)->villeDepart)->nom ?? 'Non défini' }}</h4>
                        <p>{{ $reservation->date_voyage ? $reservation->date_voyage->format('d/m/Y') : 'Non défini' }}</p>
                        <p>Heure: {{ optional(optional(optional($reservation->segment)->programme))->heure_depart ? $reservation->segment->programme->heure_depart->format('H:i') : 'Non défini' }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Arrivée</h6>
                        <h4>{{ optional(optional($reservation->segment)->villeArrivee)->nom ?? 'Non défini' }}</h4>
                        <p>Heure: {{ optional(optional(optional($reservation->segment)->programme))->heure_arrivee ? $reservation->segment->programme->heure_arrivee->format('H:i') : 'Non défini' }}</p>
                    </div>
                </div>
                
                <h6>Passagers</h6>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>CIN</th>
                            <th>Type</th>
                            <th>Siège</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reservation->passagers as $passager)
                            <tr>
                                <td>{{ $passager->nom_complet ?? 'N/A' }}</td>
                                <td>{{ $passager->cin ?? 'N/A' }}</td>
                                <td>{{ ucfirst($passager->type_passager ?? 'adulte') }}</td>
                                <td>{{ $passager->seat_number ?? 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Aucun passager</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Actions</h5>
            </div>
            <div class="card-body">
                @if($reservation->statut === 'en attente')
                    <form action="{{ route('reservation.payment', $reservation->id) }}" method="POST" class="mb-3">
                        @csrf
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-credit-card"></i> Payer maintenant
                        </button>
                    </form>
                @endif
                
                <a href="{{ route('reservation.ticket', $reservation->id) }}" 
                   target="_blank" class="btn btn-primary w-100 mb-3">
                    <i class="fas fa-download"></i> Télécharger le ticket
                </a>
                
                <form action="{{ route('reservation.cancel', $reservation->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger w-100" 
                            onclick="return confirm('Êtes-vous sûr de vouloir annuler cette réservation ?')">
                        <i class="fas fa-times"></i> Annuler la réservation
                    </button>
                </form>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Récapitulatif</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <td>Nombre de passagers:</td>
                        <td class="text-end">{{ $reservation->passagers->count() }}</td>
                    </tr>
                    <tr>
                        <td>Classe:</td>
                        <td class="text-end">{{ $reservation->classe ?? 'Économique' }}</td>
                    </tr>
                    <tr>
                        <td>Date de réservation:</td>
                        <td class="text-end">{{ $reservation->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td>Statut:</td>
                        <td class="text-end">
                            <span class="badge bg-{{ $reservation->statut === 'confirmée' ? 'success' : 'warning' }}">
                                {{ $reservation->statut }}
                            </span>
                        </td>
                    </tr>
                    <tr class="table-primary">
                        <td><strong>Total:</strong></td>
                        <td class="text-end">
                            <strong>{{ number_format($reservation->prix_total, 2) }} MAD</strong>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="text-center mt-4">
    <a href="{{ route('home') }}" class="btn btn-outline-primary me-2">
        <i class="fas fa-home"></i> Retour à l'accueil
    </a>
    <a href="{{ route('reservation.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-history"></i> Voir mes réservations
    </a>
</div>
@endsection

@push('styles')
<style>
    .alert {
        border-radius: 10px;
        padding: 2rem;
    }
    .alert i {
        color: inherit;
        opacity: 0.8;
    }
    .card {
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .card-header {
        border-radius: 8px 8px 0 0 !important;
    }
    table.table-sm td {
        padding: 0.75rem 0.5rem;
    }
</style>
@endpush