@extends('layouts.admin')

@section('title', 'Détails du Voyage')
@section('header', 'Détails du Voyage')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-500">Code voyage</label>
            <p class="text-lg font-semibold">{{ $voyage->code_voyage }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Bateau</label>
            <p class="text-lg font-semibold">{{ $voyage->bateau->nom ?? 'N/A' }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Date de départ</label>
            <p class="text-lg font-semibold">{{ $voyage->date_depart->format('d/m/Y H:i') }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Statut</label>
            <p class="text-lg font-semibold">
                <span class="px-2 py-1 text-xs rounded-full 
                    {{ $voyage->statut == 'prevu' ? 'bg-blue-100 text-blue-800' : 
                       ($voyage->statut == 'en_cours' ? 'bg-yellow-100 text-yellow-800' : 
                       ($voyage->statut == 'termine' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800')) }}">
                    {{ $voyage->statut }}
                </span>
            </p>
        </div>
        <div class="col-span-2">
            <label class="block text-sm font-medium text-gray-500">Description</label>
            <p class="text-lg font-semibold">{{ $voyage->description ?? 'Aucune description' }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Date de création</label>
            <p class="text-lg font-semibold">{{ $voyage->created_at->format('d/m/Y H:i') }}</p>
        </div>
    </div>
    
    <div class="flex justify-end space-x-3 mt-6">
        <a href="{{ route('admin.voyages.edit', $voyage->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg">Modifier</a>
        <a href="{{ route('admin.voyages.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">Retour</a>
    </div>
</div>
@endsection