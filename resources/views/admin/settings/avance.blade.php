@extends('layouts.admin')

@section('title', 'Paramètres - Avancé')
@section('header', 'Avancé')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-4 gap-6">
    <div class="bg-white rounded-lg shadow p-4">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">📂 Navigation</h3>
        <ul class="space-y-2">
            <li><a href="{{ route('admin.settings.general') }}" class="block px-3 py-2 rounded hover:bg-blue-50 text-blue-600">⚙️ Général</a></li>
            <li><a href="{{ route('admin.settings.notifications') }}" class="block px-3 py-2 rounded hover:bg-blue-50 text-blue-600">🔔 Notifications</a></li>
            <li><a href="{{ route('admin.settings.securite') }}" class="block px-3 py-2 rounded hover:bg-blue-50 text-blue-600">🔒 Sécurité</a></li>
            <li><a href="{{ route('admin.settings.facturation') }}" class="block px-3 py-2 rounded hover:bg-blue-50 text-blue-600">💰 Facturation</a></li>
            <li><a href="{{ route('admin.settings.apparence') }}" class="block px-3 py-2 rounded hover:bg-blue-50 text-blue-600">🎨 Apparence</a></li>
            <li><a href="{{ route('admin.settings.avance') }}" class="block px-3 py-2 rounded bg-blue-50 text-blue-600 font-semibold">⚡ Avancé</a></li>
        </ul>
    </div>

    <div class="md:col-span-3 bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('admin.settings.update.avance') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Mode maintenance</label>
                <select name="maintenance_mode" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="0" {{ !config('app.maintenance') ? 'selected' : '' }}>Désactivé</option>
                    <option value="1" {{ config('app.maintenance') ? 'selected' : '' }}>Activé</option>
                </select>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">Enregistrer</button>
            </div>
        </form>
    </div>
</div>
@endsection