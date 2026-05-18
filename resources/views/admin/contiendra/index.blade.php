@extends('layouts.admin')

@section('title', 'Gestion des liaisons Pavillon ↔ Trajet')
@section('header', 'Gestion des liaisons Pavillon ↔ Trajet')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.contiendra.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">+ Ajouter une liaison</a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID Pavillon</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pavillon</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Trajet</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prix (FCFA)</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach($contiendras as $item)
            <tr>
                <td class="px-6 py-4">{{ $item->idpavillon }}</td>
                <td class="px-6 py-4">{{ $item->pavillon->nom ?? 'N/A' }} ({{ $item->pavillon->classe ?? '' }})</td>
                <td class="px-6 py-4">{{ $item->trajet->nom ?? 'N/A' }}</td>
                <td class="px-6 py-4">{{ number_format($item->prix, 0, ',', ' ') }} FCFA</td>
                <td class="px-6 py-4">
                    <a href="{{ route('admin.contiendra.show', [$item->idpavillon, $item->idtrajet]) }}" class="text-blue-600 hover:text-blue-900 mr-3">Voir</a>
                    <a href="{{ route('admin.contiendra.edit', [$item->idpavillon, $item->idtrajet]) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">Modifier</a>
                    <form action="{{ route('admin.contiendra.destroy', [$item->idpavillon, $item->idtrajet]) }}" method="POST" class="inline">
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