@extends('layouts.client')

@section('title', 'Modifier mon profil')
@section('header', 'Modifier mon profil')

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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Formulaire --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-md p-6">
                <form method="POST" action="{{ route('client.profil.update') }}" id="profileForm" novalidate>
                    @csrf
                    @method('PUT')

                    {{-- Informations personnelles --}}
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">
                            <i class="fas fa-user-circle text-blue-500 mr-2"></i>Informations personnelles
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nom <span class="text-red-500">*</span></label>
                                <input type="text" name="nom" value="{{ old('nom', $client->nom) }}" required 
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nom') border-red-500 @enderror">
                                @error('nom')
                                    <p class="text-red-500 text-xs mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Prénom <span class="text-red-500">*</span></label>
                                <input type="text" name="prenom" value="{{ old('prenom', $client->prenom) }}" required 
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('prenom') border-red-500 @enderror">
                                @error('prenom')
                                    <p class="text-red-500 text-xs mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Date de naissance</label>
                                <input type="date" name="date_naissance" value="{{ old('date_naissance', $client->date_naissance ? $client->date_naissance->format('Y-m-d') : '') }}" 
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('date_naissance') border-red-500 @enderror">
                                @error('date_naissance')
                                    <p class="text-red-500 text-xs mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Genre</label>
                                <select name="genre" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('genre') border-red-500 @enderror">
                                    <option value="">Sélectionner</option>
                                    <option value="Homme" {{ old('genre', $client->genre) == 'Homme' ? 'selected' : '' }}>Homme</option>
                                    <option value="Femme" {{ old('genre', $client->genre) == 'Femme' ? 'selected' : '' }}>Femme</option>
                                    <option value="Autre" {{ old('genre', $client->genre) == 'Autre' ? 'selected' : '' }}>Autre</option>
                                </select>
                                @error('genre')
                                    <p class="text-red-500 text-xs mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nationalité</label>
                                <input type="text" name="nationalite" value="{{ old('nationalite', $client->nationalite) }}" 
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
                                <input type="email" name="email" value="{{ old('email', $client->email) }}" required 
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror">
                                @error('email')
                                    <p class="text-red-500 text-xs mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone <span class="text-red-500">*</span></label>
                                <input type="tel" name="telephone" value="{{ old('telephone', $client->telephone) }}" required 
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('telephone') border-red-500 @enderror">
                                @error('telephone')
                                    <p class="text-red-500 text-xs mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Adresse</label>
                                <input type="text" name="adresse" value="{{ old('adresse', $client->adresse) }}" 
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

                    {{-- Boutons --}}
                    <div class="flex flex-wrap justify-end space-x-3 border-t pt-4">
                        <a href="{{ route('client.profil.show') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition flex items-center">
                            <i class="fas fa-times mr-2"></i> Annuler
                        </a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition flex items-center">
                            <i class="fas fa-save mr-2"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Sidebar : informations additionnelles --}}
        <div class="space-y-6">
            {{-- Avatar --}}
            <div class="bg-white rounded-xl shadow-md p-6 text-center">
                <div class="relative inline-block">
                    <div class="w-32 h-32 rounded-full bg-blue-100 flex items-center justify-center mx-auto border-4 border-blue-200">
                        <i class="fas fa-user text-5xl text-blue-500"></i>
                    </div>
                    <button type="button" class="absolute bottom-0 right-0 bg-blue-600 text-white p-2 rounded-full shadow-lg hover:bg-blue-700 transition" title="Changer la photo de profil (fonctionnalité à venir)">
                        <i class="fas fa-camera text-sm"></i>
                    </button>
                </div>
                <h4 class="mt-4 font-medium text-gray-800">{{ $client->prenom }} {{ $client->nom }}</h4>
                <p class="text-sm text-gray-500">{{ $client->email }}</p>
                <div class="mt-4 text-xs text-gray-400">
                    <p><i class="fas fa-user-check mr-1"></i> Membre depuis {{ $client->created_at->format('d/m/Y') }}</p>
                    <p><i class="fas fa-clock mr-1"></i> Dernière modification : {{ $client->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>

            {{-- Conseils --}}
            <div class="bg-blue-50 rounded-xl p-4 border border-blue-200">
                <h5 class="font-semibold text-blue-800"><i class="fas fa-lightbulb mr-2"></i>Conseils</h5>
                <ul class="text-sm text-blue-700 mt-2 space-y-1 list-disc list-inside">
                    <li>Utilisez un email valide pour recevoir vos confirmations.</li>
                    <li>Votre téléphone nous permet de vous contacter en cas d'urgence.</li>
                    <li>Choisissez un mot de passe sécurisé (au moins 8 caractères).</li>
                </ul>
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
            return true;
        });
    });
</script>
@endsection