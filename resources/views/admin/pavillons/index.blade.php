@extends('layouts.admin')

@section('title', 'Gestion des Pavillons')
@section('header', 'Gestion des Pavillons')

@section('content')
{{-- MESSAGES FLASH --}}
@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        {{ session('error') }}
    </div>
@endif

<div class="mb-4">
    <a href="{{ route('admin.pavillons.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">+ Ajouter un pavillon</a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Classe</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Capacité max</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unité</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prix / Tarif</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bateau</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach($pavillons as $pavillon)
            <tr>
                <td class="px-6 py-4">{{ $pavillon->nom }}</td>
                <td class="px-6 py-4">{{ $pavillon->classe }}</td>
                <td class="px-6 py-4">{{ $pavillon->capacite_max }}</td>
                <td class="px-6 py-4">{{ $pavillon->unite }}</td>
                <td class="px-6 py-4">
                    @if($pavillon->prix_unitaire > 0)
                        {{ number_format($pavillon->prix_unitaire, 0, ',', ' ') }} FCFA / place
                    @elseif($pavillon->prix_tonne > 0)
                        {{ number_format($pavillon->prix_tonne, 0, ',', ' ') }} FCFA / tonne
                    @else
                        <span class="text-gray-400">N/A</span>
                    @endif
                </td>
                <td class="px-6 py-4">
                    @if($pavillon->prix_unitaire > 0)
                        <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">Passager</span>
                    @elseif($pavillon->prix_tonne > 0)
                        <span class="px-2 py-1 bg-orange-100 text-orange-800 text-xs rounded-full">Cargaison</span>
                    @else
                        <span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs rounded-full">Mixte</span>
                    @endif
                </td>
                <td class="px-6 py-4">{{ $pavillon->bateau->nom ?? 'N/A' }}</td>
                <td class="px-6 py-4">
                    <a href="{{ route('admin.pavillons.show', $pavillon->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">Voir</a>
                    <a href="{{ route('admin.pavillons.edit', $pavillon->id) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">Modifier</a>
                    <form action="{{ route('admin.pavillons.destroy', $pavillon->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Supprimer ce pavillon ?')">Supprimer</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection