@extends('layouts.admin')

@section('title', 'Détails de la liaison')
@section('header', 'Détails')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-500">ID</label>
            <p class="text-lg font-semibold">{{ $appartenir->id }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Bateau</label>
            <p class="text-lg font-semibold">{{ $appartenir->bateau->nom ?? 'N/A' }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Quai</label>
            <p class="text-lg font-semibold">{{ $appartenir->quai->nom ?? 'N/A' }} (n°{{ $appartenir->quai->numero ?? '' }})</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-500">Date création</label>
            <p class="text-lg font-semibold">{{ $appartenir->created_at->format('d/m/Y H:i') }}</p>
        </div>
    </div>

    <div class="flex justify-end space-x-3 mt-6">
        <a href="{{ route('admin.appartenir.edit', $appartenir->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg">Modifier</a>
        <a href="{{ route('admin.appartenir.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">Retour</a>
    </div>
</div>
@endsection