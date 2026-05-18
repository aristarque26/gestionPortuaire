@extends('layouts.admin')

@section('title', 'Tableau de bord')
@section('header', 'Tableau de bord')

@section('content')
{{-- Filtres par période --}}
<form method="GET" class="mb-6 bg-white p-4 rounded-lg shadow flex gap-4">
    <select name="mois" class="border rounded px-3 py-2">
        @for($i = 1; $i <= 12; $i++)
            <option value="{{ $i }}" {{ request('mois', date('m')) == $i ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
        @endfor
    </select>
    <select name="annee" class="border rounded px-3 py-2">
        @for($i = date('Y') - 2; $i <= date('Y') + 1; $i++)
            <option value="{{ $i }}" {{ request('annee', date('Y')) == $i ? 'selected' : '' }}>{{ $i }}</option>
        @endfor
    </select>
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Filtrer</button>
</form>

{{-- Statistiques générales --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 bg-blue-100 rounded-full">
                <span class="text-2xl">🚢</span>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500">Bateaux</p>
                <p class="text-2xl font-bold">{{ $totalBateaux ?? 0 }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 bg-green-100 rounded-full">
                <span class="text-2xl">⚓</span>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500">Ports</p>
                <p class="text-2xl font-bold">{{ $totalPorts ?? 0 }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 bg-yellow-100 rounded-full">
                <span class="text-2xl">✈️</span>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500">Voyages</p>
                <p class="text-2xl font-bold">{{ $totalVoyages ?? 0 }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 bg-purple-100 rounded-full">
                <span class="text-2xl">👥</span>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500">Utilisateurs</p>
                <p class="text-2xl font-bold">{{ $totalUsers ?? 0 }}</p>
            </div>
        </div>
    </div>
</div>

{{-- Mini statistiques supplémentaires --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 bg-indigo-100 rounded-full">
                <span class="text-2xl">📌</span>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500">Quais</p>
                <p class="text-2xl font-bold">{{ $totalQuais ?? 0 }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 bg-pink-100 rounded-full">
                <span class="text-2xl">🗺️</span>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500">Trajets</p>
                <p class="text-2xl font-bold">{{ $totalTrajets ?? 0 }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 bg-orange-100 rounded-full">
                <span class="text-2xl">💳</span>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500">Paiements</p>
                <p class="text-2xl font-bold">{{ $totalPaiements ?? 0 }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 bg-teal-100 rounded-full">
                <span class="text-2xl">💰</span>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500">Montant total</p>
                <p class="text-2xl font-bold">{{ number_format($montantTotalPaiements ?? 0, 0, ',', ' ') }} FCFA</p>
            </div>
        </div>
    </div>
</div>

{{-- Graphiques --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow p-3">
        <h3 class="text-md font-semibold text-gray-800 mb-2">📊 Réservations par mois</h3>
        <canvas id="reservationsChart" height="140"></canvas>
    </div>
    <div class="bg-white rounded-lg shadow p-3">
        <h3 class="text-md font-semibold text-gray-800 mb-2">💰 Recettes par pavillon</h3>
        <canvas id="recettesChart" height="140"></canvas>
    </div>
    <div class="bg-white rounded-lg shadow p-3">
        <h3 class="text-md font-semibold text-gray-800 mb-2">🚢 Occupation des bateaux (%)</h3>
        <canvas id="occupationChart" height="140"></canvas>
    </div>
    <div class="bg-white rounded-lg shadow p-3">
        <h3 class="text-md font-semibold text-gray-800 mb-2">📌 Occupation des quais (%)</h3>
        <canvas id="occupationQuaisChart" height="140"></canvas>
    </div>
</div>

{{-- Top 5 clients --}}
<div class="bg-white rounded-lg shadow p-4 mb-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">🏆 Top 5 clients</h3>
    <ul>
        @foreach($topClients ?? [] as $client)
            <li class="border-b py-2">
                {{ $client->prenom }} {{ $client->nom }} - <strong>{{ number_format($client->total_depense, 0, ',', ' ') }} FCFA</strong>
            </li>
        @endforeach
    </ul>
</div>

{{-- Alertes bateaux complets --}}
<div class="bg-white rounded-lg shadow p-4 mb-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">⚠️ Bateaux complets</h3>
    <ul>
        @forelse($bateauxComplets ?? [] as $bateau)
            <li class="text-red-600">🚢 {{ $bateau->nom }} (complet)</li>
        @empty
            <li>Aucun bateau complet</li>
        @endforelse
    </ul>
</div>

{{-- Réservations du jour --}}
<div class="bg-white rounded-lg shadow p-4 mb-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">📅 Réservations du jour (départs)</h3>
    <ul>
        @forelse($reservationsAujourdhui ?? [] as $r)
            <li>{{ $r->client->prenom }} {{ $r->client->nom }} - Voyage {{ $r->voyage->code_voyage }}</li>
        @empty
            <li>Aucune réservation aujourd'hui</li>
        @endforelse
    </ul>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-bold text-gray-800 mb-4">Bienvenue, {{ Auth::user()->prenom }} !</h2>
    <p class="text-gray-600">Vous êtes connecté en tant qu'administrateur du système de gestion portuaire.</p>
    <p class="text-gray-600 mt-2">Email : {{ Auth::user()->email }}</p>
    <p class="text-gray-600">Rôle : {{ Auth::user()->role }}</p>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Graphique 1 : Réservations par mois
        new Chart(document.getElementById('reservationsChart'), {
            type: 'bar',
            data: {
                labels: @json($moisLabels ?? []),
                datasets: [{
                    label: 'Nombre de réservations',
                    data: @json($reservationsData ?? []),
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { position: 'top', labels: { font: { size: 10 } } }
                },
                scales: { y: { beginAtZero: true } }
            }
        });

        // Graphique 2 : Recettes par pavillon
        new Chart(document.getElementById('recettesChart'), {
            type: 'pie',
            data: {
                labels: @json($pavillonLabels ?? []),
                datasets: [{
                    data: @json($recettesData ?? []),
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { position: 'right', labels: { font: { size: 10 } } }
                }
            }
        });

        // Graphique 3 : Occupation des bateaux
        new Chart(document.getElementById('occupationChart'), {
            type: 'bar',
            data: {
                labels: @json($occupationLabels ?? []),
                datasets: [{
                    label: "Taux d'occupation (%)",
                    data: @json($occupationData ?? []),
                    backgroundColor: 'rgba(255, 99, 132, 0.5)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                scales: { y: { beginAtZero: true, max: 100 } }
            }
        });

        // Graphique 4 : Occupation des quais
        new Chart(document.getElementById('occupationQuaisChart'), {
            type: 'bar',
            data: {
                labels: @json($occupationQuaisLabels ?? []),
                datasets: [{
                    label: "Taux d'occupation (%)",
                    data: @json($occupationQuaisData ?? []),
                    backgroundColor: 'rgba(75, 192, 192, 0.5)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                scales: { y: { beginAtZero: true, max: 100 } }
            }
        });
    });
</script>
@endsection