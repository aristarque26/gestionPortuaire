@extends('layouts.client')

@section('title', 'Nouvelle réservation')
@section('header', 'Nouvelle réservation')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
    <div class="container mx-auto px-4 py-8 max-w-7xl">

        {{-- Barre de progression premium --}}
        <div class="mb-8">
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center justify-between relative">
                    {{-- Ligne de connexion --}}
                    <div class="absolute top-6 left-0 right-0 h-1 bg-gray-200 rounded-full -z-0 mx-16"></div>
                    <div class="absolute top-6 left-0 h-1 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full -z-0 mx-16 transition-all duration-500" style="width: 33%;"></div>
                    
                    {{-- Étape 1 --}}
                    <div class="flex flex-col items-center relative z-10 flex-1">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold shadow-lg group hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-ship"></i>
                        </div>
                        <p class="mt-3 text-sm font-semibold text-gray-800">Voyage</p>
                        <p class="text-xs text-gray-500 mt-1">Étape 1</p>
                    </div>

                    {{-- Étape 2 --}}
                    <div class="flex flex-col items-center relative z-10 flex-1">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold shadow-lg group hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-tag"></i>
                        </div>
                        <p class="mt-3 text-sm font-semibold text-gray-800">Type & Pavillon</p>
                        <p class="text-xs text-gray-500 mt-1">Étape 2</p>
                    </div>

                    {{-- Étape 3 --}}
                    <div class="flex flex-col items-center relative z-10 flex-1">
                        <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center text-gray-500 font-bold shadow-md">
                            <i class="fas fa-check"></i>
                        </div>
                        <p class="mt-3 text-sm font-semibold text-gray-500">Confirmation</p>
                        <p class="text-xs text-gray-400 mt-1">Étape 3</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Formulaire --}}
            <div class="lg:col-span-2 space-y-6">
                <form method="POST" action="{{ route('client.reservations.store') }}" id="reservationForm" novalidate>
                    @csrf

                    {{-- 1. Choix du voyage --}}
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-ship text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-800">Choix du voyage</h3>
                                <p class="text-xs text-gray-500">Sélectionnez votre prochaine aventure</p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-search text-blue-500 text-xs"></i>
                                    Rechercher un voyage
                                </label>
                                <div class="relative">
                                    <input type="text" id="searchVoyage" placeholder="Rechercher par code, date..." 
                                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 pl-11 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none transition-all bg-gray-50 hover:bg-white">
                                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                </div>
                            </div>

                            <div>
                                <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-list text-indigo-500 text-xs"></i>
                                    Voyage <span class="text-red-500">*</span>
                                </label>
                                <select name="idvoyage" id="voyageSelect" required 
                                    class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none transition-all bg-gray-50 hover:bg-white">
                                    <option value="">Sélectionner un voyage</option>
                                    @foreach($voyages as $voyage)
                                        <option value="{{ $voyage->id }}" 
                                            data-bateau="{{ $voyage->bateau->nom ?? 'N/A' }}" 
                                            data-capacite="{{ $voyage->bateau->capacite_passager ?? 'N/A' }}"
                                            data-places-dispo="{{ $voyage->placesDisponibles() }}"
                                            data-date="{{ $voyage->date_depart->format('d/m/Y H:i') }}">
                                            {{ $voyage->code_voyage }} - {{ $voyage->date_depart->format('d/m/Y H:i') }}
                                            ({{ $voyage->placesDisponibles() }} places)
                                        </option>
                                    @endforeach
                                </select>
                                <div id="voyageSearchEmpty" class="text-sm text-red-500 mt-2 hidden flex items-center gap-1">
                                    <i class="fas fa-exclamation-circle"></i> Aucun voyage trouvé
                                </div>
                            </div>

                            {{-- Détails du voyage --}}
                            <div id="infosVoyage" class="p-5 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border-2 border-blue-200 hidden">
                                <h4 class="font-bold text-blue-800 mb-3 flex items-center gap-2">
                                    <i class="fas fa-info-circle"></i> Détails du voyage
                                </h4>
                                <div class="grid grid-cols-2 gap-3 text-sm">
                                    <div class="bg-white rounded-lg p-3 shadow-sm">
                                        <p class="text-xs text-gray-500 mb-1 flex items-center gap-1">
                                            <i class="fas fa-ship text-blue-500"></i> Bateau
                                        </p>
                                        <p class="font-semibold text-gray-800"><span id="bateauNom"></span></p>
                                    </div>
                                    <div class="bg-white rounded-lg p-3 shadow-sm">
                                        <p class="text-xs text-gray-500 mb-1 flex items-center gap-1">
                                            <i class="fas fa-users text-indigo-500"></i> Capacité
                                        </p>
                                        <p class="font-semibold text-gray-800"><span id="bateauCapacite"></span> passagers</p>
                                    </div>
                                    <div class="bg-white rounded-lg p-3 shadow-sm">
                                        <p class="text-xs text-gray-500 mb-1 flex items-center gap-1">
                                            <i class="fas fa-chair text-emerald-500"></i> Places disponibles
                                        </p>
                                        <p class="font-semibold text-gray-800"><span id="placesDisponibles"></span></p>
                                    </div>
                                    <div class="bg-white rounded-lg p-3 shadow-sm">
                                        <p class="text-xs text-gray-500 mb-1 flex items-center gap-1">
                                            <i class="fas fa-anchor text-purple-500"></i> Port
                                        </p>
                                        <p class="font-semibold text-gray-800"><span id="portNom"></span></p>
                                    </div>
                                    <div class="bg-white rounded-lg p-3 shadow-sm col-span-2">
                                        <p class="text-xs text-gray-500 mb-1 flex items-center gap-1">
                                            <i class="fas fa-map-marker-alt text-red-500"></i> Quai
                                        </p>
                                        <p class="font-semibold text-gray-800"><span id="quaiNom"></span> (n°<span id="quaiNumero"></span>)</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 2. Type de réservation --}}
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-tag text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-800">Type de réservation</h3>
                                <p class="text-xs text-gray-500">Choisissez le type de service</p>
                            </div>
                        </div>

                        <div>
                            <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-layer-group text-purple-500 text-xs"></i>
                                Type <span class="text-red-500">*</span>
                            </label>
                            <select name="type_reservation" required class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-purple-400 focus:border-purple-400 outline-none transition-all bg-gray-50 hover:bg-white" id="typeReservationSelect">
                                <option value="passage">Passage</option>
                                <option value="cargaison">Cargaison</option>
                                <option value="mixte">Mixte</option>
                            </select>
                        </div>
                    </div>

                    {{-- 3. Pavillon passager --}}
                    <div id="pavillonPassagerDiv" class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-user text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-800">Pavillon passager</h3>
                                <p class="text-xs text-gray-500">Sélectionnez votre classe</p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-user-tag text-emerald-500 text-xs"></i>
                                    Pavillon
                                </label>
                                <select name="idpavillon_passager" class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400 outline-none transition-all bg-gray-50 hover:bg-white" id="pavillonPassagerSelect">
                                    <option value="">Sélectionner un pavillon</option>
                                    @foreach($pavillons as $pavillon)
                                        <option value="{{ $pavillon->id }}" data-prix="{{ $pavillon->prix_unitaire }}" data-capacite="{{ $pavillon->capacite_max }}">
                                            {{ $pavillon->nom }} ({{ $pavillon->classe }}) - {{ number_format($pavillon->prix_unitaire, 0, ',', ' ') }} FCFA
                                        </option>
                                    @endforeach
                                </select>
                                <div id="placesPavillonInfo" class="mt-3 p-4 bg-gradient-to-r from-amber-50 to-yellow-50 rounded-xl border-2 border-amber-200 hidden">
                                    <p class="text-sm text-amber-800 flex items-center gap-2">
                                        <i class="fas fa-chair text-amber-600"></i>
                                        <span class="font-semibold"><span id="placesRestantesPavillon"></span> places restantes</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 4. Pavillon cargaison --}}
                    <div id="pavillonCargaisonDiv" class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100" style="display: none;">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-box text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-800">Pavillon cargaison</h3>
                                <p class="text-xs text-gray-500">Détails de votre expédition</p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-boxes text-orange-500 text-xs"></i>
                                    Pavillon
                                </label>
                                <select name="idpavillon_cargaison" class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-orange-400 focus:border-orange-400 outline-none transition-all bg-gray-50 hover:bg-white" id="pavillonCargaisonSelect">
                                    <option value="">Sélectionner un pavillon</option>
                                    @foreach($pavillons as $pavillon)
                                        <option value="{{ $pavillon->id }}" data-prix="{{ $pavillon->prix_tonne }}">
                                            {{ $pavillon->nom }} ({{ $pavillon->classe }}) - {{ number_format($pavillon->prix_tonne, 0, ',', ' ') }} FCFA/tonne
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-hashtag text-blue-500 text-xs"></i>
                                        Nombre de cargaisons
                                    </label>
                                    <input type="number" name="nombre_cargaison" id="nombreCargaison" min="1" 
                                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none transition-all bg-gray-50 hover:bg-white">
                                </div>
                                <div>
                                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-weight-hanging text-purple-500 text-xs"></i>
                                        Poids (tonnes)
                                    </label>
                                    <input type="number" name="poids_cargaison" id="poidsCargaison" min="0.1" step="0.1" 
                                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-purple-400 focus:border-purple-400 outline-none transition-all bg-gray-50 hover:bg-white">
                                </div>
                            </div>

                            <div id="tonnesPavillonInfo" class="p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border-2 border-blue-200 hidden">
                                <p class="text-sm text-blue-800 flex items-center gap-2">
                                    <i class="fas fa-weight-hanging text-blue-600"></i>
                                    <span class="font-semibold">Tonnes restantes : <span id="tonnesRestantesPavillon"></span> tonnes</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- 5. Description --}}
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-12 h-12 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-pen text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-800">Description</h3>
                                <p class="text-xs text-gray-500">Informations complémentaires (optionnel)</p>
                            </div>
                        </div>

                        <div>
                            <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-comment-dots text-cyan-500 text-xs"></i>
                                Description
                            </label>
                            <textarea name="description" rows="3" 
                                class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-cyan-400 focus:border-cyan-400 outline-none transition-all bg-gray-50 hover:bg-white resize-none" 
                                placeholder="Informations complémentaires..."></textarea>
                        </div>
                    </div>

                    {{-- Message d'erreur --}}
                    <div id="erreurMessage" class="p-5 bg-gradient-to-r from-red-50 to-pink-50 border-l-4 border-red-500 rounded-2xl shadow-md hidden flex items-start gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-pink-600 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-white"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-red-800">Attention !</p>
                            <p class="text-sm text-red-700 mt-1">Ce pavillon est complet pour ce voyage. Veuillez en choisir un autre.</p>
                        </div>
                    </div>

                    {{-- Boutons --}}
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                        <div class="flex flex-wrap justify-end gap-3">
                            <button type="button" id="btnAnnuler" 
                                class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl transition-all duration-300 flex items-center gap-2 font-semibold text-sm shadow-sm hover:shadow-md">
                                <i class="fas fa-times"></i> Annuler
                            </button>
                            <button type="submit" id="btnReserver" 
                                class="px-6 py-2.5 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white rounded-xl transition-all duration-300 flex items-center gap-2 font-semibold text-sm shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
                                <i class="fas fa-check"></i> Réserver
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Récapitulatif (colonne de droite) --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 sticky top-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-file-invoice text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Récapitulatif</h3>
                            <p class="text-xs text-gray-500">Détails de votre réservation</p>
                        </div>
                    </div>

                    <div id="recapContent" class="space-y-3 text-sm text-gray-700 mb-6">
                        <div class="p-8 text-center">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-clipboard-list text-3xl text-gray-300"></i>
                            </div>
                            <p class="text-gray-500 italic">Sélectionnez un voyage pour voir les détails.</p>
                        </div>
                    </div>

                    <div class="pt-6 border-t-2 border-gray-100">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-semibold text-gray-600">Total estimé</span>
                            <span id="prixTotal" class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">0 FCFA</span>
                        </div>
                    </div>

                    <div id="recapLoading" class="mt-4 text-center text-gray-500 hidden flex items-center justify-center gap-2">
                        <i class="fas fa-spinner fa-spin"></i>
                        <span class="text-sm">Mise à jour...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal de confirmation --}}
<div id="confirmationModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8 mx-4 transform transition-all">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                <i class="fas fa-question-circle text-white text-xl"></i>
            </div>
            <div>
                <h4 class="text-xl font-bold text-gray-800">Confirmer la réservation</h4>
                <p class="text-xs text-gray-500">Vérifiez les détails avant de confirmer</p>
            </div>
        </div>
        
        <p class="text-gray-600 mb-6">Voulez-vous vraiment réserver ce voyage ? Cette action ne peut pas être annulée.</p>
        
        <div class="flex justify-end gap-3">
            <button id="modalAnnuler" 
                class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl transition-all duration-300 font-semibold text-sm shadow-sm hover:shadow-md">
                Annuler
            </button>
            <button id="modalConfirmer" 
                class="px-5 py-2.5 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white rounded-xl transition-all duration-300 font-semibold text-sm shadow-md hover:shadow-lg">
                Confirmer
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const voyagesData = @json($voyages->load('bateau', 'trajets.ports.quais'));
        const voyageSelect = document.getElementById('voyageSelect');
        const searchVoyage = document.getElementById('searchVoyage');
        const typeReservationSelect = document.getElementById('typeReservationSelect');
        const pavillonPassagerSelect = document.getElementById('pavillonPassagerSelect');
        const pavillonCargaisonSelect = document.getElementById('pavillonCargaisonSelect');
        const poidsCargaison = document.getElementById('poidsCargaison');
        const nombreCargaison = document.getElementById('nombreCargaison');
        const prixTotalSpan = document.getElementById('prixTotal');
        const infosDiv = document.getElementById('infosVoyage');
        const placesPavillonInfo = document.getElementById('placesPavillonInfo');
        const placesRestantesSpan = document.getElementById('placesRestantesPavillon');
        const tonnesPavillonInfo = document.getElementById('tonnesPavillonInfo');
        const tonnesRestantesSpan = document.getElementById('tonnesRestantesPavillon');
        const erreurMessage = document.getElementById('erreurMessage');
        const btnReserver = document.getElementById('btnReserver');
        const btnAnnuler = document.getElementById('btnAnnuler');
        const pavillonPassagerDiv = document.getElementById('pavillonPassagerDiv');
        const pavillonCargaisonDiv = document.getElementById('pavillonCargaisonDiv');
        const recapContent = document.getElementById('recapContent');
        const recapLoading = document.getElementById('recapLoading');
        const modal = document.getElementById('confirmationModal');
        const modalConfirmer = document.getElementById('modalConfirmer');
        const modalAnnuler = document.getElementById('modalAnnuler');
        const form = document.getElementById('reservationForm');
        const voyageSearchEmpty = document.getElementById('voyageSearchEmpty');

        let currentPavillonId = null;
        let isSubmitting = false;

        function updateRecap() {
            const voyageId = voyageSelect.value;
            const voyage = voyagesData.find(v => v.id == voyageId);
            const type = typeReservationSelect.value;

            if (!voyageId || !voyage) {
                recapContent.innerHTML = `
                    <div class="p-8 text-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-clipboard-list text-3xl text-gray-300"></i>
                        </div>
                        <p class="text-gray-500 italic">Sélectionnez un voyage pour voir les détails.</p>
                    </div>`;
                return;
            }

            let html = `<div class="space-y-3">`;
            
            html += `
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-3 border border-blue-200">
                    <p class="text-xs text-gray-500 mb-1 flex items-center gap-1">
                        <i class="fas fa-ship text-blue-500"></i> Voyage
                    </p>
                    <p class="font-semibold text-gray-800">${voyage.code_voyage}</p>
                    <p class="text-xs text-gray-600 mt-1">${new Date(voyage.date_depart).toLocaleString('fr-FR')}</p>
                </div>`;

            html += `
                <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-3 border border-purple-200">
                    <p class="text-xs text-gray-500 mb-1 flex items-center gap-1">
                        <i class="fas fa-tag text-purple-500"></i> Type
                    </p>
                    <p class="font-semibold text-gray-800">${type === 'passage' ? 'Passage' : type === 'cargaison' ? 'Cargaison' : 'Mixte'}</p>
                </div>`;

            if (type === 'passage' || type === 'mixte') {
                const sel = pavillonPassagerSelect.options[pavillonPassagerSelect.selectedIndex];
                const nom = sel ? sel.text.split(' - ')[0] : 'Non sélectionné';
                const prix = parseFloat(sel?.dataset.prix || 0);
                html += `
                    <div class="bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl p-3 border border-emerald-200">
                        <p class="text-xs text-gray-500 mb-1 flex items-center gap-1">
                            <i class="fas fa-user text-emerald-500"></i> Pavillon passager
                        </p>
                        <p class="font-semibold text-gray-800">${nom}</p>
                        <p class="text-sm text-emerald-600 font-bold mt-1">${prix.toLocaleString('fr-FR')} FCFA</p>
                    </div>`;
            }

            if (type === 'cargaison' || type === 'mixte') {
                const sel = pavillonCargaisonSelect.options[pavillonCargaisonSelect.selectedIndex];
                const nom = sel ? sel.text.split(' - ')[0] : 'Non sélectionné';
                const prix = parseFloat(sel?.dataset.prix || 0);
                const poids = parseFloat(poidsCargaison?.value || 0);
                html += `
                    <div class="bg-gradient-to-r from-orange-50 to-red-50 rounded-xl p-3 border border-orange-200">
                        <p class="text-xs text-gray-500 mb-1 flex items-center gap-1">
                            <i class="fas fa-box text-orange-500"></i> Pavillon cargaison
                        </p>
                        <p class="font-semibold text-gray-800">${nom}</p>
                        <p class="text-sm text-orange-600 font-bold mt-1">${prix.toLocaleString('fr-FR')} FCFA/tonne</p>
                        <p class="text-xs text-gray-600 mt-1">Poids : ${poids} tonnes → ${(prix * poids).toLocaleString('fr-FR')} FCFA</p>
                    </div>`;
            }

            html += `</div>`;
            recapContent.innerHTML = html;
            calculateTotal();
        }

        function calculateTotal() {
            let prix = 0;
            const type = typeReservationSelect.value;

            if (type === 'passage' || type === 'mixte') {
                const prixUnitaire = parseFloat(pavillonPassagerSelect.options[pavillonPassagerSelect.selectedIndex]?.dataset.prix || 0);
                prix += prixUnitaire;
            }

            if (type === 'cargaison' || type === 'mixte') {
                const prixUnitaire = parseFloat(pavillonCargaisonSelect.options[pavillonCargaisonSelect.selectedIndex]?.dataset.prix || 0);
                const poids = parseFloat(poidsCargaison?.value || 0);
                prix += prixUnitaire * poids;
            }

            prixTotalSpan.innerText = prix.toLocaleString('fr-FR') + ' FCFA';
        }

        function checkAvailability() {
            const voyageId = voyageSelect.value;
            const pavillonId = currentPavillonId;
            if (!voyageId || !pavillonId) {
                placesPavillonInfo.classList.add('hidden');
                tonnesPavillonInfo.classList.add('hidden');
                erreurMessage.classList.add('hidden');
                btnReserver.disabled = false;
                return;
            }

            recapLoading.classList.remove('hidden');
            fetch(`/api/verifier-disponibilite-pavillon?voyage=${voyageId}&pavillon=${pavillonId}`)
                .then(response => response.json())
                .then(data => {
                    recapLoading.classList.add('hidden');
                    placesRestantesSpan.innerText = data.places_restantes;
                    placesPavillonInfo.classList.remove('hidden');
                    tonnesRestantesSpan.innerText = data.tonnes_restantes;
                    tonnesPavillonInfo.classList.remove('hidden');

                    if (data.places_restantes <= 0) {
                        erreurMessage.classList.remove('hidden');
                        btnReserver.disabled = true;
                    } else {
                        erreurMessage.classList.add('hidden');
                        btnReserver.disabled = false;
                    }
                })
                .catch(() => {
                    recapLoading.classList.add('hidden');
                });
        }

        function toggleSections() {
            const type = typeReservationSelect.value;

            if (type === 'passage') {
                pavillonPassagerDiv.style.display = 'block';
                pavillonCargaisonDiv.style.display = 'none';
                currentPavillonId = pavillonPassagerSelect.value;
            } else if (type === 'cargaison') {
                pavillonPassagerDiv.style.display = 'none';
                pavillonCargaisonDiv.style.display = 'block';
                currentPavillonId = pavillonCargaisonSelect.value;
            } else {
                pavillonPassagerDiv.style.display = 'block';
                pavillonCargaisonDiv.style.display = 'block';
                currentPavillonId = pavillonPassagerSelect.value;
            }
            updateRecap();
            checkAvailability();
        }

        voyageSelect.addEventListener('change', function() {
            const voyageId = this.value;
            const voyage = voyagesData.find(v => v.id == voyageId);
            if (voyage) {
                document.getElementById('bateauNom').innerText = voyage.bateau?.nom || 'N/A';
                document.getElementById('bateauCapacite').innerText = voyage.bateau?.capacite_passager || 'N/A';
                document.getElementById('placesDisponibles').innerText = voyage.places_disponibles ?? 'N/A';
                const premierTrajet = voyage.trajets?.[0];
                const premierPort = premierTrajet?.ports?.[0];
                const premierQuai = premierPort?.quais?.[0];
                document.getElementById('portNom').innerText = premierPort?.nom || 'N/A';
                document.getElementById('quaiNom').innerText = premierQuai?.nom || 'N/A';
                document.getElementById('quaiNumero').innerText = premierQuai?.numero || 'N/A';
                infosDiv.style.display = 'block';
            } else {
                infosDiv.style.display = 'none';
            }
            updateRecap();
            checkAvailability();
        });

        typeReservationSelect.addEventListener('change', function() {
            toggleSections();
        });

        pavillonPassagerSelect.addEventListener('change', function() {
            currentPavillonId = this.value;
            updateRecap();
            checkAvailability();
        });

        pavillonCargaisonSelect.addEventListener('change', function() {
            if (typeReservationSelect.value === 'cargaison') {
                currentPavillonId = this.value;
                checkAvailability();
            }
            updateRecap();
        });

        poidsCargaison.addEventListener('input', function() {
            updateRecap();
        });

        nombreCargaison.addEventListener('input', function() {
        });

        searchVoyage.addEventListener('input', function() {
            const filter = this.value.toLowerCase();
            const options = voyageSelect.options;
            let found = false;
            for (let i = 0; i < options.length; i++) {
                const text = options[i].text.toLowerCase();
                const match = text.includes(filter);
                options[i].style.display = match ? '' : 'none';
                if (match && options[i].value !== '') found = true;
            }
            voyageSearchEmpty.classList.toggle('hidden', found);
        });

        btnAnnuler.addEventListener('click', function() {
            window.location.href = "{{ route('client.dashboard') }}";
        });

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const type = typeReservationSelect.value;
            if (type === 'cargaison' || type === 'mixte') {
                const poids = parseFloat(poidsCargaison.value);
                if (!poids || poids <= 0) {
                    alert('Veuillez saisir un poids valide.');
                    poidsCargaison.focus();
                    return;
                }
            }
            if (type === 'mixte' && !pavillonPassagerSelect.value) {
                alert('Veuillez sélectionner un pavillon passager.');
                pavillonPassagerSelect.focus();
                return;
            }
            if (type === 'passage' && !pavillonPassagerSelect.value) {
                alert('Veuillez sélectionner un pavillon.');
                pavillonPassagerSelect.focus();
                return;
            }
            if (type === 'cargaison' && !pavillonCargaisonSelect.value) {
                alert('Veuillez sélectionner un pavillon cargaison.');
                pavillonCargaisonSelect.focus();
                return;
            }

            modal.classList.remove('hidden');
        });

        modalConfirmer.addEventListener('click', function() {
            modal.classList.add('hidden');
            if (isSubmitting) return;
            isSubmitting = true;
            btnReserver.disabled = true;
            btnReserver.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Envoi...';
            form.submit();
        });

        modalAnnuler.addEventListener('click', function() {
            modal.classList.add('hidden');
        });

        modal.addEventListener('click', function(e) {
            if (e.target === modal) modal.classList.add('hidden');
        });

        toggleSections();
        updateRecap();
    });
</script>
@endsection