<?php

namespace App\Services;

use App\Models\Reservation;
use App\Models\Voyage;
use App\Models\Pavillon;

class DisponibiliteService
{
    /**
     * Vérifie si un pavillon est disponible pour un voyage donné
     *
     * @param int $pavillonId
     * @param int $voyageId
     * @return bool
     */
    public static function estDisponible($pavillonId, $voyageId)
    {
        $voyage = Voyage::findOrFail($voyageId);

        // Compter les réservations déjà faites pour ce pavillon sur ce voyage
        $reservations = Reservation::where('idvoyage', $voyageId)
            ->where('idpavillon', $pavillonId)
            ->count();

        // Exemple : on considère qu'un pavillon peut être réservé max 1 fois par voyage
        // Tu peux adapter selon la capacité réelle du pavillon
        $capaciteMax = Pavillon::findOrFail($pavillonId)->capacite_max ?? 1;

        return $reservations < $capaciteMax;
    }
}