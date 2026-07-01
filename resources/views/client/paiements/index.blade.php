@extends('layouts.client')

@section('title', 'Mes paiements')
@section('header', 'Mes paiements')

@section('content')
<div class="container mx-auto px-4 py-6">
    {{-- Statistiques --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
            <p class="text-sm text-gray-500">Total</p>
            <p class="text-2xl font-bold text-gray-800">{{ $paiements->total() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
            <p class="text-sm text-gray-500">Payé</p>
            <p class="text-2xl font-bold text-green-600">{{ $paiements->where('statut', 'paye')->count() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-yellow-500">
            <p class="text-sm text-gray-500">En attente</p>
            <p class="text-2xl font-bold text-yellow-600">{{ $paiements->where('statut', 'en_attente')->count() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-red-500">
            <p class="text-sm text-gray-500">Échoué</p>
            <p class="text-2xl font-bold text-red-600">{{ $paiements->where('statut', 'echoue')->count() }}</p>
        </div>
    </div>

    {{-- Filtres --}}
    <div class="bg-white rounded-xl shadow-md p-4 mb-6">
        <form method="GET" action="{{ route('client.paiements.index') }}" id="filterForm" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[150px]">
                <label for="statut" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                <select name="statut" id="statut" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">Tous</option>
                    <option value="paye" {{ request('statut') == 'paye' ? 'selected' : '' }}>Payé</option>
                    <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                    <option value="echoue" {{ request('statut') == 'echoue' ? 'selected' : '' }}>Échoué</option>
                </select>
            </div>
            <div class="flex-1 min-w-[150px]">
                <label for="mode" class="block text-sm font-medium text-gray-700 mb-1">Mode de paiement</label>
                <select name="mode" id="mode" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">Tous</option>
                    <option value="carte" {{ request('mode') == 'carte' ? 'selected' : '' }}>Carte bancaire</option>
                    <option value="mobile_money" {{ request('mode') == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                    <option value="virement" {{ request('mode') == 'virement' ? 'selected' : '' }}>Virement</option>
                    <option value="especes" {{ request('mode') == 'especes' ? 'selected' : '' }}>Espèces</option>
                </select>
            </div>
            <div class="flex-1 min-w-[150px]">
                <label for="date_debut" class="block text-sm font-medium text-gray-700 mb-1">Date début</label>
                <input type="date" name="date_debut" id="date_debut" value="{{ request('date_debut') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>
            <div class="flex-1 min-w-[150px]">
                <label for="date_fin" class="block text-sm font-medium text-gray-700 mb-1">Date fin</label>
                <input type="date" name="date_fin" id="date_fin" value="{{ request('date_fin') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-filter mr-1"></i> Filtrer
                </button>
                <a href="{{ route('client.paiements.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg transition">
                    <i class="fas fa-undo mr-1"></i> Réinitialiser
                </a>
            </div>
        </form>
    </div>

    {{-- Liste des paiements --}}
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        @if($paiements->total() > 0)
        <div class="px-6 py-3 border-b border-gray-200 text-sm text-gray-500">
            <i class="fas fa-list mr-1"></i> {{ $paiements->total() }} paiement(s) trouvé(s)
        </div>
        @endif

        {{-- Vue desktop --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"><i class="fas fa-hashtag mr-1"></i>ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"><i class="fas fa-ticket-alt mr-1"></i>Réservation</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"><i class="fas fa-money-bill-wave mr-1"></i>Montant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"><i class="fas fa-coins mr-1"></i>Devise</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"><i class="fas fa-credit-card mr-1"></i>Mode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"><i class="fas fa-calendar mr-1"></i>Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"><i class="fas fa-circle mr-1"></i>Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"><i class="fas fa-cog mr-1"></i>Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($paiements as $paiement)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">#{{ $paiement->id }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">
                            <a href="{{ route('client.reservations.show', $paiement->idreservation) }}" class="text-blue-600 hover:underline">
                                #{{ $paiement->idreservation }}
                            </a>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ number_format($paiement->montant, 0, ',', ' ') }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ strtoupper($paiement->devise) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">
                            @php
                                $modes = [
                                    'carte' => 'Carte bancaire',
                                    'mobile_money' => 'Mobile Money',
                                    'virement' => 'Virement',
                                    'especes' => 'Espèces'
                                ];
                            @endphp
                            {{ $modes[$paiement->mode_paiement] ?? $paiement->mode_paiement }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $paiement->date_paiement->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                {{ $paiement->statut == 'paye' ? 'bg-green-100 text-green-800' :
                                   ($paiement->statut == 'en_attente' ? 'bg-yellow-100 text-yellow-800' :
                                   'bg-red-100 text-red-800') }}">
                                {{ ucfirst($paiement->statut) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <a href="{{ route('client.paiements.show', $paiement->id) }}" class="text-blue-600 hover:text-blue-900" title="Voir le détail">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if($paiement->statut == 'paye' && isset($paiement->recu))
                                <a href="{{ route('client.paiements.recu', $paiement->id) }}" class="text-green-600 hover:text-green-900 ml-2" title="Télécharger le reçu">
                                    <i class="fas fa-download"></i>
                                </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-inbox text-3xl block mb-2 text-gray-300"></i>
                            Aucun paiement trouvé pour ces critères.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Vue mobile --}}
        <div class="md:hidden divide-y divide-gray-200">
            @forelse($paiements as $paiement)
            <div class="p-4 hover:bg-gray-50 transition">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="font-medium text-gray-900">Paiement #{{ $paiement->id }}</p>
                        <p class="text-sm text-gray-500 mt-1">
                            Réservation <a href="{{ route('client.reservations.show', $paiement->idreservation) }}" class="text-blue-600 hover:underline">#{{ $paiement->idreservation }}</a>
                        </p>
                    </div>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                        {{ $paiement->statut == 'paye' ? 'bg-green-100 text-green-800' :
                           ($paiement->statut == 'en_attente' ? 'bg-yellow-100 text-yellow-800' :
                           'bg-red-100 text-red-800') }}">
                        {{ ucfirst($paiement->statut) }}
                    </span>
                </div>
                <div class="mt-2 grid grid-cols-2 gap-1 text-sm text-gray-600">
                    <p><span class="font-medium">Montant :</span> {{ number_format($paiement->montant, 0, ',', ' ') }} {{ strtoupper($paiement->devise) }}</p>
                    <p><span class="font-medium">Mode :</span> {{ $modes[$paiement->mode_paiement] ?? $paiement->mode_paiement }}</p>
                    <p><span class="font-medium">Date :</span> {{ $paiement->date_paiement->format('d/m/Y H:i') }}</p>
                </div>
                <div class="mt-3 flex gap-3">
                    <a href="{{ route('client.paiements.show', $paiement->id) }}" class="text-blue-600 hover:text-blue-900 text-sm">
                        <i class="fas fa-eye mr-1"></i> Voir
                    </a>
                    @if($paiement->statut == 'paye' && isset($paiement->recu))
                        <a href="{{ route('client.paiements.recu', $paiement->id) }}" class="text-green-600 hover:text-green-900 text-sm">
                            <i class="fas fa-download mr-1"></i> Reçu
                        </a>
                    @endif
                </div>
            </div>
            @empty
            <div class="p-8 text-center text-gray-500">
                <i class="fas fa-inbox text-4xl block mb-2 text-gray-300"></i>
                Aucun paiement trouvé.
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($paiements->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $paiements->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>

{{-- Script pour auto-soumettre le formulaire au changement (optionnel) --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterForm = document.getElementById('filterForm');
        const selects = filterForm.querySelectorAll('select');
        selects.forEach(select => {
            select.addEventListener('change', function() {
                filterForm.submit();
            });
        });
        // Pour les champs de date, on soumet après un délai (debounce)
        const dateInputs = filterForm.querySelectorAll('input[type="date"]');
        let timeout = null;
        dateInputs.forEach(input => {
            input.addEventListener('change', function() {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    filterForm.submit();
                }, 300);
            });
        });
    });
</script>
@endsection