@extends('layouts.admin')

@section('title', 'Ajouter une liaison Port ↔ Trajet')
@section('header', 'Ajouter une liaison')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <form method="POST" action="{{ route('admin.conceder.store') }}">
        @csrf

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Port</label>
                <select name="idport" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">Sélectionner un port</option>
                    @foreach($ports as $port)
                        <option value="{{ $port->id }}">{{ $port->nom }} ({{ $port->ville }})</option>
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

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ordre étape</label>
                <input type="number" name="ordre_etape" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Rôle du port</label>
                <input type="text" name="role_port" required class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="ex: départ, escale, arrivée">
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">Créer</button>
        </div>
    </form>
</div>
@endsection