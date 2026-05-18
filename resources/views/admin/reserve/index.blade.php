@extends('layouts.admin')

@section('title', 'Gestion des liaisons Réservation ↔ Pavillon')
@section('header', 'Gestion des liaisons Réservation ↔ Pavillon')

@section('content')
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID Réservation</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID Pavillon</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Réservation</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pavillon</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prix (FCFA)</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach($reserves as $item)
            <tr>
                <td class="px-6 py-4">{{ $item->idreservation }}</td>
                <td class="px-6 py-4">{{ $item->idpavillon }}</td>
                <td class="px-6 py-4">#{{ $item->reservation->id ?? 'N/A' }}</td>
                <td class="px-6 py-4">{{ $item->pavillon->nom ?? 'N/A' }}</td>
                <td class="px-6 py-4">{{ number_format($item->prix, 0, ',', ' ') }} FCFA</td>
                <td class="px-6 py-4">
                    <a href="{{ route('admin.reserve.show', [$item->idreservation, $item->idpavillon]) }}" class="text-blue-600 hover:text-blue-900 mr-3">Voir</a>
                    <a href="{{ route('admin.reserve.edit', [$item->idreservation, $item->idpavillon]) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">Modifier</a>
                    <form action="{{ route('admin.reserve.destroy', [$item->idreservation, $item->idpavillon]) }}" method="POST" class="inline">
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