@extends('layouts.client')

@section('title', 'Paiement de la réservation')
@section('header', 'Paiement sécurisé')

@section('content')
<div class="bg-white rounded-xl shadow-md p-6 max-w-2xl mx-auto">
    <h2 class="text-2xl font-bold mb-4 text-center">💳 Paiement par Maisha Pay</h2>
    
    <div class="border-t border-b py-4 my-4">
        <p><strong>🔖 Réservation n° :</strong> {{ $reservation->id }}</p>
        <p><strong>💰 Montant à payer :</strong> {{ number_format($reservation->prix_total, 0, ',', ' ') }} FCFA</p>
        <p><strong>🚢 Voyage :</strong> {{ $reservation->voyage->code_voyage }}</p>
        <p><strong>📅 Date d'embarquement :</strong> {{ \Carbon\Carbon::parse($reservation->date_embarquement)->format('d/m/Y H:i') }}</p>
    </div>

    <div class="bg-blue-50 p-4 rounded-lg mb-6">
        <p class="text-sm text-blue-800">🔒 Paiement sécurisé via Maisha Pay. Vous serez redirigé après validation.</p>
    </div>

    <form action="/client/reservations/{{ $reservation->id }}/paiement/effectuer" method="POST">
        @csrf
        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg text-lg transition">
            ✅ Confirmer le paiement (Maisha Pay)
        </button>
    </form>
</div>
@endsection