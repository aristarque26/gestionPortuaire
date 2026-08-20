@extends('layouts.client')

@section('title', 'Mes réservations')
@section('header', 'Mes réservations')

@section('content')
<div class="container mx-auto px-4 py-6">
    {{-- Statistiques --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
            <p class="text-sm text-gray-500">Total</p>
            <p class="text-2xl font-bold text-gray-800">{{ $reservations->count() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
            <p class="text-sm text-gray-500">Confirmées</p>
            <p class="text-2xl font-bold text-green-600">{{ $reservations->where('statut', 'confirme')->count() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-yellow-500">
            <p class="text-sm text-gray-500">En attente</p>
            <p class="text-2xl font-bold text-yellow-600">{{ $reservations->where('statut', 'en_attente')->count() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-red-500">
            <p class="text-sm text-gray-500">Annulées</p>
            <p class="text-2xl font-bold text-red-600">{{ $reservations->where('statut', 'annule')->count() }}</p>
        </div>
    </div>

    {{-- Filtres --}}
    <div class="bg-white rounded-xl shadow-md p-4 mb-6">
        <form method="GET" action="{{ route('client.reservations.index') }}" id="filterForm" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[150px]">
                <label for="statut" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                <select name="statut" id="statut" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">Tous</option>
                    <option value="confirme" {{ request('statut') == 'confirme' ? 'selected' : '' }}>Confirmé</option>
                    <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                    <option value="annule" {{ request('statut') == 'annule' ? 'selected' : '' }}>Annulé</option>
                </select>
            </div>
            <div class="flex-1 min-w-[150px]">
                <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                <select name="type" id="type" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">Tous</option>
                    <option value="passage" {{ request('type') == 'passage' ? 'selected' : '' }}>Passage</option>
                    <option value="cargaison" {{ request('type') == 'cargaison' ? 'selected' : '' }}>Cargaison</option>
                    <option value="mixte" {{ request('type') == 'mixte' ? 'selected' : '' }}>Mixte</option>
                </select>
            </div>
            <div class="flex-1 min-w-[200px]">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Rechercher</label>
                <div class="relative">
                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Code voyage ou ID..." class="w-full border border-gray-300 rounded-lg px-3 py-2 pl-9">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-filter mr-1"></i> Filtrer
                </button>
                <a href="{{ route('client.reservations.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg transition">
                    <i class="fas fa-undo mr-1"></i> Réinitialiser
                </a>
            </div>
        </form>
    </div>

    {{-- Liste des réservations --}}
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        {{-- Vue desktop --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"><i class="fas fa-hashtag mr-1"></i>ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"><i class="fas fa-ship mr-1"></i>Voyage</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"><i class="fas fa-tag mr-1"></i>Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"><i class="fas fa-calendar mr-1"></i>Date réservation</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"><i class="fas fa-coins mr-1"></i>Prix total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"><i class="fas fa-circle mr-1"></i>Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"><i class="fas fa-cog mr-1"></i>Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($reservations as $reservation)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">#{{ $reservation->id }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $reservation->voyage->code_voyage ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ ucfirst($reservation->type_reservation) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $reservation->date_reservation->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ number_format($reservation->prix_total ?? 0, 0, ',', ' ') }} FCFA</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full font-medium
                                {{ $reservation->statut == 'confirme' ? 'bg-green-100 text-green-800' :
                                   ($reservation->statut == 'en_attente' ? 'bg-yellow-100 text-yellow-800' :
                                   'bg-red-100 text-red-800') }}">
                                {{ ucfirst($reservation->statut) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <a href="{{ route('client.reservations.show', $reservation->id) }}" class="text-blue-600 hover:text-blue-900 mr-3" title="Voir les détails">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if($reservation->statut == 'en_attente')
                            <form action="{{ route('client.reservations.destroy', $reservation->id) }}" method="POST" class="inline" onsubmit="return confirm('Annuler cette réservation ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" title="Annuler">
                                    <i class="fas fa-times"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-inbox text-3xl block mb-2 text-gray-300"></i>
                            Aucune réservation trouvée pour ces critères.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Vue mobile --}}
        <div class="md:hidden divide-y divide-gray-200">
            @forelse($reservations as $reservation)
            <div class="p-4 hover:bg-gray-50 transition">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="font-medium text-gray-900">#{{ $reservation->id }} - {{ $reservation->voyage->code_voyage ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-500 mt-1">
                            <span class="inline-block w-2 h-2 rounded-full mr-1 
                                {{ $reservation->statut == 'confirme' ? 'bg-green-500' :
                                   ($reservation->statut == 'en_attente' ? 'bg-yellow-500' : 'bg-red-500') }}">
                            </span>
                            {{ ucfirst($reservation->statut) }}
                        </p>
                    </div>
                    <span class="text-sm font-medium text-gray-900">{{ number_format($reservation->prix_total ?? 0, 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="mt-2 grid grid-cols-2 gap-1 text-sm text-gray-600">
                    <p><span class="font-medium">Type :</span> {{ ucfirst($reservation->type_reservation) }}</p>
                    <p><span class="font-medium">Date :</span> {{ $reservation->date_reservation->format('d/m/Y H:i') }}</p>
                </div>
                <div class="mt-3 flex gap-3">
                    <a href="{{ route('client.reservations.show', $reservation->id) }}" class="text-blue-600 hover:text-blue-900 text-sm">
                        <i class="fas fa-eye mr-1"></i> Voir
                    </a>
                    @if($reservation->statut == 'en_attente')
                    <form action="{{ route('client.reservations.destroy', $reservation->id) }}" method="POST" class="inline" onsubmit="return confirm('Annuler cette réservation ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900 text-sm">
                            <i class="fas fa-times mr-1"></i> Annuler
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            @empty
            <div class="p-8 text-center text-gray-500">
                <i class="fas fa-inbox text-4xl block mb-2 text-gray-300"></i>
                Aucune réservation trouvée.
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($reservations->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $reservations->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>

{{-- Script pour auto-soumettre le formulaire --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterForm = document.getElementById('filterForm');
        const selects = filterForm.querySelectorAll('select');
        selects.forEach(select => {
            select.addEventListener('change', function() {
                filterForm.submit();
            });
        });
        const searchInput = document.getElementById('search');
        let timeout = null;
        searchInput.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                filterForm.submit();
            }, 500);
        });
    });
</script>
@endsection

/* Documentation complète de l'application - toutes les fonctionnalités expliquées */