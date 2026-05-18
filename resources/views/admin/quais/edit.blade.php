@extends('layouts.admin')

@section('title', 'Modifier un Quai')
@section('header', 'Modifier un Quai')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <form method="POST" action="{{ route('admin.quais.update', $quai->id) }}">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nom du quai</label>
                <input type="text" name="nom" value="{{ old('nom', $quai->nom) }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Numéro</label>
                <input type="number" name="numero" value="{{ old('numero', $quai->numero) }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>
        
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Type de quai</label>
                <select name="type_quai" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="passager" {{ $quai->type_quai == 'passager' ? 'selected' : '' }}>Passager</option>
                    <option value="cargaison" {{ $quai->type_quai == 'cargaison' ? 'selected' : '' }}>Cargaison</option>
                    <option value="mixte" {{ $quai->type_quai == 'mixte' ? 'selected' : '' }}>Mixte</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Capacité (tonnes)</label>
                <input type="number" name="capacite" value="{{ old('capacite', $quai->capacite) }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>
        
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Port</label>
                <select name="idport" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Sélectionner un port</option>
                    @foreach($ports as $port)
                        <option value="{{ $port->id }}" {{ $quai->idport == $port->id ? 'selected' : '' }}>{{ $port->nom }} ({{ $port->ville }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                <select name="statut" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="libre" {{ $quai->statut == 'libre' ? 'selected' : '' }}>Libre</option>
                    <option value="occupe" {{ $quai->statut == 'occupe' ? 'selected' : '' }}>Occupé</option>
                    <option value="maintenance" {{ $quai->statut == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                </select>
            </div>
        </div>
        
        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">Mettre à jour</button>
        </div>
    </form>
</div>
@endsection