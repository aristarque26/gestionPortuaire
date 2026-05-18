@extends('layouts.admin')

@section('title', 'Détails du Pavillon')
@section('header', 'Détails du Pavillon')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-500">Nom</label>
            <p class="text-lg font-semibold">{{ $pavillon->nom }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Classe</label>
            <p class="text-lg font-semibold">{{ $pavillon->classe }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Capacité maximale</label>
            <p class="text-lg font-semibold">{{ $pavillon->capacite_max }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Unité</label>
            <p class="text-lg font-semibold">{{ $pavillon->unite }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Prix unitaire</label>
            <p class="text-lg font-semibold">{{ number_format($pavillon->prix_unitaire, 0, ',', ' ') }} FCFA</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Bateau</label>
            <p class="text-lg font-semibold">{{ $pavillon->bateau->nom ?? 'N/A' }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Date de création</label>
            <p class="text-lg font-semibold">{{ $pavillon->created_at->format('d/m/Y H:i') }}</p>
        </div>
    </div>
    
    <div class="flex justify-end space-x-3 mt-6">
        <a href="{{ route('admin.pavillons.edit', $pavillon->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg">Modifier</a>
        <a href="{{ route('admin.pavillons.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">Retour</a>
    </div>
</div>
@endsection