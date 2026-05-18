@extends('layouts.admin')

@section('title', 'Gestion des Réservations')
@section('header', 'Gestion des Réservations')

@section('content')
<div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-semibold text-gray-800">Gestion des Réservations</h1>
    <a href="{{ route('admin.export.reservations') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
        📎 Exporter Excel
    </a>
</div>

{{-- Filtres de recherche --}}
<form method="GET" class="mb-4 grid grid-cols-1 md:grid-cols-4 gap-4 bg-white p-4 rounded-lg shadow">
    <input type="text" name="statut" placeholder="Statut (en_attente, confirme, annule, arrive)" value="{{ request('statut') }}" class="border border-gray-300 rounded-lg px-3 py-2">
    <input type="date" name="date_debut" value="{{ request('date_debut') }}" class="border border-gray-300 rounded-lg px-3 py-2">
    <input type="date" name="date_fin" value="{{ request('date_fin') }}" class="border border-gray-300 rounded-lg px-3 py-2">
    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Filtrer</button>
</form>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Voyage</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date réservation</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach($reservations as $reservation)
            <tr>
                <td class="px-6 py-4">{{ $reservation->id }}</td>
                <td class="px-6 py-4">{{ $reservation->client->prenom ?? 'N/A' }} {{ $reservation->client->nom ?? '' }}</td>
                <td class="px-6 py-4">{{ $reservation->voyage->code_voyage ?? 'N/A' }}</td>
                <td class="px-6 py-4">{{ $reservation->type_reservation }}</td>
                <td class="px-6 py-4">{{ $reservation->date_reservation->format('d/m/Y H:i') }}</td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 text-xs rounded-full 
                        {{ $reservation->statut == 'en_attente' ? 'bg-yellow-100 text-yellow-800' : 
                           ($reservation->statut == 'confirme' ? 'bg-green-100 text-green-800' : 
                           ($reservation->statut == 'arrive' ? 'bg-blue-100 text-blue-800' : 
                           ($reservation->statut == 'paye' ? 'bg-purple-100 text-purple-800' : 'bg-red-100 text-red-800'))) }}">
                        {{ $reservation->statut }}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <a href="{{ route('admin.reservations.show', $reservation->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">Voir</a>
                    
                    @if($reservation->statut == 'en_attente')
                        <form action="{{ route('admin.reservations.confirmer', $reservation->id) }}" method="POST" class="inline mr-3">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="text-green-600 hover:text-green-900" onclick="return confirm('Confirmer cette réservation ?')">
                                ✅ Confirmer
                            </button>
                        </form>
                    @endif
                    
                    <form action="{{ route('admin.reservations.destroy', $reservation->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Supprimer cette réservation ?')">🗑️ Supprimer</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $reservations->links() }}
</div>
@endsection