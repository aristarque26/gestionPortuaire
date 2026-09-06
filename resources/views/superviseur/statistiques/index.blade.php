<!-- resources/views/superviseur/statistiques/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="flex">
    @include('superviseur.layouts.sidebar')
    
    <div class="flex-1 p-6 bg-gray-100 min-h-screen">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Tableau de bord des statistiques</h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Statistiques financières -->
            <a href="{{ route('superviseur.statistiques.financieres') }}" 
               class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition group">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-green-100 p-3 rounded-full">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <svg class="w-6 h-6 text-gray-400 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-800">Statistiques financières</h3>
                <p class="text-sm text-gray-500 mt-1">CA, paiements, tendances</p>
            </a>

            <!-- Statistiques des réservations -->
            <a href="{{ route('superviseur.statistiques.reservations') }}" 
               class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition group">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-blue-100 p-3 rounded-full">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <svg class="w-6 h-6 text-gray-400 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-800">Statistiques des réservations</h3>
                <p class="text-sm text-gray-500 mt-1">Volume, statuts, évolution</p>
            </a>

            <!-- Statistiques du personnel -->
            <a href="{{ route('superviseur.statistiques.personnel') }}" 
               class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition group">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-purple-100 p-3 rounded-full">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <svg class="w-6 h-6 text-gray-400 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-800">Statistiques du personnel</h3>
                <p class="text-sm text-gray-500 mt-1">Effectifs, salaires, répartition</p>
            </a>
        </div>
    </div>
</div>
@endsection