@extends('layouts.admin')

@section('title', 'Détails de la liaison')
@section('header', 'Détails')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-500">ID</label>
            <p class="text-lg font-semibold">{{ $contiendra->id }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Pavillon</label>
            <p class="text-lg font-semibold">{{ $contiendra->pavillon->nom ?? 'N/A' }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Trajet</label>
            <p class="text-lg font-semibold">{{ $contiendra->trajet->nom ?? 'N/A' }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Prix</label>
            <p class="text-lg font-semibold">{{ number_format($contiendra->prix, 0, ',', ' ') }} FCFA</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Date création</label>
            <p class="text-lg font-semibold">{{ $contiendra->created_at->format('d/m/Y H:i') }}</p>
        </div>
    </div>

    <div class="flex justify-end space-x-3 mt-6">
        <a href="{{ route('admin.contiendra.edit', $contiendra->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg">Modifier</a>
        <a href="{{ route('admin.contiendra.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">Retour</a>
    </div>
</div>
@endsection