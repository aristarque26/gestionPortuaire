@extends('layouts.admin')

@section('title', 'Ajouter une liaison Pavillon ↔ Trajet')
@section('header', 'Ajouter une liaison')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <form method="POST" action="{{ route('admin.contiendra.store') }}">
        @csrf

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pavillon</label>
                <select name="idpavillon" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">Sélectionner un pavillon</option>
                    @foreach($pavillons as $pavillon)
                        <option value="{{ $pavillon->id }}">{{ $pavillon->nom }} ({{ $pavillon->classe }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Trajet</label>
                <select name="idtrajet" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">Sélectionner un trajet</option>
                    @foreach($trajets as $trajet)
                        <option value="{{ $trajet->id }}">{{ $trajet->nom }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Prix (FCFA)</label>
            <input type="number" step="100" name="prix" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">Créer</button>
        </div>
    </form>
</div>
@endsection