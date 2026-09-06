<!-- resources/views/superviseur/dashboard/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="flex">
    @include('superviseur.layouts.sidebar')
    
    <div class="flex-1 p-6 bg-gray-100 min-h-screen">
        <!-- En-tête -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Tableau de bord - Superviseur</h1>
            <div class="text-sm text-gray-500">
                {{ now()->format('d/m/Y H:i') }}
            </div>
        </div>

        <!-- Alertes -->
        @if(count($alertes) > 0)
        <div class="mb-6 space-y-2">
            @foreach($alertes as $alerte)
            <div class="p-4 rounded-lg flex items-center justify-between 
                        {{ $alerte['type'] == 'danger' ? 'bg-red-100 text-red-800 border border-red-200' : 'bg-yellow-100 text-yellow-800 border border-yellow-200' }}">
                <div class="flex items-center">
                    @if($alerte['type'] == 'danger')
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    @else
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    @endif
                    <span>{{ $alerte['message'] }}</span>
                </div>
                <a href="{{ $alerte['lien'] }}" class="text-sm underline hover:no-underline">
                    Voir →
                </a>
            </div>
            @endforeach
        </div>
        @endif

        <!-- Statistiques - Première ligne -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Réservations aujourd'hui</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $stats['reservations_aujourdhui'] }}</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">En attente</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $stats['reservations_en_attente'] }}</p>
                    </div>
                    <div class="bg-yellow-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Voyages en cours</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $stats['voyages_en_cours'] }}</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">CA aujourd'hui</p>
                        <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['ca_aujourdhui'], 0, ',', ' ') }} FC</p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques - Deuxième ligne -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <p class="text-sm text-gray-500">Personnel actif</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['personnel_actif'] }} / {{ $stats['total_personnel'] }}</p>
                <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $stats['total_personnel'] > 0 ? ($stats['personnel_actif'] / $stats['total_personnel']) * 100 : 0 }}%"></div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <p class="text-sm text-gray-500">Quais libres</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['quais_libres'] }} / {{ $stats['total_quais'] }}</p>
                <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-green-600 h-2 rounded-full" style="width: {{ $stats['total_quais'] > 0 ? ($stats['quais_libres'] / $stats['total_quais']) * 100 : 0 }}%"></div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <p class="text-sm text-gray-500">Bateaux en service</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['bateaux_service'] }} / {{ $stats['total_bateaux'] }}</p>
                <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-green-600 h-2 rounded-full" style="width: {{ $stats['total_bateaux'] > 0 ? ($stats['bateaux_service'] / $stats['total_bateaux']) * 100 : 0 }}%"></div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <p class="text-sm text-gray-500">Paiements en attente</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['paiements_attente'] }}</p>
                <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-yellow-600 h-2 rounded-full" style="width: {{ $stats['paiements_aujourdhui'] > 0 ? min(100, ($stats['paiements_attente'] / ($stats['paiements_attente'] + $stats['paiements_aujourdhui'])) * 100) : 0 }}%"></div>
                </div>
            </div>
        </div>

        <!-- Graphiques -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Évolution des réservations -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Évolution des réservations</h3>
                <canvas id="chartReservations" height="200"></canvas>
            </div>

            <!-- Répartition par statut -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Répartition par statut</h3>
                <canvas id="chartStatuts" height="200"></canvas>
            </div>
        </div>

        <!-- Dernières réservations -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Dernières réservations</h3>
                <a href="{{ route('superviseur.reservations.index') }}" class="text-blue-600 hover:underline text-sm">
                    Voir toutes →
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Client</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Bateau</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Date</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Montant</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Statut</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dernieresReservations as $res)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm">{{ $res->client->nom }} {{ $res->client->prenom }}</td>
                            <td class="px-4 py-3 text-sm">{{ $res->voyage->bateau->nom ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-sm">{{ Carbon\Carbon::parse($res->date_reservation)->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3 text-sm">{{ number_format($res->prix_total, 0, ',', ' ') }} FC</td>
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
                                <a href="{{ route('superviseur.reservations.show', $res->id) }}" class="text-blue-600 hover:underline">
                                    Voir
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-gray-500">Aucune réservation récente</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Derniers paiements et personnel -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Derniers paiements -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Derniers paiements</h3>
                <div class="space-y-3">
                    @forelse($derniersPaiements as $paiement)
                    <div class="flex justify-between items-center border-b pb-2">
                        <div>
                            <p class="text-sm font-medium">{{ $paiement->reservation->client->nom ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500">{{ Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-green-600">{{ number_format($paiement->montant, 0, ',', ' ') }} {{ $paiement->devise }}</p>
                            <span class="text-xs px-2 py-1 rounded-full 
                                @if($paiement->statut == 'paye') bg-green-100 text-green-800
                                @elseif($paiement->statut == 'en_attente') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ $paiement->statut }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-gray-500">Aucun paiement récent</p>
                    @endforelse
                </div>
            </div>

            <!-- Derniers personnels -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Derniers personnels</h3>
                <div class="space-y-3">
                    @forelse($derniersPersonnel as $p)
                    <div class="flex justify-between items-center border-b pb-2">
                        <div>
                            <p class="text-sm font-medium">{{ $p->user->name }} {{ $p->user->prenom }}</p>
                            <p class="text-xs text-gray-500">{{ $p->poste }} - {{ $p->service }}</p>
                        </div>
                        <div class="text-right">
                            <span class="text-xs px-2 py-1 rounded-full 
                                @if($p->statut == 'actif') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ $p->statut }}
                            </span>
                            <span class="text-xs px-2 py-1 rounded-full bg-blue-100 text-blue-800 ml-1">
                                {{ $p->personnel_role }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-gray-500">Aucun personnel récent</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Graphique des réservations
    const ctx1 = document.getElementById('chartReservations').getContext('2d');
    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartReservations['labels']) !!},
            datasets: [{
                label: 'Réservations',
                data: {!! json_encode($chartReservations['data']) !!},
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

    // Graphique des statuts
    const ctx2 = document.getElementById('chartStatuts').getContext('2d');
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($chartStatuts['labels']) !!},
            datasets: [{
                data: {!! json_encode($chartStatuts['data']) !!},
                backgroundColor: [
                    '#F59E0B', // Jaune
                    '#3B82F6', // Bleu
                    '#10B981', // Vert
                    '#8B5CF6', // Violet
                    '#EF4444'  // Rouge
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
@endpush