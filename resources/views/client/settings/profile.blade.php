@extends('layouts.client')

@section('title', 'Paramètres - Mon profil')
@section('header', 'Mon profil')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-4 gap-6">
    <div class="bg-white rounded-lg shadow p-4">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">📂 Navigation</h3>
        <ul class="space-y-2">
            <li><a href="{{ route('client.settings.profile') }}" class="block px-3 py-2 rounded bg-blue-50 text-blue-600 font-semibold">👤 Mon profil</a></li>
            <li><a href="{{ route('client.settings.theme') }}" class="block px-3 py-2 rounded hover:bg-blue-50 text-blue-600">🎨 Thème</a></li>
        </ul>
    </div>

    <div class="md:col-span-3 bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('client.settings.update.profile') }}">
            @csrf

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Prénom</label>
                    <input type="text" name="prenom" value="{{ old('prenom', $user->prenom) }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                    <input type="text" name="telephone" value="{{ old('telephone', $user->telephone) }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Adresse</label>
                <input type="text" name="adresse" value="{{ old('adresse', $client->adresse ?? '') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nationalité</label>
                    <input type="text" name="nationalite" value="{{ old('nationalite', $client->nationalite ?? '') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Genre</label>
                    <select name="genre" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value="">Sélectionner</option>
                        <option value="Homme" {{ ($client->genre ?? '') == 'Homme' ? 'selected' : '' }}>Homme</option>
                        <option value="Femme" {{ ($client->genre ?? '') == 'Femme' ? 'selected' : '' }}>Femme</option>
                        <option value="Autre" {{ ($client->genre ?? '') == 'Autre' ? 'selected' : '' }}>Autre</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">Enregistrer</button>
            </div>
        </form>
    </div>
</div>
@endsection