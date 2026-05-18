@extends('layouts.client')

@section('title', 'Tableau de bord')
@section('header', 'Mon tableau de bord')

@section('content')
<!-- Statistiques -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-md p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm opacity-90">Mes réservations</p>
                <p class="text-3xl font-bold">{{ $totalReservations ?? 0 }}</p>
            </div>
            <span class="text-4xl">📋</span>
        </div>
    </div>
    
    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-md p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm opacity-90">Voyages à venir</p>
                <p class="text-3xl font-bold">{{ $voyagesAVenir ?? 0 }}</p>
            </div>
            <span class="text-4xl">✈️</span>
        </div>
    </div>
    
    <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl shadow-md p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm opacity-90">Paiements effectués</p>
                <p class="text-3xl font-bold">{{ $totalPaiements ?? 0 }}</p>
            </div>
            <span class="text-4xl">💰</span>
        </div>
    </div>
</div>

<!-- Actions rapides -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <a href="{{ route('client.reservations.create') }}" class="block bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition transform hover:scale-105">
        <div class="flex items-center space-x-4">
            <div class="p-3 bg-blue-100 rounded-full">
                <span class="text-2xl">📝</span>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800">Nouvelle réservation</h3>
                <p class="text-sm text-gray-500">Réservez un voyage</p>
            </div>
        </div>
    </a>
    
    <a href="{{ route('client.reservations.index') }}" class="block bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition transform hover:scale-105">
        <div class="flex items-center space-x-4">
            <div class="p-3 bg-green-100 rounded-full">
                <span class="text-2xl">📋</span>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800">Mes réservations</h3>
                <p class="text-sm text-gray-500">Consultez l'historique</p>
            </div>
        </div>
    </a>
    
    <a href="{{ route('client.paiements.index') }}" class="block bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition transform hover:scale-105">
        <div class="flex items-center space-x-4">
            <div class="p-3 bg-yellow-100 rounded-full">
                <span class="text-2xl">💰</span>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800">Mes paiements</h3>
                <p class="text-sm text-gray-500">Suivez vos transactions</p>
            </div>
        </div>
    </a>
    
    <a href="{{ route('client.profil.show') }}" class="block bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition transform hover:scale-105">
        <div class="flex items-center space-x-4">
            <div class="p-3 bg-purple-100 rounded-full">
                <span class="text-2xl">👤</span>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800">Mon profil</h3>
                <p class="text-sm text-gray-500">Modifiez vos informations</p>
            </div>
        </div>
    </a>
</div>

<!-- Dernières réservations -->
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800">📋 Mes dernières réservations</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Voyage</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($dernieresReservations ?? [] as $reservation)
                <tr>
                    <td class="px-6 py-4">#{{ $reservation->id }}</td>
                    <td class="px-6 py-4">{{ $reservation->voyage->code_voyage ?? 'N/A' }}</td>
                    <td class="px-6 py-4">{{ $reservation->created_at->format('d/m/Y') }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full 
                            {{ $reservation->statut == 'confirme' ? 'bg-green-100 text-green-800' : 
                               ($reservation->statut == 'en_attente' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            {{ $reservation->statut }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('client.reservations.show', $reservation->id) }}" class="text-blue-600 hover:text-blue-900">Voir</a>
                    </td>
                </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            Aucune réservation pour le moment.
                            <a href="{{ route('client.reservations.create') }}" class="text-blue-600 hover:underline">Réserver maintenant</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection