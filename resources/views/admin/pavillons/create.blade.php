@extends('layouts.admin')

@section('title', 'Ajouter un Pavillon')
@section('header', 'Ajouter un Pavillon')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <form method="POST" action="{{ route('admin.pavillons.store') }}">
        @csrf
        
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nom du pavillon</label>
                <input type="text" name="nom" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Classe</label>
                <input type="text" name="classe" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>
        </div>
        
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Capacité maximale</label>
                <input type="number" name="capacite_max" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Unité</label>
                <input type="text" name="unite" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>
        </div>

        {{-- Prix unitaire (passager) --}}
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Prix unitaire (FCFA) - pour passager</label>
            <input type="number" step="100" name="prix_unitaire" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
        </div>

        {{-- Prix par tonne (cargaison) --}}
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Prix par tonne (FCFA) - pour cargaison</label>
            <input type="number" step="100" name="prix_tonne" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
        </div>
        
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Bateau</label>
            <select name="idbateau" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                <option value="">Sélectionner un bateau</option>
                @foreach($bateaux as $bateau)
                    <option value="{{ $bateau->id }}">{{ $bateau->nom }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">Créer le pavillon</button>
        </div>
    </form>
</div>
@endsection