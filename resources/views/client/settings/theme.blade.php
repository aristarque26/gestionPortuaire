@extends('layouts.client')

@section('title', 'Paramètres - Thème')
@section('header', 'Thème')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-4 gap-6">
    <div class="bg-white rounded-lg shadow p-4">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">📂 Navigation</h3>
        <ul class="space-y-2">
            <li><a href="{{ route('client.settings.profile') }}" class="block px-3 py-2 rounded hover:bg-blue-50 text-blue-600">👤 Mon profil</a></li>
            <li><a href="{{ route('client.settings.theme') }}" class="block px-3 py-2 rounded bg-blue-50 text-blue-600 font-semibold">🎨 Thème</a></li>
        </ul>
    </div>

    <div class="md:col-span-3 bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('client.settings.update.theme') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Thème</label>
                <select name="theme" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="light" {{ session('client_theme') == 'light' ? 'selected' : '' }}>Clair</option>
                    <option value="dark" {{ session('client_theme') == 'dark' ? 'selected' : '' }}>Sombre</option>
                </select>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">Enregistrer</button>
            </div>
        </form>
    </div>
</div>
@endsection