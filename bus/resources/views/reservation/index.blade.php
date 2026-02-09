@extends('layouts.app')

@section('title', 'Mes Réservations - SATAS')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">
            <i class="fas fa-history text-primary me-2"></i>
            Mes Réservations
        </h1>
        <a href="{{ route('reservation.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Nouvelle Réservation
        </a>
    </div>

    <!-- Filtres -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('reservation.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="statut" class="form-label">Statut</label>
                    <select name="statut" id="statut" class="form-select">
                        <option value="">Tous les statuts</option>
                        <option value="en attente" {{ request('statut') == 'en attente' ? 'selected' : '' }}>En attente</option>
                        <option value="confirmée" {{ request('statut') == 'confirmée' ? 'selected' : '' }}>Confirmée</option>
                        <option value="annulée" {{ request('statut') == 'annulée' ? 'selected' : '' }}>Annulée</option>
                        <option value="expirée" {{ request('statut') == 'expirée' ? 'selected' : '' }}>Expirée</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="date_debut" class="form-label">Date de début</label>
                    <input type="date" name="date_debut" id="date_debut" 
                           class="form-control" value="{{ request('date_debut') }}">
                </div>
                <div class="col-md-3">
                    <label for="date_fin" class="form-label">Date de fin</label>
                    <input type="date" name="date_fin" id="date_fin" 
                           class="form-control" value="{{ request('date_fin') }}">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-outline-primary w-100 me-2">
                        <i class="fas fa-filter"></i> Filtrer
                    </button>
                    <a href="{{ route('reservation.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    @if($reservations->isEmpty())
    <div class="text-center py-5">
        <div class="empty-state">
            <i class="fas fa-ticket-alt fa-4x text-muted mb-3"></i>
            <h4>Aucune réservation trouvée</h4>
            <p class="text-muted">Vous n'avez pas encore fait de réservation.</p>
            <a href="{{ route('reservation.create') }}" class="btn btn-primary mt-2">
                <i class="fas fa-plus me-2"></i>Faire une réservation
            </a>
        </div>
    </div>
    @else
    <!-- Liste des réservations -->
    <div class="row">
        @foreach($reservations as $reservation)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 reservation-card 
                @if($reservation->statut === 'confirmée') border-success 
                @elseif($reservation->statut === 'annulée') border-danger
                @elseif($reservation->statut === 'expirée') border-secondary
                @else border-warning @endif">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <span class="badge bg-{{ 
                            $reservation->statut === 'confirmée' ? 'success' : 
                            ($reservation->statut === 'en attente' ? 'warning' : 
                            ($reservation->statut === 'annulée' ? 'danger' : 'secondary')) 
                        }}">
                            {{ $reservation->statut }}
                        </span>
                        <small class="text-muted ms-2">#{{ $reservation->numero_reservation }}</small>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" 
                                   href="{{ route('reservation.confirmation', $reservation->id) }}">
                                    <i class="fas fa-eye me-2"></i>Voir détails
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" 
                                   href="{{ route('reservation.ticket', $reservation->id) }}"
                                   target="_blank">
                                    <i class="fas fa-download me-2"></i>Télécharger ticket
                                </a>
                            </li>
                            @if($reservation->statut === 'en attente')
                            <li>
                                <form action="{{ route('reservation.payment', $reservation->id) }}" 
                                      method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-credit-card me-2"></i>Payer maintenant
                                    </button>
                                </form>
                            </li>
                            @endif
                            @if(in_array($reservation->statut, ['en attente', 'confirmée']))
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('reservation.cancel', $reservation->id) }}" 
                                      method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger"
                                            onclick="return confirm('Annuler cette réservation ?')">
                                        <i class="fas fa-times me-2"></i>Annuler
                                    </button>
                                </form>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Trajet -->
                    <div class="trajet-info mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <div>
                                <h6 class="mb-1">{{ optional(optional($reservation->segment)->villeDepart)->nom ?? 'Non défini' }}</h6>
                                <small class="text-muted">
                                    {{ $reservation->date_voyage ? $reservation->date_voyage->format('d/m/Y') : 'N/D' }} à 
                                    {{ optional(optional(optional($reservation->segment)->programme))->heure_depart ? $reservation->segment->programme->heure_depart->format('H:i') : 'N/D' }}
                                </small>
                            </div>
                            <div class="text-end">
                                <h6 class="mb-1">{{ optional(optional($reservation->segment)->villeArrivee)->nom ?? 'Non défini' }}</h6>
                                <small class="text-muted">
                                    Arrivée: {{ optional(optional(optional($reservation->segment)->programme))->heure_arrivee ? $reservation->segment->programme->heure_arrivee->format('H:i') : 'N/D' }}
                                </small>
                            </div>
                        </div>
                        <div class="trajet-progress">
                            <div class="trajet-dot depart"></div>
                            <div class="trajet-line"></div>
                            <div class="trajet-dot arrivee"></div>
                        </div>
                        <small class="text-muted d-block text-center mt-2">
                            <i class="fas fa-clock me-1"></i>
                            {{ optional($reservation->segment)->duree_estimee ?? 'N/A' }} min
                        </small>
                    </div>
                    
                    <!-- Détails rapides -->
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <small class="text-muted d-block mb-1">
                                <i class="fas fa-users me-1"></i> Passagers
                            </small>
                            <div class="fw-bold">
                                {{ $reservation->passagers->count() }}
                            </div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block mb-1">
                                <i class="fas fa-chair me-1"></i> Classe
                            </small>
                            <div class="fw-bold">
                                {{ $reservation->classe ?? 'Économique' }}
                            </div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block mb-1">
                                <i class="fas fa-hashtag me-1"></i> Sièges
                            </small>
                            <div>
                                @if($reservation->sieges && $reservation->sieges->count() > 0)
                                    @foreach($reservation->sieges as $siege)
                                        <span class="badge bg-secondary me-1">{{ $siege->numero }}</span>
                                    @endforeach
                                @else
                                    <span class="badge bg-secondary">{{ $reservation->seat_number ?? 'N/A' }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block mb-1">
                                <i class="fas fa-money-bill me-1"></i> Montant
                            </small>
                            <div class="fw-bold text-primary">
                                {{ number_format($reservation->prix_total, 2) }} MAD
                            </div>
                        </div>
                    </div>
                    
                    <!-- Compagnie -->
                    <div class="d-flex align-items-center mt-3 pt-3 border-top">
                        <div class="flex-shrink-0">
                            <i class="fas fa-bus fa-lg text-primary"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <small class="text-muted d-block">Compagnie</small>
                            <div class="fw-bold">{{ optional(optional($reservation->segment)->autocar)->compagnie ?? 'Non défini' }}</div>
                            <small class="text-muted">
                                {{ optional(optional($reservation->segment)->autocar)->type ?? 'N/A' }} • 
                                #{{ optional(optional($reservation->segment)->autocar)->numero ?? 'N/A' }}
                            </small>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer bg-transparent">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="fas fa-calendar me-1"></i>
                            Réservé le {{ $reservation->created_at->format('d/m/Y H:i') }}
                        </small>
                        <a href="{{ route('reservation.confirmation', $reservation->id) }}" 
                           class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-external-link-alt me-1"></i>Détails
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($reservations->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $reservations->withQueryString()->links() }}
    </div>
    @endif
    @endif
</div>
@endsection

@push('styles')
<style>
    .reservation-card {
        transition: all 0.3s ease;
        border-width: 2px;
        overflow: hidden;
    }
    
    .reservation-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.15);
    }
    
    .empty-state {
        padding: 60px 20px;
    }
    
    .empty-state i {
        opacity: 0.5;
    }
    
    .trajet-progress {
        position: relative;
        height: 4px;
        background: #e9ecef;
        border-radius: 2px;
        margin: 10px 0;
    }
    
    .trajet-line {
        position: absolute;
        top: 0;
        left: 10%;
        right: 10%;
        height: 100%;
        background: linear-gradient(90deg, #0d6efd 0%, #198754 100%);
        border-radius: 2px;
    }
    
    .trajet-dot {
        position: absolute;
        top: 50%;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        transform: translateY(-50%);
    }
    
    .trajet-dot.depart {
        left: 0;
        background-color: #0d6efd;
        border: 3px solid white;
        box-shadow: 0 0 0 2px #0d6efd;
    }
    
    .trajet-dot.arrivee {
        right: 0;
        background-color: #198754;
        border: 3px solid white;
        box-shadow: 0 0 0 2px #198754;
    }
    
    .dropdown-item form {
        margin: 0;
    }
    
    .dropdown-item button {
        width: 100%;
        text-align: left;
        background: none;
        border: none;
        padding: 0;
    }
    
    .card-header {
        background-color: rgba(0,0,0,0.02);
    }
    
    .border-success {
        border-color: #198754 !important;
    }
    
    .border-warning {
        border-color: #ffc107 !important;
    }
    
    .border-danger {
        border-color: #dc3545 !important;
    }
    
    .border-secondary {
        border-color: #6c757d !important;
    }
</style>
@endpush