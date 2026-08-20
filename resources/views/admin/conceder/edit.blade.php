@extends('layouts.admin')

@section('title', 'Modifier une liaison Port ↔ Trajet')
@section('header', 'Modifier la liaison')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <form method="POST" action="{{ route('admin.conceder.update', [$conceder->idport, $conceder->idtrajet]) }}">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Port</label>
                <select name="idport" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">Sélectionner un port</option>
                    @foreach($ports as $port)
                        <option value="{{ $port->id }}" {{ $conceder->idport == $port->id ? 'selected' : '' }}>{{ $port->nom }} ({{ $port->ville }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Trajet</label>
                <select name="idtrajet" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">Sélectionner un trajet</option>
                    @foreach($trajets as $trajet)
                        <option value="{{ $trajet->id }}" {{ $conceder->idtrajet == $trajet->id ? 'selected' : '' }}>{{ $trajet->nom }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ordre étape</label>
                <input type="number" name="ordre_etape" value="{{ old('ordre_etape', $conceder->ordre_etape) }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Rôle du port</label>
                <input type="text" name="role_port" value="{{ old('role_port', $conceder->role_port) }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">Mettre à jour</button>
        </div>
    </form>
</div>
@endsection