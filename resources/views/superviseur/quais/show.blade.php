<!-- resources/views/superviseur/quais/show.blade.php -->
@extends('layouts.app')

@section('content')
<div class="flex">
    @include('superviseur.layouts.sidebar')
    
    <div class="flex-1 p-6 bg-gray-100 min-h-screen">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Détail du quai</h1>
                <p class="text-sm text-gray-500">{{ $quai->nom }} - Port: {{ $quai->port->nom ?? 'N/A' }}</p>
            </div>
            <a href="{{ route('superviseur.quais.index') }}" class="text-blue-600 hover:underline">
                ← Retour à la liste
            </a>
        </div>

        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
            {{ session('success') }}
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Colonne gauche -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Informations -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Informations du quai</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Nom</p>
                            <p class="font-medium">{{ $quai->nom }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Numéro</p>
                            <p class="font-medium">{{ $quai->numero }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Port</p>
                            <p class="font-medium">{{ $quai->port->nom ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Type</p>
                            <span class="px-2 py-1 rounded-full text-xs font-semibold
                                @if($quai->type_quai == 'passager') bg-blue-100 text-blue-800
                                @elseif($quai->type_quai == 'cargaison') bg-orange-100 text-orange-800
                                @else bg-purple-100 text-purple-800 @endif">
                                {{ $quai->type_quai }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Capacité</p>
                            <p class="font-medium">{{ $quai->capacite }} places</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Statut</p>
                            <span class="px-3 py-1 rounded-full text-sm font-semibold
                                @if($quai->statut == 'libre') bg-green-100 text-green-800
                                @elseif($quai->statut == 'occupe') bg-red-100 text-red-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                {{ strtoupper($quai->statut) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Bateaux assignés -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Bateaux assignés</h3>
                    @if($quai->bateaux->count() > 0)
                    <div class="space-y-2">
                        @foreach($quai->bateaux as $bateau)
                        <div class="flex justify-between items-center border-b pb-2">
                            <div>
                                <p class="font-medium">{{ $bateau->nom }}</p>
                                <p class="text-xs text-gray-500">{{ $bateau->immatriculation }} - {{ $bateau->type }}</p>
                            </div>
                            <span class="text-xs px-2 py-1 rounded-full
                                @if($bateau->statut == 'en_service') bg-green-100 text-green-800
                                @elseif($bateau->statut == 'en_maintenance') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ $bateau->statut }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-gray-500 text-sm">Aucun bateau assigné à ce quai.</p>
                    @endif
                </div>
            </div>

            <!-- Colonne droite -->
            <div class="space-y-6">
                <!-- Actions -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Actions</h3>
                    <div class="space-y-2">
                        <form method="POST" action="{{ route('superviseur.quais.statut', $quai->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="space-y-2">
                                <select name="statut" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 mb-2">
                                    <option value="libre" {{ $quai->statut == 'libre' ? 'selected' : '' }}>Libre</option>
                                    <option value="occupe" {{ $quai->statut == 'occupe' ? 'selected' : '' }}>Occupé</option>
                                    <option value="maintenance" {{ $quai->statut == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                </select>
                                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition text-sm">
                                    Changer le statut
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Statistiques -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Statistiques</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Bateaux assignés</span>
                            <span class="font-medium">{{ $quai->bateaux->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Temps d'occupation</span>
                            <span class="font-medium">--</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection