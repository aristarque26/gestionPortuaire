@extends('layouts.client')

@section('title', 'Détails du paiement')
@section('header', 'Détails du paiement')

@section('content')
<div class="bg-white rounded-xl shadow-md p-6">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-500">ID Paiement</label>
            <p class="text-lg font-semibold">#{{ $paiement->id }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Réservation</label>
            <p class="text-lg font-semibold">#{{ $paiement->idreservation }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Montant</label>
            <p class="text-lg font-semibold">{{ number_format($paiement->montant, 0, ',', ' ') }} {{ $paiement->devise }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Mode de paiement</label>
            <p class="text-lg font-semibold">{{ $paiement->mode_paiement }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Date</label>
            <p class="text-lg font-semibold">{{ $paiement->date_paiement->format('d/m/Y H:i') }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Statut</label>
            <p class="text-lg font-semibold">
                <span class="px-2 py-1 text-xs rounded-full 
                    {{ $paiement->statut == 'paye' ? 'bg-green-100 text-green-800' : 
                       ($paiement->statut == 'en_attente' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                    {{ $paiement->statut }}
                </span>
            </p>
        </div>
    </div>
    
    <div class="flex justify-end space-x-3 mt-6">
        <a href="{{ route('client.paiements.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">Retour</a>
    </div>
</div>
@endsection