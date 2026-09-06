<!-- resources/views/superviseur/statistiques/personnel.blade.php -->
@extends('layouts.app')

@section('content')
<div class="flex">
    @include('superviseur.layouts.sidebar')
    
    <div class="flex-1 p-6 bg-gray-100 min-h-screen">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Statistiques du personnel</h1>
            <a href="{{ route('superviseur.statistiques.export', ['type' => 'personnel']) }}" 
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition text-sm flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Exporter
            </a>
        </div>

        <!-- Cartes -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                <p class="text-sm text-gray-500">Total personnel</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
                <p class="text-sm text-gray-500">Actifs</p>
                <p class="text-2xl font-bold text-green-600">{{ $stats['actif'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500">
                <p class="text-sm text-gray-500">Inactifs</p>
                <p class="text-2xl font-bold text-red-600">{{ $stats['inactif'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
                <p class="text-sm text-gray-500">Ancienneté moyenne</p>
                <p class="text-2xl font-bold text-gray-800">{{ number_format($ancienneteMoyenne, 1) }} ans</p>
            </div>
        </div>

        <!-- Salaires -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <p class="text-sm text-gray-500">Masse salariale</p>
                <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['masse_salariale'], 0, ',', ' ') }} USD</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6">
                <p class="text-sm text-gray-500">Salaire moyen</p>
                <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['salaire_moyen'], 0, ',', ' ') }} USD</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6">
                <p class="text-sm text-gray-500">Écart salarial</p>
                <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['salaire_max'] - $stats['salaire_min'], 0, ',', ' ') }} USD</p>
            </div>
        </div>

        <!-- Graphiques compacts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Répartition par rôle -->
            <div class="bg-white rounded-lg shadow-md p-4">
                <h3 class="text-sm font-semibold text-gray-800 mb-3">Répartition par rôle</h3>
                <div class="space-y-2">
                    @foreach($stats['par_role'] as $role)
                    <div>
                        <div class="flex justify-between text-sm">
                            <span class="capitalize">{{ $role->personnel_role }}</span>
                            <span class="font-bold">{{ $role->total }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $stats['total'] > 0 ? ($role->total / $stats['total']) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Graphique des embauches - REDIMENSIONNÉ comme g1 -->
            <div class="bg-white rounded-lg shadow-md p-4">
                <h3 class="text-sm font-semibold text-gray-800 mb-3">Évolution des embauches</h3>
                @if(!empty($embauchesParMois['labels']) && count($embauchesParMois['labels']) > 0)
                    <div class="h-48">
                        <canvas id="chartEmbauches"></canvas>
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <p>Aucune donnée d'embauche</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Salaires par rôle -->
        <div class="bg-white rounded-lg shadow-md p-6 mt-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Salaires par rôle</h3>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Rôle</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Moyenne</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Min</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Max</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($salairesParRole as $sr)
                        <tr class="border-t">
                            <td class="px-4 py-2 text-sm capitalize">{{ $sr->personnel_role }}</td>
                            <td class="px-4 py-2 text-sm font-bold">{{ number_format($sr->moyenne, 0, ',', ' ') }} USD</td>
                            <td class="px-4 py-2 text-sm">{{ number_format($sr->min, 0, ',', ' ') }} USD</td>
                            <td class="px-4 py-2 text-sm">{{ number_format($sr->max, 0, ',', ' ') }} USD</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('chartEmbauches');
        if (ctx) {
            const labels = {!! json_encode($embauchesParMois['labels'] ?? []) !!};
            const data = {!! json_encode($embauchesParMois['data'] ?? []) !!};
            
            if (labels.length > 0) {
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Embauches',
                            data: data,
                            backgroundColor: 'rgba(59, 130, 246, 0.6)',
                            borderColor: 'rgb(59, 130, 246)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { 
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { 
                                    stepSize: 1,
                                    font: { size: 10 }
                                }
                            },
                            x: {
                                ticks: { 
                                    font: { size: 9 },
                                    maxRotation: 45,
                                    minRotation: 0
                                }
                            }
                        }
                    }
                });
            }
        }
    });
</script>
@endpush
@endsection