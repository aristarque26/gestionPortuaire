<?php

namespace App\Services;

use App\Models\Pavillon;

class PrixService
{
    /**
     * Calcule le prix total d'une réservation
     *
     * @param int $pavillonId
     * @param string $typeReservation (passage, cargaison, mixte)
     * @param float $poidsCargaison (en tonnes)
     * @return float
     */
    public static function calculer($pavillonId, $typeReservation, $poidsCargaison = 0)
    {
        $pavillon = Pavillon::findOrFail($pavillonId);
        $prix = $pavillon->prix_unitaire;

        // Supplément pour cargaison ou mixte (100 FCFA par tonne)
        if (in_array($typeReservation, ['cargaison', 'mixte']) && $poidsCargaison > 0) {
            $prix += $poidsCargaison * 100;
        }

        return round($prix, 2);
    }
}