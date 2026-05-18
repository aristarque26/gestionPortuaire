@extends('layouts.admin')

@section('title', 'Modifier un Voyage')
@section('header', 'Modifier un Voyage')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <form method="POST" action="{{ route('admin.voyages.update', $voyage->id) }}">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Code voyage</label>
                <input type="text" name="code_voyage" value="{{ old('code_voyage', $voyage->code_voyage) }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Bateau</label>
                <select name="idbateau" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Sélectionner un bateau</option>
                    @foreach($bateaux as $bateau)
                        <option value="{{ $bateau->id }}" {{ $voyage->idbateau == $bateau->id ? 'selected' : '' }}>{{ $bateau->nom }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea name="description" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description', $voyage->description) }}</textarea>
        </div>
        
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date de départ</label>
                <input type="datetime-local" name="date_depart" value="{{ old('date_depart', $voyage->date_depart->format('Y-m-d\TH:i')) }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                <select name="statut" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="prevu" {{ $voyage->statut == 'prevu' ? 'selected' : '' }}>Prévu</option>
                    <option value="en_cours" {{ $voyage->statut == 'en_cours' ? 'selected' : '' }}>En cours</option>
                    <option value="termine" {{ $voyage->statut == 'termine' ? 'selected' : '' }}>Terminé</option>
                    <option value="annule" {{ $voyage->statut == 'annule' ? 'selected' : '' }}>Annulé</option>
                </select>
            </div>
        </div>
        
        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">Mettre à jour</button>
        </div>
    </form>
</div>
@endsection