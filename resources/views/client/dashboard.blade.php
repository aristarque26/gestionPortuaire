@extends('layouts.client')

@section('title', 'Tableau de bord')
@section('header', 'Mon tableau de bord')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
    <div class="container mx-auto px-4 py-8 max-w-7xl">

        {{-- Bannière de bienvenue premium --}}
        <div class="relative bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-700 rounded-3xl shadow-2xl p-8 mb-10 text-white overflow-hidden group">
            <!-- Effets décoratifs animés -->
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute -top-24 -right-24 w-96 h-96 bg-white/10 rounded-full blur-3xl animate-pulse"></div>
                <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-blue-400/20 rounded-full blur-3xl animate-pulse delay-700"></div>
                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-indigo-500/10 rounded-full blur-3xl"></div>
            </div>
            
            <!-- Motif décoratif -->
            <div class="absolute top-0 right-0 opacity-5 transform rotate-12">
                <i class="fas fa-anchor text-[200px]"></i>
            </div>

            <div class="relative z-10 flex items-center justify-between flex-wrap gap-6">
                <div class="flex-1 min-w-[280px]">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-14 h-14 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center border border-white/30 shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-hand-wave text-2xl animate-wave"></i>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold tracking-tight">
                                Bonjour, {{ $user->prenom ?? $user->name }} 👋
                            </h1>
                            <p class="text-blue-100 mt-1 flex items-center gap-2 text-sm">
                                <i class="fas fa-calendar-day"></i>
                                <span>{{ ucfirst(now()->format('l d F Y')) }}</span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 flex-wrap">
                    <div class="px-5 py-3 bg-white/15 backdrop-blur-md rounded-2xl border border-white/20 shadow-lg hover:bg-white/20 transition-all duration-300 flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-exchange-alt text-lg"></i>
                        </div>
                        <div>
                            <p class="text-xs text-blue-100">Taux du jour</p>
                            <p class="font-bold text-sm">1 USD = {{ number_format($tauxCDF ?? 2500, 0, ',', ' ') }} CDF</p>
                        </div>
                    </div>
                    
                    <a href="{{ route('client.settings.profile') }}" class="px-5 py-3 bg-white/15 backdrop-blur-md rounded-2xl border border-white/20 shadow-lg hover:bg-white/25 hover:scale-105 transition-all duration-300 flex items-center gap-3 group/btn">
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center group-hover/btn:rotate-12 transition-transform">
                            <i class="fas fa-user-cog text-lg"></i>
                        </div>
                        <span class="font-medium text-sm">Mon Profil</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Statistiques premium avec effets hover --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            @php
                $stats = [
                    ['label' => 'Réservations', 'value' => $totalReservations ?? 0, 'icon' => 'ticket-alt', 'gradient' => 'from-blue-500 to-blue-600', 'bg' => 'bg-blue-50', 'text' => 'text-blue-600', 'evolution' => $evolutionReservations ?? null],
                    ['label' => 'Voyages à venir', 'value' => $voyagesAVenir ?? 0, 'icon' => 'ship', 'gradient' => 'from-emerald-500 to-green-600', 'bg' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'sub' => 'Prochain : '.($prochainDepart ?? 'Aucun')],
                    ['label' => 'Paiements (total)', 'value' => number_format($montantTotalPaiements ?? 0, 0, ',', ' '), 'icon' => 'credit-card', 'gradient' => 'from-purple-500 to-indigo-600', 'bg' => 'bg-purple-50', 'text' => 'text-purple-600', 'sub' => 'CDF'],
                    ['label' => 'En attente', 'value' => $enAttente ?? 0, 'icon' => 'clock', 'gradient' => 'from-amber-500 to-orange-600', 'bg' => 'bg-amber-50', 'text' => 'text-amber-600', 'sub' => 'à confirmer'],
                ];
            @endphp

            @foreach($stats as $stat)
            <div class="relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 p-6 group overflow-hidden border border-gray-100 hover:-translate-y-1">
                <!-- Gradient background au hover -->
                <div class="absolute inset-0 bg-gradient-to-br {{ $stat['gradient'] }} opacity-0 group-hover:opacity-5 transition-opacity duration-500"></div>
                
                <div class="relative z-10">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-12 h-12 {{ $stat['bg'] }} rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-{{ $stat['icon'] }} {{ $stat['text'] }} text-xl"></i>
                        </div>
                        <div class="flex items-center gap-1">
                            @if(isset($stat['evolution']))
                                <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-1 rounded-full flex items-center gap-1">
                                    <i class="fas fa-arrow-up text-[10px]"></i>
                                    +{{ $stat['evolution'] }}%
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mb-2">
                        <p class="text-sm text-gray-500 font-medium mb-1">{{ $stat['label'] }}</p>
                        <p class="text-3xl font-bold text-gray-800 tracking-tight">{{ $stat['value'] }}</p>
                    </div>
                    
                    @if(isset($stat['sub']))
                        <p class="text-xs text-gray-400 mt-3 pt-3 border-t border-gray-100">{{ $stat['sub'] }}</p>
                    @endif
                    
                    @if(isset($stat['evolution']))
                        <p class="text-xs text-green-600 mt-3 pt-3 border-t border-gray-100 flex items-center gap-1">
                            <i class="fas fa-chart-line"></i>
                            <span>+{{ $stat['evolution'] }}% ce mois</span>
                        </p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        {{-- Graphique et convertisseur --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-10">
            {{-- Graphique amélioré --}}
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-800 flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-chart-bar text-white"></i>
                        </div>
                        <div>
                            <p class="text-lg font-bold">Évolution des réservations</p>
                            <p class="text-xs text-gray-500 font-normal">6 derniers mois</p>
                        </div>
                    </h3>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full"></span>
                        <span class="text-xs text-gray-500">Réservations</span>
                    </div>
                </div>
                
                <div class="relative">
                    <div class="flex items-end h-56 space-x-3 px-2">
                        @php
                            $mois = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'];
                            $valeurs = [5, 8, 6, 12, 9, 15];
                            $max = max($valeurs);
                        @endphp
                        @foreach($valeurs as $i => $v)
                        <div class="flex-1 flex flex-col items-center group">
                            <div class="relative w-full flex-1 flex items-end">
                                <div class="w-full bg-gradient-to-t from-blue-500 via-blue-400 to-indigo-500 rounded-t-xl transition-all duration-500 hover:from-blue-600 hover:via-blue-500 hover:to-indigo-600 group-hover:shadow-lg relative overflow-hidden" 
                                     style="height: {{ ($v/$max)*100 }}%; min-height: 20px;">
                                    <!-- Effet brillant -->
                                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
                                </div>
                                <!-- Tooltip au hover -->
                                <div class="absolute -top-10 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs px-3 py-1.5 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none whitespace-nowrap shadow-lg">
                                    {{ $v }} réservations
                                    <div class="absolute top-full left-1/2 transform -translate-x-1/2 w-2 h-2 bg-gray-800 rotate-45 -mt-1"></div>
                                </div>
                            </div>
                            <span class="text-xs text-gray-500 mt-3 font-medium">{{ $mois[$i] }}</span>
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- Lignes de grille -->
                    <div class="absolute inset-0 flex flex-col justify-between pointer-events-none pb-8">
                        @for($i = 0; $i < 5; $i++)
                            <div class="border-t border-gray-100 w-full"></div>
                        @endfor
                    </div>
                </div>
                
                <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
                    <span><i class="fas fa-info-circle mr-1"></i> Nombre de réservations par mois</span>
                    <span class="font-semibold text-gray-700">Total: {{ array_sum($valeurs) }}</span>
                </div>
            </div>

            {{-- Convertisseur premium --}}
            <div class="bg-gradient-to-br from-emerald-500 via-green-500 to-teal-600 rounded-2xl shadow-lg p-6 text-white relative overflow-hidden">
                <!-- Effets décoratifs -->
                <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-40 h-40 bg-emerald-400/20 rounded-full blur-3xl"></div>
                
                <div class="relative z-10">
                    <h3 class="text-xl font-bold flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <div>
                            <p class="text-lg font-bold">Convertisseur</p>
                            <p class="text-xs text-emerald-100 font-normal">USD ↔ CDF</p>
                        </div>
                    </h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm text-emerald-100 mb-2 block font-medium">Montant en USD</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-emerald-200 font-semibold">$</span>
                                <input type="number" id="usdInput" value="1" 
                                    class="w-full bg-white/15 backdrop-blur-md border border-white/30 rounded-xl px-4 py-3 pl-8 text-white placeholder-white/50 focus:ring-2 focus:ring-white/50 focus:border-white/50 outline-none transition-all"
                                    oninput="convertCurrency()">
                            </div>
                        </div>
                        
                        <div class="flex justify-center">
                            <div class="w-10 h-10 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center border border-white/30">
                                <i class="fas fa-arrow-down animate-bounce"></i>
                            </div>
                        </div>
                        
                        <div>
                            <label class="text-sm text-emerald-100 mb-2 block font-medium">Montant en CDF</label>
                            <div class="relative">
                                <input type="text" id="cdfOutput" value="2 500" 
                                    class="w-full bg-white/15 backdrop-blur-md border border-white/30 rounded-xl px-4 py-3 text-white font-semibold placeholder-white/50 outline-none"
                                    readonly>
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-emerald-200 font-semibold">CDF</span>
                            </div>
                        </div>
                        
                        <div class="pt-3 border-t border-white/20">
                            <p class="text-xs text-emerald-100 flex items-center gap-2">
                                <i class="fas fa-info-circle"></i>
                                <span>Taux : 1 USD = {{ number_format($tauxCDF ?? 2500, 0, ',', ' ') }} CDF</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Prochains voyages --}}
        @if(isset($prochainsVoyages) && $prochainsVoyages->count() > 0)
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-10 border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-800 flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-calendar-check text-white"></i>
                    </div>
                    <div>
                        <p class="text-lg font-bold">Prochains voyages</p>
                        <p class="text-xs text-gray-500 font-normal">Vos départs à venir</p>
                    </div>
                </h3>
                @if($prochainsVoyages->count() > 3)
                <a href="{{ route('client.voyages.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-semibold flex items-center gap-2 hover:gap-3 transition-all">
                    Voir tous <i class="fas fa-arrow-right"></i>
                </a>
                @endif
            </div>
            
            <div class="space-y-3">
                @foreach($prochainsVoyages->take(3) as $voyage)
                <div class="flex items-center justify-between p-4 rounded-xl hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-300 border border-transparent hover:border-blue-100 group">
                    <div class="flex items-center gap-4 flex-1">
                        <div class="text-center bg-gradient-to-br from-blue-500 to-indigo-600 text-white rounded-xl px-4 py-3 min-w-[80px] shadow-lg group-hover:scale-105 transition-transform">
                            <span class="text-2xl font-bold block">{{ $voyage->date_depart->format('d') }}</span>
                            <span class="text-xs font-medium uppercase">{{ $voyage->date_depart->format('M') }}</span>
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-gray-800 text-lg">{{ $voyage->code_voyage }}</p>
                            <div class="flex items-center gap-3 text-sm text-gray-500 mt-1">
                                <span class="flex items-center gap-1">
                                    <i class="fas fa-ship text-blue-500"></i>
                                    {{ $voyage->bateau->nom ?? 'N/A' }}
                                </span>
                                <span class="text-gray-300">•</span>
                                <span class="flex items-center gap-1">
                                    <i class="fas fa-clock text-indigo-500"></i>
                                    {{ $voyage->date_depart->format('H:i') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="text-right">
                            <span class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-full bg-gradient-to-r from-emerald-50 to-green-50 text-emerald-700 border border-emerald-200">
                                <i class="fas fa-hourglass-half"></i>
                                {{ now()->diffInDays($voyage->date_depart) }} jours
                            </span>
                        </div>
                        <a href="{{ route('client.reservations.create') }}?voyage={{ $voyage->id }}" 
                           class="px-5 py-2.5 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl font-semibold text-sm hover:shadow-lg hover:scale-105 transition-all duration-300 flex items-center gap-2">
                            Réserver <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Actions rapides premium --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-10">
            @php
                $actions = [
                    ['route' => 'client.reservations.create', 'title' => 'Nouvelle réservation', 'desc' => 'Réservez un voyage', 'icon' => 'plus-circle', 'gradient' => 'from-blue-500 to-indigo-600', 'bg' => 'from-blue-50 to-indigo-50', 'border' => 'border-blue-100'],
                    ['route' => 'client.reservations.index', 'title' => 'Mes réservations', 'desc' => 'Historique complet', 'icon' => 'list-ul', 'gradient' => 'from-emerald-500 to-green-600', 'bg' => 'from-emerald-50 to-green-50', 'border' => 'border-emerald-100'],
                    ['route' => 'client.paiements.index', 'title' => 'Mes paiements', 'desc' => 'Suivez vos transactions', 'icon' => 'coins', 'gradient' => 'from-amber-500 to-orange-600', 'bg' => 'from-amber-50 to-orange-50', 'border' => 'border-amber-100'],
                    ['route' => 'client.settings.profile', 'title' => 'Mon profil', 'desc' => 'Modifier mes infos', 'icon' => 'user-cog', 'gradient' => 'from-purple-500 to-pink-600', 'bg' => 'from-purple-50 to-pink-50', 'border' => 'border-purple-100'],
                ];
            @endphp

            @foreach($actions as $action)
            <a href="{{ route($action['route']) }}" 
               class="relative bg-gradient-to-br {{ $action['bg'] }} rounded-2xl shadow-md hover:shadow-2xl transition-all duration-500 p-6 group border {{ $action['border'] }} hover:-translate-y-1 overflow-hidden">
                <!-- Effet brillant au hover -->
                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/50 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
                
                <div class="relative z-10 flex items-center gap-4">
                    <div class="w-14 h-14 bg-gradient-to-br {{ $action['gradient'] }} rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                        <i class="fas fa-{{ $action['icon'] }} text-white text-2xl"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-bold text-gray-800 text-lg">{{ $action['title'] }}</p>
                        <p class="text-sm text-gray-600 mt-1">{{ $action['desc'] }}</p>
                    </div>
                    <i class="fas fa-chevron-right text-gray-400 group-hover:text-gray-600 group-hover:translate-x-1 transition-all"></i>
                </div>
            </a>
            @endforeach
        </div>

        {{-- Dernières réservations, paiements + notifications --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Réservations --}}
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-indigo-600 text-white flex justify-between items-center">
                    <h3 class="text-lg font-bold flex items-center gap-2">
                        <i class="fas fa-ticket-alt"></i> Réservations
                    </h3>
                    <a href="{{ route('client.reservations.index') }}" class="text-sm hover:underline flex items-center gap-1 font-medium">
                        Voir tout <i class="fas fa-chevron-right text-xs"></i>
                    </a>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($dernieresReservations ?? [] as $reservation)
                    <div class="px-6 py-4 hover:bg-gradient-to-r hover:from-blue-50 hover:to-transparent transition-all flex items-center justify-between group">
                        <div class="flex-1">
                            <p class="font-bold text-gray-800 flex items-center gap-2">
                                <span class="text-sm">#{{ $reservation->id }}</span>
                                <span class="text-gray-400">•</span>
                                <span>{{ $reservation->voyage->code_voyage ?? 'N/A' }}</span>
                            </p>
                            <p class="text-xs text-gray-500 flex items-center gap-1 mt-1">
                                <i class="far fa-calendar-alt"></i>
                                {{ $reservation->created_at->format('d/m/Y H:i') }}
                            </p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="px-3 py-1.5 text-xs font-semibold rounded-full flex items-center gap-1
                                {{ $reservation->statut == 'confirme' ? 'bg-gradient-to-r from-green-50 to-emerald-50 text-green-700 border border-green-200' : 
                                   ($reservation->statut == 'en_attente' ? 'bg-gradient-to-r from-amber-50 to-yellow-50 text-amber-700 border border-amber-200' : 'bg-gradient-to-r from-red-50 to-pink-50 text-red-700 border border-red-200') }}">
                                <i class="fas fa-{{ $reservation->statut == 'confirme' ? 'check-circle' : ($reservation->statut == 'en_attente' ? 'hourglass-half' : 'times-circle') }}"></i>
                                {{ ucfirst($reservation->statut) }}
                            </span>
                            <a href="{{ route('client.reservations.show', $reservation->id) }}" 
                               class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center text-blue-600 hover:bg-blue-100 group-hover:scale-110 transition-all">
                                <i class="fas fa-chevron-right text-xs"></i>
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="px-6 py-12 text-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-inbox text-3xl text-gray-300"></i>
                        </div>
                        <p class="text-gray-500 mb-3">Aucune réservation</p>
                        <a href="{{ route('client.reservations.create') }}" 
                           class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl font-semibold text-sm hover:shadow-lg transition-all">
                            <i class="fas fa-plus"></i> Réserver maintenant
                        </a>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- Paiements --}}
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                <div class="px-6 py-4 bg-gradient-to-r from-purple-500 to-pink-600 text-white flex justify-between items-center">
                    <h3 class="text-lg font-bold flex items-center gap-2">
                        <i class="fas fa-credit-card"></i> Paiements
                    </h3>
                    <a href="{{ route('client.paiements.index') }}" class="text-sm hover:underline flex items-center gap-1 font-medium">
                        Voir tout <i class="fas fa-chevron-right text-xs"></i>
                    </a>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($derniersPaiements ?? [] as $paiement)
                    <div class="px-6 py-4 hover:bg-gradient-to-r hover:from-purple-50 hover:to-transparent transition-all flex items-center justify-between group">
                        <div class="flex-1">
                            <p class="font-bold text-gray-800 flex items-center gap-2">
                                <span class="text-sm">#{{ $paiement->id }}</span>
                                <span class="text-gray-400">•</span>
                                <span class="text-lg">{{ number_format($paiement->montant, 0, ',', ' ') }} CDF</span>
                            </p>
                            <p class="text-xs text-gray-500 flex items-center gap-2 mt-1">
                                <span class="flex items-center gap-1">
                                    <i class="far fa-calendar-alt"></i>
                                    {{ $paiement->date_paiement->format('d/m/Y H:i') }}
                                </span>
                                <span class="text-gray-300">•</span>
                                <span class="text-gray-400">≈ {{ number_format($paiement->montant / ($tauxCDF ?? 2500), 2) }} USD</span>
                            </p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="px-3 py-1.5 text-xs font-semibold rounded-full flex items-center gap-1
                                {{ $paiement->statut == 'paye' ? 'bg-gradient-to-r from-green-50 to-emerald-50 text-green-700 border border-green-200' : 
                                   ($paiement->statut == 'en_attente' ? 'bg-gradient-to-r from-amber-50 to-yellow-50 text-amber-700 border border-amber-200' : 'bg-gradient-to-r from-red-50 to-pink-50 text-red-700 border border-red-200') }}">
                                <i class="fas fa-{{ $paiement->statut == 'paye' ? 'check-circle' : ($paiement->statut == 'en_attente' ? 'hourglass-half' : 'times-circle') }}"></i>
                                {{ ucfirst($paiement->statut) }}
                            </span>
                            <a href="{{ route('client.paiements.show', $paiement->id) }}" 
                               class="w-8 h-8 bg-purple-50 rounded-lg flex items-center justify-center text-purple-600 hover:bg-purple-100 group-hover:scale-110 transition-all">
                                <i class="fas fa-chevron-right text-xs"></i>
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="px-6 py-12 text-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-credit-card text-3xl text-gray-300"></i>
                        </div>
                        <p class="text-gray-500">Aucun paiement</p>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- Notifications & Profil --}}
            <div class="space-y-6">
                {{-- Complétion du profil --}}
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <h4 class="text-sm font-bold text-gray-800 flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user-check text-white text-sm"></i>
                        </div>
                        Complétion du profil
                    </h4>
                    @php
                        $progression = 70;
                    @endphp
                    <div class="relative">
                        <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
                            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-3 rounded-full transition-all duration-1000 relative overflow-hidden" 
                                 style="width: {{ $progression }}%">
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent animate-shimmer"></div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between mt-3">
                            <span class="text-sm font-bold text-gray-700">{{ $progression }}%</span>
                            <a href="{{ route('client.settings.profile') }}" 
                               class="text-sm text-blue-600 hover:text-blue-700 font-semibold flex items-center gap-1 hover:gap-2 transition-all">
                                Compléter <i class="fas fa-arrow-right text-xs"></i>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Notifications --}}
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                            <div class="w-8 h-8 bg-gradient-to-br from-amber-500 to-orange-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-bell text-white text-sm"></i>
                            </div>
                            Notifications
                        </h4>
                        <span class="w-6 h-6 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center">3</span>
                    </div>
                    <ul class="space-y-3">
                        <li class="flex items-start gap-3 p-3 rounded-xl hover:bg-blue-50 transition-colors cursor-pointer group">
                            <div class="w-2 h-2 bg-blue-500 rounded-full mt-2 flex-shrink-0 group-hover:scale-125 transition-transform"></div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-700 font-medium">Votre réservation #1234 a été confirmée.</p>
                                <p class="text-xs text-gray-400 mt-1">Il y a 2 heures</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-3 p-3 rounded-xl hover:bg-green-50 transition-colors cursor-pointer group">
                            <div class="w-2 h-2 bg-green-500 rounded-full mt-2 flex-shrink-0 group-hover:scale-125 transition-transform"></div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-700 font-medium">Paiement de 150 000 CDF reçu.</p>
                                <p class="text-xs text-gray-400 mt-1">Il y a 5 heures</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-3 p-3 rounded-xl hover:bg-red-50 transition-colors cursor-pointer group">
                            <div class="w-2 h-2 bg-red-500 rounded-full mt-2 flex-shrink-0 group-hover:scale-125 transition-transform"></div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-700 font-medium">Rappel : votre voyage du 15 juillet approche.</p>
                                <p class="text-xs text-gray-400 mt-1">Il y a 1 jour</p>
                            </div>
                        </li>
                    </ul>
                    <a href="#" class="text-xs text-blue-600 hover:text-blue-700 font-semibold mt-4 inline-flex items-center gap-1 hover:gap-2 transition-all">
                        Voir toutes les notifications <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Styles personnalisés --}}
@push('styles')
<style>
    @keyframes wave {
        0%, 100% { transform: rotate(0deg); }
        25% { transform: rotate(20deg); }
        75% { transform: rotate(-15deg); }
    }
    .animate-wave {
        animation: wave 2s ease-in-out infinite;
    }
    
    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }
    .animate-shimmer {
        animation: shimmer 2s infinite;
    }
</style>
@endpush

{{-- Script pour le convertisseur --}}
@push('scripts')
<script>
    function convertCurrency() {
        let usd = document.getElementById('usdInput').value;
        let taux = {{ $tauxCDF ?? 2500 }};
        if (!usd) usd = 0;
        let cdf = usd * taux;
        document.getElementById('cdfOutput').value = new Intl.NumberFormat('fr-FR').format(cdf);
    }
    document.addEventListener('DOMContentLoaded', convertCurrency);
</script>
@endpush

@endsection