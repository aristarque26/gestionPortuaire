@extends('layouts.admin')

@section('title', 'Détails de la Réservation')
@section('header', 'Détails de la Réservation')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-500">ID Réservation</label>
            <p class="text-lg font-semibold">#{{ $reservation->id }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Statut</label>
            <p class="text-lg font-semibold">
                <form method="POST" action="{{ route('admin.reservations.update', $reservation->id) }}" class="inline">
                    @csrf
                    @method('PUT')
                    <select name="statut" onchange="this.form.submit()" class="border rounded-lg px-3 py-1">
                        <option value="en_attente" {{ $reservation->statut == 'en_attente' ? 'selected' : '' }}>En attente</option>
                        <option value="confirme" {{ $reservation->statut == 'confirme' ? 'selected' : '' }}>Confirmé</option>
                        <option value="annule" {{ $reservation->statut == 'annule' ? 'selected' : '' }}>Annulé</option>
                        <option value="arrive" {{ $reservation->statut == 'arrive' ? 'selected' : '' }}>Arrivé</option>
                    </select>
                </form>
            </p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Client</label>
            <p class="text-lg font-semibold">{{ $reservation->client->prenom ?? 'N/A' }} {{ $reservation->client->nom ?? '' }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Email client</label>
            <p class="text-lg font-semibold">{{ $reservation->client->email ?? 'N/A' }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Voyage</label>
            <p class="text-lg font-semibold">{{ $reservation->voyage->code_voyage ?? 'N/A' }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Bateau</label>
            <p class="text-lg font-semibold">{{ $reservation->voyage->bateau->nom ?? 'N/A' }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Type de réservation</label>
            <p class="text-lg font-semibold">{{ $reservation->type_reservation }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Date de réservation</label>
            <p class="text-lg font-semibold">{{ $reservation->date_reservation->format('d/m/Y H:i') }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Date d'embarquement</label>
            <p class="text-lg font-semibold">{{ $reservation->date_embarquement->format('d/m/Y H:i') }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Date d'arrivée</label>
            <p class="text-lg font-semibold">{{ $reservation->date_arrivee ? $reservation->date_arrivee->format('d/m/Y H:i') : 'Non définie' }}</p>
        </div>
        @if($reservation->type_reservation == 'cargaison' || $reservation->type_reservation == 'mixte')
        <div>
            <label class="block text-sm font-medium text-gray-500">Nombre de cargaison</label>
            <p class="text-lg font-semibold">{{ $reservation->nombre_cargaison ?? 0 }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Poids (tonnes)</label>
            <p class="text-lg font-semibold">{{ $reservation->poids_cargaison ?? 0 }} t</p>
        </div>
        @endif
        @if($reservation->description)
        <div class="col-span-2">
            <label class="block text-sm font-medium text-gray-500">Description</label>
            <p class="text-lg font-semibold">{{ $reservation->description }}</p>
        </div>
        @endif
        @if($reservation->pavillons->count() > 0)
        <div class="col-span-2">
            <label class="block text-sm font-medium text-gray-500">Pavillons réservés</label>
            <ul class="list-disc list-inside">
                @foreach($reservation->pavillons as $pavillon)
                <li>{{ $pavillon->nom }} ({{ $pavillon->classe }}) - Prix: {{ number_format($pavillon->pivot->prix, 0, ',', ' ') }} FCFA</li>
                @endforeach
            </ul>
        </div>
        @endif
        @if($reservation->paiement)
        <div>
            <label class="block text-sm font-medium text-gray-500">Paiement</label>
            <p class="text-lg font-semibold">{{ number_format($reservation->paiement->montant, 0, ',', ' ') }} {{ $reservation->paiement->devise }}</p>
            <p class="text-sm text-gray-500">Statut: {{ $reservation->paiement->statut }}</p>
        </div>
        @endif
    </div>
    
    <div class="flex justify-end space-x-3 mt-6">
        <a href="{{ route('admin.reservations.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">Retour</a>
    </div>
</div>
@endsection