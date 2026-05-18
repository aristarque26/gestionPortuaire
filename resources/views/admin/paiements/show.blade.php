@extends('layouts.admin')

@section('title', 'Détails du Paiement')
@section('header', 'Détails du Paiement')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
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
                <form method="POST" action="{{ route('admin.paiements.update', $paiement->id) }}" class="inline">
                    @csrf
                    @method('PUT')
                    <select name="statut" onchange="this.form.submit()" class="border rounded-lg px-3 py-1">
                        <option value="en_attente" {{ $paiement->statut == 'en_attente' ? 'selected' : '' }}>En attente</option>
                        <option value="paye" {{ $paiement->statut == 'paye' ? 'selected' : '' }}>Payé</option>
                        <option value="echoue" {{ $paiement->statut == 'echoue' ? 'selected' : '' }}>Échoué</option>
                        <option value="rembourse" {{ $paiement->statut == 'rembourse' ? 'selected' : '' }}>Remboursé</option>
                    </select>
                </form>
            </p>
        </div>
    </div>
    
    <div class="flex justify-end space-x-3 mt-6">
        <a href="{{ route('admin.paiements.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">Retour</a>
    </div>
</div>
@endsection