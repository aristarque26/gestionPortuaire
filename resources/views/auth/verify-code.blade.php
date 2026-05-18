<x-guest-layout>
    <div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-4">Vérification</h2>
        <p class="text-gray-600 mb-4">Un code a été envoyé à <strong>{{ session('verif_email') }}</strong></p>

        @if(session('code'))
            <div class="bg-green-100 text-green-700 p-2 rounded mb-4">
                Code de test (sans email réel) : <strong>{{ session('code') }}</strong>
            </div>
        @endif

        <form method="POST" action="{{ route('find.verify') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Code de vérification</label>
                <input type="text" name="code" required class="mt-1 w-full border rounded-lg px-3 py-2">
                @error('code') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">Vérifier</button>
        </form>
    </div>
</x-guest-layout>