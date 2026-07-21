@extends('layouts.admin')

@section('title', 'Ajouter un Pavillon')
@section('header', 'Ajouter un Pavillon')

@section('content')
<div class="bg-white rounded-lg shadow p-6">

    {{-- AFFICHAGE DES ERREURS DE VALIDATION (amélioré) --}}
    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded shadow" role="alert">
            <p class="font-bold">❌ Erreur de validation</p>
            <ul class="list-disc pl-5 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.pavillons.store') }}">
        @csrf
        
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nom du pavillon</label>
                <input type="text" name="nom" required class="w-full border border-gray-300 rounded-lg px-3 py-2" value="{{ old('nom') }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Classe</label>
                <input type="text" name="classe" required class="w-full border border-gray-300 rounded-lg px-3 py-2" value="{{ old('classe') }}">
            </div>
        </div>
        
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Capacité maximale</label>
                <input type="number" name="capacite_max" required min="1" class="w-full border border-gray-300 rounded-lg px-3 py-2" value="{{ old('capacite_max') }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Unité</label>
                <input type="text" name="unite" required class="w-full border border-gray-300 rounded-lg px-3 py-2" value="{{ old('unite') }}">
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Prix unitaire (FCFA) - pour passager</label>
            <input type="number" step="100" name="prix_unitaire" class="w-full border border-gray-300 rounded-lg px-3 py-2" value="{{ old('prix_unitaire') }}">
            <p class="text-xs text-gray-500 mt-1">Laissez vide si ce pavillon n’est pas pour des passagers.</p>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Prix par tonne (FCFA) - pour cargaison</label>
            <input type="number" step="100" name="prix_tonne" class="w-full border border-gray-300 rounded-lg px-3 py-2" value="{{ old('prix_tonne') }}">
            <p class="text-xs text-gray-500 mt-1">Laissez vide si ce pavillon n’est pas pour des cargaisons.</p>
        </div>
        
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Bateau</label>
            <select name="idbateau" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                <option value="">Sélectionner un bateau</option>
                @foreach($bateaux as $bateau)
                    <option value="{{ $bateau->id }}" {{ old('idbateau') == $bateau->id ? 'selected' : '' }}>{{ $bateau->nom }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Devise</label>
            <select name="devise" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                <option value="FC" {{ old('devise') == 'FC' ? 'selected' : '' }}>FC</option>
                <option value="USD" {{ old('devise') == 'USD' ? 'selected' : '' }}>USD</option>
            </select>
        </div>
        
        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">Créer le pavillon</button>
        </div>
    </form>
</div>
@endsection