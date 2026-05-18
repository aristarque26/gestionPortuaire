@extends('layouts.admin')

@section('title', 'Ajouter une liaison Bateau ↔ Quai')
@section('header', 'Ajouter une liaison')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <form method="POST" action="{{ route('admin.appartenir.store') }}">
        @csrf

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Bateau</label>
                <select name="idbateau" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">Sélectionner un bateau</option>
                    @foreach($bateaux as $bateau)
                        <option value="{{ $bateau->id }}">{{ $bateau->nom }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Quai</label>
                <select name="idquai" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">Sélectionner un quai</option>
                    @foreach($quais as $quai)
                        <option value="{{ $quai->id }}">{{ $quai->nom }} (n°{{ $quai->numero }})</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">Créer</button>
        </div>
    </form>
</div>
@endsection