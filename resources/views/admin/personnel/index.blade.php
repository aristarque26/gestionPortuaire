@extends('layouts.admin')

@section('title', 'Gestion du personnel')
@section('header', 'Gestion du personnel')

@section('content')
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="p-4 border-b border-gray-200 flex justify-between items-center">
        <h2 class="text-xl font-semibold text-gray-800">Liste des agents</h2>
        <a href="{{ route('admin.personnel.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
             Ajouter un agent
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Matricule</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom complet</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Poste</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Service</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rôle</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($personnels as $personnel)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $personnel->matricule }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $personnel->user->nom_complet ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $personnel->poste }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $personnel->service }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                            {{ $personnel->role_label }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full 
                            {{ $personnel->statut == 'actif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($personnel->statut) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <a href="{{ route('admin.personnel.show', $personnel->id) }}" class="text-blue-600 hover:text-blue-900 mr-3" title="Voir">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.personnel.edit', $personnel->id) }}" class="text-yellow-600 hover:text-yellow-900 mr-3" title="Modifier">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.personnel.destroy', $personnel->id) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer cet agent ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" title="Supprimer">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                        <i class="fas fa-users text-4xl block mb-2 text-gray-300"></i>
                        Aucun agent enregistré.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($personnels instanceof \Illuminate\Pagination\LengthAwarePaginator && $personnels->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $personnels->links() }}
    </div>
    @endif
</div>
@endsection