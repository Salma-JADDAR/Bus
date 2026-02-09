@extends('layouts.app')

@section('title', 'Ticket - ' . $reservation->numero_reservation)

@section('content')
<div class="container">
    <!-- Version imprimable -->
    <div class="card ticket-card printable" id="printable-ticket">
        <div class="card-header ticket-header">
            <div class="row align-items-center">
                <div class="col-6">
                    <img src="{{ asset('images/logo.png') }}" alt="SATAS" class="logo" style="height: 60px;">
                </div>
                <div class="col-6 text-end">
                    <h4 class="mb-0">SATAS</h4>
                    <small>Système Automatisé de Transport par Autocar au Maroc</small>
                </div>
            </div>
        </div>
        
        <div class="card-body">
            <!-- En-tête avec numéro et statut -->
            <div class="row mb-4">
                <div class="col-md-8">
                    <h2 class="ticket-title">TICKET DE VOYAGE</h2>
                    <p class="ticket-number">N°: <strong>{{ $reservation->numero_reservation }}</strong></p>
                </div>
                <div class="col-md-4 text-end">
                    <div class="status-badge {{ $reservation->statut === 'confirmée' ? 'confirmed' : 'pending' }}">
                        {{ strtoupper($reservation->statut) }}
                    </div>
                    <p class="text-muted mt-2">
                        Émis le: {{ $reservation->created_at->format('d/m/Y H:i') }}
                    </p>
                </div>
            </div>

            <!-- Informations du voyage -->
            <div class="voyage-info p-4 mb-4">
                <div class="row">
                    <div class="col-md-5 text-center">
                        <h3 class="ville-depart">{{ optional(optional($reservation->segment)->villeDepart)->nom ?? 'Non défini' }}</h3>
                        <p class="heure-depart">
                            Départ: {{ optional(optional(optional($reservation->segment)->programme))->heure_depart ? $reservation->segment->programme->heure_depart->format('H:i') : 'Non défini' }}
                        </p>
                        <p class="date-voyage">
                            {{ $reservation->date_voyage ? $reservation->date_voyage->format('d/m/Y') : 'Non défini' }}
                        </p>
                    </div>
                    
                    <div class="col-md-2 text-center">
                        <div class="voyage-trajet">
                            <div class="trajet-ligne"></div>
                            <div class="trajet-icon">
                                <i class="fas fa-bus"></i>
                            </div>
                            <div class="duree-voyage">
                                {{ optional($reservation->segment)->duree_estimee ?? 'N/A' }} min
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-5 text-center">
                        <h3 class="ville-arrivee">{{ optional(optional($reservation->segment)->villeArrivee)->nom ?? 'Non défini' }}</h3>
                        <p class="heure-arrivee">
                            Arrivée: {{ optional(optional(optional($reservation->segment)->programme))->heure_arrivee ? $reservation->segment->programme->heure_arrivee->format('H:i') : 'Non défini' }}
                        </p>
                        <p class="type-autocar">
                            {{ optional(optional($reservation->segment)->autocar)->type ?? 'Non défini' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Détails de la réservation -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="detail-card">
                        <h5><i class="fas fa-info-circle"></i> Informations voyage</h5>
                        <table class="table table-sm">
                            <tr>
                                <td>Compagnie:</td>
                                <td><strong>{{ optional(optional($reservation->segment)->autocar)->compagnie ?? 'Non défini' }}</strong></td>
                            </tr>
                            <tr>
                                <td>Classe:</td>
                                <td><strong>{{ $reservation->classe ?? 'Économique' }}</strong></td>
                            </tr>
                            <tr>
                                <td>Numéro autocar:</td>
                                <td><strong>{{ optional(optional($reservation->segment)->autocar)->numero ?? 'N/A' }}</strong></td>
                            </tr>
                            <tr>
                                <td>Sièges:</td>
                                <td>
                                    <strong>
                                        @if($reservation->sieges && $reservation->sieges->count() > 0)
                                            @foreach($reservation->sieges as $siege)
                                                {{ $siege->numero }}{{ !$loop->last ? ', ' : '' }}
                                            @endforeach
                                        @else
                                            {{ $reservation->seat_number ?? 'N/A' }}
                                        @endif
                                    </strong>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="detail-card">
                        <h5><i class="fas fa-user"></i> Informations client</h5>
                        <table class="table table-sm">
                            <tr>
                                <td>Nom:</td>
                                <td><strong>{{ optional($reservation->user)->nom_complet ?? 'N/A' }}</strong></td>
                            </tr>
                            <tr>
                                <td>Email:</td>
                                <td><strong>{{ optional($reservation->user)->email ?? 'N/A' }}</strong></td>
                            </tr>
                            <tr>
                                <td>Téléphone:</td>
                                <td><strong>{{ optional($reservation->user)->telephone ?? 'Non renseigné' }}</strong></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Liste des passagers -->
            <div class="passagers-list mb-4">
                <h5><i class="fas fa-users"></i> Liste des passagers</h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>Nom complet</th>
                                <th>CIN/Passport</th>
                                <th>Type</th>
                                <th>Date naissance</th>
                                <th>Siège</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reservation->passagers as $index => $passager)
                            <tr>
                                <td>{{ $passager->nom_complet ?? 'N/A' }}</td>
                                <td>{{ $passager->cin ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-{{ ($passager->type_passager ?? 'adulte') === 'adulte' ? 'primary' : 'success' }}">
                                        {{ ucfirst($passager->type_passager ?? 'adulte') }}
                                    </span>
                                </td>
                                <td>{{ $passager->date_naissance ? $passager->date_naissance->format('d/m/Y') : 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-secondary">
                                        {{ $passager->seat_number ?? 'N/A' }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Aucun passager trouvé</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Code QR et total -->
            <div class="row align-items-end">
                <div class="col-md-6">
                    <div class="qr-code">
                        <h6>Code de vérification:</h6>
                        <div class="verification-code">
                            {{ strtoupper(substr(md5($reservation->id . $reservation->numero_reservation), 0, 8)) }}
                        </div>
                        <small class="text-muted">Présentez ce code à l'embarquement</small>
                    </div>
                </div>
                <div class="col-md-6 text-end">
                    <div class="total-section">
                        <h4>Total: {{ number_format($reservation->prix_total, 2) }} MAD</h4>
                        <p class="text-muted">TTC</p>
                    </div>
                </div>
            </div>

            <!-- Instructions -->
            <div class="instructions mt-4 p-3">
                <h6><i class="fas fa-exclamation-circle"></i> Instructions importantes:</h6>
                <ul class="mb-0">
                    <li>Présentez ce ticket à l'embarquement (version numérique ou imprimée)</li>
                    <li>Arrivez au moins 30 minutes avant le départ</li>
                    <li>Ayez une pièce d'identité valide pour chaque passager</li>
                    <li>Les bagages ne doivent pas dépasser 20kg par personne</li>
                </ul>
            </div>
        </div>
        
        <div class="card-footer ticket-footer text-center">
            <p class="mb-0">
                <i class="fas fa-phone"></i> Service client: +212 5 XX XX XX XX | 
                <i class="fas fa-globe"></i> www.satas.ma |
                <i class="fas fa-envelope"></i> contact@satas.ma
            </p>
        </div>
    </div>

    <!-- Boutons d'action -->
    <div class="action-buttons text-center mt-4 no-print">
        <button onclick="window.print()" class="btn btn-primary btn-lg me-3">
            <i class="fas fa-print"></i> Imprimer le ticket
        </button>
        
        <a href="{{ route('reservation.confirmation', $reservation->id) }}" 
           class="btn btn-outline-primary btn-lg me-3">
            <i class="fas fa-arrow-left"></i> Retour à la confirmation
        </a>
        
        <button onclick="window.print()" class="btn btn-success btn-lg">
            <i class="fas fa-download"></i> Télécharger en PDF
        </button>
    </div>
</div>
@endsection

@push('styles')
<style>
    .ticket-card {
        max-width: 1000px;
        margin: 0 auto;
        border: 2px solid #333;
        border-radius: 15px;
        overflow: hidden;
    }
    
    .ticket-header {
        background: linear-gradient(135deg, #1a237e 0%, #283593 100%);
        color: white;
        padding: 20px;
    }
    
    .status-badge {
        display: inline-block;
        padding: 8px 20px;
        border-radius: 20px;
        font-weight: bold;
        font-size: 14px;
        text-transform: uppercase;
    }
    
    .status-badge.confirmed {
        background-color: #4CAF50;
        color: white;
    }
    
    .status-badge.pending {
        background-color: #FF9800;
        color: white;
    }
    
    .voyage-info {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        border-radius: 10px;
        border: 1px solid #ddd;
    }
    
    .ville-depart, .ville-arrivee {
        color: #1a237e;
        font-weight: bold;
        margin-bottom: 10px;
    }
    
    .voyage-trajet {
        position: relative;
        height: 100px;
        margin: 20px 0;
    }
    
    .trajet-ligne {
        position: absolute;
        top: 50%;
        left: 10%;
        right: 10%;
        height: 3px;
        background: linear-gradient(90deg, #1a237e 0%, #283593 100%);
        z-index: 1;
    }
    
    .trajet-icon {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: white;
        border: 3px solid #1a237e;
        border-radius: 50%;
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 2;
    }
    
    .trajet-icon i {
        font-size: 24px;
        color: #1a237e;
    }
    
    .duree-voyage {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        text-align: center;
        color: #666;
        font-size: 14px;
    }
    
    .detail-card {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        border: 1px solid #dee2e6;
        height: 100%;
    }
    
    .detail-card h5 {
        color: #1a237e;
        margin-bottom: 15px;
    }
    
    .verification-code {
        font-family: 'Courier New', monospace;
        font-size: 28px;
        font-weight: bold;
        letter-spacing: 2px;
        color: #1a237e;
        background: #f0f0f0;
        padding: 10px;
        border-radius: 5px;
        text-align: center;
        margin: 10px 0;
    }
    
    .instructions {
        background: #fff3cd;
        border-left: 4px solid #ffc107;
        border-radius: 4px;
    }
    
    .ticket-footer {
        background: #f8f9fa;
        border-top: 1px solid #dee2e6;
        font-size: 12px;
        color: #666;
    }
    
    /* Styles pour l'impression */
    @media print {
        .no-print {
            display: none !important;
        }
        
        .ticket-card {
            border: none;
            box-shadow: none;
            margin: 0;
            max-width: 100%;
        }
        
        .action-buttons {
            display: none;
        }
        
        body {
            background: white !important;
            font-size: 12px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Utilise simplement l'impression du navigateur
    // L'utilisateur peut choisir "Imprimer en PDF" dans les options d'impression
</script>
@endpush