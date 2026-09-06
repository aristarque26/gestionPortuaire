<!-- resources/views/personnel/dashboard.blade.php -->
@extends('layouts.app')

@section('content')
<div class="flex">
    @include('personnel.layouts.sidebar')
    
    <div class="flex-1 p-6 bg-gray-100 min-h-screen">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Tableau de bord - Personnel</h1>
            <div class="text-sm text-gray-500">{{ now()->format('d/m/Y H:i') }}</div>
        </div>

        <!-- Message de bienvenue -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Bienvenue, {{ $user->name }} {{ $user->prenom }} !</h2>
            <p class="text-gray-600 mt-2">
                Vous êtes connecté en tant que 
                <span class="font-semibold text-blue-600">{{ $user->personnel->personnel_role ?? 'Personnel' }}</span>
            </p>
            <p class="text-gray-500 text-sm mt-1">Poste : {{ $user->personnel->poste ?? 'N/A' }}</p>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                <p class="text-sm text-gray-500">Réservations aujourd'hui</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['reservations_aujourdhui'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
                <p class="text-sm text-gray-500">Voyages en cours</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['voyages_en_cours'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
                <p class="text-sm text-gray-500">Bateaux en service</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['bateaux_service'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
                <p class="text-sm text-gray-500">Personnel actif</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['personnel_actif'] }}</p>
            </div>
        </div>

        <!-- Dernières réservations -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Dernières réservations</h3>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Client</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Bateau</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Date</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dernieresReservations as $res)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm">{{ $res->client->nom }} {{ $res->client->prenom }}</td>
                            <td class="px-4 py-3 text-sm">{{ $res->voyage->bateau->nom ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-sm">{{ Carbon\Carbon::parse($res->date_reservation)->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3 text-sm">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold
                                    @if($res->statut == 'en_attente') bg-yellow-100 text-yellow-800
                                    @elseif($res->statut == 'confirme') bg-blue-100 text-blue-800
                                    @elseif($res->statut == 'paye') bg-green-100 text-green-800
                                    @elseif($res->statut == 'arrive') bg-purple-100 text-purple-800
                                    @elseif($res->statut == 'annule') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ $res->statut }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-gray-500">Aucune réservation récente</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection