<!-- resources/views/personnel/comptable/dashboard.blade.php -->
@extends('layouts.app')

@section('content')
<div class="flex">
    @include('superviseur.layouts.sidebar')
    
    <div class="flex-1 p-6 bg-gray-100 min-h-screen">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Dashboard Comptable</h1>
            <div class="text-sm text-gray-500">{{ now()->format('d/m/Y H:i') }}</div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Bienvenue, {{ $user->name }} {{ $user->prenom }} !</h2>
            <p class="text-gray-600">Comptable - {{ $user->personnel->poste ?? 'N/A' }}</p>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
                <p class="text-sm text-gray-500">CA aujourd'hui</p>
                <p class="text-2xl font-bold text-green-600">{{ number_format($stats['ca_aujourdhui'], 0, ',', ' ') }} FC</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
                <p class="text-sm text-gray-500">En attente</p>
                <p class="text-2xl font-bold text-yellow-600">{{ $stats['paiements_attente'] }}</p>
                <p class="text-xs text-gray-500">{{ number_format($stats['montant_attente'], 0, ',', ' ') }} FC</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
                <p class="text-sm text-gray-500">CA ce mois</p>
                <p class="text-2xl font-bold text-purple-600">{{ number_format($stats['ca_mois'], 0, ',', ' ') }} FC</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                <p class="text-sm text-gray-500">CA total</p>
                <p class="text-2xl font-bold text-blue-600">{{ number_format($stats['ca_total'], 0, ',', ' ') }} FC</p>
            </div>
        </div>

        <!-- Derniers paiements -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Paiements en attente</h3>
                <div class="space-y-3">
                    @forelse($paiementsAttente as $p)
                    <div class="flex justify-between items-center border-b pb-2">
                        <div>
                            <p class="text-sm font-medium">{{ $p->reservation->client->nom ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500">{{ $p->mode_paiement }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-yellow-600">{{ number_format($p->montant, 0, ',', ' ') }} {{ $p->devise }}</p>
                            <a href="{{ route('comptable.paiements.valider', $p->id) }}" 
                               class="text-xs text-green-600 hover:underline"
                               onclick="return confirm('Valider ce paiement ?')">Valider</a>
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-gray-500">Aucun paiement en attente</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Derniers paiements</h3>
                <div class="space-y-3">
                    @forelse($derniersPaiements as $p)
                    <div class="flex justify-between items-center border-b pb-2">
                        <div>
                            <p class="text-sm font-medium">{{ $p->reservation->client->nom ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500">{{ Carbon\Carbon::parse($p->date_paiement)->format('d/m/Y') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-green-600">{{ number_format($p->montant, 0, ',', ' ') }} {{ $p->devise }}</p>
                            <span class="text-xs px-2 py-1 rounded-full bg-green-100 text-green-800">Payé</span>
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-gray-500">Aucun paiement</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection