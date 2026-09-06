<!-- resources/views/superviseur/personnel/show.blade.php -->
@extends('layouts.app')

@section('content')
<div class="flex">
    @include('superviseur.layouts.sidebar')
    
    <div class="flex-1 p-6 bg-gray-100 min-h-screen">
        <!-- En-tête -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Détail du personnel</h1>
                <p class="text-sm text-gray-500">{{ $personnel->matricule }}</p>
            </div>
            <a href="{{ route('superviseur.personnel.index') }}" class="text-blue-600 hover:underline">
                ← Retour à la liste
            </a>
        </div>

        <!-- Messages flash -->
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
            {{ session('success') }}
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Colonne gauche -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Informations personnelles -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Informations personnelles</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Nom</p>
                            <p class="font-medium">{{ $personnel->user->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Prénom</p>
                            <p class="font-medium">{{ $personnel->user->prenom ?? 'Non renseigné' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="font-medium">{{ $personnel->user->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Téléphone</p>
                            <p class="font-medium">{{ $personnel->user->telephone ?? 'Non renseigné' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Informations professionnelles -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Informations professionnelles</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Matricule</p>
                            <p class="font-medium">{{ $personnel->matricule }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Poste</p>
                            <p class="font-medium">{{ $personnel->poste }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Service</p>
                            <p class="font-medium">{{ $personnel->service }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Rôle</p>
                            <span class="px-2 py-1 rounded-full text-xs font-semibold
                                @if($personnel->personnel_role == 'superviseur') bg-purple-100 text-purple-800
                                @elseif($personnel->personnel_role == 'comptable') bg-blue-100 text-blue-800
                                @elseif($personnel->personnel_role == 'caissier') bg-green-100 text-green-800
                                @elseif($personnel->personnel_role == 'gestionnaire') bg-orange-100 text-orange-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ $personnel->personnel_role }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Date d'embauche</p>
                            <p class="font-medium">{{ Carbon\Carbon::parse($personnel->date_embauche)->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Ancienneté</p>
                            <p class="font-medium">{{ $anciennete->y }} ans {{ $anciennete->m }} mois</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Salaire</p>
                            <p class="font-medium text-lg text-green-600">{{ number_format($personnel->salaire, 0, ',', ' ') }} USD</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Statut</p>
                            <span class="px-3 py-1 rounded-full text-sm font-semibold
                                @if($personnel->statut == 'actif') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ strtoupper($personnel->statut) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Colonne droite -->
            <div class="space-y-6">
                <!-- Actions -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Actions</h3>
                    <div class="space-y-2">
                        <a href="{{ route('superviseur.personnel.edit', $personnel->id) }}" 
                           class="w-full inline-block text-center bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition text-sm">
                            ✏️ Modifier
                        </a>
                        
                        @if($personnel->statut == 'actif')
                        <form method="POST" action="{{ route('superviseur.personnel.desactiver', $personnel->id) }}">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition text-sm"
                                    onclick="return confirm('Désactiver ce personnel ?')">
                                🔒 Désactiver
                            </button>
                        </form>
                        @else
                        <form method="POST" action="{{ route('superviseur.personnel.activer', $personnel->id) }}">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition text-sm"
                                    onclick="return confirm('Activer ce personnel ?')">
                                🔓 Activer
                            </button>
                        </form>
                        @endif
                    </div>
                </div>

                <!-- Statistiques -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Statistiques</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Réservations traitées</span>
                            <span class="font-medium">0</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Paiements validés</span>
                            <span class="font-medium">0</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Voyages supervisés</span>
                            <span class="font-medium">0</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection