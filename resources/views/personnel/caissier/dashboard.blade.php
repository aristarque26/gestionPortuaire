<!-- resources/views/personnel/caissier/dashboard.blade.php -->
@extends('layouts.app')

@section('content')
<div class="flex">
    @include('superviseur.layouts.sidebar')
    
    <div class="flex-1 p-6 bg-gray-100 min-h-screen">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Dashboard Caissier</h1>
            <div class="text-sm text-gray-500">{{ now()->format('d/m/Y H:i') }}</div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Bienvenue, {{ $user->name }} {{ $user->prenom }} !</h2>
            <p class="text-gray-600">Caissier - {{ $user->personnel->poste ?? 'N/A' }}</p>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                <p class="text-sm text-gray-500">Paiements aujourd'hui</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['paiements_aujourdhui'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
                <p class="text-sm text-gray-500">CA aujourd'hui</p>
                <p class="text-2xl font-bold text-green-600">{{ number_format($stats['ca_aujourdhui'], 0, ',', ' ') }} FC</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
                <p class="text-sm text-gray-500">En attente</p>
                <p class="text-2xl font-bold text-yellow-600">{{ $stats['paiements_attente'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
                <p class="text-sm text-gray-500">Total encaissé</p>
                <p class="text-2xl font-bold text-purple-600">{{ $stats['total_paye'] }}</p>
            </div>
        </div>

        <!-- Paiements à encaisser -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Paiements à encaisser</h3>
                <a href="{{ route('caissier.paiements.attente') }}" class="text-blue-600 hover:underline text-sm">Voir tous →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Client</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Montant</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Mode</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Date</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($paiementsAttente as $p)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm">{{ $p->reservation->client->nom ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-sm font-bold">{{ number_format($p->montant, 0, ',', ' ') }} {{ $p->devise }}</td>
                            <td class="px-4 py-3 text-sm">{{ $p->mode_paiement }}</td>
                            <td class="px-4 py-3 text-sm">{{ Carbon\Carbon::parse($p->date_paiement)->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3 text-sm">
                                <form method="POST" action="{{ route('caissier.paiements.encaisser', $p->id) }}" class="inline">
                                    @csrf @method('PUT')
                                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded-lg text-xs"
                                            onclick="return confirm('Encaisser ce paiement ?')">
                                        Encaisser
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-gray-500">Aucun paiement en attente</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection