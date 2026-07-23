@extends('layouts.client')

@section('title', 'Détails du voyage')
@section('header', 'Détails du voyage')

@section('content')
<div class="container mx-auto px-4 py-6">
    {{-- En-tête --}}
    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-ship text-blue-500 mr-2"></i>
                    {{ $voyage->code_voyage }}
                </h1>
                <p class="text-gray-600 mt-1">
                    🚢 {{ $voyage->bateau->nom ?? 'Bateau inconnu' }}
                </p>
            </div>
            <div class="mt-2 md:mt-0">
                <span class="px-3 py-1 text-sm font-semibold rounded-full
                    {{ $voyage->statut == 'prevu' ? 'bg-green-100 text-green-800' :
                       ($voyage->statut == 'en_cours' ? 'bg-yellow-100 text-yellow-800' :
                       ($voyage->statut == 'termine' ? 'bg-gray-100 text-gray-800' :
                       'bg-red-100 text-red-800')) }}">
                    {{ ucfirst($voyage->statut ?? 'Disponible') }}
                </span>
            </div>
        </div>
        <div class="mt-4 flex flex-wrap gap-4 text-sm text-gray-600">
            <span><i class="far fa-calendar-alt mr-1"></i> Départ : {{ $voyage->date_depart->format('d/m/Y H:i') }}</span>
            <span><i class="fas fa-chair mr-1"></i> Places disponibles : {{ $voyage->placesDisponibles() }} / {{ $voyage->bateau->capacite_passager ?? 0 }}</span>
        </div>
    </div>

    {{-- Grille principale --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Colonne de gauche --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Bateau --}}
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">
                    <i class="fas fa-anchor text-blue-500 mr-2"></i>Informations du bateau
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Nom</label>
                        <p class="text-gray-900 font-medium">{{ $voyage->bateau->nom ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Type</label>
                        <p class="text-gray-900 font-medium">{{ $voyage->bateau->type ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Immatriculation</label>
                        <p class="text-gray-900 font-medium">{{ $voyage->bateau->immatriculation ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Capacité passagers</label>
                        <p class="text-gray-900 font-medium">{{ $voyage->bateau->capacite_passager ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Capacité cargaison (tonnes)</label>
                        <p class="text-gray-900 font-medium">{{ $voyage->bateau->capacite_cargaison ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Statut</label>
                        <p class="text-gray-900 font-medium">{{ ucfirst($voyage->bateau->statut ?? 'N/A') }}</p>
                    </div>
                </div>
            </div>

            {{-- Trajets --}}
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">
                    <i class="fas fa-route text-blue-500 mr-2"></i>Itinéraire
                </h3>
                @forelse($voyage->trajets->sortBy('ordre') as $trajet)
                <div class="border-l-4 border-blue-400 pl-4 py-2 mb-3 last:mb-0">
                    <div class="flex flex-wrap items-center justify-between">
                        <div>
                            <span class="font-semibold text-gray-800">Étape {{ $trajet->ordre }} : {{ $trajet->nom }}</span>
                            <p class="text-sm text-gray-600">{{ $trajet->description ?? 'Aucune description' }}</p>
                        </div>
                        <div class="text-sm text-gray-500">
                            <span class="mr-3"><i class="fas fa-arrows-left-right mr-1"></i> {{ $trajet->distance }} km</span>
                            <span><i class="far fa-calendar mr-1"></i> {{ \Carbon\Carbon::parse($trajet->date)->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                    <div class="mt-2 flex flex-wrap gap-2">
                        @forelse($trajet->ports as $port)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $port->pivot->role_port == 'Départ' ? 'bg-green-100 text-green-800' :
                               ($port->pivot->role_port == 'Arrivée' ? 'bg-red-100 text-red-800' :
                               'bg-blue-100 text-blue-800') }}">
                            <i class="fas fa-{{ $port->pivot->role_port == 'Départ' ? 'play' :
                               ($port->pivot->role_port == 'Arrivée' ? 'stop' : 'pause') }} mr-1"></i>
                            {{ $port->nom }} ({{ $port->ville }})
                            @if($port->pivot->role_port)
                                <span class="ml-1 font-semibold">- {{ $port->pivot->role_port }}</span>
                            @endif
                        </span>
                        @empty
                        <span class="text-sm text-gray-400">Aucun port associé</span>
                        @endforelse
                    </div>
                </div>
                @empty
                <p class="text-gray-500">Aucun trajet défini pour ce voyage.</p>
                @endforelse
            </div>

            {{-- Pavillons --}}
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">
                    <i class="fas fa-door-open text-blue-500 mr-2"></i>Pavillons disponibles
                </h3>
                @if($voyage->bateau->pavillons->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($voyage->bateau->pavillons as $pavillon)
                    @php
                        $placesPavillon = $pavillon->placesDisponiblesPourVoyage($voyage->id);
                        $low = $placesPavillon < 5;
                    @endphp
                    <div class="border rounded-lg p-4 {{ $low ? 'bg-yellow-50 border-yellow-300' : 'bg-gray-50 border-gray-200' }}">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-semibold text-gray-800">{{ $pavillon->nom }}</h4>
                                <p class="text-sm text-gray-500">{{ $pavillon->classe ?? 'Standard' }}</p>
                            </div>
                            @if($low)
                            <span class="px-2 py-0.5 bg-red-100 text-red-700 text-xs font-semibold rounded-full">⚠️ Dernières places !</span>
                            @endif
                        </div>
                        <div class="mt-2 grid grid-cols-2 gap-2 text-sm">
                            @if($pavillon->prix_unitaire > 0)
                            <div>
                                <span class="text-gray-500">Passager</span>
                                <p class="font-semibold text-gray-800">{{ number_format($pavillon->prix_unitaire, 0, ',', ' ') }} FCFA</p>
                            </div>
                            @endif
                            @if($pavillon->prix_tonne > 0)
                            <div>
                                <span class="text-gray-500">Cargaison</span>
                                <p class="font-semibold text-gray-800">{{ number_format($pavillon->prix_tonne, 0, ',', ' ') }} FCFA/tonne</p>
                            </div>
                            @endif
                            <div class="col-span-2">
                                <span class="text-gray-500">Places disponibles</span>
                                <p class="font-semibold {{ $low ? 'text-red-600' : 'text-green-600' }}">
                                    {{ $placesPavillon }} / {{ $pavillon->capacite_max }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-gray-500">Aucun pavillon disponible pour ce voyage.</p>
                @endif
            </div>
        </div>

        {{-- Colonne de droite --}}
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-md p-6">
                <h4 class="font-semibold text-gray-700 mb-3"><i class="fas fa-info-circle text-blue-500 mr-2"></i>Résumé</h4>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between"><span class="text-gray-600">Code voyage</span><span class="font-medium">{{ $voyage->code_voyage }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-600">Bateau</span><span class="font-medium">{{ $voyage->bateau->nom ?? 'N/A' }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-600">Départ</span><span class="font-medium">{{ $voyage->date_depart->format('d/m/Y H:i') }}</span></div>
                    <div class="flex justify-between border-t pt-2 border-gray-200"><span class="text-gray-600">Places disponibles</span><span class="font-bold {{ $voyage->placesDisponibles() > 0 ? 'text-green-600' : 'text-red-600' }}">{{ $voyage->placesDisponibles() }}</span></div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6">
                <h4 class="font-semibold text-gray-700 mb-3"><i class="fas fa-cog mr-2"></i>Actions</h4>
                <div class="space-y-3">
                    <a href="{{ route('client.reservations.create') }}?voyage={{ $voyage->id }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center justify-center {{ $voyage->placesDisponibles() == 0 ? 'opacity-50 cursor-not-allowed' : '' }}">
                        <i class="fas fa-pencil-alt mr-2"></i> Réserver
                    </a>
                    <a href="{{ route('client.voyages.index') }}" class="w-full bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg flex items-center justify-center">
                        <i class="fas fa-arrow-left mr-2"></i> Retour aux voyages
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection