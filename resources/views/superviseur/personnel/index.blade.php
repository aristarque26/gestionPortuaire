<!-- resources/views/superviseur/personnel/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="flex">
    @include('superviseur.layouts.sidebar')
    
    <div class="flex-1 p-6 bg-gray-100 min-h-screen">
        <!-- En-tête -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Gestion du Personnel</h1>
            <div class="flex space-x-2">
                <a href="{{ route('superviseur.personnel.export', request()->all()) }}" 
                   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition text-sm flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Exporter CSV
                </a>
            </div>
        </div>

        <!-- Statistiques rapides -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
            <div class="bg-white p-3 rounded-lg shadow text-center">
                <p class="text-2xl font-bold text-gray-800">{{ $statistiques['total'] }}</p>
                <p class="text-xs text-gray-500">Total</p>
            </div>
            <div class="bg-white p-3 rounded-lg shadow text-center border-l-4 border-green-500">
                <p class="text-2xl font-bold text-green-600">{{ $statistiques['actif'] }}</p>
                <p class="text-xs text-gray-500">Actifs</p>
            </div>
            <div class="bg-white p-3 rounded-lg shadow text-center border-l-4 border-red-500">
                <p class="text-2xl font-bold text-red-600">{{ $statistiques['inactif'] }}</p>
                <p class="text-xs text-gray-500">Inactifs</p>
            </div>
            <div class="bg-white p-3 rounded-lg shadow text-center border-l-4 border-blue-500">
                <p class="text-2xl font-bold text-blue-600">{{ $statistiques['superviseurs'] }}</p>
                <p class="text-xs text-gray-500">Superviseurs</p>
            </div>
        </div>

        <!-- Filtres -->
        <div class="bg-white rounded-lg shadow-md p-4 mb-6">
            <form method="GET" action="{{ route('superviseur.personnel.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Recherche</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Nom, matricule..." class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                    <select name="statut" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="tous">Tous</option>
                        <option value="actif" {{ request('statut') == 'actif' ? 'selected' : '' }}>Actif</option>
                        <option value="inactif" {{ request('statut') == 'inactif' ? 'selected' : '' }}>Inactif</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rôle</label>
                    <select name="role" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="tous">Tous</option>
                        @foreach($roles as $key => $label)
                        <option value="{{ $key }}" {{ request('role') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Service</label>
                    <input type="text" name="service" value="{{ request('service') }}" 
                           placeholder="Service..." class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div class="flex items-end space-x-2">
                    <a href="{{ route('superviseur.personnel.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                        Réinitialiser
                    </a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Filtrer
                    </button>
                </div>
            </form>
        </div>

        <!-- Tableau du personnel -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Matricule</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Nom complet</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Poste</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Service</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Rôle</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Salaire</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Statut</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($personnel as $p)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm font-medium">{{ $p->matricule }}</td>
                            <td class="px-4 py-3 text-sm">
                                {{ $p->user->name }} {{ $p->user->prenom }}
                                <div class="text-xs text-gray-500">{{ $p->user->email }}</div>
                            </td>
                            <td class="px-4 py-3 text-sm">{{ $p->poste }}</td>
                            <td class="px-4 py-3 text-sm">{{ $p->service }}</td>
                            <td class="px-4 py-3 text-sm">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold
                                    @if($p->personnel_role == 'superviseur') bg-purple-100 text-purple-800
                                    @elseif($p->personnel_role == 'comptable') bg-blue-100 text-blue-800
                                    @elseif($p->personnel_role == 'caissier') bg-green-100 text-green-800
                                    @elseif($p->personnel_role == 'gestionnaire') bg-orange-100 text-orange-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ $p->personnel_role }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm font-semibold">{{ number_format($p->salaire, 0, ',', ' ') }} USD</td>
                            <td class="px-4 py-3 text-sm">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold
                                    @if($p->statut == 'actif') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ $p->statut }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <div class="flex space-x-2">
                                    <a href="{{ route('superviseur.personnel.show', $p->id) }}" 
                                       class="text-blue-600 hover:underline">Voir</a>
                                    <a href="{{ route('superviseur.personnel.edit', $p->id) }}" 
                                       class="text-yellow-600 hover:underline">Modifier</a>
                                    @if($p->statut == 'actif')
                                    <form method="POST" action="{{ route('superviseur.personnel.desactiver', $p->id) }}" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="text-red-600 hover:underline" 
                                                onclick="return confirm('Désactiver ce personnel ?')">Désactiver</button>
                                    </form>
                                    @else
                                    <form method="POST" action="{{ route('superviseur.personnel.activer', $p->id) }}" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="text-green-600 hover:underline"
                                                onclick="return confirm('Activer ce personnel ?')">Activer</button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-8 text-gray-500">
                                Aucun personnel trouvé.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 border-t">
                {{ $personnel->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection