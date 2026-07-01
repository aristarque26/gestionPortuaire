@extends('layouts.client')

@section('title', 'Paramètres')
@section('header', 'Paramètres')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        {{-- Sidebar --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-md p-4">
                <div class="text-center mb-6">
                    <div class="w-20 h-20 rounded-full bg-blue-100 flex items-center justify-center mx-auto border-2 border-blue-200">
                        <i class="fas fa-user text-3xl text-blue-500"></i>
                    </div>
                    <h4 class="mt-2 font-medium text-gray-800">{{ $user->prenom ?? $user->name }} {{ $user->name }}</h4>
                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                </div>
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('client.settings.profile') }}" class="flex items-center px-3 py-2 rounded-lg hover:bg-gray-50 text-gray-700 transition">
                            <i class="fas fa-user-circle w-5 mr-2"></i> Mon profil
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('client.settings.password') }}" class="flex items-center px-3 py-2 rounded-lg hover:bg-gray-50 text-gray-700 transition">
                            <i class="fas fa-lock w-5 mr-2"></i> Sécurité
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('client.reservations.index') }}" class="flex items-center px-3 py-2 rounded-lg hover:bg-gray-50 text-gray-700 transition">
                            <i class="fas fa-ticket-alt w-5 mr-2"></i> Mes réservations
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('client.paiements.index') }}" class="flex items-center px-3 py-2 rounded-lg hover:bg-gray-50 text-gray-700 transition">
                            <i class="fas fa-credit-card w-5 mr-2"></i> Mes paiements
                        </a>
                    </li>
                    <li class="pt-2 border-t border-gray-200 mt-2">
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="flex items-center w-full px-3 py-2 rounded-lg hover:bg-red-50 text-red-600 transition">
                                <i class="fas fa-sign-out-alt w-5 mr-2"></i> Déconnexion
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Contenu principal --}}
        <div class="lg:col-span-3 space-y-6">
            {{-- En-tête de bienvenue --}}
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-800">
                    Bienvenue, {{ $user->prenom ?? $user->name }} ! 👋
                </h2>
                <p class="text-gray-600 mt-1">Gérez vos informations, réservations et paiements depuis votre espace client.</p>
            </div>

            {{-- Statistiques --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-blue-500">
                    <p class="text-sm text-gray-500">Réservations</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalReservations ?? 0 }}</p>
                </div>
                <div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-green-500">
                    <p class="text-sm text-gray-500">Confirmées</p>
                    <p class="text-2xl font-bold text-green-600">{{ $reservationsConfirmees ?? 0 }}</p>
                </div>
                <div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-yellow-500">
                    <p class="text-sm text-gray-500">En attente</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $reservationsEnAttente ?? 0 }}</p>
                </div>
                <div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-purple-500">
                    <p class="text-sm text-gray-500">Paiements</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $totalPaiements ?? 0 }}</p>
                </div>
            </div>

            {{-- Accès rapide --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="{{ route('client.settings.profile') }}" class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition flex items-center justify-between group">
                    <div>
                        <i class="fas fa-user-circle text-3xl text-blue-500 group-hover:text-blue-600"></i>
                        <h3 class="text-lg font-semibold text-gray-800 mt-2">Mon profil</h3>
                        <p class="text-sm text-gray-500">Modifier vos informations personnelles</p>
                    </div>
                    <i class="fas fa-chevron-right text-gray-400 group-hover:text-blue-500 transition"></i>
                </a>
                <a href="{{ route('client.settings.password') }}" class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition flex items-center justify-between group">
                    <div>
                        <i class="fas fa-lock text-3xl text-blue-500 group-hover:text-blue-600"></i>
                        <h3 class="text-lg font-semibold text-gray-800 mt-2">Sécurité</h3>
                        <p class="text-sm text-gray-500">Changer votre mot de passe</p>
                    </div>
                    <i class="fas fa-chevron-right text-gray-400 group-hover:text-blue-500 transition"></i>
                </a>
                <a href="{{ route('client.reservations.index') }}" class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition flex items-center justify-between group">
                    <div>
                        <i class="fas fa-ticket-alt text-3xl text-blue-500 group-hover:text-blue-600"></i>
                        <h3 class="text-lg font-semibold text-gray-800 mt-2">Mes réservations</h3>
                        <p class="text-sm text-gray-500">Consulter l'historique de vos réservations</p>
                    </div>
                    <i class="fas fa-chevron-right text-gray-400 group-hover:text-blue-500 transition"></i>
                </a>
                <a href="{{ route('client.paiements.index') }}" class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition flex items-center justify-between group">
                    <div>
                        <i class="fas fa-credit-card text-3xl text-blue-500 group-hover:text-blue-600"></i>
                        <h3 class="text-lg font-semibold text-gray-800 mt-2">Mes paiements</h3>
                        <p class="text-sm text-gray-500">Voir l'historique de vos paiements</p>
                    </div>
                    <i class="fas fa-chevron-right text-gray-400 group-hover:text-blue-500 transition"></i>
                </a>
            </div>

            {{-- Dernières activités --}}
            @if(isset($dernieresReservations) && $dernieresReservations->count() > 0 || isset($derniersPaiements) && $derniersPaiements->count() > 0)
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4"><i class="fas fa-history text-blue-500 mr-2"></i>Dernières activités</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if(isset($dernieresReservations) && $dernieresReservations->count() > 0)
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-2">📋 Dernières réservations</h4>
                        <ul class="divide-y divide-gray-100">
                            @foreach($dernieresReservations->take(3) as $reservation)
                            <li class="py-2">
                                <a href="{{ route('client.reservations.show', $reservation->id) }}" class="flex justify-between items-center hover:text-blue-600">
                                    <span class="text-sm text-gray-700">#{{ $reservation->id }} - {{ $reservation->voyage->code_voyage ?? 'N/A' }}</span>
                                    <span class="text-xs px-2 py-1 rounded-full 
                                        {{ $reservation->statut == 'confirme' ? 'bg-green-100 text-green-800' :
                                           ($reservation->statut == 'en_attente' ? 'bg-yellow-100 text-yellow-800' :
                                           'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($reservation->statut) }}
                                    </span>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    @if(isset($derniersPaiements) && $derniersPaiements->count() > 0)
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-2">💰 Derniers paiements</h4>
                        <ul class="divide-y divide-gray-100">
                            @foreach($derniersPaiements->take(3) as $paiement)
                            <li class="py-2">
                                <a href="{{ route('client.paiements.show', $paiement->id) }}" class="flex justify-between items-center hover:text-blue-600">
                                    <span class="text-sm text-gray-700">#{{ $paiement->id }} - {{ number_format($paiement->montant, 0, ',', ' ') }} {{ strtoupper($paiement->devise) }}</span>
                                    <span class="text-xs px-2 py-1 rounded-full 
                                        {{ $paiement->statut == 'paye' ? 'bg-green-100 text-green-800' :
                                           ($paiement->statut == 'en_attente' ? 'bg-yellow-100 text-yellow-800' :
                                           'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($paiement->statut) }}
                                    </span>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
                @if(isset($dernieresReservations) && $dernieresReservations->count() > 0 || isset($derniersPaiements) && $derniersPaiements->count() > 0)
                <div class="mt-4 text-right">
                    <a href="{{ route('client.reservations.index') }}" class="text-sm text-blue-600 hover:underline">Voir toutes les réservations →</a>
                    <span class="mx-2 text-gray-300">|</span>
                    <a href="{{ route('client.paiements.index') }}" class="text-sm text-blue-600 hover:underline">Voir tous les paiements →</a>
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>
@endsection