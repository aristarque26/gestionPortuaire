@extends('layouts.admin')

@section('title', 'Gestion des liaisons Bateau ↔ Quai')
@section('header', 'Gestion des liaisons Bateau ↔ Quai')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.appartenir.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">+ Ajouter une liaison</a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID Bateau</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bateau</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quai</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach($appartenirs as $item)
            <tr>
                <td class="px-6 py-4">{{ $item->idbateau }}</td>
                <td class="px-6 py-4">{{ $item->bateau->nom ?? 'N/A' }}</td>
                <td class="px-6 py-4">{{ $item->quai->nom ?? 'N/A' }} (n°{{ $item->quai->numero ?? '' }})</td>
                <td class="px-6 py-4">
                    <a href="{{ route('admin.appartenir.show', [$item->idbateau, $item->idquai]) }}" class="text-blue-600 hover:text-blue-900 mr-3">Voir</a>
                    <a href="{{ route('admin.appartenir.edit', [$item->idbateau, $item->idquai]) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">Modifier</a>
                    <form action="{{ route('admin.appartenir.destroy', [$item->idbateau, $item->idquai]) }}" method="POST" class="inline">
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