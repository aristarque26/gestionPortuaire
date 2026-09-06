<!-- resources/views/superviseur/voyages/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="flex">
    @include('superviseur.layouts.sidebar')
    
    <div class="flex-1 p-6 bg-gray-100 min-h-screen">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Gestion des Voyages</h1>
            <a href="{{ route('superviseur.voyages.export', request()->all()) }}" 
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition text-sm flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Exporter CSV
            </a>
        </div>

        <!-- Statistiques - Format cartes comme c6 -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow-md p-4 text-center border-l-4 border-gray-500 hover:shadow-lg transition hover:scale-105">
                <p class="text-2xl font-bold text-gray-800">{{ $statistiques['total'] }}</p>
                <p class="text-xs text-gray-500 uppercase tracking-wider">Total</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4 text-center border-l-4 border-blue-500 hover:shadow-lg transition hover:scale-105">
                <p class="text-2xl font-bold text-blue-600">{{ $statistiques['prevu'] }}</p>
                <p class="text-xs text-gray-500 uppercase tracking-wider">Prévus</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4 text-center border-l-4 border-green-500 hover:shadow-lg transition hover:scale-105">
                <p class="text-2xl font-bold text-green-600">{{ $statistiques['en_cours'] }}</p>
                <p class="text-xs text-gray-500 uppercase tracking-wider">En cours</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4 text-center border-l-4 border-purple-500 hover:shadow-lg transition hover:scale-105">
                <p class="text-2xl font-bold text-purple-600">{{ $statistiques['termine'] }}</p>
                <p class="text-xs text-gray-500 uppercase tracking-wider">Terminés</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4 text-center border-l-4 border-red-500 hover:shadow-lg transition hover:scale-105">
                <p class="text-2xl font-bold text-red-600">{{ $statistiques['annule'] }}</p>
                <p class="text-xs text-gray-500 uppercase tracking-wider">Annulés</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4 text-center border-l-4 border-yellow-500 hover:shadow-lg transition hover:scale-105">
                <p class="text-2xl font-bold text-yellow-600">{{ $statistiques['prochains'] }}</p>
                <p class="text-xs text-gray-500 uppercase tracking-wider">Prochains</p>
            </div>
        </div>

        <!-- Filtres -->
        <div class="bg-white rounded-lg shadow-md p-4 mb-6">
            <form method="GET" action="{{ route('superviseur.voyages.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                    <select name="statut" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="tous">Tous</option>
                        <option value="prevu" {{ request('statut') == 'prevu' ? 'selected' : '' }}>Prévu</option>
                        <option value="en_cours" {{ request('statut') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                        <option value="termine" {{ request('statut') == 'termine' ? 'selected' : '' }}>Terminé</option>
                        <option value="annule" {{ request('statut') == 'annule' ? 'selected' : '' }}>Annulé</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bateau</label>
                    <select name="bateau_id" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Tous</option>
                        @foreach($bateaux as $bateau)
                        <option value="{{ $bateau->id }}" {{ request('bateau_id') == $bateau->id ? 'selected' : '' }}>{{ $bateau->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date début</label>
                    <input type="date" name="date_debut" value="{{ request('date_debut') }}" 
                           class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date fin</label>
                    <input type="date" name="date_fin" value="{{ request('date_fin') }}" 
                           class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div class="flex items-end space-x-2">
                    <a href="{{ route('superviseur.voyages.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
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
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Code</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Bateau</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Description</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Date départ</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Statut</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Réservations</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($voyages as $voyage)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm font-medium">{{ $voyage->code_voyage }}</td>
                            <td class="px-4 py-3 text-sm">{{ $voyage->bateau->nom ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-sm">{{ Str::limit($voyage->description, 30) }}</td>
                            <td class="px-4 py-3 text-sm">{{ Carbon\Carbon::parse($voyage->date_depart)->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3 text-sm">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold
                                    @if($voyage->statut == 'prevu') bg-blue-100 text-blue-800
                                    @elseif($voyage->statut == 'en_cours') bg-green-100 text-green-800
                                    @elseif($voyage->statut == 'termine') bg-purple-100 text-purple-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ $voyage->statut }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-center">{{ $voyage->reservations->count() }}</td>
                            <td class="px-4 py-3 text-sm">
                                <a href="{{ route('superviseur.voyages.show', $voyage->id) }}" 
                                   class="text-blue-600 hover:underline">Voir</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-gray-500">
                                Aucun voyage trouvé.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 border-t">
                {{ $voyages->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection