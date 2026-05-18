@extends('layouts.admin')

@section('title', 'Gestion des Quais')
@section('header', 'Gestion des Quais')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.quais.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">+ Ajouter un quai</a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Numéro</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Capacité</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Port</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach($quais as $quai)
            <tr>
                <td class="px-6 py-4">{{ $quai->nom }}</td>
                <td class="px-6 py-4">{{ $quai->numero }}</td>
                <td class="px-6 py-4">{{ $quai->type_quai }}</td>
                <td class="px-6 py-4">{{ $quai->capacite }} t</td>
                <td class="px-6 py-4">{{ $quai->port->nom ?? 'N/A' }}</td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 text-xs rounded-full 
                        {{ $quai->statut == 'libre' ? 'bg-green-100 text-green-800' : 
                           ($quai->statut == 'occupe' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                        {{ $quai->statut }}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <a href="{{ route('admin.quais.show', $quai->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">Voir</a>
                    <a href="{{ route('admin.quais.edit', $quai->id) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">Modifier</a>
                    <form action="{{ route('admin.quais.destroy', $quai->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Supprimer ce quai ?')">Supprimer</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection