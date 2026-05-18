@extends('layouts.client')

@section('title', 'Nouvelle réservation')
@section('header', 'Nouvelle réservation')

@section('content')
<div class="bg-white rounded-xl shadow-md p-6">
    <form method="POST" action="{{ route('client.reservations.store') }}" id="reservationForm">
        @csrf

        {{-- 1. Choix du voyage --}}
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Voyage</label>
            <select name="idvoyage" required class="w-full border border-gray-300 rounded-lg px-3 py-2" id="voyageSelect">
                <option value="">Sélectionner un voyage</option>
                @foreach($voyages as $voyage)
                    <option value="{{ $voyage->id }}" 
                        data-bateau="{{ $voyage->bateau->nom ?? 'N/A' }}" 
                        data-capacite="{{ $voyage->bateau->capacite_passager ?? 'N/A' }}"
                        data-places-dispo="{{ $voyage->placesDisponibles() }}">
                        {{ $voyage->code_voyage }} - {{ $voyage->date_depart->format('d/m/Y H:i') }}
                        ({{ $voyage->placesDisponibles() }} places disponibles)
                    </option>
                @endforeach
            </select>
        </div>

        {{-- 2. Infos dynamiques --}}
        <div id="infosVoyage" class="mb-4 p-3 bg-gray-100 rounded-lg" style="display: none;">
            <h4 class="font-semibold text-gray-700 mb-2">🚢 Détails du voyage</h4>
            <p><strong>Bateau :</strong> <span id="bateauNom"></span> (Capacité : <span id="bateauCapacite"></span> passagers)</p>
            <p><strong>Places disponibles :</strong> <span id="placesDisponibles"></span></p>
            <p><strong>Port :</strong> <span id="portNom"></span></p>
            <p><strong>Quai :</strong> <span id="quaiNom"></span> (n°<span id="quaiNumero"></span>)</p>
        </div>

        {{-- 3. Type de réservation --}}
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Type de réservation</label>
            <select name="type_reservation" required class="w-full border border-gray-300 rounded-lg px-3 py-2" id="typeReservationSelect">
                <option value="passage">Passage</option>
                <option value="cargaison">Cargaison</option>
                <option value="mixte">Mixte</option>
            </select>
        </div>

        {{-- 4. Pavillon passager (visible pour passage ou mixte) --}}
        <div class="mb-4" id="pavillonPassagerDiv">
            <label class="block text-sm font-medium text-gray-700 mb-1">Pavillon (passager)</label>
            <select name="idpavillon_passager" class="w-full border border-gray-300 rounded-lg px-3 py-2" id="pavillonPassagerSelect">
                <option value="">Sélectionner un pavillon</option>
                @foreach($pavillons as $pavillon)
                    <option value="{{ $pavillon->id }}" data-prix="{{ $pavillon->prix_unitaire }}" data-capacite="{{ $pavillon->capacite_max }}">
                        {{ $pavillon->nom }} ({{ $pavillon->classe }}) - {{ number_format($pavillon->prix_unitaire, 0, ',', ' ') }} FCFA
                    </option>
                @endforeach
            </select>
        </div>

        {{-- 5. Pavillon cargaison + poids (visible pour cargaison ou mixte) --}}
        <div class="mb-4" id="pavillonCargaisonDiv" style="display: none;">
            <label class="block text-sm font-medium text-gray-700 mb-1">Pavillon (cargaison)</label>
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
                    <input type="number" name="nombre_cargaison" id="nombreCargaison" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Poids (tonnes)</label>
                    <input type="number" name="poids_cargaison" id="poidsCargaison" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
            </div>
        </div>

        {{-- 6. Places restantes pour le pavillon passager --}}
        <div id="placesPavillonInfo" class="mb-4 p-2 bg-yellow-50 rounded-lg hidden">
            <p class="text-sm text-yellow-800">🪑 <span id="placesRestantesPavillon"></span> places restantes pour ce pavillon</p>
        </div>

        {{-- 7. Tonnes restantes pour le pavillon cargaison --}}
        <div id="tonnesPavillonInfo" class="mb-4 p-2 bg-blue-50 rounded-lg hidden">
            <p class="text-sm text-blue-800">⚖️ Tonnes restantes : <span id="tonnesRestantesPavillon"></span> tonnes</p>
        </div>

        {{-- 8. Description --}}
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Description (optionnelle)</label>
            <textarea name="description" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2"></textarea>
        </div>

        {{-- 9. RÉCAPITULATIF AVANT VALIDATION --}}
        <div id="recapitulatif" class="mb-4 p-4 bg-green-50 rounded-lg border border-green-200 hidden">
            <h4 class="font-semibold text-green-800 mb-2">📋 Récapitulatif de votre réservation</h4>
            <div id="recapContent" class="text-sm text-green-700 space-y-1"></div>
        </div>

        {{-- 10. Prix total --}}
        <div class="mb-4 p-3 bg-gray-100 rounded-lg">
            <label class="block text-sm font-medium text-gray-700 mb-1">💰 Prix total estimé</label>
            <p id="prixTotal" class="text-2xl font-bold text-blue-600">0 FCFA</p>
        </div>

        {{-- 11. Message d'alerte et bouton --}}
        <div id="erreurMessage" class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg hidden">
            ⚠️ Ce pavillon est complet pour ce voyage. Veuillez en choisir un autre.
        </div>

        <div class="flex justify-end space-x-3">
            <button type="button" id="btnAnnuler" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                Annuler
            </button>
            <button type="submit" id="btnReserver" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                Réserver
            </button>
        </div>
    </form>
</div>

<script>
    const voyagesData = @json($voyages->load('bateau', 'trajets.ports.quais'));
    const voyageSelect = document.getElementById('voyageSelect');
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
    const recapitulatifDiv = document.getElementById('recapitulatif');
    const recapContent = document.getElementById('recapContent');

    let currentPavillonId = null;

    // Mise à jour des infos voyage
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
        mettreAJourRecapitulatif();
        verifierDisponibilite();
    });

    // Afficher/masquer les sections selon le type de réservation
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
        } else if (type === 'mixte') {
            pavillonPassagerDiv.style.display = 'block';
            pavillonCargaisonDiv.style.display = 'block';
            currentPavillonId = pavillonPassagerSelect.value;
        }
        mettreAJourRecapitulatif();
        verifierDisponibilite();
        calculerPrix();
    }

    // Mettre à jour le récapitulatif
    function mettreAJourRecapitulatif() {
        const voyageId = voyageSelect.value;
        const voyage = voyagesData.find(v => v.id == voyageId);
        const type = typeReservationSelect.value;
        
        if (!voyageId || !voyage) {
            recapitulatifDiv.classList.add('hidden');
            return;
        }
        
        let recapHtml = `<p>🚢 Voyage : ${voyage.code_voyage} (${new Date(voyage.date_depart).toLocaleString()})</p>`;
        
        // Passager
        if (type === 'passage' || type === 'mixte') {
            const selectedOption = pavillonPassagerSelect.options[pavillonPassagerSelect.selectedIndex];
            const prixUnitaire = parseFloat(selectedOption?.dataset.prix || 0);
            recapHtml += `<p>🏠 Pavillon passager : ${selectedOption?.text.split(' - ')[0]} → ${prixUnitaire.toLocaleString()} FCFA</p>`;
        }
        
        // Cargaison
        if (type === 'cargaison' || type === 'mixte') {
            const selectedOption = pavillonCargaisonSelect.options[pavillonCargaisonSelect.selectedIndex];
            const prixUnitaire = parseFloat(selectedOption?.dataset.prix || 0);
            const poids = parseFloat(poidsCargaison?.value || 0);
            recapHtml += `<p>🏠 Pavillon cargaison : ${selectedOption?.text.split(' - ')[0]} → ${prixUnitaire.toLocaleString()} FCFA/tonne</p>`;
            recapHtml += `<p>⚖️ Poids : ${poids} tonnes → ${(prixUnitaire * poids).toLocaleString()} FCFA</p>`;
        }
        
        recapContent.innerHTML = recapHtml;
        recapitulatifDiv.classList.remove('hidden');
    }

    // Vérifier les disponibilités (places et tonnes)
    function verifierDisponibilite() {
        const voyageId = voyageSelect.value;
        const pavillonId = currentPavillonId;
        
        if (!voyageId || !pavillonId) {
            placesPavillonInfo.classList.add('hidden');
            tonnesPavillonInfo.classList.add('hidden');
            erreurMessage.classList.add('hidden');
            btnReserver.disabled = false;
            return;
        }

        fetch(`/api/verifier-disponibilite-pavillon?voyage=${voyageId}&pavillon=${pavillonId}`)
            .then(response => response.json())
            .then(data => {
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
            });
    }

    // Calcul du prix total
    function calculerPrix() {
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
        mettreAJourRecapitulatif();
    }

    // Écouteurs d'événements
    typeReservationSelect.addEventListener('change', () => {
        toggleSections();
        calculerPrix();
    });
    
    pavillonPassagerSelect.addEventListener('change', () => {
        currentPavillonId = pavillonPassagerSelect.value;
        mettreAJourRecapitulatif();
        verifierDisponibilite();
        calculerPrix();
    });
    
    pavillonCargaisonSelect.addEventListener('change', () => {
        if (typeReservationSelect.value === 'cargaison') {
            currentPavillonId = pavillonCargaisonSelect.value;
            verifierDisponibilite();
        }
        mettreAJourRecapitulatif();
        calculerPrix();
    });
    
    if (poidsCargaison) poidsCargaison.addEventListener('input', () => {
        calculerPrix();
        mettreAJourRecapitulatif();
    });
    if (nombreCargaison) nombreCargaison.addEventListener('input', calculerPrix);

    // Bouton Annuler
    btnAnnuler.addEventListener('click', function() {
        window.location.href = "{{ route('client.dashboard') }}";
    });

    // Initialisation
    toggleSections();
</script>
@endsection