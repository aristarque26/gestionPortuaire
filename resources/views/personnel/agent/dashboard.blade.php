<!-- resources/views/personnel/agent/dashboard.blade.php -->
@extends('layouts.app')

@section('content')
<div class="flex">
    @include('superviseur.layouts.sidebar')
    
    <div class="flex-1 p-6 bg-gray-100 min-h-screen">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Dashboard Agent Portuaire</h1>
            <div class="text-sm text-gray-500">{{ now()->format('d/m/Y H:i') }}</div>
        </div>

        <!-- Message de bienvenue -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Bienvenue, {{ $user->name }} {{ $user->prenom }} !</h2>
            <p class="text-gray-600 mt-2">Agent portuaire - {{ $user->personnel->poste ?? 'N/A' }}</p>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                <p class="text-sm text-gray-500">Réservations aujourd'hui</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['reservations_aujourdhui'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
                <p class="text-sm text-gray-500">En attente</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['reservations_en_attente'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
                <p class="text-sm text-gray-500">Voyages en cours</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['voyages_en_cours'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
                <p class="text-sm text-gray-500">Bateaux en service</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['bateaux_service'] }}</p>
            </div>
        </div>

        <!-- Prochains voyages -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Prochains voyages</h3>
                <div class="space-y-3">
                    @forelse($prochainsVoyages as $voyage)
                    <div class="flex justify-between items-center border-b pb-2">
                        <div>
                            <p class="font-medium">{{ $voyage->code_voyage }}</p>
                            <p class="text-sm text-gray-500">{{ $voyage->bateau->nom ?? 'N/A' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold">{{ Carbon\Carbon::parse($voyage->date_depart)->format('d/m/Y H:i') }}</p>
                            <span class="text-xs px-2 py-1 rounded-full bg-blue-100 text-blue-800">Prévu</span>
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-gray-500">Aucun voyage prévu</p>
                    @endforelse
                </div>
            </div>

            <!-- Dernières réservations -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Dernières réservations</h3>
                    <a href="{{ route('agent.reservations.index') }}" class="text-blue-600 hover:underline text-sm">
                        Voir toutes →
                    </a>
                </div>
                <div class="space-y-3">
                    @forelse($dernieresReservations as $res)
                    <div class="flex justify-between items-center border-b pb-2">
                        <div>
                            <p class="font-medium">{{ $res->client->nom }} {{ $res->client->prenom }}</p>
                            <p class="text-sm text-gray-500">{{ $res->voyage->bateau->nom ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="text-xs px-2 py-1 rounded-full
                                @if($res->statut == 'en_attente') bg-yellow-100 text-yellow-800
                                @elseif($res->statut == 'confirme') bg-blue-100 text-blue-800
                                @elseif($res->statut == 'paye') bg-green-100 text-green-800
                                @elseif($res->statut == 'arrive') bg-purple-100 text-purple-800
                                @elseif($res->statut == 'annule') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ $res->statut }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-gray-500">Aucune réservation</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection