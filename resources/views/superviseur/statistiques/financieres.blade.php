<!-- resources/views/superviseur/statistiques/financieres.blade.php -->
@extends('layouts.app')

@section('content')
<div class="flex">
    @include('superviseur.layouts.sidebar')
    
    <div class="flex-1 p-6 bg-gray-100 min-h-screen">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Statistiques financières</h1>
            <div class="flex space-x-2">
                <form method="GET" action="{{ route('superviseur.statistiques.financieres') }}" class="flex items-center space-x-2">
                    <select name="periode" class="rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="aujourdhui" {{ request('periode') == 'aujourdhui' ? 'selected' : '' }}>Aujourd'hui</option>
                        <option value="semaine" {{ request('periode') == 'semaine' ? 'selected' : '' }}>Cette semaine</option>
                        <option value="mois" {{ request('periode') == 'mois' ? 'selected' : '' }}>Ce mois</option>
                        <option value="trimestre" {{ request('periode') == 'trimestre' ? 'selected' : '' }}>Ce trimestre</option>
                        <option value="annee" {{ request('periode') == 'annee' ? 'selected' : '' }}>Cette année</option>
                    </select>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">
                        Appliquer
                    </button>
                </form>
                <a href="{{ route('superviseur.statistiques.export', ['type' => 'financieres', 'periode' => request('periode', 'mois')]) }}" 
                   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition text-sm flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Exporter
                </a>
            </div>
        </div>

        <!-- Période -->
        <div class="bg-white rounded-lg shadow-md p-4 mb-6">
            <p class="text-sm text-gray-600">
                Période: <span class="font-semibold">{{ $dateDebut->format('d/m/Y') }}</span> 
                au <span class="font-semibold">{{ $dateFin->format('d/m/Y') }}</span>
            </p>
        </div>

        <!-- Cartes -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
                <p class="text-sm text-gray-500">Chiffre d'affaires</p>
                <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['ca_total'], 0, ',', ' ') }} FC</p>
                @if($evolution != 0)
                <p class="text-xs {{ $evolution > 0 ? 'text-green-600' : 'text-red-600' }}">
                    {{ $evolution > 0 ? '+' : '' }}{{ number_format($evolution, 1) }}% vs mois précédent
                </p>
                @endif
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                <p class="text-sm text-gray-500">Nombre de paiements</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['nb_paiements'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
                <p class="text-sm text-gray-500">Panier moyen</p>
                <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['ca_moyen'], 0, ',', ' ') }} FC</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
                <p class="text-sm text-gray-500">Paiements en attente</p>
                <p class="text-2xl font-bold text-yellow-600">{{ $paiementsAttente }}</p>
            </div>
        </div>

        <!-- Graphiques avec gestion des données vides -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Évolution du CA</h3>
                @if(!empty($chartCAJournalier['labels']) && count($chartCAJournalier['labels']) > 0 && $chartCAJournalier['labels'][0] != 'Aucune donnée' && array_sum($chartCAJournalier['data']) > 0)
                    <canvas id="chartCA" height="200"></canvas>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <p>Aucune donnée disponible pour cette période</p>
                        <p class="text-sm">Essayez de changer la période</p>
                    </div>
                @endif
            </div>
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">CA par bateau</h3>
                @if(!empty($chartCABateau['labels']) && count($chartCABateau['labels']) > 0)
                    <canvas id="chartCABateau" height="200"></canvas>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p>Aucune donnée de CA par bateau</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Paiements par mode -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Paiements par mode</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @forelse($paiementsParMode as $pm)
                <div class="text-center p-3 bg-gray-50 rounded-lg">
                    <p class="text-2xl font-bold text-gray-800">{{ $pm->total }}</p>
                    <p class="text-sm text-gray-500">{{ $pm->mode_paiement }}</p>
                    <p class="text-xs text-green-600">{{ number_format($pm->montant, 0, ',', ' ') }} FC</p>
                </div>
                @empty
                <div class="col-span-4 text-center py-4 text-gray-500">
                    Aucun paiement effectué
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Graphique CA Journalier
        const chartCAElement = document.getElementById('chartCA');
        if (chartCAElement) {
            const ctx1 = chartCAElement.getContext('2d');
            const labels1 = {!! json_encode($chartCAJournalier['labels'] ?? []) !!};
            const data1 = {!! json_encode($chartCAJournalier['data'] ?? []) !!};
            
            if (labels1.length > 0 && labels1[0] !== 'Aucune donnée' && data1.some(v => v > 0)) {
                new Chart(ctx1, {
                    type: 'bar',
                    data: {
                        labels: labels1,
                        datasets: [{
                            label: 'CA (FC)',
                            data: data1,
                            backgroundColor: 'rgba(59, 130, 246, 0.6)',
                            borderColor: 'rgb(59, 130, 246)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }
        }

        // Graphique CA par bateau
        const chartCABateauElement = document.getElementById('chartCABateau');
        if (chartCABateauElement) {
            const ctx2 = chartCABateauElement.getContext('2d');
            const labels2 = {!! json_encode($chartCABateau['labels'] ?? []) !!};
            const data2 = {!! json_encode($chartCABateau['data'] ?? []) !!};
            
            if (labels2.length > 0 && data2.some(v => v > 0)) {
                new Chart(ctx2, {
                    type: 'doughnut',
                    data: {
                        labels: labels2,
                        datasets: [{
                            data: data2,
                            backgroundColor: ['#3B82F6', '#10B981', '#F59E0B', '#8B5CF6', '#EF4444', '#EC4899', '#14B8A6']
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { position: 'bottom' }
                        }
                    }
                });
            }
        }
    });
</script>
@endpush
@endsection