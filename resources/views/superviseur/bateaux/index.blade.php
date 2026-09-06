<!-- resources/views/superviseur/bateaux/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="flex">
    @include('superviseur.layouts.sidebar')
    
    <div class="flex-1 p-6 bg-gray-100 min-h-screen">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Gestion des Bateaux</h1>
            <a href="{{ route('superviseur.bateaux.export', request()->all()) }}" 
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition text-sm flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Exporter CSV
            </a>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
            <div class="bg-white p-3 rounded-lg shadow text-center">
                <p class="text-2xl font-bold text-gray-800">{{ $statistiques['total'] }}</p>
                <p class="text-xs text-gray-500">Total</p>
            </div>
            <div class="bg-white p-3 rounded-lg shadow text-center border-l-4 border-green-500">
                <p class="text-2xl font-bold text-green-600">{{ $statistiques['en_service'] }}</p>
                <p class="text-xs text-gray-500">En service</p>
            </div>
            <div class="bg-white p-3 rounded-lg shadow text-center border-l-4 border-yellow-500">
                <p class="text-2xl font-bold text-yellow-600">{{ $statistiques['en_maintenance'] }}</p>
                <p class="text-xs text-gray-500">Maintenance</p>
            </div>
            <div class="bg-white p-3 rounded-lg shadow text-center border-l-4 border-red-500">
                <p class="text-2xl font-bold text-red-600">{{ $statistiques['hors_service'] }}</p>
                <p class="text-xs text-gray-500">Hors service</p>
            </div>
        </div>

        <!-- Filtres -->
        <div class="bg-white rounded-lg shadow-md p-4 mb-6">
            <form method="GET" action="{{ route('superviseur.bateaux.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Recherche</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Nom ou immatriculation..." class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                    <select name="statut" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="tous">Tous</option>
                        <option value="en_service" {{ request('statut') == 'en_service' ? 'selected' : '' }}>En service</option>
                        <option value="en_maintenance" {{ request('statut') == 'en_maintenance' ? 'selected' : '' }}>Maintenance</option>
                        <option value="hors_service" {{ request('statut') == 'hors_service' ? 'selected' : '' }}>Hors service</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                    <select name="type" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="tous">Tous</option>
                        <option value="cargo" {{ request('type') == 'cargo' ? 'selected' : '' }}>Cargo</option>
                        <option value="mixte" {{ request('type') == 'mixte' ? 'selected' : '' }}>Mixte</option>
                        <option value="passager" {{ request('type') == 'passager' ? 'selected' : '' }}>Passager</option>
                    </select>
                </div>
                <div class="flex items-end space-x-2">
                    <a href="{{ route('superviseur.bateaux.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
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
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Nom</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Immatriculation</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Type</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Capacité</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Statut</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bateaux as $bateau)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm font-medium">{{ $bateau->nom }}</td>
                            <td class="px-4 py-3 text-sm">{{ $bateau->immatriculation }}</td>
                            <td class="px-4 py-3 text-sm">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold
                                    @if($bateau->type == 'cargo') bg-orange-100 text-orange-800
                                    @elseif($bateau->type == 'mixte') bg-purple-100 text-purple-800
                                    @else bg-blue-100 text-blue-800 @endif">
                                    {{ $bateau->type }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm">{{ $bateau->capacite_totale }}</td>
                            <td class="px-4 py-3 text-sm">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold
                                    @if($bateau->statut == 'en_service') bg-green-100 text-green-800
                                    @elseif($bateau->statut == 'en_maintenance') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ $bateau->statut }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <a href="{{ route('superviseur.bateaux.show', $bateau->id) }}" 
                                   class="text-blue-600 hover:underline">Voir</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-gray-500">
                                Aucun bateau trouvé.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 border-t">
                {{ $bateaux->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection