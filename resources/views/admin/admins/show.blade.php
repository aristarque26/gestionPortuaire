@extends('layouts.admin')

@section('title', 'Détails Administrateur')
@section('header', 'Détails Administrateur')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-500">Prénom</label>
        <p class="text-lg font-semibold">{{ $admin->prenom }}</p>
    </div>
    
    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-500">Nom</label>
        <p class="text-lg font-semibold">{{ $admin->name }}</p>
    </div>
    
    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-500">Email</label>
        <p class="text-lg font-semibold">{{ $admin->email }}</p>
    </div>
    
    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-500">Téléphone</label>
        <p class="text-lg font-semibold">{{ $admin->telephone }}</p>
    </div>
    
    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-500">Statut</label>
        <p class="text-lg font-semibold">
            <span class="px-2 py-1 text-xs rounded-full {{ $admin->statut == 'actif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                {{ $admin->statut }}
            </span>
        </p>
    </div>
    
    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-500">Date de création</label>
        <p class="text-lg font-semibold">{{ $admin->created_at->format('d/m/Y H:i') }}</p>
    </div>
    
    <div class="flex justify-end space-x-3">
        <a href="{{ route('admin.admins.edit', $admin->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg">Modifier</a>
        <a href="{{ route('admin.admins.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">Retour</a>
    </div>
</div>
@endsection