<!-- resources/views/superviseur/reservations/show.blade.php -->
@extends('layouts.app')

@section('content')
<div class="flex">
    @include('superviseur.layouts.sidebar')
    
    <div class="flex-1 p-6 bg-gray-100 min-h-screen">
        <!-- En-tête -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Détail de la réservation #{{ $reservation->id }}</h1>
                <p class="text-sm text-gray-500">Créée le {{ Carbon\Carbon::parse($reservation->date_reservation)->format('d/m/Y à H:i') }}</p>
            </div>
            <a href="{{ route('superviseur.reservations.index') }}" class="text-blue-600 hover:underline">
                ← Retour à la liste
            </a>
        </div>

        <!-- Messages flash -->
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
            {{ session('error') }}
        </div>
        @endif

        <!-- Informations principales -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Colonne gauche -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Informations client -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Informations client</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Nom complet</p>
                            <p class="font-medium">{{ $reservation->client->nom }} {{ $reservation->client->prenom }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="font-medium">{{ $reservation->client->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Téléphone</p>
                            <p class="font-medium">{{ $reservation->client->telephone ?? 'Non renseigné' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Nationalité</p>
                            <p class="font-medium">{{ $reservation->client->nationalite ?? 'Non renseignée' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Informations voyage -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Informations voyage</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Bateau</p>
                            <p class="font-medium">{{ $reservation->voyage->bateau->nom ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Code voyage</p>
                            <p class="font-medium">{{ $reservation->voyage->code_voyage }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Date d'embarquement</p>
                            <p class="font-medium">{{ Carbon\Carbon::parse($reservation->date_embarquement)->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Date d'arrivée</p>
                            <p class="font-medium">{{ $reservation->date_arrivee ? Carbon\Carbon::parse($reservation->date_arrivee)->format('d/m/Y H:i') : 'Non arrivé' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Informations réservation -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Détails de la réservation</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Type</p>
                            <span class="px-2 py-1 rounded-full text-xs font-semibold
                                @if($reservation->type_reservation == 'passage') bg-blue-100 text-blue-800
                                @elseif($reservation->type_reservation == 'cargaison') bg-orange-100 text-orange-800
                                @else bg-purple-100 text-purple-800 @endif">
                                {{ $reservation->type_reservation }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Pavillon</p>
                            <p class="font-medium">{{ $reservation->pavillon->nom ?? 'N/A' }}</p>
                        </div>
                        @if($reservation->nombre_cargaison)
                        <div>
                            <p class="text-sm text-gray-500">Nombre de cargaisons</p>
                            <p class="font-medium">{{ $reservation->nombre_cargaison }}</p>
                        </div>
                        @endif
                        @if($reservation->poids_cargaison)
                        <div>
                            <p class="text-sm text-gray-500">Poids cargaison</p>
                            <p class="font-medium">{{ $reservation->poids_cargaison }} kg</p>
                        </div>
                        @endif
                        @if($reservation->description)
                        <div class="col-span-2">
                            <p class="text-sm text-gray-500">Description</p>
                            <p class="font-medium">{{ $reservation->description }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Colonne droite -->
            <div class="space-y-6">
                <!-- Statut et actions -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Statut</h3>
                    <div class="text-center py-4">
                        <span class="px-4 py-2 rounded-full text-sm font-semibold
                            @if($reservation->statut == 'en_attente') bg-yellow-100 text-yellow-800
                            @elseif($reservation->statut == 'confirme') bg-blue-100 text-blue-800
                            @elseif($reservation->statut == 'paye') bg-green-100 text-green-800
                            @elseif($reservation->statut == 'arrive') bg-purple-100 text-purple-800
                            @elseif($reservation->statut == 'annule') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ strtoupper($reservation->statut) }}
                        </span>
                    </div>

                    <div class="space-y-2">
                        <h4 class="text-sm font-semibold text-gray-700">Actions</h4>
                        
                        @if($reservation->statut == 'en_attente' || $reservation->statut == 'confirme')
                        <form method="POST" action="{{ route('superviseur.reservations.confirmer', $reservation->id) }}" class="inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition text-sm">
                                ✅ Confirmer
                            </button>
                        </form>
                        @endif

                        @if($reservation->statut == 'confirme' || $reservation->statut == 'paye')
                        <form method="POST" action="{{ route('superviseur.reservations.marquer.paye', $reservation->id) }}" class="inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition text-sm">
                                💰 Marquer comme payée
                            </button>
                        </form>
                        @endif

                        @if($reservation->statut == 'paye')
                        <form method="POST" action="{{ route('superviseur.reservations.marquer.arrivee', $reservation->id) }}" class="inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition text-sm">
                                🚢 Marquer comme arrivée
                            </button>
                        </form>
                        @endif

                        @if(!in_array($reservation->statut, ['annule', 'arrive']))
                        <form method="POST" action="{{ route('superviseur.reservations.annuler', $reservation->id) }}" class="inline" 
                              onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette réservation ?')">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition text-sm">
                                ❌ Annuler
                            </button>
                        </form>
                        @endif
                    </div>
                </div>

                <!-- Paiements -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Paiements</h3>
                    <div class="space-y-3">
                        @forelse($reservation->paiements as $paiement)
                        <div class="flex justify-between items-center border-b pb-2">
                            <div>
                                <p class="text-sm font-medium">{{ number_format($paiement->montant, 0, ',', ' ') }} {{ $paiement->devise }}</p>
                                <p class="text-xs text-gray-500">{{ $paiement->mode_paiement }} - {{ Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y H:i') }}</p>
                            </div>
                            <span class="text-xs px-2 py-1 rounded-full
                                @if($paiement->statut == 'paye') bg-green-100 text-green-800
                                @elseif($paiement->statut == 'en_attente') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ $paiement->statut }}
                            </span>
                        </div>
                        @empty
                        <p class="text-center text-gray-500 text-sm">Aucun paiement</p>
                        @endforelse
                    </div>
                </div>

                <!-- Trajets -->
                @if($reservation->voyage->trajets->count() > 0)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Trajets</h3>
                    <div class="space-y-2">
                        @foreach($reservation->voyage->trajets as $trajet)
                        <div class="flex items-center space-x-2 text-sm">
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">{{ $trajet->ordre }}</span>
                            <span>{{ $trajet->nom }}</span>
                            <span class="text-gray-400">→</span>
                            <span class="text-xs text-gray-500">{{ number_format($trajet->distance, 0) }} km</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection