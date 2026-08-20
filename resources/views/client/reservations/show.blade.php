@extends('layouts.client')

@section('title', 'Détails de la réservation')
@section('header', 'Détails de la réservation')

@section('content')
<div class="container mx-auto px-4 py-6">
    {{-- Bandeau de statut --}}
    <div class="rounded-t-xl p-4 mb-6 flex items-center justify-between
        {{ $reservation->statut == 'confirme' ? 'bg-green-50 border-l-4 border-green-500' :
           ($reservation->statut == 'en_attente' ? 'bg-yellow-50 border-l-4 border-yellow-500' :
           'bg-red-50 border-l-4 border-red-500') }}">
        <div class="flex items-center">
            <i class="fas fa-info-circle text-2xl mr-3
                {{ $reservation->statut == 'confirme' ? 'text-green-500' :
                   ($reservation->statut == 'en_attente' ? 'text-yellow-500' : 'text-red-500') }}">
            </i>
            <div>
                <h2 class="text-xl font-bold text-gray-800">Réservation #{{ $reservation->id }}</h2>
                <p class="text-sm text-gray-600">
                    Réservée le {{ $reservation->date_reservation->format('d/m/Y à H:i') }}
                </p>
            </div>
        </div>
        <span class="px-3 py-1 text-sm font-semibold rounded-full
            {{ $reservation->statut == 'confirme' ? 'bg-green-200 text-green-800' :
               ($reservation->statut == 'en_attente' ? 'bg-yellow-200 text-yellow-800' :
               'bg-red-200 text-red-800') }}">
            {{ ucfirst($reservation->statut) }}
        </span>
    </div>

    {{-- Grille principale --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Colonne de gauche (2/3) : détails --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Carte : Voyage --}}
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">
                    <i class="fas fa-ship text-blue-500 mr-2"></i>Voyage
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Code voyage</label>
                        <p class="text-gray-900 font-medium">
                            {{ $reservation->voyage->code_voyage ?? 'N/A' }}
                            <a href="/client/voyages/{{ $reservation->voyage->id }}" class="text-blue-600 hover:underline ml-2 text-sm">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Date d'embarquement</label>
                        <p class="text-gray-900 font-medium">{{ $reservation->date_embarquement->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Port</label>
                        <p class="text-gray-900 font-medium">{{ $reservation->voyage->trajets->first()->ports->first()->nom ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Quai</label>
                        <p class="text-gray-900 font-medium">
                            {{ $reservation->voyage->trajets->first()->ports->first()->quais->first()->nom ?? 'N/A' }}
                            (n°{{ $reservation->voyage->trajets->first()->ports->first()->quais->first()->numero ?? 'N/A' }})
                        </p>
                    </div>
                </div>
            </div>

            {{-- Carte : Bateau --}}
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">
                    <i class="fas fa-anchor text-blue-500 mr-2"></i>Bateau
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Nom</label>
                        <p class="text-gray-900 font-medium">{{ $reservation->voyage->bateau->nom ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Type</label>
                        <p class="text-gray-900 font-medium">{{ $reservation->voyage->bateau->type ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Capacité passagers</label>
                        <p class="text-gray-900 font-medium">{{ $reservation->voyage->bateau->capacite_passager ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Capacité cargaison (tonnes)</label>
                        <p class="text-gray-900 font-medium">{{ $reservation->voyage->bateau->capacite_cargaison ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            {{-- Carte : Pavillon --}}
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">
                    <i class="fas fa-door-open text-blue-500 mr-2"></i>Pavillon
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Nom</label>
                        <p class="text-gray-900 font-medium">{{ $reservation->pavillon->nom ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Classe</label>
                        <p class="text-gray-900 font-medium">{{ $reservation->pavillon->classe ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Capacité max</label>
                        <p class="text-gray-900 font-medium">{{ $reservation->pavillon->capacite_max ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Prix unitaire</label>
                        <p class="text-gray-900 font-medium">
                            @if($reservation->type_reservation == 'cargaison')
                                {{ number_format($reservation->pavillon->prix_tonne ?? 0, 0, ',', ' ') }} FCFA/tonne
                            @else
                                {{ number_format($reservation->pavillon->prix_unitaire ?? 0, 0, ',', ' ') }} FCFA
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            {{-- Carte : Détails de la réservation --}}
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">
                    <i class="fas fa-clipboard-list text-blue-500 mr-2"></i>Détails de la réservation
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Type</label>
                        <p class="text-gray-900 font-medium">
                            <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                {{ ucfirst($reservation->type_reservation) }}
                            </span>
                        </p>
                    </div>
                    @if($reservation->type_reservation != 'passage')
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Nombre de cargaisons</label>
                        <p class="text-gray-900 font-medium">{{ $reservation->nombre_cargaison ?? 0 }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Poids total (tonnes)</label>
                        <p class="text-gray-900 font-medium">{{ $reservation->poids_cargaison ?? 0 }} t</p>
                    </div>
                    @endif
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Prix total</label>
                        <p class="text-xl font-bold text-blue-600">{{ number_format($reservation->prix_total, 0, ',', ' ') }} FCFA</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Date de réservation</label>
                        <p class="text-gray-900 font-medium">{{ $reservation->date_reservation->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
                @if($reservation->description)
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <label class="block text-sm font-medium text-gray-500">Description</label>
                    <p class="text-gray-900">{{ $reservation->description }}</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Colonne de droite (1/3) : résumé et actions --}}
        <div class="space-y-6">
            {{-- Carte : Résumé des prix --}}
            <div class="bg-white rounded-xl shadow-md p-6">
                <h4 class="font-semibold text-gray-700 mb-3"><i class="fas fa-coins text-yellow-500 mr-2"></i>Résumé des prix</h4>
                @php
                    $prixUnitaire = 0;
                    if ($reservation->type_reservation == 'passage') {
                        $prixUnitaire = $reservation->pavillon->prix_unitaire ?? 0;
                    } elseif ($reservation->type_reservation == 'cargaison') {
                        $prixUnitaire = $reservation->pavillon->prix_tonne ?? 0;
                    } else {
                        $prixUnitaire = ($reservation->pavillon->prix_unitaire ?? 0) + ($reservation->pavillon->prix_tonne ?? 0) * ($reservation->poids_cargaison ?? 0);
                    }
                @endphp
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Prix unitaire</span>
                        <span class="font-medium">{{ number_format($prixUnitaire, 0, ',', ' ') }} FCFA</span>
                    </div>
                    @if($reservation->type_reservation != 'passage')
                    <div class="flex justify-between">
                        <span class="text-gray-600">Poids</span>
                        <span class="font-medium">{{ $reservation->poids_cargaison ?? 0 }} t</span>
                    </div>
                    @endif
                    <div class="flex justify-between border-t pt-2 border-gray-200 font-bold text-lg">
                        <span class="text-gray-800">Total</span>
                        <span class="text-blue-600">{{ number_format($reservation->prix_total, 0, ',', ' ') }} FCFA</span>
                    </div>
                </div>
            </div>

            {{-- Carte : Itinéraire et point de rencontre --}}
            <div class="bg-blue-50 rounded-xl shadow-md p-6 border border-blue-200">
                <h4 class="font-semibold text-blue-800 mb-3"><i class="fas fa-map-marked-alt mr-2"></i>Point de rencontre</h4>
                <p class="text-blue-800 text-sm">
                    <i class="fas fa-location-dot mr-1"></i> Accueil principal du port de 
                    {{ $reservation->voyage->trajets->first()->ports->first()->ville ?? 'N/A' }}
                    (bâtiment administrativ
                </p>
                <h4 class="font-semibold text-blue-800 mt-4 mb-2"><i class="fas fa-route mr-2"></i>Itinéraire conseillé</h4>
                <ol class="text-sm text-blue-800 list-decimal list-inside space-y-1">
                    <li>Entrée principale du port</li>
                    <li>Présentez-vous au contrôle</li>
                    <li>Direction quai n°{{ $reservation->voyage->trajets->first()->ports->first()->quais->first()->numero ?? 'N/A' }}</li>
                    <li>Accès au bateau</li>
                    <li>Rendez-vous au pavillon {{ $reservation->pavillon->nom ?? 'N/A' }}</li>
                </ol>
            </div>

            {{-- Carte : Actions --}}
            <div class="bg-white rounded-xl shadow-md p-6">
                <h4 class="font-semibold text-gray-700 mb-3"><i class="fas fa-cog mr-2"></i>Actions</h4>
                <div class="space-y-3">
                    @if($reservation->statut == 'en_attente')
                    <form action="{{ route('client.reservations.destroy', $reservation->id) }}" method="POST" onsubmit="return confirm('Annuler cette réservation ? Cette action est irréversible.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition flex items-center justify-center">
                            <i class="fas fa-times mr-2"></i> Annuler la réservation
                        </button>
                    </form>
                    @endif
                    <button onclick="window.print()" class="w-full bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg transition flex items-center justify-center">
                        <i class="fas fa-print mr-2"></i> Imprimer
                    </button>
                    <a href="{{ route('client.reservations.index') }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition flex items-center justify-center">
                        <i class="fas fa-arrow-left mr-2"></i> Retour à mes réservations
                    </a>
                </div>
            </div>

            {{-- Message d'alerte si annulée --}}
            @if($reservation->statut == 'annule')
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                    <p class="text-sm text-red-700">Cette réservation a été annulée. Aucune action n'est possible.</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

/* Documentation complète de l'application - toutes les fonctionnalités expliquées */