<!-- resources/views/personnel/agent/reservations.blade.php -->
@extends('layouts.app')

@section('content')
<div class="flex">
    @include('superviseur.layouts.sidebar')
    
    <div class="flex-1 p-6 bg-gray-100 min-h-screen">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Gestion des Réservations</h1>
            <a href="{{ route('agent.reservations.export', request()->all()) }}" 
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition text-sm flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Exporter CSV
            </a>
        </div>

        <!-- Statistiques rapides -->
        <div class="grid grid-cols-3 md:grid-cols-6 gap-3 mb-6">
            <div class="bg-white p-3 rounded-lg shadow text-center">
                <p class="text-2xl font-bold text-gray-800">{{ $statistiques['total'] }}</p>
                <p class="text-xs text-gray-500">Total</p>
            </div>
            <div class="bg-white p-3 rounded-lg shadow text-center border-l-4 border-yellow-500">
                <p class="text-2xl font-bold text-yellow-600">{{ $statistiques['en_attente'] }}</p>
                <p class="text-xs text-gray-500">En attente</p>
            </div>
            <div class="bg-white p-3 rounded-lg shadow text-center border-l-4 border-blue-500">
                <p class="text-2xl font-bold text-blue-600">{{ $statistiques['confirme'] }}</p>
                <p class="text-xs text-gray-500">Confirmées</p>
            </div>
            <div class="bg-white p-3 rounded-lg shadow text-center border-l-4 border-green-500">
                <p class="text-2xl font-bold text-green-600">{{ $statistiques['paye'] }}</p>
                <p class="text-xs text-gray-500">Payées</p>
            </div>
            <div class="bg-white p-3 rounded-lg shadow text-center border-l-4 border-purple-500">
                <p class="text-2xl font-bold text-purple-600">{{ $statistiques['arrive'] }}</p>
                <p class="text-xs text-gray-500">Arrivées</p>
            </div>
            <div class="bg-white p-3 rounded-lg shadow text-center border-l-4 border-red-500">
                <p class="text-2xl font-bold text-red-600">{{ $statistiques['annule'] }}</p>
                <p class="text-xs text-gray-500">Annulées</p>
            </div>
        </div>

        <!-- Filtres -->
        <div class="bg-white rounded-lg shadow-md p-4 mb-6">
            <form method="GET" action="{{ route('agent.reservations.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                    <select name="statut" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="tous">Tous</option>
                        <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                        <option value="confirme" {{ request('statut') == 'confirme' ? 'selected' : '' }}>Confirmée</option>
                        <option value="paye" {{ request('statut') == 'paye' ? 'selected' : '' }}>Payée</option>
                        <option value="arrive" {{ request('statut') == 'arrive' ? 'selected' : '' }}>Arrivée</option>
                        <option value="annule" {{ request('statut') == 'annule' ? 'selected' : '' }}>Annulée</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Recherche</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Nom client..." class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div class="flex items-end space-x-2">
                    <a href="{{ route('agent.reservations.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                        Réinitialiser
                    </a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Filtrer
                    </button>
                </div>
            </form>
        </div>

        <!-- Tableau -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">#</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Client</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Bateau</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Type</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Date</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Montant</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Statut</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reservations as $res)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm">{{ $res->id }}</td>
                            <td class="px-4 py-3 text-sm">{{ $res->client->nom }} {{ $res->client->prenom }}</td>
                            <td class="px-4 py-3 text-sm">{{ $res->voyage->bateau->nom ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-sm">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold
                                    @if($res->type_reservation == 'passage') bg-blue-100 text-blue-800
                                    @elseif($res->type_reservation == 'cargaison') bg-orange-100 text-orange-800
                                    @else bg-purple-100 text-purple-800 @endif">
                                    {{ $res->type_reservation }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm">{{ Carbon\Carbon::parse($res->date_reservation)->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3 text-sm font-semibold">{{ number_format($res->prix_total, 0, ',', ' ') }} FC</td>
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
                            <td class="px-4 py-3 text-sm">
                                <a href="{{ route('agent.reservations.show', $res->id) }}" class="text-blue-600 hover:underline">Voir</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-8 text-gray-500">
                                Aucune réservation trouvée.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 border-t">
                {{ $reservations->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection