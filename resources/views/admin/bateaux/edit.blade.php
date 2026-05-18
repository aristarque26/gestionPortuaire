@extends('layouts.admin')

@section('title', 'Modifier un Bateau')
@section('header', 'Modifier un Bateau')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <form method="POST" action="{{ route('admin.bateaux.update', $bateau->id) }}">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nom du bateau</label>
                <input type="text" name="nom" value="{{ old('nom', $bateau->nom) }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                <select name="type" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="cargo" {{ $bateau->type == 'cargo' ? 'selected' : '' }}>Cargo</option>
                    <option value="mixte" {{ $bateau->type == 'mixte' ? 'selected' : '' }}>Mixte</option>
                    <option value="passager" {{ $bateau->type == 'passager' ? 'selected' : '' }}>Passager</option>
                </select>
            </div>
        </div>
        
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Immatriculation</label>
                <input type="text" name="immatriculation" value="{{ old('immatriculation', $bateau->immatriculation) }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Capacité totale</label>
                <input type="number" name="capacite_totale" value="{{ old('capacite_totale', $bateau->capacite_totale) }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>
        </div>
        
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Capacité passager</label>
                <input type="number" name="capacite_passager" value="{{ old('capacite_passager', $bateau->capacite_passager) }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Capacité cargaison</label>
                <input type="number" name="capacite_cargaison" value="{{ old('capacite_cargaison', $bateau->capacite_cargaison) }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>
        </div>
        
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
            <select name="statut" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                <option value="en_service" {{ $bateau->statut == 'en_service' ? 'selected' : '' }}>En service</option>
                <option value="en_maintenance" {{ $bateau->statut == 'en_maintenance' ? 'selected' : '' }}>En maintenance</option>
                <option value="hors_service" {{ $bateau->statut == 'hors_service' ? 'selected' : '' }}>Hors service</option>
            </select>
        </div>
        
        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">Mettre à jour</button>
        </div>
    </form>
</div>
@endsection