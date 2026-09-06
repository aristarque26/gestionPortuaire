<!-- resources/views/superviseur/personnel/edit.blade.php -->
@extends('layouts.app')

@section('content')
<div class="flex">
    @include('superviseur.layouts.sidebar')
    
    <div class="flex-1 p-6 bg-gray-100 min-h-screen">
        <!-- En-tête -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Modifier le personnel</h1>
                <p class="text-sm text-gray-500">{{ $personnel->matricule }} - {{ $personnel->user->name }} {{ $personnel->user->prenom }}</p>
            </div>
            <a href="{{ route('superviseur.personnel.show', $personnel->id) }}" class="text-blue-600 hover:underline">
                ← Retour
            </a>
        </div>

        <!-- Formulaire -->
        <div class="bg-white rounded-lg shadow-md p-6 max-w-2xl">
            <form method="POST" action="{{ route('superviseur.personnel.update', $personnel->id) }}">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Poste *</label>
                        <input type="text" name="poste" value="{{ old('poste', $personnel->poste) }}" 
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('poste') border-red-500 @enderror" required>
                        @error('poste')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Service *</label>
                        <input type="text" name="service" value="{{ old('service', $personnel->service) }}" 
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('service') border-red-500 @enderror" required>
                        @error('service')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Rôle *</label>
                        <select name="personnel_role" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('personnel_role') border-red-500 @enderror" required>
                            @foreach($roles as $key => $label)
                            <option value="{{ $key }}" {{ old('personnel_role', $personnel->personnel_role) == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                            @endforeach
                        </select>
                        @error('personnel_role')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Salaire (USD) *</label>
                        <input type="number" step="0.01" name="salaire" value="{{ old('salaire', $personnel->salaire) }}" 
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('salaire') border-red-500 @enderror" required>
                        @error('salaire')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date d'embauche *</label>
                        <input type="date" name="date_embauche" value="{{ old('date_embauche', $personnel->date_embauche->format('Y-m-d')) }}" 
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('date_embauche') border-red-500 @enderror" required>
                        @error('date_embauche')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end space-x-2 pt-4 border-t">
                        <a href="{{ route('superviseur.personnel.show', $personnel->id) }}" 
                           class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                            Annuler
                        </a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            Mettre à jour
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection