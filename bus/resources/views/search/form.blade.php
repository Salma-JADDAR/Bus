@extends('layouts.app')

@section('title', 'Rechercher un trajet - SATAS')

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0"><i class="fas fa-search"></i> Rechercher un trajet</h4>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('search') }}">
            @csrf
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Ville de départ</label>
                    <select name="ville_depart_id" class="form-control" required>
                        <option value="">Choisir...</option>
                        @foreach($villes as $ville)
                            <option value="{{ $ville->id }}">{{ $ville->nom }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Ville d'arrivée</label>
                    <select name="ville_arrivee_id" class="form-control" required>
                        <option value="">Choisir...</option>
                        @foreach($villes as $ville)
                            <option value="{{ $ville->id }}">{{ $ville->nom }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Date de voyage</label>
                    <input type="date" name="date_voyage" 
                           class="form-control" 
                           min="{{ date('Y-m-d') }}"
                           value="{{ date('Y-m-d') }}"
                           required>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nombre de passagers</label>
                    <select name="passagers" class="form-control" required>
                        @for($i = 1; $i <= 10; $i++)
                            <option value="{{ $i }}">{{ $i }} passager{{ $i > 1 ? 's' : '' }}</option>
                        @endfor
                    </select>
                </div>
            </div>
            
            <div class="text-center">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-search"></i> Rechercher
                </button>
            </div>
        </form>
    </div>
</div>
@endsection