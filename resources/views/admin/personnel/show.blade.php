@extends('layouts.admin')

@section('title', 'Détails de l\'agent')
@section('header', 'Détails de l\'agent')

@section('content')
<div class="bg-white rounded-xl shadow-md p-6 max-w-4xl mx-auto">
    <div class="flex justify-between items-start mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Fiche de l'agent</h2>
        <div class="flex gap-2">
            <a href="{{ route('admin.personnel.edit', $personnel->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition">
                <i class="fas fa-edit mr-1"></i> Modifier
            </a>
            <a href="{{ route('admin.personnel.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg transition">
                <i class="fas fa-arrow-left mr-1"></i> Retour
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- Colonne 1 --}}
        <div>
            <p><strong>Matricule :</strong> {{ $personnel->matricule }}</p>
            <p><strong>Nom complet :</strong> {{ $personnel->user->nom_complet ?? 'N/A' }}</p>
            <p><strong>Email :</strong> {{ $personnel->user->email ?? 'N/A' }}</p>
            <p><strong>Téléphone :</strong> {{ $personnel->user->telephone ?? 'N/A' }}</p>
            <p><strong>Adresse :</strong> {{ $personnel->user->adresse ?? 'N/A' }}</p>
        </div>

        {{-- Colonne 2 --}}
        <div>
            <p><strong>Poste :</strong> {{ $personnel->poste }}</p>
            <p><strong>Service :</strong> {{ $personnel->service }}</p>
            <p><strong>Rôle :</strong> {{ $personnel->role_label }}</p>
            <p><strong>Salaire :</strong> {{ number_format($personnel->salaire, 0, ',', ' ') }} CDF</p>
            <p><strong>Date d'embauche :</strong> {{ \Carbon\Carbon::parse($personnel->date_embauche)->format('d/m/Y') }}</p>
            <p><strong>Statut :</strong>
                <span class="px-2 py-1 text-xs rounded-full 
                    {{ $personnel->statut == 'actif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ ucfirst($personnel->statut) }}
                </span>
            </p>
        </div>
    </div>
</div>
@endsection