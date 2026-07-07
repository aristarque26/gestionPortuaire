@extends('layouts.client')

@section('title', 'Voyages disponibles')
@section('header', 'Voyages disponibles')

@section('content')
<div class="container mx-auto px-4 py-6">
    {{-- Statistiques --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-blue-500">
            <p class="text-sm text-gray-500">Voyages disponibles</p>
            <p class="text-2xl font-bold text-gray-800">{{ $voyages->count() }}</p>
        </div>
        @php
            $dates = $voyages->pluck('date_depart')->filter();
        @endphp
        @if($dates->isNotEmpty())
        <div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-green-500">
            <p class="text-sm text-gray-500">Prochain départ</p>
            <p class="text-lg font-bold text-green-600">{{ $dates->min()->format('d/m/Y') }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-purple-500">
            <p class="text-sm text-gray-500">Dernier départ</p>
            <p class="text-lg font-bold text-purple-600">{{ $dates->max()->format('d/m/Y') }}</p>
        </div>
        @endif
        <div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-yellow-500">
            <p class="text-sm text-gray-500">Ports desservis</p>
            <p class="text-2xl font-bold text-yellow-600">
                {{ $voyages->flatMap(fn($v) => $v->trajets->flatMap(fn($t) => $t->ports->pluck('nom')))->unique()->count() }}
            </p>
        </div>
    </div>

    {{-- Filtres et recherche --}}
    <div class="bg-white rounded-xl shadow-md p-4 mb-6">
        <form method="GET" action="{{ route('client.voyages.index') }}" id="filterForm" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Rechercher</label>
                <div class="relative">
                    <input type="text" name="search" id="search" value="{{ request('search') }}" 
                           placeholder="Code, bateau, port, ville..." 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 pl-9 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
            </div>
            <div class="flex-1 min-w-[150px]">
                <label for="port" class="block text-sm font-medium text-gray-700 mb-1">Port de départ</label>
                <select name="port" id="port" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Tous</option>
                    @foreach($ports as $port)
                        <option value="{{ $port->id }}" {{ request('port') == $port->id ? 'selected' : '' }}>
                            {{ $port->nom }} ({{ $port->ville }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1 min-w-[130px]">
                <label for="date_debut" class="block text-sm font-medium text-gray-700 mb-1">Départ après</label>
                <input type="date" name="date_debut" id="date_debut" value="{{ request('date_debut') }}" 
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex-1 min-w-[130px]">
                <label for="date_fin" class="block text-sm font-medium text-gray-700 mb-1">Départ avant</label>
                <input type="date" name="date_fin" id="date_fin" value="{{ request('date_fin') }}" 
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex-1 min-w-[130px]">
                <label for="sort" class="block text-sm font-medium text-gray-700 mb-1">Trier par</label>
                <select name="sort" id="sort" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="date_asc" {{ request('sort') == 'date_asc' ? 'selected' : '' }}>Date (proche → lointain)</option>
                    <option value="date_desc" {{ request('sort') == 'date_desc' ? 'selected' : '' }}>Date (lointain → proche)</option>
                    <option value="prix_asc" {{ request('sort') == 'prix_asc' ? 'selected' : '' }}>Prix (croissant)</option>
                    <option value="prix_desc" {{ request('sort') == 'prix_desc' ? 'selected' : '' }}>Prix (décroissant)</option>
                    <option value="places_asc" {{ request('sort') == 'places_asc' ? 'selected' : '' }}>Places disponibles (croissant)</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-filter mr-1"></i> Filtrer
                </button>
                <a href="{{ route('client.voyages.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg transition">
                    <i class="fas fa-undo mr-1"></i> Réinitialiser
                </a>
            </div>
        </form>
    </div>

    {{-- Liste des voyages --}}
    @if($voyages->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($voyages as $voyage)
            <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition flex flex-col">
                <div class="p-6 flex-1">
                    {{-- En-tête --}}
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">
                                <i class="fas fa-ship text-blue-500 mr-2"></i>{{ $voyage->code_voyage }}
                            </h3>
                            <p class="text-sm text-gray-500">🚢 {{ $voyage->bateau->nom ?? 'Bateau inconnu' }}</p>
                        </div>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                            {{ $voyage->statut == 'disponible' ? 'bg-green-100 text-green-800' : 
                               ($voyage->statut == 'complet' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ ucfirst($voyage->statut ?? 'Disponible') }}
                        </span>
                    </div>

                    {{-- Date et places --}}
                    <div class="flex flex-wrap gap-3 mb-4 text-sm text-gray-600">
                        <span><i class="far fa-calendar-alt mr-1"></i> Départ : {{ $voyage->date_depart->format('d/m/Y H:i') }}</span>
                        <span><i class="fas fa-chair mr-1"></i> Places : {{ $voyage->placesDisponibles() }} / {{ $voyage->bateau->capacite_passager ?? 0 }}</span>
                        @if($voyage->date_arrivee_estimee)
                        <span><i class="fas fa-clock mr-1"></i> Durée : {{ $voyage->date_depart->diffInHours($voyage->date_arrivee_estimee) }}h</span>
                        @endif
                    </div>

                    {{-- Port et quai --}}
                    @php
                        $premierTrajet = $voyage->trajets->first();
                        $premierPort = $premierTrajet ? $premierTrajet->ports->first() : null;
                        $premierQuai = $premierPort ? $premierPort->quais->first() : null;
                    @endphp
                    <div class="bg-gray-50 rounded-lg p-3 mb-4 text-sm">
                        <p class="font-semibold text-gray-700"><i class="fas fa-anchor mr-1"></i> Embarquement</p>
                        <p class="text-gray-600">{{ $premierPort->nom ?? 'N/A' }} - {{ $premierPort->ville ?? '' }}</p>
                        <p class="text-gray-600">Quai {{ $premierQuai->nom ?? 'N/A' }} (n°{{ $premierQuai->numero ?? 'N/A' }})</p>
                        @if($voyage->trajets->count() > 1)
                            <p class="text-xs text-gray-500 mt-1">{{ $voyage->trajets->count() }} escales</p>
                        @endif
                    </div>

                    {{-- Pavillons disponibles --}}
                    <div class="mb-4">
                        <p class="text-sm font-semibold text-gray-700 mb-2"><i class="fas fa-door-open mr-1"></i> Pavillons</p>
                        <div class="space-y-2">
                            @foreach($voyage->bateau->pavillons ?? [] as $pavillon)
                                @php
                                    $placesPavillon = $pavillon->placesDisponiblesPourVoyage($voyage->id);
                                    $prix = $pavillon->prix_unitaire ?? 0;
                                    $classe = $pavillon->classe ?? 'Standard';
                                    $low = $placesPavillon < 5;
                                @endphp
                                <div class="flex justify-between items-center text-sm p-2 rounded {{ $low ? 'bg-yellow-50' : 'bg-gray-50' }}">
                                    <div>
                                        <span class="font-medium">{{ $pavillon->nom }}</span>
                                        <span class="text-xs text-gray-500 ml-2">{{ $classe }}</span>
                                        @if($low)
                                            <span class="ml-2 text-xs text-red-600 font-semibold">⚠️ Dernières places !</span>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        <span class="font-semibold text-gray-800">{{ number_format($prix, 0, ',', ' ') }} FCFA</span>
                                        <span class="text-xs text-gray-500 block">🪑 {{ $placesPavillon }} restantes</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Bouton --}}
                <div class="px-6 pb-4">
                    <a href="{{ route('client.reservations.create') }}?voyage={{ $voyage->id }}" 
                       class="block text-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition {{ $voyage->placesDisponibles() == 0 ? 'opacity-50 cursor-not-allowed' : '' }}">
                        <i class="fas fa-pencil-alt mr-1"></i> Réserver
                    </a>
                    @if($voyage->placesDisponibles() == 0)
                        <p class="text-xs text-center text-red-500 mt-1">Complet</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($voyages->hasPages())
        <div class="mt-6">
            {{ $voyages->appends(request()->query())->links() }}
        </div>
        @endif
    @else
        <div class="bg-white rounded-xl shadow-md p-12 text-center text-gray-500">
            <i class="fas fa-ship text-5xl block mb-4 text-gray-300"></i>
            <p class="text-lg font-medium">Aucun voyage ne correspond à vos critères.</p>
            <p class="text-sm">Essayez d'élargir votre recherche ou de réinitialiser les filtres.</p>
            <a href="{{ route('client.voyages.index') }}" class="inline-block mt-4 text-blue-600 hover:underline">Voir tous les voyages</a>
        </div>
    @endif
</div>

{{-- Script pour auto-soumission au changement des filtres --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('filterForm');
        const inputs = form.querySelectorAll('select, input[type="date"]');
        let timeout = null;

        inputs.forEach(input => {
            input.addEventListener('change', function() {
                clearTimeout(timeout);
                timeout = setTimeout(() => { form.submit(); }, 300);
            });
        });

        // Pour la recherche textuelle, on utilise un délai (debounce)
        const searchInput = document.getElementById('search');
        searchInput.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => { form.submit(); }, 400);
        });
    });
</script>
@endsection