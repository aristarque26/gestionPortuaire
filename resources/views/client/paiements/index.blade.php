@extends('layouts.client')

@section('title', 'Mes paiements')
@section('header', 'Mes paiements')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
    <div class="container mx-auto px-4 py-8 max-w-7xl">

        {{-- Statistiques premium --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            @php
                $statsCards = [
                    ['label' => 'Total', 'value' => $paiements->count(), 'icon' => 'list', 'gradient' => 'from-blue-500 to-indigo-600', 'bg' => 'bg-blue-50', 'text' => 'text-blue-600'],
                    ['label' => 'Payé', 'value' => $paiements->where('statut', 'paye')->count(), 'icon' => 'check-circle', 'gradient' => 'from-emerald-500 to-green-600', 'bg' => 'bg-emerald-50', 'text' => 'text-emerald-600'],
                    ['label' => 'En attente', 'value' => $paiements->where('statut', 'en_attente')->count(), 'icon' => 'hourglass-half', 'gradient' => 'from-amber-500 to-orange-600', 'bg' => 'bg-amber-50', 'text' => 'text-amber-600'],
                    ['label' => 'Échoué', 'value' => $paiements->where('statut', 'echoue')->count(), 'icon' => 'times-circle', 'gradient' => 'from-red-500 to-pink-600', 'bg' => 'bg-red-50', 'text' => 'text-red-600'],
                ];
            @endphp

            @foreach($statsCards as $stat)
            <div class="relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 p-6 group overflow-hidden border border-gray-100 hover:-translate-y-1">
                <div class="absolute inset-0 bg-gradient-to-br {{ $stat['gradient'] }} opacity-0 group-hover:opacity-5 transition-opacity duration-500"></div>
                
                <div class="relative z-10">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-12 h-12 {{ $stat['bg'] }} rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-{{ $stat['icon'] }} {{ $stat['text'] }} text-xl"></i>
                        </div>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-500 font-medium mb-1">{{ $stat['label'] }}</p>
                        <p class="text-3xl font-bold text-gray-800 tracking-tight">{{ $stat['value'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Filtres premium --}}
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-8 border border-gray-100">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-filter text-white"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Filtres</h3>
                    <p class="text-xs text-gray-500">Affinez votre recherche</p>
                </div>
            </div>

            <form method="GET" action="{{ route('client.paiements.index') }}" id="filterForm" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                <div>
                    <label for="statut" class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-circle text-blue-500 text-xs"></i> Statut
                    </label>
                    <select name="statut" id="statut" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-400 focus:border-transparent outline-none transition-all bg-gray-50 hover:bg-white">
                        <option value="">Tous les statuts</option>
                        <option value="paye" {{ request('statut') == 'paye' ? 'selected' : '' }}>Payé</option>
                        <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                        <option value="echoue" {{ request('statut') == 'echoue' ? 'selected' : '' }}>Échoué</option>
                    </select>
                </div>

                <div>
                    <label for="mode" class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-credit-card text-purple-500 text-xs"></i> Mode
                    </label>
                    <select name="mode" id="mode" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-purple-400 focus:border-transparent outline-none transition-all bg-gray-50 hover:bg-white">
                        <option value="">Tous les modes</option>
                        <option value="carte" {{ request('mode') == 'carte' ? 'selected' : '' }}>Carte bancaire</option>
                        <option value="mobile_money" {{ request('mode') == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                        <option value="virement" {{ request('mode') == 'virement' ? 'selected' : '' }}>Virement</option>
                        <option value="especes" {{ request('mode') == 'especes' ? 'selected' : '' }}>Espèces</option>
                    </select>
                </div>

                <div>
                    <label for="date_debut" class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-calendar-alt text-emerald-500 text-xs"></i> Date début
                    </label>
                    <input type="date" name="date_debut" id="date_debut" value="{{ request('date_debut') }}" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-emerald-400 focus:border-transparent outline-none transition-all bg-gray-50 hover:bg-white">
                </div>

                <div>
                    <label for="date_fin" class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-calendar-check text-amber-500 text-xs"></i> Date fin
                    </label>
                    <input type="date" name="date_fin" id="date_fin" value="{{ request('date_fin') }}" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-amber-400 focus:border-transparent outline-none transition-all bg-gray-50 hover:bg-white">
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white px-4 py-2.5 rounded-xl transition-all duration-300 shadow-md hover:shadow-lg flex items-center justify-center gap-2 font-semibold text-sm">
                        <i class="fas fa-filter"></i> Filtrer
                    </button>
                    <a href="{{ route('client.paiements.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2.5 rounded-xl transition-all duration-300 flex items-center justify-center gap-2 font-semibold text-sm" title="Réinitialiser">
                        <i class="fas fa-undo"></i>
                    </a>
                </div>
            </form>
        </div>

        {{-- Liste des paiements --}}
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
            {{-- Header avec compteur --}}
            @if($paiements->count() > 0)
            <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-list text-white text-sm"></i>
                    </div>
                    <p class="text-sm font-semibold text-gray-700">
                        <span class="text-blue-600 font-bold">{{ $paiements->count() }}</span> paiement(s) trouvé(s)
                    </p>
                </div>
            </div>
            @endif

            {{-- Vue desktop --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider flex items-center gap-2">
                                <i class="fas fa-hashtag"></i> ID
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider flex items-center gap-2">
                                <i class="fas fa-ticket-alt"></i> Réservation
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider flex items-center gap-2">
                                <i class="fas fa-money-bill-wave"></i> Montant
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider flex items-center gap-2">
                                <i class="fas fa-coins"></i> Devise
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider flex items-center gap-2">
                                <i class="fas fa-credit-card"></i> Mode
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider flex items-center gap-2">
                                <i class="fas fa-calendar"></i> Date
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider flex items-center gap-2">
                                <i class="fas fa-circle"></i> Statut
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider flex items-center gap-2">
                                <i class="fas fa-cog"></i> Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($paiements as $paiement)
                        <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-300 group">
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-gray-800">#{{ $paiement->id }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('client.reservations.show', $paiement->idreservation) }}" 
                                   class="text-sm font-semibold text-blue-600 hover:text-blue-800 hover:underline transition-colors">
                                    #{{ $paiement->idreservation }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-gray-800">{{ number_format($paiement->montant, 0, ',', ' ') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-gradient-to-r from-purple-50 to-indigo-50 text-purple-700 text-xs font-bold border border-purple-200">
                                    {{ strtoupper($paiement->devise) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $modes = [
                                        'carte' => ['label' => 'Carte bancaire', 'icon' => 'credit-card', 'color' => 'blue'],
                                        'mobile_money' => ['label' => 'Mobile Money', 'icon' => 'mobile-alt', 'color' => 'green'],
                                        'virement' => ['label' => 'Virement', 'icon' => 'university', 'color' => 'purple'],
                                        'especes' => ['label' => 'Espèces', 'icon' => 'money-bill', 'color' => 'amber']
                                    ];
                                    $mode = $modes[$paiement->mode_paiement] ?? ['label' => $paiement->mode_paiement, 'icon' => 'question', 'color' => 'gray'];
                                @endphp
                                <span class="inline-flex items-center gap-1.5 text-sm text-gray-700">
                                    <i class="fas fa-{{ $mode['icon'] }} text-{{ $mode['color'] }}-500"></i>
                                    {{ $mode['label'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-600 flex items-center gap-1.5">
                                    <i class="far fa-clock text-gray-400"></i>
                                    {{ $paiement->date_paiement->format('d/m/Y H:i') }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold rounded-full
                                    {{ $paiement->statut == 'paye' ? 'bg-gradient-to-r from-green-50 to-emerald-50 text-green-700 border border-green-200' :
                                       ($paiement->statut == 'en_attente' ? 'bg-gradient-to-r from-amber-50 to-yellow-50 text-amber-700 border border-amber-200' :
                                       'bg-gradient-to-r from-red-50 to-pink-50 text-red-700 border border-red-200') }}">
                                    <i class="fas fa-{{ $paiement->statut == 'paye' ? 'check-circle' : ($paiement->statut == 'en_attente' ? 'hourglass-half' : 'times-circle') }}"></i>
                                    {{ ucfirst(str_replace('_', ' ', $paiement->statut)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('client.paiements.show', $paiement->id) }}" 
                                       class="w-9 h-9 bg-blue-50 hover:bg-blue-100 rounded-lg flex items-center justify-center text-blue-600 hover:text-blue-800 transition-all group-hover:scale-110" 
                                       title="Voir le détail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($paiement->statut == 'paye' && isset($paiement->recu))
                                    <a href="{{ route('client.paiements.recu', $paiement->id) }}" 
                                       class="w-9 h-9 bg-emerald-50 hover:bg-emerald-100 rounded-lg flex items-center justify-center text-emerald-600 hover:text-emerald-800 transition-all group-hover:scale-110" 
                                       title="Télécharger le reçu">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-16 text-center">
                                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-inbox text-4xl text-gray-300"></i>
                                </div>
                                <p class="text-gray-500 font-medium mb-4">Aucun paiement trouvé pour ces critères</p>
                                <a href="{{ route('client.paiements.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl font-semibold text-sm hover:shadow-lg transition-all">
                                    <i class="fas fa-undo"></i> Réinitialiser les filtres
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Vue mobile --}}
            <div class="md:hidden divide-y divide-gray-100">
                @forelse($paiements as $paiement)
                <div class="p-5 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-300">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <p class="font-bold text-gray-800 text-lg">Paiement #{{ $paiement->id }}</p>
                            <p class="text-sm text-gray-500 mt-1 flex items-center gap-1">
                                <i class="fas fa-ticket-alt text-blue-500"></i>
                                Réservation 
                                <a href="{{ route('client.reservations.show', $paiement->idreservation) }}" class="text-blue-600 hover:underline font-semibold">
                                    #{{ $paiement->idreservation }}
                                </a>
                            </p>
                        </div>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold rounded-full
                            {{ $paiement->statut == 'paye' ? 'bg-gradient-to-r from-green-50 to-emerald-50 text-green-700 border border-green-200' :
                               ($paiement->statut == 'en_attente' ? 'bg-gradient-to-r from-amber-50 to-yellow-50 text-amber-700 border border-amber-200' :
                               'bg-gradient-to-r from-red-50 to-pink-50 text-red-700 border border-red-200') }}">
                            <i class="fas fa-{{ $paiement->statut == 'paye' ? 'check-circle' : ($paiement->statut == 'en_attente' ? 'hourglass-half' : 'times-circle') }}"></i>
                            {{ ucfirst(str_replace('_', ' ', $paiement->statut)) }}
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <div class="bg-gray-50 rounded-xl p-3">
                            <p class="text-xs text-gray-500 mb-1 flex items-center gap-1">
                                <i class="fas fa-money-bill-wave text-emerald-500"></i> Montant
                            </p>
                            <p class="font-bold text-gray-800">{{ number_format($paiement->montant, 0, ',', ' ') }} <span class="text-xs text-purple-600">{{ strtoupper($paiement->devise) }}</span></p>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-3">
                            <p class="text-xs text-gray-500 mb-1 flex items-center gap-1">
                                <i class="fas fa-credit-card text-blue-500"></i> Mode
                            </p>
                            <p class="font-bold text-gray-800 text-sm">{{ $modes[$paiement->mode_paiement] ?? $paiement->mode_paiement }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                        <p class="text-xs text-gray-500 flex items-center gap-1">
                            <i class="far fa-clock"></i>
                            {{ $paiement->date_paiement->format('d/m/Y H:i') }}
                        </p>
                        <div class="flex gap-2">
                            <a href="{{ route('client.paiements.show', $paiement->id) }}" 
                               class="px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg text-sm font-semibold flex items-center gap-1 transition-all">
                                <i class="fas fa-eye"></i> Voir
                            </a>
                            @if($paiement->statut == 'paye' && isset($paiement->recu))
                            <a href="{{ route('client.paiements.recu', $paiement->id) }}" 
                               class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-600 rounded-lg text-sm font-semibold flex items-center gap-1 transition-all">
                                <i class="fas fa-download"></i> Reçu
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-12 text-center">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-inbox text-4xl text-gray-300"></i>
                    </div>
                    <p class="text-gray-500 font-medium mb-4">Aucun paiement trouvé</p>
                    <a href="{{ route('client.paiements.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl font-semibold text-sm hover:shadow-lg transition-all">
                        <i class="fas fa-undo"></i> Réinitialiser les filtres
                    </a>
                </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if($paiements->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-gray-600">
                        Affichage de <span class="font-semibold">{{ $paiements->firstItem() }}</span> à <span class="font-semibold">{{ $paiements->lastItem() }}</span> sur <span class="font-semibold">{{ $paiements->total() }}</span> résultats
                    </p>
                </div>
                <div class="mt-3">
                    {{ $paiements->appends(request()->query())->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Script pour auto-soumettre le formulaire --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterForm = document.getElementById('filterForm');
        const selects = filterForm.querySelectorAll('select');
        selects.forEach(select => {
            select.addEventListener('change', function() {
                filterForm.submit();
            });
        });
        
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
@endpush

@endsection