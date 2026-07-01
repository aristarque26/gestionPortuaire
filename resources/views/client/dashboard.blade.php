@extends('layouts.client')

@section('title', 'Tableau de bord')
@section('header', 'Mon tableau de bord')

@section('content')
<div class="container mx-auto px-4 py-6">
    {{-- Bannière de bienvenue --}}
    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl shadow-lg p-6 mb-6 text-white">
        <div class="flex items-center justify-between flex-wrap">
            <div>
                <h1 class="text-2xl font-bold">Bonjour, {{ $user->prenom ?? $user->name }} 👋</h1>
                <p class="text-blue-100 mt-1">Bienvenue sur votre espace client. Aujourd'hui, {{ now()->format('l d F Y') }}</p>
            </div>
            <div class="flex items-center space-x-3 mt-3 md:mt-0">
                <span class="px-3 py-1 bg-white bg-opacity-20 rounded-full text-sm">
                    <i class="fas fa-calendar-alt mr-1"></i> {{ now()->format('d/m/Y') }}
                </span>
                <a href="{{ route('client.settings.profile') }}" class="px-3 py-1 bg-white bg-opacity-20 rounded-full text-sm hover:bg-opacity-30 transition">
                    <i class="fas fa-user-cog mr-1"></i> Profil
                </a>
            </div>
        </div>
    </div>

    {{-- Statistiques --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-md p-5 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Réservations</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalReservations ?? 0 }}</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-full">
                    <i class="fas fa-ticket-alt text-blue-500"></i>
                </div>
            </div>
            @if(isset($evolutionReservations))
            <p class="text-xs text-green-600 mt-2"><i class="fas fa-arrow-up mr-1"></i> +{{ $evolutionReservations }}% ce mois</p>
            @endif
        </div>

        <div class="bg-white rounded-xl shadow-md p-5 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Voyages à venir</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $voyagesAVenir ?? 0 }}</p>
                </div>
                <div class="p-3 bg-green-100 rounded-full">
                    <i class="fas fa-ship text-green-500"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-2">Prochain départ : {{ $prochainDepart ?? 'Aucun' }}</p>
        </div>

        <div class="bg-white rounded-xl shadow-md p-5 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Paiements</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalPaiements ?? 0 }}</p>
                </div>
                <div class="p-3 bg-purple-100 rounded-full">
                    <i class="fas fa-credit-card text-purple-500"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-2">Montant total : {{ number_format($montantTotalPaiements ?? 0, 0, ',', ' ') }} FCFA</p>
        </div>

        <div class="bg-white rounded-xl shadow-md p-5 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">En attente</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $enAttente ?? 0 }}</p>
                </div>
                <div class="p-3 bg-yellow-100 rounded-full">
                    <i class="fas fa-clock text-yellow-500"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-2">Réservations à confirmer</p>
        </div>
    </div>

    {{-- Prochains voyages (timeline) --}}
    @if(isset($prochainsVoyages) && $prochainsVoyages->count() > 0)
    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4"><i class="fas fa-calendar-check text-blue-500 mr-2"></i>Prochains voyages</h3>
        <div class="space-y-4">
            @foreach($prochainsVoyages->take(3) as $voyage)
            <div class="flex items-center justify-between border-b border-gray-100 pb-3 last:border-0">
                <div class="flex items-center space-x-4">
                    <div class="text-center">
                        <span class="text-sm font-bold text-blue-600">{{ $voyage->date_depart->format('d') }}</span>
                        <span class="block text-xs text-gray-500">{{ $voyage->date_depart->format('M') }}</span>
                    </div>
                    <div>
                        <p class="font-medium text-gray-800">{{ $voyage->code_voyage }}</p>
                        <p class="text-sm text-gray-500">{{ $voyage->bateau->nom ?? 'N/A' }} - {{ $voyage->date_depart->format('H:i') }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                        {{ now()->diffInDays($voyage->date_depart) }} jours
                    </span>
                    <a href="{{ route('client.reservations.create') }}?voyage={{ $voyage->id }}" class="block text-xs text-blue-600 hover:underline mt-1">Réserver</a>
                </div>
            </div>
            @endforeach
        </div>
        @if(isset($prochainsVoyages) && $prochainsVoyages->count() > 3)
        <div class="mt-4 text-right">
            <a href="{{ route('client.voyages.index') }}" class="text-sm text-blue-600 hover:underline">Voir tous les voyages →</a>
        </div>
        @endif
    </div>
    @endif

    {{-- Actions rapides --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <a href="{{ route('client.reservations.create') }}" class="bg-white rounded-xl shadow-md p-4 hover:shadow-lg transition flex items-center space-x-3 group">
            <div class="p-3 bg-blue-100 rounded-full group-hover:bg-blue-200 transition">
                <i class="fas fa-plus text-blue-600"></i>
            </div>
            <div>
                <p class="font-medium text-gray-800">Nouvelle réservation</p>
                <p class="text-xs text-gray-500">Réservez un voyage</p>
            </div>
        </a>

        <a href="{{ route('client.reservations.index') }}" class="bg-white rounded-xl shadow-md p-4 hover:shadow-lg transition flex items-center space-x-3 group">
            <div class="p-3 bg-green-100 rounded-full group-hover:bg-green-200 transition">
                <i class="fas fa-list text-green-600"></i>
            </div>
            <div>
                <p class="font-medium text-gray-800">Mes réservations</p>
                <p class="text-xs text-gray-500">Historique complet</p>
            </div>
        </a>

        <a href="{{ route('client.paiements.index') }}" class="bg-white rounded-xl shadow-md p-4 hover:shadow-lg transition flex items-center space-x-3 group">
            <div class="p-3 bg-yellow-100 rounded-full group-hover:bg-yellow-200 transition">
                <i class="fas fa-coins text-yellow-600"></i>
            </div>
            <div>
                <p class="font-medium text-gray-800">Mes paiements</p>
                <p class="text-xs text-gray-500">Suivez vos transactions</p>
            </div>
        </a>

        <a href="{{ route('client.settings.profile') }}" class="bg-white rounded-xl shadow-md p-4 hover:shadow-lg transition flex items-center space-x-3 group">
            <div class="p-3 bg-purple-100 rounded-full group-hover:bg-purple-200 transition">
                <i class="fas fa-user-cog text-purple-600"></i>
            </div>
            <div>
                <p class="font-medium text-gray-800">Mon profil</p>
                <p class="text-xs text-gray-500">Modifier mes infos</p>
            </div>
        </a>
    </div>

    {{-- Dernières réservations et paiements --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Dernières réservations --}}
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800"><i class="fas fa-ticket-alt text-blue-500 mr-2"></i>Dernières réservations</h3>
                <a href="{{ route('client.reservations.index') }}" class="text-sm text-blue-600 hover:underline">Voir tout</a>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($dernieresReservations ?? [] as $reservation)
                <div class="px-6 py-3 hover:bg-gray-50 transition flex items-center justify-between">
                    <div>
                        <p class="font-medium text-gray-800">#{{ $reservation->id }} - {{ $reservation->voyage->code_voyage ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-500">{{ $reservation->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <span class="px-2 py-1 text-xs rounded-full 
                            {{ $reservation->statut == 'confirme' ? 'bg-green-100 text-green-800' : 
                               ($reservation->statut == 'en_attente' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            {{ ucfirst($reservation->statut) }}
                        </span>
                        <a href="{{ route('client.reservations.show', $reservation->id) }}" class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>
                </div>
                @empty
                <div class="px-6 py-8 text-center text-gray-500">
                    <i class="fas fa-inbox text-3xl block mb-2 text-gray-300"></i>
                    Aucune réservation pour le moment.
                    <a href="{{ route('client.reservations.create') }}" class="block text-blue-600 hover:underline mt-2">Réserver maintenant</a>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Derniers paiements --}}
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800"><i class="fas fa-credit-card text-purple-500 mr-2"></i>Derniers paiements</h3>
                <a href="{{ route('client.paiements.index') }}" class="text-sm text-blue-600 hover:underline">Voir tout</a>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($derniersPaiements ?? [] as $paiement)
                <div class="px-6 py-3 hover:bg-gray-50 transition flex items-center justify-between">
                    <div>
                        <p class="font-medium text-gray-800">#{{ $paiement->id }} - {{ number_format($paiement->montant, 0, ',', ' ') }} {{ strtoupper($paiement->devise) }}</p>
                        <p class="text-xs text-gray-500">{{ $paiement->date_paiement->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <span class="px-2 py-1 text-xs rounded-full 
                            {{ $paiement->statut == 'paye' ? 'bg-green-100 text-green-800' : 
                               ($paiement->statut == 'en_attente' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            {{ ucfirst($paiement->statut) }}
                        </span>
                        <a href="{{ route('client.paiements.show', $paiement->id) }}" class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>
                </div>
                @empty
                <div class="px-6 py-8 text-center text-gray-500">
                    <i class="fas fa-credit-card text-3xl block mb-2 text-gray-300"></i>
                    Aucun paiement enregistré.
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection