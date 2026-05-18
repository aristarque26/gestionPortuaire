@extends('layouts.client')

@section('title', 'Voyages disponibles')
@section('header', 'Voyages disponibles')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    @forelse($voyages as $voyage)
    <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition">
        <div class="p-6">
            {{-- En-tête du voyage --}}
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-xl font-bold text-gray-800">✈️ Voyage : {{ $voyage->code_voyage }}</h3>
                    <p class="text-sm text-gray-500">🚢 Bateau : {{ $voyage->bateau->nom ?? 'N/A' }}</p>
                </div>
                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                    {{ $voyage->statut }}
                </span>
            </div>

            {{-- Infos départ --}}
            <div class="mb-4">
                <p class="text-gray-600">📅 Départ : {{ $voyage->date_depart->format('d/m/Y H:i') }}</p>
                <p class="text-gray-600">🪑 Places disponibles : {{ $voyage->placesDisponibles() }} / {{ $voyage->bateau->capacite_passager ?? 0 }}</p>
            </div>

            {{-- Infos port et quai --}}
            @php
                $premierTrajet = $voyage->trajets->first();
                $premierPort = $premierTrajet ? $premierTrajet->ports->first() : null;
                $premierQuai = $premierPort ? $premierPort->quais->first() : null;
            @endphp
            <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                <p class="text-sm font-semibold text-gray-700">⚓ Port d'embarquement :</p>
                <p class="text-gray-600">{{ $premierPort->nom ?? 'N/A' }} - {{ $premierPort->ville ?? '' }}</p>
                <p class="text-sm font-semibold text-gray-700 mt-2">📍 Quai :</p>
                <p class="text-gray-600">{{ $premierQuai->nom ?? 'N/A' }} (n°{{ $premierQuai->numero ?? 'N/A' }})</p>
            </div>

            {{-- Pavillons disponibles --}}
            <div class="mb-4">
                <p class="text-sm font-semibold text-gray-700 mb-2">🏠 Pavillons disponibles :</p>
                <ul class="space-y-1">
                    @foreach($voyage->bateau->pavillons ?? [] as $pavillon)
                        @php
                            $placesPavillon = $pavillon->placesDisponiblesPourVoyage($voyage->id);
                        @endphp
                        <li class="text-gray-600 flex justify-between">
                            <span>{{ $pavillon->nom }} ({{ $pavillon->classe }})</span>
                            <span class="font-semibold">{{ number_format($pavillon->prix_unitaire, 0, ',', ' ') }} FCFA</span>
                        </li>
                        <li class="text-xs text-gray-500 ml-4">🪑 Places restantes : {{ $placesPavillon }} / {{ $pavillon->capacite_max }}</li>
                    @endforeach
                </ul>
            </div>

            {{-- Bouton réserver --}}
            <a href="{{ route('client.reservations.create') }}?voyage={{ $voyage->id }}" 
               class="block text-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                📝 Réserver
            </a>
        </div>
    </div>
    @empty
    <div class="col-span-full text-center py-8 text-gray-500">
        Aucun voyage disponible pour le moment.
    </div>
    @endforelse
</div>
@endsection