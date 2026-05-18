@extends('layouts.admin')

@section('title', 'Gestion des liaisons Port ↔ Trajet')
@section('header', 'Gestion des liaisons Port ↔ Trajet')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.conceder.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">+ Ajouter une liaison</a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID Port</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Port</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Trajet</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ordre</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rôle</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach($conceders as $item)
            <tr>
                <td class="px-6 py-4">{{ $item->idport }}</td>
                <td class="px-6 py-4">{{ $item->port->nom ?? 'N/A' }}</td>
                <td class="px-6 py-4">{{ $item->trajet->nom ?? 'N/A' }}</td>
                <td class="px-6 py-4">{{ $item->ordre_etape }}</td>
                <td class="px-6 py-4">{{ $item->role_port }}</td>
                <td class="px-6 py-4">
                    <a href="{{ route('admin.conceder.show', [$item->idport, $item->idtrajet]) }}" class="text-blue-600 hover:text-blue-900 mr-3">Voir</a>
                    <a href="{{ route('admin.conceder.edit', [$item->idport, $item->idtrajet]) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">Modifier</a>
                    <form action="{{ route('admin.conceder.destroy', [$item->idport, $item->idtrajet]) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Supprimer cette liaison ?')">Supprimer</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection