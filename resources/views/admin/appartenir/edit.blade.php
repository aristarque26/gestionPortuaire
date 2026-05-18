@extends('layouts.admin')

@section('title', 'Modifier une liaison Bateau ↔ Quai')
@section('header', 'Modifier la liaison')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <form method="POST" action="{{ route('admin.appartenir.update', [$appartenir->idbateau, $appartenir->idquai]) }}">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Bateau</label>
                <select name="idbateau" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">Sélectionner un bateau</option>
                    @foreach($bateaux as $bateau)
                        <option value="{{ $bateau->id }}" {{ $appartenir->idbateau == $bateau->id ? 'selected' : '' }}>{{ $bateau->nom }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Quai</label>
                <select name="idquai" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">Sélectionner un quai</option>
                    @foreach($quais as $quai)
                        <option value="{{ $quai->id }}" {{ $appartenir->idquai == $quai->id ? 'selected' : '' }}>{{ $quai->nom }} (n°{{ $quai->numero }})</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">Mettre à jour</button>
        </div>
    </form>
</div>
@endsection