@extends('layouts.admin')

@section('title', 'Paramètres')
@section('header', 'Paramètres généraux')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-4 gap-6">
    {{-- Menu latéral des sous-pages --}}
    <div class="bg-white rounded-lg shadow p-4">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">📂 Navigation</h3>
        <ul class="space-y-2">
            <li><a href="{{ route('admin.settings.general') }}" class="block px-3 py-2 rounded hover:bg-blue-50 text-blue-600">⚙️ Général</a></li>
            <li><a href="{{ route('admin.settings.notifications') }}" class="block px-3 py-2 rounded hover:bg-blue-50 text-blue-600">🔔 Notifications</a></li>
            <li><a href="{{ route('admin.settings.securite') }}" class="block px-3 py-2 rounded hover:bg-blue-50 text-blue-600">🔒 Sécurité</a></li>
            <li><a href="{{ route('admin.settings.facturation') }}" class="block px-3 py-2 rounded hover:bg-blue-50 text-blue-600">💰 Facturation</a></li>
            <li><a href="{{ route('admin.settings.apparence') }}" class="block px-3 py-2 rounded hover:bg-blue-50 text-blue-600">🎨 Apparence</a></li>
            <li><a href="{{ route('admin.settings.avance') }}" class="block px-3 py-2 rounded hover:bg-blue-50 text-blue-600">⚡ Avancé</a></li>
        </ul>
    </div>

    {{-- Contenu principal --}}
    <div class="md:col-span-3 bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Bienvenue dans les paramètres</h2>
        <p class="text-gray-600">Sélectionnez une section dans le menu de gauche pour configurer l'application.</p>
    </div>
</div>
@endsection