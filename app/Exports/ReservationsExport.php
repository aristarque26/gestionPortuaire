<?php

namespace App\Exports;

use App\Models\Reservation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ReservationsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Reservation::with('client', 'voyage', 'pavillon')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Client',
            'Voyage',
            'Pavillon',
            'Type',
            'Prix total (FCFA)',
            'Statut',
            'Date réservation',
            'Date embarquement'
        ];
    }

    public function map($reservation): array
    {
        return [
            $reservation->id,
            $reservation->client->prenom . ' ' . $reservation->client->nom,
            $reservation->voyage->code_voyage,
            $reservation->pavillon->nom ?? 'N/A',
            $reservation->type_reservation,
            number_format($reservation->prix_total, 0, ',', ' '),
            $reservation->statut,
            $reservation->date_reservation->format('d/m/Y H:i'),
            $reservation->date_embarquement->format('d/m/Y H:i'),
        ];
    }
}

/* Documentation complète de l'application - toutes les fonctionnalités expliquées */