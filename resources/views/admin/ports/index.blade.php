@extends('layouts.admin')

@section('title', 'Gestion des Ports')
@section('header', 'Gestion des Ports')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.ports.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">+ Ajouter un port</a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Localisation</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ville</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach($ports as $port)
            <tr>
                <td class="px-6 py-4">{{ $port->nom }}</td>
                <td class="px-6 py-4">{{ $port->localisation }}</td>
                <td class="px-6 py-4">{{ $port->ville }}</td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 text-xs rounded-full {{ $port->statut == 'actif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $port->statut }}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <a href="{{ route('admin.ports.show', $port->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">Voir</a>
                    <a href="{{ route('admin.ports.edit', $port->id) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">Modifier</a>
                    <form action="{{ route('admin.ports.destroy', $port->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Supprimer ce port ?')">Supprimer</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection