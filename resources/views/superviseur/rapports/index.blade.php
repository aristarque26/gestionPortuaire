<!-- resources/views/superviseur/rapports/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="flex">
    @include('superviseur.layouts.sidebar')
    
    <div class="flex-1 p-6 bg-gray-100 min-h-screen">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Génération de rapports</h1>

        <div class="bg-white rounded-lg shadow-md p-6 max-w-2xl">
            <form method="POST" action="{{ route('superviseur.rapports.generer') }}" id="rapportForm">
                @csrf

                <div class="space-y-4">
                    <!-- Type de rapport -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type de rapport *</label>
                        <select name="type" id="typeRapport" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="">Sélectionner...</option>
                            <option value="reservations">Réservations</option>
                            <option value="paiements">Paiements</option>
                            <option value="voyages">Voyages</option>
                            <option value="personnel">Personnel</option>
                        </select>
                    </div>

                    <!-- Période -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Période *</label>
                        <select name="periode" id="periode" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="aujourdhui">Aujourd'hui</option>
                            <option value="semaine">Cette semaine</option>
                            <option value="mois" selected>Ce mois</option>
                            <option value="trimestre">Ce trimestre</option>
                            <option value="annee">Cette année</option>
                            <option value="personnalise">Personnalisée</option>
                        </select>
                    </div>

                    <!-- Dates personnalisées -->
                    <div id="datesPersonnalisees" class="grid grid-cols-2 gap-4 hidden">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date début *</label>
                            <input type="date" name="date_debut" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date fin *</label>
                            <input type="date" name="date_fin" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>

                    <!-- Format -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Format *</label>
                        <div class="grid grid-cols-3 gap-4">
                            <label class="flex items-center space-x-2 p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="format" value="pdf" checked>
                                <span>
                                    <svg class="w-5 h-5 text-red-600 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                    PDF
                                </span>
                            </label>
                            <label class="flex items-center space-x-2 p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="format" value="excel">
                                <span>
                                    <svg class="w-5 h-5 text-green-600 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                                    </svg>
                                    Excel
                                </span>
                            </label>
                            <label class="flex items-center space-x-2 p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="format" value="csv">
                                <span>
                                    <svg class="w-5 h-5 text-blue-600 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                    CSV
                                </span>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition font-semibold">
                        Générer le rapport
                    </button>
                </div>
            </form>
        </div>

        <!-- Historique des rapports -->
        <div class="bg-white rounded-lg shadow-md p-6 mt-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Rapports récents</h3>
            <p class="text-sm text-gray-500 text-center py-4">
                Les rapports générés apparaîtront ici.
            </p>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('periode').addEventListener('change', function() {
        const div = document.getElementById('datesPersonnalisees');
        if (this.value === 'personnalise') {
            div.classList.remove('hidden');
        } else {
            div.classList.add('hidden');
        }
    });
</script>
@endpush
@endsection