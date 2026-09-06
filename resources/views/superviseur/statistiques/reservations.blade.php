<!-- resources/views/superviseur/statistiques/reservations.blade.php -->
@extends('layouts.app')

@section('content')
<div class="flex">
    @include('superviseur.layouts.sidebar')
    
    <div class="flex-1 p-6 bg-gray-100 min-h-screen">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Statistiques des réservations</h1>
            <div class="flex space-x-2">
                <form method="GET" action="{{ route('superviseur.statistiques.reservations') }}" class="flex items-center space-x-2">
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
                <a href="{{ route('superviseur.statistiques.export', ['type' => 'reservations', 'periode' => request('periode', 'mois')]) }}" 
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
                @if($evolution != 0)
                <span class="ml-4 {{ $evolution > 0 ? 'text-green-600' : 'text-red-600' }}">
                    {{ $evolution > 0 ? '+' : '' }}{{ number_format($evolution, 1) }}% vs période précédente
                </span>
                @endif
            </p>
        </div>

        <!-- Cartes -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 mb-6">
            <div class="bg-white p-3 rounded-lg shadow text-center">
                <p class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</p>
                <p class="text-xs text-gray-500">Total</p>
            </div>
            <div class="bg-white p-3 rounded-lg shadow text-center border-l-4 border-yellow-500">
                <p class="text-2xl font-bold text-yellow-600">{{ $stats['en_attente'] }}</p>
                <p class="text-xs text-gray-500">En attente</p>
            </div>
            <div class="bg-white p-3 rounded-lg shadow text-center border-l-4 border-blue-500">
                <p class="text-2xl font-bold text-blue-600">{{ $stats['confirme'] }}</p>
                <p class="text-xs text-gray-500">Confirmées</p>
            </div>
            <div class="bg-white p-3 rounded-lg shadow text-center border-l-4 border-green-500">
                <p class="text-2xl font-bold text-green-600">{{ $stats['paye'] }}</p>
                <p class="text-xs text-gray-500">Payées</p>
            </div>
            <div class="bg-white p-3 rounded-lg shadow text-center border-l-4 border-purple-500">
                <p class="text-2xl font-bold text-purple-600">{{ $stats['arrive'] }}</p>
                <p class="text-xs text-gray-500">Arrivées</p>
            </div>
            <div class="bg-white p-3 rounded-lg shadow text-center border-l-4 border-red-500">
                <p class="text-2xl font-bold text-red-600">{{ $stats['annule'] }}</p>
                <p class="text-xs text-gray-500">Annulées</p>
            </div>
        </div>

        <!-- Graphiques -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Évolution des réservations</h3>
                <canvas id="chartReservations" height="200"></canvas>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Répartition par statut</h3>
                <canvas id="chartStatuts" height="200"></canvas>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Par type</h3>
                <div class="space-y-3">
                    @foreach($reservationsParType as $rt)
                    <div class="flex justify-between items-center">
                        <span class="text-sm capitalize">{{ $rt->type_reservation }}</span>
                        <span class="font-bold">{{ $rt->total }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $stats['total'] > 0 ? ($rt->total / $stats['total']) * 100 : 0 }}%"></div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Par bateau</h3>
                <div class="space-y-3">
                    @foreach($chartParBateau['labels'] ?? [] as $index => $label)
                    <div class="flex justify-between items-center">
                        <span class="text-sm">{{ $label }}</span>
                        <span class="font-bold">{{ $chartParBateau['data'][$index] ?? 0 }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx1 = document.getElementById('chartReservations').getContext('2d');
    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartJournalier['labels']) !!},
            datasets: [{
                label: 'Réservations',
                data: {!! json_encode($chartJournalier['data']) !!},
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            }
        }
    });

    const ctx2 = document.getElementById('chartStatuts').getContext('2d');
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($chartStatuts['labels']) !!},
            datasets: [{
                data: {!! json_encode($chartStatuts['data']) !!},
                backgroundColor: ['#F59E0B', '#3B82F6', '#10B981', '#8B5CF6', '#EF4444']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
</script>
@endpush
@endsection