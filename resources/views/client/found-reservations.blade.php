@extends('layouts.client')

@section('title', 'Mes réservations')
@section('header', 'Mes réservations')

@section('content')
<div class="bg-white rounded-xl shadow-md p-6">
    <h2 class="text-xl font-bold mb-4">Réservations trouvées</h2>
    @if($reservations->isEmpty())
        <p>Aucune réservation trouvée.</p>
    @else
        <ul class="space-y-4">
            @foreach($reservations as $res)
                <li class="border-b pb-2">
                    <p><strong>Voyage :</strong> {{ $res->voyage->code_voyage }}</p>
                    <p><strong>Pavillon :</strong> {{ $res->pavillon->nom ?? 'Non défini' }}</p>
                    <p><strong>Prix total :</strong> {{ number_format($res->prix_total, 0, ',', ' ') }} FCFA</p>
                    <p><strong>Statut :</strong> {{ $res->statut }}</p>
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection