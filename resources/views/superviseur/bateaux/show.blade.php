<!-- resources/views/superviseur/bateaux/show.blade.php -->
@extends('layouts.app')

@section('content')
<div class="flex">
    @include('superviseur.layouts.sidebar')
    
    <div class="flex-1 p-6 bg-gray-100 min-h-screen">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Détail du bateau</h1>
                <p class="text-sm text-gray-500">{{ $bateau->nom }} - {{ $bateau->immatriculation }}</p>
            </div>
            <a href="{{ route('superviseur.bateaux.index') }}" class="text-blue-600 hover:underline">
                ← Retour à la liste
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <!-- Informations -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Informations du bateau</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Nom</p>
                            <p class="font-medium">{{ $bateau->nom }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Immatriculation</p>
                            <p class="font-medium">{{ $bateau->immatriculation }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Type</p>
                            <span class="px-2 py-1 rounded-full text-xs font-semibold
                                @if($bateau->type == 'cargo') bg-orange-100 text-orange-800
                                @elseif($bateau->type == 'mixte') bg-purple-100 text-purple-800
                                @else bg-blue-100 text-blue-800 @endif">
                                {{ $bateau->type }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Statut</p>
                            <span class="px-2 py-1 rounded-full text-xs font-semibold
                                @if($bateau->statut == 'en_service') bg-green-100 text-green-800
                                @elseif($bateau->statut == 'en_maintenance') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ $bateau->statut }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Capacité totale</p>
                            <p class="font-medium">{{ $bateau->capacite_totale }} places</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Capacité passagers</p>
                            <p class="font-medium">{{ $bateau->capacite_passager }} places</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Capacité cargaison</p>
                            <p class="font-medium">{{ $bateau->capacite_cargaison }} tonnes</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Quai actuel</p>
                            <p class="font-medium">{{ $quaiActuel->nom ?? 'Non assigné' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Pavillons -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Pavillons</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($bateau->pavillons as $pavillon)
                        <div class="border rounded-lg p-3">
                            <p class="font-medium">{{ $pavillon->nom }}</p>
                            <p class="text-sm text-gray-500">Classe: {{ $pavillon->classe }}</p>
                            <p class="text-sm text-gray-500">Capacité: {{ $pavillon->capacite_max }}</p>
                            <p class="text-sm font-semibold text-green-600">{{ number_format($pavillon->prix_unitaire, 0, ',', ' ') }} {{ $pavillon->devise }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Colonne droite -->
            <div class="space-y-6">
                <!-- Statistiques -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Statistiques</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Voyages</span>
                            <span class="font-bold">{{ $stats['nb_voyages'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Voyages prévus</span>
                            <span class="font-bold">{{ $stats['nb_voyages_prevus'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Réservations</span>
                            <span class="font-bold">{{ $stats['nb_reservations'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">CA total</span>
                            <span class="font-bold text-green-600">{{ number_format($stats['ca_total'], 0, ',', ' ') }} FC</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Capacité utilisée</span>
                            <span class="font-bold">{{ number_format($stats['capacite_utilisee'], 0) }} / {{ $stats['capacite_totale'] }}</span>
                        </div>
                    </div>
                </div>

                <!-- Derniers voyages -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Derniers voyages</h3>
                    <div class="space-y-2">
                        @foreach($bateau->voyages as $voyage)
                        <div class="flex justify-between items-center border-b pb-2">
                            <span class="text-sm">{{ $voyage->code_voyage }}</span>
                            <span class="text-xs px-2 py-1 rounded-full
                                @if($voyage->statut == 'prevu') bg-blue-100 text-blue-800
                                @elseif($voyage->statut == 'en_cours') bg-green-100 text-green-800
                                @elseif($voyage->statut == 'termine') bg-purple-100 text-purple-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ $voyage->statut }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection