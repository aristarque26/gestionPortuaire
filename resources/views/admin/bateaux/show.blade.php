@extends('layouts.admin')

@section('title', 'Détails du Bateau')
@section('header', 'Détails du Bateau')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-500">Nom</label>
            <p class="text-lg font-semibold">{{ $bateau->nom }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Type</label>
            <p class="text-lg font-semibold">{{ $bateau->type }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Immatriculation</label>
            <p class="text-lg font-semibold">{{ $bateau->immatriculation }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Capacité totale</label>
            <p class="text-lg font-semibold">{{ $bateau->capacite_totale }} t</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Capacité passager</label>
            <p class="text-lg font-semibold">{{ $bateau->capacite_passager }} personnes</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Capacité cargaison</label>
            <p class="text-lg font-semibold">{{ $bateau->capacite_cargaison }} t</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Statut</label>
            <p class="text-lg font-semibold">
                <span class="px-2 py-1 text-xs rounded-full 
                    {{ $bateau->statut == 'en_service' ? 'bg-green-100 text-green-800' : 
                       ($bateau->statut == 'en_maintenance' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                    {{ $bateau->statut }}
                </span>
            </p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Date de création</label>
            <p class="text-lg font-semibold">{{ $bateau->created_at->format('d/m/Y H:i') }}</p>
        </div>
    </div>
    
    <div class="flex justify-end space-x-3 mt-6">
        <a href="{{ route('admin.bateaux.edit', $bateau->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg">Modifier</a>
        <a href="{{ route('admin.bateaux.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">Retour</a>
    </div>
</div>
@endsection