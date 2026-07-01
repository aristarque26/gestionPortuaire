@extends('layouts.client')

@section('title', 'Nouvelle réservation')
@section('header', 'Nouvelle réservation')

@section('content')
<div class="container mx-auto px-4 py-6">
    {{-- Barre de progression --}}
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <div class="flex items-center">
                    <span class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-600 text-white text-sm font-bold">1</span>
                    <span class="ml-2 text-sm font-medium text-gray-700">Voyage</span>
                </div>
                <div class="h-1 bg-blue-200 rounded mt-1" style="width: 33%;"></div>
            </div>
            <div class="flex-1">
                <div class="flex items-center">
                    <span class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-600 text-white text-sm font-bold">2</span>
                    <span class="ml-2 text-sm font-medium text-gray-700">Type & Pavillon</span>
                </div>
                <div class="h-1 bg-blue-200 rounded mt-1" style="width: 33%;"></div>
            </div>
            <div class="flex-1">
                <div class="flex items-center">
                    <span class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-300 text-gray-600 text-sm font-bold">3</span>
                    <span class="ml-2 text-sm font-medium text-gray-500">Confirmation</span>
                </div>
                <div class="h-1 bg-gray-200 rounded mt-1"></div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Formulaire --}}
        <div class="lg:col-span-2">
            <form method="POST" action="{{ route('client.reservations.store') }}" id="reservationForm" novalidate>
                @csrf
                <div class="bg-white rounded-xl shadow-md p-6 space-y-6">

                    {{-- 1. Choix du voyage --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-ship mr-1"></i> Voyage
                        </label>
                        <div class="relative">
                            <input type="text" id="searchVoyage" placeholder="Rechercher un voyage (code, date...)" class="w-full border border-gray-300 rounded-lg px-3 py-2 pl-9">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                        <select name="idvoyage" id="voyageSelect" required class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1">
                            <option value="">Sélectionner un voyage</option>
                            @foreach($voyages as $voyage)
                                <option value="{{ $voyage->id }}" 
                                    data-bateau="{{ $voyage->bateau->nom ?? 'N/A' }}" 
                                    data-capacite="{{ $voyage->bateau->capacite_passager ?? 'N/A' }}"
                                    data-places-dispo="{{ $voyage->placesDisponibles() }}"
                                    data-date="{{ $voyage->date_depart->format('d/m/Y H:i') }}">
                                    {{ $voyage->code_voyage }} - {{ $voyage->date_depart->format('d/m/Y H:i') }}
                                    ({{ $voyage->placesDisponibles() }} places disponibles)
                                </option>
                            @endforeach
                        </select>
                        <div id="voyageSearchEmpty" class="text-sm text-red-500 mt-1 hidden">Aucun voyage trouvé</div>
                    </div>

                    {{-- Détails du voyage --}}
                    <div id="infosVoyage" class="p-4 bg-blue-50 rounded-lg border border-blue-200 hidden">
                        <h4 class="font-semibold text-blue-800 mb-2"><i class="fas fa-info-circle mr-1"></i> Détails du voyage</h4>
                        <div class="grid grid-cols-2 gap-2 text-sm">
                            <div><span class="font-medium">Bateau :</span> <span id="bateauNom"></span></div>
                            <div><span class="font-medium">Capacité :</span> <span id="bateauCapacite"></span> passagers</div>
                            <div><span class="font-medium">Places disponibles :</span> <span id="placesDisponibles"></span></div>
                            <div><span class="font-medium">Port :</span> <span id="portNom"></span></div>
                            <div><span class="font-medium">Quai :</span> <span id="quaiNom"></span> (n°<span id="quaiNumero"></span>)</div>
                        </div>
                    </div>

                    {{-- 2. Type de réservation --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-tag mr-1"></i> Type de réservation
                        </label>
                        <select name="type_reservation" required class="w-full border border-gray-300 rounded-lg px-3 py-2" id="typeReservationSelect">
                            <option value="passage">Passage</option>
                            <option value="cargaison">Cargaison</option>
                            <option value="mixte">Mixte</option>
                        </select>
                    </div>

                    {{-- 3. Pavillon passager --}}
                    <div id="pavillonPassagerDiv">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-user mr-1"></i> Pavillon (passager)
                        </label>
                        <select name="idpavillon_passager" class="w-full border border-gray-300 rounded-lg px-3 py-2" id="pavillonPassagerSelect">
                            <option value="">Sélectionner un pavillon</option>
                            @foreach($pavillons as $pavillon)
                                <option value="{{ $pavillon->id }}" data-prix="{{ $pavillon->prix_unitaire }}" data-capacite="{{ $pavillon->capacite_max }}">
                                    {{ $pavillon->nom }} ({{ $pavillon->classe }}) - {{ number_format($pavillon->prix_unitaire, 0, ',', ' ') }} FCFA
                                </option>
                            @endforeach
                        </select>
                        <div id="placesPavillonInfo" class="mt-2 p-2 bg-yellow-50 rounded-lg hidden">
                            <p class="text-sm text-yellow-800"><i class="fas fa-chair mr-1"></i> <span id="placesRestantesPavillon"></span> places restantes</p>
                        </div>
                    </div>

                    {{-- 4. Pavillon cargaison --}}
                    <div id="pavillonCargaisonDiv" style="display: none;">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-box mr-1"></i> Pavillon (cargaison)
                        </label>
                        <select name="idpavillon_cargaison" class="w-full border border-gray-300 rounded-lg px-3 py-2" id="pavillonCargaisonSelect">
                            <option value="">Sélectionner un pavillon</option>
                            @foreach($pavillons as $pavillon)
                                <option value="{{ $pavillon->id }}" data-prix="{{ $pavillon->prix_tonne }}">
                                    {{ $pavillon->nom }} ({{ $pavillon->classe }}) - {{ number_format($pavillon->prix_tonne, 0, ',', ' ') }} FCFA/tonne
                                </option>
                            @endforeach
                        </select>
                        <div class="grid grid-cols-2 gap-4 mt-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre de cargaisons</label>
                                <input type="number" name="nombre_cargaison" id="nombreCargaison" min="1" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Poids (tonnes)</label>
                                <input type="number" name="poids_cargaison" id="poidsCargaison" min="0.1" step="0.1" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                            </div>
                        </div>
                        <div id="tonnesPavillonInfo" class="mt-2 p-2 bg-blue-50 rounded-lg hidden">
                            <p class="text-sm text-blue-800"><i class="fas fa-weight-hanging mr-1"></i> Tonnes restantes : <span id="tonnesRestantesPavillon"></span> tonnes</p>
                        </div>
                    </div>

                    {{-- 5. Description --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-pen mr-1"></i> Description (optionnelle)
                        </label>
                        <textarea name="description" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="Informations complémentaires..."></textarea>
                    </div>

                    {{-- 6. Validation et soumission --}}
                    <div id="erreurMessage" class="p-3 bg-red-100 text-red-700 rounded-lg hidden">
                        <i class="fas fa-exclamation-circle mr-1"></i> Ce pavillon est complet pour ce voyage. Veuillez en choisir un autre.
                    </div>

                    <div class="flex justify-end space-x-3 pt-4 border-t">
                        <button type="button" id="btnAnnuler" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition">
                            <i class="fas fa-times mr-1"></i> Annuler
                        </button>
                        <button type="submit" id="btnReserver" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed">
                            <i class="fas fa-check mr-1"></i> Réserver
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Récapitulatif (colonne de droite) --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-md p-6 sticky top-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4"><i class="fas fa-file-invoice mr-1"></i> Récapitulatif</h3>
                <div id="recapContent" class="space-y-2 text-sm text-gray-700">
                    <p class="text-gray-500 italic">Sélectionnez un voyage pour voir les détails.</p>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <div class="flex justify-between text-base font-medium">
                        <span>Total estimé</span>
                        <span id="prixTotal" class="text-xl font-bold text-blue-600">0 FCFA</span>
                    </div>
                </div>
                <div id="recapLoading" class="mt-4 text-center text-gray-500 hidden">
                    <i class="fas fa-spinner fa-spin mr-1"></i> Mise à jour...
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal de confirmation --}}
<div id="confirmationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6">
        <h4 class="text-lg font-semibold text-gray-800 mb-2"><i class="fas fa-question-circle mr-1"></i> Confirmer la réservation</h4>
        <p class="text-gray-600 mb-4">Voulez-vous vraiment réserver ce voyage ?</p>
        <div class="flex justify-end space-x-3">
            <button id="modalAnnuler" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg transition">Annuler</button>
            <button id="modalConfirmer" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">Confirmer</button>
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

        // Fonction pour mettre à jour le récapitulatif
        function updateRecap() {
            const voyageId = voyageSelect.value;
            const voyage = voyagesData.find(v => v.id == voyageId);
            const type = typeReservationSelect.value;

            if (!voyageId || !voyage) {
                recapContent.innerHTML = `<p class="text-gray-500 italic">Sélectionnez un voyage pour voir les détails.</p>`;
                return;
            }

            let html = `<div class="space-y-1">
                <p><span class="font-medium">Voyage :</span> ${voyage.code_voyage} (${new Date(voyage.date_depart).toLocaleString()})</p>
                <p><span class="font-medium">Type :</span> ${type === 'passage' ? 'Passage' : type === 'cargaison' ? 'Cargaison' : 'Mixte'}</p>`;

            if (type === 'passage' || type === 'mixte') {
                const sel = pavillonPassagerSelect.options[pavillonPassagerSelect.selectedIndex];
                const nom = sel ? sel.text.split(' - ')[0] : 'Non sélectionné';
                const prix = parseFloat(sel?.dataset.prix || 0);
                html += `<p><span class="font-medium">Pavillon passager :</span> ${nom} → ${prix.toLocaleString()} FCFA</p>`;
            }

            if (type === 'cargaison' || type === 'mixte') {
                const sel = pavillonCargaisonSelect.options[pavillonCargaisonSelect.selectedIndex];
                const nom = sel ? sel.text.split(' - ')[0] : 'Non sélectionné';
                const prix = parseFloat(sel?.dataset.prix || 0);
                const poids = parseFloat(poidsCargaison?.value || 0);
                html += `<p><span class="font-medium">Pavillon cargaison :</span> ${nom} → ${prix.toLocaleString()} FCFA/tonne</p>`;
                html += `<p><span class="font-medium">Poids :</span> ${poids} tonnes → ${(prix * poids).toLocaleString()} FCFA</p>`;
            }

            html += `</div>`;
            recapContent.innerHTML = html;
            calculateTotal();
        }

        // Calcul du prix total
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

            prixTotalSpan.innerText = prix.toLocaleString() + ' FCFA';
        }

        // Vérifier disponibilité
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

        // Toggle sections selon type
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
            } else { // mixte
                pavillonPassagerDiv.style.display = 'block';
                pavillonCargaisonDiv.style.display = 'block';
                currentPavillonId = pavillonPassagerSelect.value;
            }
            updateRecap();
            checkAvailability();
        }

        // Événements
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
            if (typeReservationSelect.value === 'mixte') {
                // On garde le pavillon passager comme référence pour la disponibilité
            }
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
            // On pourrait ajouter un calcul basé sur le nombre mais pas utilisé pour le prix
        });

        // Recherche dans la liste des voyages
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

        // Annuler
        btnAnnuler.addEventListener('click', function() {
            window.location.href = "{{ route('client.dashboard') }}";
        });

        // Validation avant soumission
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            // Vérifications supplémentaires
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

            // Ouvrir la modal de confirmation
            modal.classList.remove('hidden');
        });

        // Modal - Confirmer
        modalConfirmer.addEventListener('click', function() {
            modal.classList.add('hidden');
            if (isSubmitting) return;
            isSubmitting = true;
            btnReserver.disabled = true;
            btnReserver.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Envoi...';
            form.submit();
        });

        // Modal - Annuler
        modalAnnuler.addEventListener('click', function() {
            modal.classList.add('hidden');
        });

        // Fermer la modal en cliquant à l'extérieur
        modal.addEventListener('click', function(e) {
            if (e.target === modal) modal.classList.add('hidden');
        });

        // Initialisation
        toggleSections();
        updateRecap();
    });
</script>
@endsection