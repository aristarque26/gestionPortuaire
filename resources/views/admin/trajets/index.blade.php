@extends('layouts.admin')

@section('title', 'Gestion des Trajets')
@section('header', 'Gestion des Trajets')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.trajets.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">+ Ajouter un trajet</a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Voyage</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Distance</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ordre</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach($trajets as $trajet)
            <tr>
                <td class="px-6 py-4">{{ $trajet->nom }}</td>
                <td class="px-6 py-4">{{ $trajet->voyage->code_voyage ?? 'N/A' }}</td>
                <td class="px-6 py-4">{{ $trajet->date->format('d/m/Y H:i') }}</td>
                <td class="px-6 py-4">{{ number_format($trajet->distance, 2, ',', ' ') }} km</td>
                <td class="px-6 py-4">{{ $trajet->ordre }}</td>
                <td class="px-6 py-4">
                    <a href="{{ route('admin.trajets.show', $trajet->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">Voir</a>
                    <a href="{{ route('admin.trajets.edit', $trajet->id) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">Modifier</a>
                    <form action="{{ route('admin.trajets.destroy', $trajet->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Supprimer ce trajet ?')">Supprimer</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection