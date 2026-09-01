@extends('layouts.admin')

@section('title', 'Ajouter un agent')
@section('header', 'Ajouter un agent')

@section('content')
<div class="bg-white rounded-xl shadow-md p-6 max-w-4xl mx-auto">
    <h2 class="text-xl font-bold text-gray-800 mb-6">Nouvel agent portuaire</h2>

    <form action="{{ route('admin.personnel.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Matricule --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Matricule *</label>
                <input type="text" name="matricule" value="{{ old('matricule') }}" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                @error('matricule') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Nom --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nom *</label>
                <input type="text" name="nom" value="{{ old('nom') }}" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                @error('nom') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Prénom --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Prénom *</label>
                <input type="text" name="prenom" value="{{ old('prenom') }}" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                @error('prenom') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Email --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Mot de passe --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Mot de passe *</label>
                <input type="password" name="password" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Téléphone --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone *</label>
                <input type="text" name="telephone" value="{{ old('telephone') }}" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                @error('telephone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Adresse --}}
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Adresse *</label>
                <textarea name="adresse" rows="2" required
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">{{ old('adresse') }}</textarea>
                @error('adresse') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Nationalité --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nationalité *</label>
                <input type="text" name="nationalite" value="{{ old('nationalite', 'Congolaise') }}" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                @error('nationalite') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Genre --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Genre *</label>
                <select name="genre" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                    <option value="">Sélectionner</option>
                    <option value="Homme" {{ old('genre') == 'Homme' ? 'selected' : '' }}>Homme</option>
                    <option value="Femme" {{ old('genre') == 'Femme' ? 'selected' : '' }}>Femme</option>
                </select>
                @error('genre') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Poste --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Poste *</label>
                <input type="text" name="poste" value="{{ old('poste') }}" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                @error('poste') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Service --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Service *</label>
                <input type="text" name="service" value="{{ old('service') }}" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                @error('service') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Date d'embauche --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date d'embauche *</label>
                <input type="date" name="date_embauche" value="{{ old('date_embauche') }}" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                @error('date_embauche') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Rôle personnel --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Rôle *</label>
                <select name="personnel_role" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                    <option value="">Sélectionner</option>
                    <option value="superviseur" {{ old('personnel_role') == 'superviseur' ? 'selected' : '' }}>Superviseur</option>
                    <option value="comptable" {{ old('personnel_role') == 'comptable' ? 'selected' : '' }}>Comptable</option>
                    <option value="caissier" {{ old('personnel_role') == 'caissier' ? 'selected' : '' }}>Caissier</option>
                    <option value="agent_portuaire" {{ old('personnel_role') == 'agent_portuaire' ? 'selected' : '' }}>Agent portuaire</option>
                    <option value="gestionnaire" {{ old('personnel_role') == 'gestionnaire' ? 'selected' : '' }}>Gestionnaire</option>
                </select>
                @error('personnel_role') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Salaire --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Salaire mensuel (CDF) *</label>
                <input type="number" step="100" name="salaire" value="{{ old('salaire') }}" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                @error('salaire') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Statut --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Statut *</label>
                <select name="statut" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                    <option value="actif" {{ old('statut') == 'actif' ? 'selected' : '' }}>Actif</option>
                    <option value="inactif" {{ old('statut') == 'inactif' ? 'selected' : '' }}>Inactif</option>
                </select>
                @error('statut') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('admin.personnel.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg transition">Annuler</a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">Ajouter l'agent</button>
        </div>
    </form>
</div>
@endsection