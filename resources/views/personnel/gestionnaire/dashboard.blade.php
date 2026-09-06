<!-- resources/views/personnel/gestionnaire/dashboard.blade.php -->
@extends('layouts.app')

@section('content')
<div class="flex">
    @include('superviseur.layouts.sidebar')
    
    <div class="flex-1 p-6 bg-gray-100 min-h-screen">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Dashboard Gestionnaire</h1>
            <div class="text-sm text-gray-500">{{ now()->format('d/m/Y H:i') }}</div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Bienvenue, {{ $user->name }} {{ $user->prenom }} !</h2>
            <p class="text-gray-600">Gestionnaire - {{ $user->personnel->poste ?? 'N/A' }}</p>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                <p class="text-sm text-gray-500">Voyages prévus</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['voyages_prevus'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
                <p class="text-sm text-gray-500">En cours</p>
                <p class="text-2xl font-bold text-green-600">{{ $stats['voyages_en_cours'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
                <p class="text-sm text-gray-500">Bateaux en service</p>
                <p class="text-2xl font-bold text-purple-600">{{ $stats['bateaux_service'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
                <p class="text-sm text-gray-500">Quais libres</p>
                <p class="text-2xl font-bold text-yellow-600">{{ $stats['quais_libres'] }}</p>
            </div>
        </div>

        <!-- Prochains voyages -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Prochains voyages</h3>
                <div class="space-y-3">
                    @forelse($prochainsVoyages as $voyage)
                    <div class="flex justify-between items-center border-b pb-2">
                        <div>
                            <p class="font-medium">{{ $voyage->code_voyage }}</p>
                            <p class="text-sm text-gray-500">{{ $voyage->bateau->nom ?? 'N/A' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold">{{ Carbon\Carbon::parse($voyage->date_depart)->format('d/m/Y H:i') }}</p>
                            <span class="text-xs px-2 py-1 rounded-full bg-blue-100 text-blue-800">Prévu</span>
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-gray-500">Aucun voyage prévu</p>
                    @endforelse
                </div>
            </div>

            <!-- Bateaux en maintenance -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Bateaux en maintenance</h3>
                <div class="space-y-3">
                    @forelse($bateauxMaintenance as $bateau)
                    <div class="flex justify-between items-center border-b pb-2">
                        <div>
                            <p class="font-medium">{{ $bateau->nom }}</p>
                            <p class="text-sm text-gray-500">{{ $bateau->immatriculation }}</p>
                        </div>
                        <span class="text-xs px-2 py-1 rounded-full bg-yellow-100 text-yellow-800">Maintenance</span>
                    </div>
                    @empty
                    <p class="text-center text-gray-500">Aucun bateau en maintenance</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection