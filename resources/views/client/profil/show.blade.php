@extends('layouts.client')

@section('title', 'Mon profil')
@section('header', 'Mon profil')

@section('content')
<div class="bg-white rounded-xl shadow-md p-6">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-500">Nom</label>
            <p class="text-lg font-semibold">{{ $client->nom }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Prénom</label>
            <p class="text-lg font-semibold">{{ $client->prenom }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Email</label>
            <p class="text-lg font-semibold">{{ $client->email }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Téléphone</label>
            <p class="text-lg font-semibold">{{ $client->telephone }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Adresse</label>
            <p class="text-lg font-semibold">{{ $client->adresse ?? 'Non renseignée' }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Nationalité</label>
            <p class="text-lg font-semibold">{{ $client->nationalite ?? 'Non renseignée' }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Genre</label>
            <p class="text-lg font-semibold">{{ $client->genre ?? 'Non renseigné' }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Date d'inscription</label>
            <p class="text-lg font-semibold">{{ $client->date_inscription->format('d/m/Y H:i') }}</p>
        </div>
    </div>
    
    <div class="flex justify-end space-x-3 mt-6">
        <a href="{{ route('client.profil.edit') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg">Modifier</a>
    </div>
</div>
@endsection