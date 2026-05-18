@extends('layouts.client')

@section('title', 'Mes paiements')
@section('header', 'Mes paiements')

@section('content')
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="overflow-x-auto">
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
                @forelse($paiements as $paiement)
                <tr>
                    <td class="px-6 py-4">#{{ $paiement->id }}</td>
                    <td class="px-6 py-4">#{{ $paiement->idreservation }}</td>
                    <td class="px-6 py-4">{{ number_format($paiement->montant, 0, ',', ' ') }}</td>
                    <td class="px-6 py-4">{{ $paiement->devise }}</td>
                    <td class="px-6 py-4">{{ $paiement->mode_paiement }}</td>
                    <td class="px-6 py-4">{{ $paiement->date_paiement->format('d/m/Y H:i') }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full 
                            {{ $paiement->statut == 'paye' ? 'bg-green-100 text-green-800' : 
                               ($paiement->statut == 'en_attente' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            {{ $paiement->statut }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('client.paiements.show', $paiement->id) }}" class="text-blue-600 hover:text-blue-900">Voir</a>
                    </td>
                </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                            Aucun paiement trouvé.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection