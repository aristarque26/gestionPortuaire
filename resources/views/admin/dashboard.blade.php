@extends('layouts.admin')

@section('title', 'Tableau de bord')
@section('header', 'Tableau de bord')

{{-- Ajout de la police Google (Roboto) --}}
@push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
    </style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-6 max-w-7xl">

    {{-- En-tête avec bienvenue et taux de change --}}
    <div class="bg-gradient-to-r from-indigo-600 to-purple-700 rounded-2xl shadow-xl p-6 mb-8 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 opacity-10">
            <i class="fas fa-ship text-8xl"></i>
        </div>
        <div class="flex items-center justify-between flex-wrap relative z-10">
            <div>
                <h1 class="text-2xl font-bold flex items-center gap-2">
                    <i class="fas fa-user-shield mr-2"></i> Bonjour, {{ Auth::user()->prenom ?? Auth::user()->name }}
                </h1>
                <p class="text-indigo-100 mt-1 flex items-center gap-2">
                    <i class="fas fa-calendar-day"></i> {{ now()->format('l d F Y') }}
                </p>
                <p class="text-indigo-200 text-sm mt-1 flex items-center gap-2">
                    <i class="fas fa-envelope"></i> {{ Auth::user()->email }}
                    <span class="ml-3 px-2 py-0.5 bg-white/20 rounded-full text-xs">{{ Auth::user()->role }}</span>
                </p>
            </div>
            <div class="flex items-center space-x-3 mt-3 md:mt-0">
                <span class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-sm flex items-center gap-2">
                    <i class="fas fa-exchange-alt"></i> 1 USD = {{ number_format($tauxCDF ?? 2500, 0, ',', ' ') }} CDF
                </span>
                <a href="{{ route('admin.settings.index') }}" class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-sm hover:bg-white/30 transition flex items-center gap-2">
                    <i class="fas fa-cog"></i> Paramètres
                </a>
            </div>
        </div>
    </div>

    {{-- Filtres période --}}
    <div class="bg-white rounded-2xl shadow-md p-5 mb-8">
        <form method="GET" class="flex flex-wrap items-center gap-4">
            <div class="flex items-center gap-2">
                <i class="fas fa-calendar-alt text-gray-400"></i>
                <select name="mois" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-400 outline-none text-sm">
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ request('mois', date('m')) == $i ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                    @endfor
                </select>
            </div>
            <div class="flex items-center gap-2">
                <i class="fas fa-calendar-year text-gray-400"></i>
                <select name="annee" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-400 outline-none text-sm">
                    @for($i = date('Y') - 2; $i <= date('Y') + 1; $i++)
                        <option value="{{ $i }}" {{ request('annee', date('Y')) == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg transition flex items-center gap-2 text-sm">
                <i class="fas fa-filter"></i> Filtrer
            </button>
            <a href="{{ request()->url() }}" class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1">
                <i class="fas fa-undo-alt"></i> Réinitialiser
            </a>
        </form>
    </div>

    {{-- Statistiques générales --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        @php
            $stats = [
                ['label' => 'Bateaux', 'value' => $totalBateaux ?? 0, 'icon' => 'ship', 'color' => 'blue'],
                ['label' => 'Ports', 'value' => $totalPorts ?? 0, 'icon' => 'anchor', 'color' => 'green'],
                ['label' => 'Voyages', 'value' => $totalVoyages ?? 0, 'icon' => 'route', 'color' => 'yellow'],
                ['label' => 'Utilisateurs', 'value' => $totalUsers ?? 0, 'icon' => 'users', 'color' => 'purple'],
            ];
        @endphp
        @foreach($stats as $stat)
        <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition duration-300 p-5 border-l-4 border-{{ $stat['color'] }}-500 group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 flex items-center gap-1">
                        <i class="fas fa-{{ $stat['icon'] }} text-{{ $stat['color'] }}-500 mr-1"></i>
                        {{ $stat['label'] }}
                    </p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stat['value'] }}</p>
                </div>
                <div class="p-3 bg-{{ $stat['color'] }}-100 rounded-full group-hover:scale-110 transition">
                    <i class="fas fa-{{ $stat['icon'] }} text-{{ $stat['color'] }}-500 text-xl"></i>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Mini statistiques supplémentaires (quais, trajets, paiements, montant total) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        @php
            $extraStats = [
                ['label' => 'Quais', 'value' => $totalQuais ?? 0, 'icon' => 'map-pin', 'color' => 'indigo'],
                ['label' => 'Trajets', 'value' => $totalTrajets ?? 0, 'icon' => 'map-signs', 'color' => 'pink'],
                ['label' => 'Paiements', 'value' => $totalPaiements ?? 0, 'icon' => 'credit-card', 'color' => 'orange'],
                ['label' => 'Montant total', 'value' => number_format($montantTotalPaiements ?? 0, 0, ',', ' ').' CDF', 'icon' => 'money-bill-wave', 'color' => 'teal', 'sub' => '≈ '.number_format(($montantTotalPaiements ?? 0) / ($tauxCDF ?? 2500), 0, ',', ' ').' USD'],
            ];
        @endphp
        @foreach($extraStats as $stat)
        <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition duration-300 p-5 border-l-4 border-{{ $stat['color'] }}-500 group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 flex items-center gap-1">
                        <i class="fas fa-{{ $stat['icon'] }} text-{{ $stat['color'] }}-500 mr-1"></i>
                        {{ $stat['label'] }}
                    </p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stat['value'] }}</p>
                    @if(isset($stat['sub']))
                        <p class="text-xs text-gray-400 mt-1">{{ $stat['sub'] }}</p>
                    @endif
                </div>
                <div class="p-3 bg-{{ $stat['color'] }}-100 rounded-full group-hover:scale-110 transition">
                    <i class="fas fa-{{ $stat['icon'] }} text-{{ $stat['color'] }}-500 text-xl"></i>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Graphiques avec Google Charts --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        {{-- Graphique 1 : Évolution des réservations --}}
        <div class="bg-white rounded-2xl shadow-md p-5">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2 mb-3">
                <i class="fas fa-chart-line text-blue-500"></i> Évolution des réservations par mois
            </h3>
            <div id="reservationsChart" style="height: 200px;"></div>
        </div>

        {{-- Graphique 2 : Évolution des recettes mensuelles --}}
        <div class="bg-white rounded-2xl shadow-md p-5">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2 mb-3">
                <i class="fas fa-chart-line text-purple-500"></i> Évolution des recettes mensuelles (CDF)
            </h3>
            <div id="recettesChart" style="height: 200px;"></div>
        </div>

        {{-- Graphique 3 : Occupation des bateaux --}}
        <div class="bg-white rounded-2xl shadow-md p-5">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2 mb-3">
                <i class="fas fa-ship text-red-500"></i> Occupation des bateaux (%)
            </h3>
            <div id="occupationChart" style="height: 200px;"></div>
        </div>

        {{-- Graphique 4 : Occupation des quais --}}
        <div class="bg-white rounded-2xl shadow-md p-5">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2 mb-3">
                <i class="fas fa-map-pin text-teal-500"></i> Occupation des quais (%)
            </h3>
            <div id="occupationQuaisChart" style="height: 200px;"></div>
        </div>
    </div>

    {{-- Convertisseur de devises et actions rapides --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="md:col-span-1 bg-white rounded-2xl shadow-md p-5">
            <h3 class="text-md font-semibold text-gray-800 flex items-center gap-2 mb-4">
                <i class="fas fa-money-bill-wave text-green-500"></i> Convertisseur USD / CDF
            </h3>
            <div class="space-y-3">
                <div>
                    <label class="text-sm text-gray-500">Montant en USD</label>
                    <input type="number" id="usdInput" value="1" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-400 outline-none" oninput="convertCurrency()">
                </div>
                <div class="flex justify-center">
                    <i class="fas fa-arrow-down text-gray-400"></i>
                </div>
                <div>
                    <label class="text-sm text-gray-500">Montant en CDF</label>
                    <input type="text" id="cdfOutput" value="2 500" class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50" readonly>
                </div>
                <p class="text-xs text-gray-400 mt-2">
                    <i class="fas fa-info-circle"></i> Taux : 1 USD = {{ number_format($tauxCDF ?? 2500, 0, ',', ' ') }} CDF
                </p>
            </div>
        </div>

        {{-- Actions rapides administrateur --}}
        <div class="md:col-span-2 grid grid-cols-1 sm:grid-cols-3 gap-4">
            <a href="{{ route('admin.bateaux.index') }}" class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl shadow-sm hover:shadow-lg transition p-4 flex items-center space-x-3 group border border-blue-200">
                <div class="p-3 bg-blue-500 rounded-xl group-hover:bg-blue-600 transition text-white">
                    <i class="fas fa-ship text-xl"></i>
                </div>
                <div>
                    <p class="font-medium text-gray-800">Gérer les bateaux</p>
                    <p class="text-xs text-gray-500">Ajouter, modifier</p>
                </div>
            </a>
            <a href="{{ route('admin.ports.index') }}" class="bg-gradient-to-br from-green-50 to-green-100 rounded-2xl shadow-sm hover:shadow-lg transition p-4 flex items-center space-x-3 group border border-green-200">
                <div class="p-3 bg-green-500 rounded-xl group-hover:bg-green-600 transition text-white">
                    <i class="fas fa-anchor text-xl"></i>
                </div>
                <div>
                    <p class="font-medium text-gray-800">Gérer les ports</p>
                    <p class="text-xs text-gray-500">Quais, pavillons</p>
                </div>
            </a>
            <a href="{{ route('admin.admins.index') }}" class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-2xl shadow-sm hover:shadow-lg transition p-4 flex items-center space-x-3 group border border-purple-200">
                <div class="p-3 bg-purple-500 rounded-xl group-hover:bg-purple-600 transition text-white">
                    <i class="fas fa-users-cog text-xl"></i>
                </div>
                <div>
                    <p class="font-medium text-gray-800">Utilisateurs</p>
                    <p class="text-xs text-gray-500">Gestion des comptes</p>
                </div>
            </a>
        </div>
    </div>

    {{-- Top 5 clients, Alertes, Réservations du jour --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        {{-- Top clients --}}
        <div class="bg-white rounded-2xl shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center gap-2">
                <i class="fas fa-trophy text-yellow-500 text-lg"></i>
                <h3 class="text-lg font-semibold text-gray-800">Top 5 clients</h3>
            </div>
            <ul class="divide-y divide-gray-100">
                @forelse($topClients ?? [] as $client)
                    <li class="px-6 py-3 flex items-center justify-between hover:bg-gray-50 transition">
                        <span class="flex items-center gap-2">
                            <i class="fas fa-user-circle text-gray-400 text-xl"></i>
                            {{ $client->prenom }} {{ $client->nom }}
                        </span>
                        <span class="font-semibold text-indigo-600">
                            {{ number_format($client->total_depense, 0, ',', ' ') }} CDF
                            <span class="text-xs text-gray-400 ml-1">(≈ {{ number_format($client->total_depense / ($tauxCDF ?? 2500), 0, ',', ' ') }} USD)</span>
                        </span>
                    </li>
                @empty
                    <li class="px-6 py-8 text-center text-gray-500">
                        <i class="fas fa-user-slash text-3xl block mb-2 text-gray-300"></i>
                        Aucun client
                    </li>
                @endforelse
            </ul>
        </div>

        {{-- Alertes bateaux complets --}}
        <div class="bg-white rounded-2xl shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center gap-2">
                <i class="fas fa-exclamation-triangle text-red-500 text-lg"></i>
                <h3 class="text-lg font-semibold text-gray-800">Bateaux complets</h3>
            </div>
            <ul class="divide-y divide-gray-100">
                @forelse($bateauxComplets ?? [] as $bateau)
                    <li class="px-6 py-3 flex items-center gap-3 text-red-600">
                        <i class="fas fa-ship"></i>
                        {{ $bateau->nom }}
                        <span class="ml-auto text-xs bg-red-100 text-red-800 px-2 py-1 rounded-full">Complet</span>
                    </li>
                @empty
                    <li class="px-6 py-8 text-center text-gray-500">
                        <i class="fas fa-check-circle text-green-400 text-3xl block mb-2"></i>
                        Aucun bateau complet
                    </li>
                @endforelse
            </ul>
        </div>

        {{-- Réservations du jour --}}
        <div class="bg-white rounded-2xl shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center gap-2">
                <i class="fas fa-calendar-check text-blue-500 text-lg"></i>
                <h3 class="text-lg font-semibold text-gray-800">Réservations du jour</h3>
            </div>
            <ul class="divide-y divide-gray-100">
                @forelse($reservationsAujourdhui ?? [] as $r)
                    <li class="px-6 py-3 flex items-center gap-2 hover:bg-gray-50 transition">
                        <i class="fas fa-user-circle text-gray-400"></i>
                        <span>{{ $r->client->prenom }} {{ $r->client->nom }}</span>
                        <span class="ml-auto text-sm text-gray-500">Voyage {{ $r->voyage->code_voyage }}</span>
                    </li>
                @empty
                    <li class="px-6 py-8 text-center text-gray-500">
                        <i class="fas fa-calendar-day text-3xl block mb-2 text-gray-300"></i>
                        Aucune réservation aujourd'hui
                    </li>
                @endforelse
            </ul>
        </div>
    </div>

    {{-- Pied de page : informations complémentaires --}}
    <div class="bg-white rounded-2xl shadow-md p-6">
        <div class="flex flex-wrap items-center justify-between">
            <div>
                <h4 class="text-md font-semibold text-gray-700 flex items-center gap-2">
                    <i class="fas fa-info-circle text-indigo-500"></i> Informations système
                </h4>
                <p class="text-sm text-gray-500 mt-1">Connecté en tant qu'administrateur · Gestion portuaire v2.0</p>
            </div>
            <div class="text-sm text-gray-400">
                <i class="fas fa-clock"></i> Dernière mise à jour : {{ now()->format('H:i:s') }}
            </div>
        </div>
    </div>
</div>

{{-- Scripts Google Charts et convertisseur --}}
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
    // Chargement de Google Charts
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawCharts);

    function drawCharts() {
        // 1. Évolution des réservations (courbe)
        var reservationsData = google.visualization.arrayToDataTable([
            ['Mois', 'Réservations'],
            @foreach($moisLabels ?? [] as $index => $mois)
                ['{{ $mois }}', {{ $reservationsData[$index] ?? 0 }}],
            @endforeach
        ]);
        var reservationsOptions = {
            curveType: 'function',
            legend: { position: 'none' },
            colors: ['#1e88e5'],
            vAxis: { minValue: 0, gridlines: { color: '#e0e0e0' } },
            hAxis: { slantedText: true, textStyle: { fontSize: 10 } },
            chartArea: { width: '85%', height: '80%' },
            tooltip: { trigger: 'focus' }
        };
        var chart1 = new google.visualization.LineChart(document.getElementById('reservationsChart'));
        chart1.draw(reservationsData, reservationsOptions);

        // 2. Évolution des recettes mensuelles (courbe) – nécessite $recettesMois
        // Si la variable n'existe pas, on crée un tableau vide pour éviter une erreur
        var recettesMois = @json($recettesMois ?? []);
        var recettesData = new google.visualization.DataTable();
        recettesData.addColumn('string', 'Mois');
        recettesData.addColumn('number', 'Recettes (CDF)');
        if (Object.keys(recettesMois).length > 0) {
            for (var mois in recettesMois) {
                recettesData.addRow([mois, recettesMois[mois]]);
            }
        } else {
            // Données factices si aucune donnée n'est fournie (pour l'exemple)
            recettesData.addRow(['Jan', 0]);
            recettesData.addRow(['Fév', 0]);
        }
        var recettesOptions = {
            curveType: 'function',
            legend: { position: 'none' },
            colors: ['#8e24aa'],
            vAxis: { minValue: 0, format: 'short', gridlines: { color: '#e0e0e0' } },
            hAxis: { slantedText: true, textStyle: { fontSize: 10 } },
            chartArea: { width: '85%', height: '80%' },
            tooltip: { trigger: 'focus' }
        };
        var chart2 = new google.visualization.LineChart(document.getElementById('recettesChart'));
        chart2.draw(recettesData, recettesOptions);

        // 3. Occupation des bateaux (barres)
        var occupationData = google.visualization.arrayToDataTable([
            ['Bateau', 'Occupation (%)'],
            @foreach($occupationLabels ?? [] as $index => $label)
                ['{{ $label }}', {{ $occupationData[$index] ?? 0 }}],
            @endforeach
        ]);
        var occupationOptions = {
            legend: { position: 'none' },
            colors: ['#e53935'],
            vAxis: { minValue: 0, maxValue: 100, gridlines: { color: '#e0e0e0' } },
            hAxis: { slantedText: true, textStyle: { fontSize: 10 } },
            chartArea: { width: '85%', height: '80%' }
        };
        var chart3 = new google.visualization.ColumnChart(document.getElementById('occupationChart'));
        chart3.draw(occupationData, occupationOptions);

        // 4. Occupation des quais (barres)
        var occupationQuaisData = google.visualization.arrayToDataTable([
            ['Quai', 'Occupation (%)'],
            @foreach($occupationQuaisLabels ?? [] as $index => $label)
                ['{{ $label }}', {{ $occupationQuaisData[$index] ?? 0 }}],
            @endforeach
        ]);
        var occupationQuaisOptions = {
            legend: { position: 'none' },
            colors: ['#00897b'],
            vAxis: { minValue: 0, maxValue: 100, gridlines: { color: '#e0e0e0' } },
            hAxis: { slantedText: true, textStyle: { fontSize: 10 } },
            chartArea: { width: '85%', height: '80%' }
        };
        var chart4 = new google.visualization.ColumnChart(document.getElementById('occupationQuaisChart'));
        chart4.draw(occupationQuaisData, occupationQuaisOptions);
    }

    // Convertisseur de devises
    function convertCurrency() {
        let usd = document.getElementById('usdInput').value;
        let taux = {{ $tauxCDF ?? 200 }};
        if (!usd) usd = 0;
        let cdf = usd * taux;
        document.getElementById('cdfOutput').value = new Intl.NumberFormat('fr-FR').format(cdf) + ' CDF';
    }
    document.addEventListener('DOMContentLoaded', convertCurrency);
</script>
@endsection