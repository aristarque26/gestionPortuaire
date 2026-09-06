<!-- resources/views/superviseur/voyages/show.blade.php -->
@extends('layouts.app')

@section('content')
<div class="flex">
    @include('superviseur.layouts.sidebar')
    
    <div class="flex-1 p-6 bg-gray-100 min-h-screen">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Détail du voyage</h1>
                <p class="text-sm text-gray-500">{{ $voyage->code_voyage }} - {{ $voyage->bateau->nom ?? 'N/A' }}</p>
            </div>
            <a href="{{ route('superviseur.voyages.index') }}" class="text-blue-600 hover:underline">
                ← Retour à la liste
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <!-- Informations -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Informations du voyage</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Code</p>
                            <p class="font-medium">{{ $voyage->code_voyage }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Bateau</p>
                            <p class="font-medium">{{ $voyage->bateau->nom ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Description</p>
                            <p class="font-medium">{{ $voyage->description ?? 'Aucune description' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Statut</p>
                            <span class="px-2 py-1 rounded-full text-xs font-semibold
                                @if($voyage->statut == 'prevu') bg-blue-100 text-blue-800
                                @elseif($voyage->statut == 'en_cours') bg-green-100 text-green-800
                                @elseif($voyage->statut == 'termine') bg-purple-100 text-purple-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ $voyage->statut }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Date de départ</p>
                            <p class="font-medium">{{ Carbon\Carbon::parse($voyage->date_depart)->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Créé le</p>
                            <p class="font-medium">{{ Carbon\Carbon::parse($voyage->created_at)->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Trajets -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Trajets</h3>
                    <div class="space-y-2">
                        @foreach($voyage->trajets as $trajet)
                        <div class="flex items-center justify-between border-b pb-2">
                            <div class="flex items-center space-x-3">
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">{{ $trajet->ordre }}</span>
                                <span class="font-medium">{{ $trajet->nom }}</span>
                                <span class="text-sm text-gray-500">{{ number_format($trajet->distance, 0) }} km</span>
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $trajet->ports->first()->nom ?? 'N/A' }}
                            </div>
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
                            <span class="text-sm text-gray-500">Réservations</span>
                            <span class="font-bold text-lg">{{ $stats['nb_reservations'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Passagers</span>
                            <span class="font-bold">{{ $stats['nb_passagers'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Cargaison (kg)</span>
                            <span class="font-bold">{{ $stats['nb_cargaison'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">CA total</span>
                            <span class="font-bold text-green-600">{{ number_format($stats['ca_total'], 0, ',', ' ') }} FC</span>
                        </div>
                    </div>
                </div>

                <!-- Par statut -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Répartition</h3>
                    <div class="space-y-2">
                        @foreach($stats['par_statut'] as $statut => $count)
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">{{ ucfirst($statut) }}</span>
                            <span class="font-medium">{{ $count }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection