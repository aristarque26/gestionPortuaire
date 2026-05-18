@extends('layouts.admin')

@section('title', 'Détails de la liaison')
@section('header', 'Détails')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-500">ID</label>
            <p class="text-lg font-semibold">{{ $conceder->id }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Port</label>
            <p class="text-lg font-semibold">{{ $conceder->port->nom ?? 'N/A' }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Trajet</label>
            <p class="text-lg font-semibold">{{ $conceder->trajet->nom ?? 'N/A' }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Ordre étape</label>
            <p class="text-lg font-semibold">{{ $conceder->ordre_etape }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Rôle</label>
            <p class="text-lg font-semibold">{{ $conceder->role_port }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Date création</label>
            <p class="text-lg font-semibold">{{ $conceder->created_at->format('d/m/Y H:i') }}</p>
        </div>
    </div>

    <div class="flex justify-end space-x-3 mt-6">
        <a href="{{ route('admin.conceder.edit', $conceder->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg">Modifier</a>
        <a href="{{ route('admin.conceder.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">Retour</a>
    </div>
</div>
@endsection