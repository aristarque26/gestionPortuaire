@extends('layouts.client')

@section('title', 'Paramètres')
@section('header', 'Paramètres')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-4 gap-6">
    <div class="bg-white rounded-lg shadow p-4">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">📂 Navigation</h3>
        <ul class="space-y-2">
            <li><a href="{{ route('client.settings.profile') }}" class="block px-3 py-2 rounded hover:bg-blue-50 text-blue-600">👤 Mon profil</a></li>
            <li><a href="{{ route('client.settings.theme') }}" class="block px-3 py-2 rounded hover:bg-blue-50 text-blue-600">🎨 Thème (clair/sombre)</a></li>
        </ul>
    </div>

    <div class="md:col-span-3 bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Bienvenue dans les paramètres</h2>
        <p class="text-gray-600">Sélectionnez une section dans le menu de gauche pour configurer votre compte.</p>
    </div>
</div>
@endsection