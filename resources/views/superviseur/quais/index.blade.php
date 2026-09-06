<!-- resources/views/superviseur/quais/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="flex">
    @include('superviseur.layouts.sidebar')
    
    <div class="flex-1 p-6 bg-gray-100 min-h-screen">
        <!-- En-tête -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Gestion des Quais</h1>
            <a href="{{ route('superviseur.quais.export') }}" 
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition text-sm flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Exporter CSV
            </a>
        </div>

        <!-- Statistiques rapides -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
            <div class="bg-white p-3 rounded-lg shadow text-center">
                <p class="text-2xl font-bold text-gray-800">{{ $statistiques['total'] }}</p>
                <p class="text-xs text-gray-500">Total</p>
            </div>
            <div class="bg-white p-3 rounded-lg shadow text-center border-l-4 border-green-500">
                <p class="text-2xl font-bold text-green-600">{{ $statistiques['libre'] }}</p>
                <p class="text-xs text-gray-500">Libres</p>
            </div>
            <div class="bg-white p-3 rounded-lg shadow text-center border-l-4 border-red-500">
                <p class="text-2xl font-bold text-red-600">{{ $statistiques['occupe'] }}</p>
                <p class="text-xs text-gray-500">Occupés</p>
            </div>
            <div class="bg-white p-3 rounded-lg shadow text-center border-l-4 border-yellow-500">
                <p class="text-2xl font-bold text-yellow-600">{{ $statistiques['maintenance'] }}</p>
                <p class="text-xs text-gray-500">Maintenance</p>
            </div>
        </div>

        <!-- Filtres -->
        <div class="bg-white rounded-lg shadow-md p-4 mb-6">
            <form method="GET" action="{{ route('superviseur.quais.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                    <select name="statut" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="tous">Tous</option>
                        <option value="libre" {{ request('statut') == 'libre' ? 'selected' : '' }}>Libre</option>
                        <option value="occupe" {{ request('statut') == 'occupe' ? 'selected' : '' }}>Occupé</option>
                        <option value="maintenance" {{ request('statut') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                    <select name="type" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="tous">Tous</option>
                        <option value="passager" {{ request('type') == 'passager' ? 'selected' : '' }}>Passager</option>
                        <option value="cargaison" {{ request('type') == 'cargaison' ? 'selected' : '' }}>Cargaison</option>
                        <option value="mixte" {{ request('type') == 'mixte' ? 'selected' : '' }}>Mixte</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Port</label>
                    <select name="port_id" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Tous</option>
                        @foreach($ports as $port)
                        <option value="{{ $port->id }}" {{ request('port_id') == $port->id ? 'selected' : '' }}>{{ $port->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end space-x-2">
                    <a href="{{ route('superviseur.quais.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                        Réinitialiser
                    </a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Filtrer
                    </button>
                </div>
            </form>
        </div>

        <!-- Tableau des quais -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Nom</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Port</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Numéro</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Type</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Capacité</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Statut</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($quais as $quai)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm font-medium">{{ $quai->nom }}</td>
                            <td class="px-4 py-3 text-sm">{{ $quai->port->nom ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-sm">{{ $quai->numero }}</td>
                            <td class="px-4 py-3 text-sm">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold
                                    @if($quai->type_quai == 'passager') bg-blue-100 text-blue-800
                                    @elseif($quai->type_quai == 'cargaison') bg-orange-100 text-orange-800
                                    @else bg-purple-100 text-purple-800 @endif">
                                    {{ $quai->type_quai }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm">{{ $quai->capacite }}</td>
                            <td class="px-4 py-3 text-sm">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold
                                    @if($quai->statut == 'libre') bg-green-100 text-green-800
                                    @elseif($quai->statut == 'occupe') bg-red-100 text-red-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ $quai->statut }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <div class="flex space-x-2">
                                    <a href="{{ route('superviseur.quais.show', $quai->id) }}" 
                                       class="text-blue-600 hover:underline">Voir</a>
                                    @if($quai->statut != 'libre')
                                    <form method="POST" action="{{ route('superviseur.quais.liberer', $quai->id) }}" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="text-green-600 hover:underline"
                                                onclick="return confirm('Libérer ce quai ?')">Libérer</button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-gray-500">
                                Aucun quai trouvé.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 border-t">
                {{ $quais->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection