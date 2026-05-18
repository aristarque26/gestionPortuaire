@extends('layouts.client')

@section('title', 'Détails de la réservation')
@section('header', 'Détails de la réservation')

@section('content')
<div class="bg-white rounded-xl shadow-md p-6">
    {{-- En-tête avec statut --}}
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Réservation #{{ $reservation->id }}</h2>
        <span class="px-3 py-1 text-sm rounded-full 
            {{ $reservation->statut == 'confirme' ? 'bg-green-100 text-green-800' : 
               ($reservation->statut == 'en_attente' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
            {{ ucfirst($reservation->statut) }}
        </span>
    </div>

    {{-- Informations voyage --}}
    <div class="border-b pb-4 mb-4">
        <h3 class="text-lg font-semibold text-gray-700 mb-3">🚢 Détails du voyage</h3>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-500">Code voyage</label>
                <p class="text-gray-900 font-medium">{{ $reservation->voyage->code_voyage ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500">Date d'embarquement</label>
                <p class="text-gray-900 font-medium">{{ $reservation->date_embarquement->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>

    {{-- Informations port et quai --}}
    <div class="border-b pb-4 mb-4">
        <h3 class="text-lg font-semibold text-gray-700 mb-3">⚓ Embarquement</h3>
        @php
            $premierTrajet = $reservation->voyage->trajets->first();
            $premierPort = $premierTrajet ? $premierTrajet->ports->first() : null;
            $premierQuai = $premierPort ? $premierPort->quais->first() : null;
        @endphp
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-500">Port</label>
                <p class="text-gray-900 font-medium">{{ $premierPort->nom ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500">Ville</label>
                <p class="text-gray-900 font-medium">{{ $premierPort->ville ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500">Quai</label>
                <p class="text-gray-900 font-medium">{{ $premierQuai->nom ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500">Numéro de quai</label>
                <p class="text-gray-900 font-medium">{{ $premierQuai->numero ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    {{-- Informations bateau --}}
    <div class="border-b pb-4 mb-4">
        <h3 class="text-lg font-semibold text-gray-700 mb-3">🚢 Bateau</h3>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-500">Nom du bateau</label>
                <p class="text-gray-900 font-medium">{{ $reservation->voyage->bateau->nom ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500">Type</label>
                <p class="text-gray-900 font-medium">{{ $reservation->voyage->bateau->type ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    {{-- Informations pavillon --}}
    <div class="border-b pb-4 mb-4">
        <h3 class="text-lg font-semibold text-gray-700 mb-3">🏠 Pavillon</h3>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-500">Nom</label>
                <p class="text-gray-900 font-medium">{{ $reservation->pavillon->nom ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500">Classe</label>
                <p class="text-gray-900 font-medium">{{ $reservation->pavillon->classe ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    {{-- Informations réservation --}}
    <div class="border-b pb-4 mb-4">
        <h3 class="text-lg font-semibold text-gray-700 mb-3">📋 Détails de la réservation</h3>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-500">Type</label>
                <p class="text-gray-900 font-medium">{{ $reservation->type_reservation }}</p>
            </div>
            @if($reservation->type_reservation != 'passage')
            <div>
                <label class="block text-sm font-medium text-gray-500">Poids (tonnes)</label>
                <p class="text-gray-900 font-medium">{{ $reservation->poids_cargaison ?? 0 }} t</p>
            </div>
            @endif
            <div>
                <label class="block text-sm font-medium text-gray-500">Prix total</label>
                <p class="text-xl font-bold text-blue-600">{{ number_format($reservation->prix_total, 0, ',', ' ') }} FCFA</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500">Date de réservation</label>
                <p class="text-gray-900 font-medium">{{ $reservation->date_reservation->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>

    {{-- Point de rencontre et itinéraire conseillé --}}
    <div class="bg-blue-50 rounded-lg p-4 mb-4">
        <h3 class="text-lg font-semibold text-blue-800 mb-2">📍 Point de rencontre</h3>
        <p class="text-blue-800">Accueil principal du port de {{ $premierPort->ville ?? 'N/A' }} (bâtiment administratif)</p>
        
        <h3 class="text-lg font-semibold text-blue-800 mt-4 mb-2">🗺️ Itinéraire conseillé</h3>
        <p class="text-blue-800">1. Entrée principale du port → 2. Présentez-vous au contrôle → 3. Direction quai n°{{ $premierQuai->numero ?? 'N/A' }} → 4. Accès au bateau → 5. Rendez-vous au pavillon {{ $reservation->pavillon->nom ?? 'N/A' }}</p>
    </div>

    {{-- Description --}}
    @if($reservation->description)
    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-500">Description</label>
        <p class="text-gray-900">{{ $reservation->description }}</p>
    </div>
    @endif

    {{-- Bouton retour --}}
    <div class="flex justify-end mt-6">
        <a href="{{ route('client.reservations.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition">
            Retour à mes réservations
        </a>
    </div>
</div>
@endsection