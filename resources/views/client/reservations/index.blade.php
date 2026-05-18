@extends('layouts.client')

@section('title', 'Mes réservations')
@section('header', 'Mes réservations')

@section('content')
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Voyage</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date réservation</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($reservations as $reservation)
                <tr>
                    <td class="px-6 py-4">#{{ $reservation->id }}</td>
                    <td class="px-6 py-4">{{ $reservation->voyage->code_voyage ?? 'N/A' }}</td>
                    <td class="px-6 py-4">{{ $reservation->type_reservation }}</td>
                    <td class="px-6 py-4">{{ $reservation->date_reservation->format('d/m/Y H:i') }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full 
                            {{ $reservation->statut == 'confirme' ? 'bg-green-100 text-green-800' : 
                               ($reservation->statut == 'en_attente' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            {{ $reservation->statut }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('client.reservations.show', $reservation->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">Voir</a>
                        @if($reservation->statut == 'en_attente')
                        <form action="{{ route('client.reservations.destroy', $reservation->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Annuler cette réservation ?')">Annuler</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            Aucune réservation trouvée.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection