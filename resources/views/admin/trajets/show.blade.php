@extends('layouts.admin')

@section('title', 'Détails du Trajet')
@section('header', 'Détails du Trajet')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-500">Nom</label>
            <p class="text-lg font-semibold">{{ $trajet->nom }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Voyage</label>
            <p class="text-lg font-semibold">{{ $trajet->voyage->code_voyage ?? 'N/A' }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Date</label>
            <p class="text-lg font-semibold">{{ $trajet->date->format('d/m/Y H:i') }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Distance</label>
            <p class="text-lg font-semibold">{{ number_format($trajet->distance, 2, ',', ' ') }} km</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Ordre</label>
            <p class="text-lg font-semibold">{{ $trajet->ordre }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Date de création</label>
            <p class="text-lg font-semibold">{{ $trajet->created_at->format('d/m/Y H:i') }}</p>
        </div>
        @if($trajet->description)
        <div class="col-span-2">
            <label class="block text-sm font-medium text-gray-500">Description</label>
            <p class="text-lg font-semibold">{{ $trajet->description }}</p>
        </div>
        @endif
    </div>
    
    <div class="flex justify-end space-x-3 mt-6">
        <a href="{{ route('admin.trajets.edit', $trajet->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg">Modifier</a>
        <a href="{{ route('admin.trajets.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">Retour</a>
    </div>
</div>
@endsection