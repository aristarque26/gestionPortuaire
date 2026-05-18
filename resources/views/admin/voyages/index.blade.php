@extends('layouts.admin')

@section('title', 'Gestion des Voyages')
@section('header', 'Gestion des Voyages')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.voyages.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">+ Ajouter un voyage</a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Code</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bateau</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date départ</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach($voyages as $voyage)
            <tr>
                <td class="px-6 py-4">{{ $voyage->code_voyage }}</td>
                <td class="px-6 py-4">{{ $voyage->bateau->nom ?? 'N/A' }}</td>
                <td class="px-6 py-4">{{ $voyage->date_depart->format('d/m/Y H:i') }}</td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 text-xs rounded-full 
                        {{ $voyage->statut == 'prevu' ? 'bg-blue-100 text-blue-800' : 
                           ($voyage->statut == 'en_cours' ? 'bg-yellow-100 text-yellow-800' : 
                           ($voyage->statut == 'termine' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800')) }}">
                        {{ $voyage->statut }}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <a href="{{ route('admin.voyages.show', $voyage->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">Voir</a>
                    <a href="{{ route('admin.voyages.edit', $voyage->id) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">Modifier</a>
                    <form action="{{ route('admin.voyages.destroy', $voyage->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Supprimer ce voyage ?')">Supprimer</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection