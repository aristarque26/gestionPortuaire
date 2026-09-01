@extends('layouts.personnel')

@section('title', 'Tableau de bord - Personnel')
@section('header', 'Espace Agent Portuaire')

@section('content')
<div class="bg-white rounded-xl shadow-md p-6">
    <h2 class="text-2xl font-bold text-gray-800">Bienvenue, {{ Auth::user()->nom_complet }} !</h2>
    <p class="text-gray-600 mt-2">Vous êtes connecté en tant qu'agent portuaire.</p>
    <p class="text-gray-600">Poste : {{ Auth::user()->personnel->poste ?? 'Non défini' }}</p>
    <p class="text-gray-600">Rôle : {{ Auth::user()->personnel->role_label ?? 'Non défini' }}</p>
</div>
@endsection