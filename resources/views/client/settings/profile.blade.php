@extends('layouts.client')

@section('title', 'Paramètres - Mon profil')
@section('header', 'Mon profil')

@section('content')
<div class="container mx-auto px-4 py-6">
    {{-- Messages flash --}}
    @if(session('success'))
    <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-lg">
        <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-lg">
        <i class="fas fa-exclamation-circle mr-2"></i> Veuillez corriger les erreurs ci-dessous.
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        {{-- Sidebar --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-md p-4">
                <div class="text-center mb-6">
                    <div class="w-20 h-20 rounded-full bg-blue-100 flex items-center justify-center mx-auto border-2 border-blue-200">
                        <i class="fas fa-user text-3xl text-blue-500"></i>
                    </div>
                    <h4 class="mt-2 font-medium text-gray-800">{{ $user->prenom ?? $user->name }} {{ $user->name }}</h4>
                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                </div>
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('client.settings.profile') }}" class="flex items-center px-3 py-2 rounded-lg bg-blue-50 text-blue-600 font-medium">
                            <i class="fas fa-user-circle w-5 mr-2"></i> Mon profil
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('client.settings.password') }}" class="flex items-center px-3 py-2 rounded-lg hover:bg-gray-50 text-gray-700 transition">
                            <i class="fas fa-lock w-5 mr-2"></i> Sécurité
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('client.reservations.index') }}" class="flex items-center px-3 py-2 rounded-lg hover:bg-gray-50 text-gray-700 transition">
                            <i class="fas fa-ticket-alt w-5 mr-2"></i> Mes réservations
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('client.paiements.index') }}" class="flex items-center px-3 py-2 rounded-lg hover:bg-gray-50 text-gray-700 transition">
                            <i class="fas fa-credit-card w-5 mr-2"></i> Mes paiements
                        </a>
                    </li>
                    <li class="pt-2 border-t border-gray-200 mt-2">
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="flex items-center w-full px-3 py-2 rounded-lg hover:bg-red-50 text-red-600 transition">
                                <i class="fas fa-sign-out-alt w-5 mr-2"></i> Déconnexion
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Formulaire --}}
        <div class="lg:col-span-3">
            <div class="bg-white rounded-xl shadow-md p-6">
                <form method="POST" action="{{ route('client.settings.update.profile') }}" id="profileForm" novalidate>
                    @csrf

                    {{-- Informations personnelles --}}
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">
                            <i class="fas fa-user-circle text-blue-500 mr-2"></i>Informations personnelles
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nom <span class="text-red-500">*</span></label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                                @error('name')
                                    <p class="text-red-500 text-xs mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Prénom <span class="text-red-500">*</span></label>
                                <input type="text" name="prenom" value="{{ old('prenom', $user->prenom) }}" required
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('prenom') border-red-500 @enderror">
                                @error('prenom')
                                    <p class="text-red-500 text-xs mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Date de naissance</label>
                                <input type="date" name="date_naissance" value="{{ old('date_naissance', isset($client) && $client->date_naissance ? $client->date_naissance->format('Y-m-d') : '') }}"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('date_naissance') border-red-500 @enderror">
                                @error('date_naissance')
                                    <p class="text-red-500 text-xs mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Genre</label>
                                <select name="genre" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('genre') border-red-500 @enderror">
                                    <option value="">Sélectionner</option>
                                    <option value="Homme" {{ old('genre', $client->genre ?? '') == 'Homme' ? 'selected' : '' }}>Homme</option>
                                    <option value="Femme" {{ old('genre', $client->genre ?? '') == 'Femme' ? 'selected' : '' }}>Femme</option>
                                    <option value="Autre" {{ old('genre', $client->genre ?? '') == 'Autre' ? 'selected' : '' }}>Autre</option>
                                </select>
                                @error('genre')
                                    <p class="text-red-500 text-xs mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nationalité</label>
                                <input type="text" name="nationalite" value="{{ old('nationalite', $client->nationalite ?? '') }}"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nationalite') border-red-500 @enderror">
                                @error('nationalite')
                                    <p class="text-red-500 text-xs mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Coordonnées --}}
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">
                            <i class="fas fa-address-card text-blue-500 mr-2"></i>Coordonnées
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror">
                                @error('email')
                                    <p class="text-red-500 text-xs mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone <span class="text-red-500">*</span></label>
                                <input type="tel" name="telephone" value="{{ old('telephone', $user->telephone) }}" required
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('telephone') border-red-500 @enderror">
                                @error('telephone')
                                    <p class="text-red-500 text-xs mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Adresse</label>
                                <input type="text" name="adresse" value="{{ old('adresse', $client->adresse ?? '') }}"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('adresse') border-red-500 @enderror">
                                @error('adresse')
                                    <p class="text-red-500 text-xs mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Sécurité --}}
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">
                            <i class="fas fa-lock text-blue-500 mr-2"></i>Sécurité
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nouveau mot de passe (laisser vide pour ne pas changer)</label>
                                <input type="password" name="password" id="password"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror">
                                @error('password')
                                    <p class="text-red-500 text-xs mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Confirmer le mot de passe</label>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <p id="passwordMatch" class="text-xs mt-1 hidden"></p>
                            </div>
                        </div>
                        <div class="mt-2 text-sm text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i> Le mot de passe doit contenir au moins 8 caractères.
                        </div>
                    </div>

                    {{-- Bouton --}}
                    <div class="flex justify-end border-t pt-4">
                        <button type="submit" id="submitBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition flex items-center">
                            <i class="fas fa-save mr-2"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Vérification de la correspondance des mots de passe
        const password = document.getElementById('password');
        const passwordConfirm = document.getElementById('password_confirmation');
        const passwordMatch = document.getElementById('passwordMatch');

        function checkPasswordMatch() {
            if (password.value || passwordConfirm.value) {
                if (password.value === passwordConfirm.value) {
                    passwordMatch.textContent = '✅ Les mots de passe correspondent.';
                    passwordMatch.className = 'text-xs mt-1 text-green-600';
                    passwordMatch.classList.remove('hidden');
                } else {
                    passwordMatch.textContent = '❌ Les mots de passe ne correspondent pas.';
                    passwordMatch.className = 'text-xs mt-1 text-red-600';
                    passwordMatch.classList.remove('hidden');
                }
            } else {
                passwordMatch.classList.add('hidden');
            }
        }

        password.addEventListener('input', checkPasswordMatch);
        passwordConfirm.addEventListener('input', checkPasswordMatch);

        // Validation du formulaire avant soumission
        document.getElementById('profileForm').addEventListener('submit', function(e) {
            if (password.value || passwordConfirm.value) {
                if (password.value !== passwordConfirm.value) {
                    e.preventDefault();
                    alert('Les mots de passe ne correspondent pas.');
                    passwordConfirm.focus();
                    return false;
                }
                if (password.value.length < 8) {
                    e.preventDefault();
                    alert('Le mot de passe doit contenir au moins 8 caractères.');
                    password.focus();
                    return false;
                }
            }
            // Désactiver le bouton pour éviter les doublons
            document.getElementById('submitBtn').disabled = true;
            document.getElementById('submitBtn').innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Enregistrement...';
            return true;
        });
    });
</script>
@endsection