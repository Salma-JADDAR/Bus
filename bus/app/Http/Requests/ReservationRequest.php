<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use App\Models\Segment;
use Carbon\Carbon;

class ReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'segment_id' => 'required|exists:segments,id',
            'date_voyage' => 'required|date|after_or_equal:today',
            'nb_passagers' => 'required|integer|min:1|max:10',
            
            // Options globales
            'has_assurance' => 'nullable|boolean',
            'has_snackbox' => 'nullable|boolean',
            'has_seat_premium' => 'nullable|boolean',
            
            // Passagers
            'passagers' => 'required|array|size:' . $this->input('nb_passagers', 1),
            'passagers.*.nom_complet' => 'required|string|max:100',
            'passagers.*.cin' => 'required|string|max:20|regex:/^[A-Z]{2}\d{6}$/',
            'passagers.*.date_naissance' => 'required|date|before_or_equal:today',
            'passagers.*.type_passager' => 'required|in:adulte,enfant',
            'passagers.*.email' => 'nullable|email|max:100',
            'passagers.*.telephone' => 'nullable|string|max:20',
            'passagers.*.besoins_speciaux' => 'nullable|string|max:500',
            
            // Options par passager
            'passagers.*.has_assurance' => 'nullable|boolean',
            'passagers.*.has_snackbox' => 'nullable|boolean',
            'passagers.*.has_seat_premium' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'passagers.*.cin.regex' => 'Le format du CIN doit être AA123456 (2 lettres suivies de 6 chiffres).',
            'passagers.*.date_naissance.before_or_equal' => 'La date de naissance doit être dans le passé.',
            'passagers.*.type_passager.in' => 'Le type de passager doit être "adulte" ou "enfant".',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Vérifier la disponibilité des places
            if ($this->has('segment_id') && $this->has('date_voyage') && $this->has('nb_passagers')) {
                $segment = Segment::find($this->input('segment_id'));
                $dateVoyage = $this->input('date_voyage');
                $nbPassagers = $this->input('nb_passagers');
                
                if ($segment) {
                    $placesDisponibles = $this->verifierPlacesDisponibles($segment, $dateVoyage);
                    
                    if ($placesDisponibles < $nbPassagers) {
                        $validator->errors()->add(
                            'nb_passagers',
                            "Il ne reste que {$placesDisponibles} place(s) disponible(s) pour ce trajet."
                        );
                    }
                    
                    // Vérifier sièges premium si demandés
                    if ($this->input('has_seat_premium', false)) {
                        if (!$segment->programme->bus->marque === 'Premium') {
                            $validator->errors()->add(
                                'has_seat_premium',
                                'Les sièges premium ne sont disponibles que sur les bus Premium.'
                            );
                        }
                    }
                }
            }
            
            // Vérifier âge des enfants
            foreach ($this->input('passagers', []) as $index => $passager) {
                if ($passager['type_passager'] === 'enfant') {
                    $age = Carbon::parse($passager['date_naissance'])->age;
                    if ($age >= 12) {
                        $validator->errors()->add(
                            "passagers.{$index}.type_passager",
                            "Un passager de {$age} ans doit être enregistré comme adulte."
                        );
                    }
                }
            }
        });
    }

    private function verifierPlacesDisponibles(Segment $segment, string $dateVoyage): int
    {
        $placesOccupees = DB::table('reservations')
            ->where('segment_id', $segment->id)
            ->whereDate('date_voyage', $dateVoyage)
            ->whereIn('statut', ['en attente', 'confirmée'])
            ->sum('nb_passagers');
        
        return max(0, $segment->programme->bus->capacite - $placesOccupees);
    }
}