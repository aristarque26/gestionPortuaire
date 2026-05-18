@extends('layouts.admin')

@section('title', 'Détails du Port')
@section('header', 'Détails du Port')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-500">Nom</label>
            <p class="text-lg font-semibold">{{ $port->nom }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Ville</label>
            <p class="text-lg font-semibold">{{ $port->ville }}</p>
        </div>
        <div class="col-span-2">
            <label class="block text-sm font-medium text-gray-500">Localisation</label>
            <p class="text-lg font-semibold">{{ $port->localisation }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Statut</label>
            <p class="text-lg font-semibold">
                <span class="px-2 py-1 text-xs rounded-full {{ $port->statut == 'actif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $port->statut }}
                </span>
            </p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Date de création</label>
            <p class="text-lg font-semibold">{{ $port->created_at->format('d/m/Y H:i') }}</p>
        </div>
    </div>
    
    <div class="flex justify-end space-x-3 mt-6">
        <a href="{{ route('admin.ports.edit', $port->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg">Modifier</a>
        <a href="{{ route('admin.ports.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">Retour</a>
    </div>
</div>
@endsection