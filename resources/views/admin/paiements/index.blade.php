@extends('layouts.admin')

@section('title', 'Gestion des Paiements')
@section('header', 'Gestion des Paiements')

@section('content')
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Réservation</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Montant</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Devise</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mode</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach($paiements as $paiement)
            <tr>
                <td class="px-6 py-4">{{ $paiement->id }}</td>
                <td class="px-6 py-4">#{{ $paiement->idreservation }}</td>
                <td class="px-6 py-4">{{ number_format($paiement->montant, 0, ',', ' ') }}</td>
                <td class="px-6 py-4">{{ $paiement->devise }}</td>
                <td class="px-6 py-4">{{ $paiement->mode_paiement }}</td>
                <td class="px-6 py-4">{{ $paiement->date_paiement->format('d/m/Y H:i') }}</td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 text-xs rounded-full 
                        {{ $paiement->statut == 'paye' ? 'bg-green-100 text-green-800' : 
                           ($paiement->statut == 'en_attente' ? 'bg-yellow-100 text-yellow-800' : 
                           ($paiement->statut == 'rembourse' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800')) }}">
                        {{ $paiement->statut }}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <a href="{{ route('admin.paiements.show', $paiement->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">Voir</a>
                    <form action="{{ route('admin.paiements.destroy', $paiement->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Supprimer ce paiement ?')">Supprimer</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection