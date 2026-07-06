@extends('layouts.client')

@section('title', 'Modifier mon profil')
@section('header', 'Modifier mon profil')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
    <div class="container mx-auto px-4 py-8 max-w-7xl">

        {{-- Messages flash --}}
        @if(session('success'))
        <div class="mb-6 p-5 bg-gradient-to-r from-emerald-50 to-green-50 border-l-4 border-emerald-500 rounded-2xl shadow-md flex items-start gap-3 group hover:shadow-lg transition-all">
            <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-green-600 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                <i class="fas fa-check-circle text-white"></i>
            </div>
            <div class="flex-1">
                <p class="font-semibold text-emerald-800">Succès !</p>
                <p class="text-sm text-emerald-700 mt-1">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        @if($errors->any())
        <div class="mb-6 p-5 bg-gradient-to-r from-red-50 to-pink-50 border-l-4 border-red-500 rounded-2xl shadow-md flex items-start gap-3 group hover:shadow-lg transition-all">
            <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-pink-600 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                <i class="fas fa-exclamation-circle text-white"></i>
            </div>
            <div class="flex-1">
                <p class="font-semibold text-red-800">Attention !</p>
                <p class="text-sm text-red-700 mt-1">Veuillez corriger les erreurs ci-dessous.</p>
                <ul class="text-xs text-red-600 mt-2 space-y-1">
                    @foreach($errors->all() as $error)
                        <li class="flex items-center gap-1">
                            <i class="fas fa-chevron-right text-[8px]"></i> {{ $error }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Formulaire principal --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Informations personnelles --}}
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-user-circle text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Informations personnelles</h3>
                            <p class="text-xs text-gray-500">Vos données d'identité</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('client.profil.update') }}" id="profileForm" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-user text-blue-500 text-xs"></i>
                                    Nom <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nom" value="{{ old('nom', $client->nom) }}" required 
                                    class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none transition-all bg-gray-50 hover:bg-white @error('nom') border-red-400 bg-red-50 @enderror">
                                @error('nom')
                                    <p class="text-red-500 text-xs mt-2 flex items-center gap-1">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-user-tag text-indigo-500 text-xs"></i>
                                    Prénom <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="prenom" value="{{ old('prenom', $client->prenom) }}" required 
                                    class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none transition-all bg-gray-50 hover:bg-white @error('prenom') border-red-400 bg-red-50 @enderror">
                                @error('prenom')
                                    <p class="text-red-500 text-xs mt-2 flex items-center gap-1">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-birthday-cake text-purple-500 text-xs"></i>
                                    Date de naissance
                                </label>
                                <input type="date" name="date_naissance" value="{{ old('date_naissance', $client->date_naissance ? $client->date_naissance->format('Y-m-d') : '') }}" 
                                    class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-purple-400 focus:border-purple-400 outline-none transition-all bg-gray-50 hover:bg-white @error('date_naissance') border-red-400 bg-red-50 @enderror">
                                @error('date_naissance')
                                    <p class="text-red-500 text-xs mt-2 flex items-center gap-1">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-venus-mars text-pink-500 text-xs"></i>
                                    Genre
                                </label>
                                <select name="genre" class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-pink-400 focus:border-pink-400 outline-none transition-all bg-gray-50 hover:bg-white @error('genre') border-red-400 bg-red-50 @enderror">
                                    <option value="">Sélectionner</option>
                                    <option value="Homme" {{ old('genre', $client->genre) == 'Homme' ? 'selected' : '' }}>Homme</option>
                                    <option value="Femme" {{ old('genre', $client->genre) == 'Femme' ? 'selected' : '' }}>Femme</option>
                                    <option value="Autre" {{ old('genre', $client->genre) == 'Autre' ? 'selected' : '' }}>Autre</option>
                                </select>
                                @error('genre')
                                    <p class="text-red-500 text-xs mt-2 flex items-center gap-1">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-flag text-emerald-500 text-xs"></i>
                                    Nationalité
                                </label>
                                <input type="text" name="nationalite" value="{{ old('nationalite', $client->nationalite) }}" 
                                    class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400 outline-none transition-all bg-gray-50 hover:bg-white @error('nationalite') border-red-400 bg-red-50 @enderror">
                                @error('nationalite')
                                    <p class="text-red-500 text-xs mt-2 flex items-center gap-1">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        {{-- Coordonnées --}}
                        <div class="mt-8">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center shadow-lg">
                                    <i class="fas fa-address-card text-white text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-800">Coordonnées</h3>
                                    <p class="text-xs text-gray-500">Comment vous contacter</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-envelope text-blue-500 text-xs"></i>
                                        Email <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" name="email" value="{{ old('email', $client->email) }}" required 
                                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none transition-all bg-gray-50 hover:bg-white @error('email') border-red-400 bg-red-50 @enderror">
                                    @error('email')
                                        <p class="text-red-500 text-xs mt-2 flex items-center gap-1">
                                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-phone text-green-500 text-xs"></i>
                                        Téléphone <span class="text-red-500">*</span>
                                    </label>
                                    <input type="tel" name="telephone" value="{{ old('telephone', $client->telephone) }}" required 
                                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-green-400 focus:border-green-400 outline-none transition-all bg-gray-50 hover:bg-white @error('telephone') border-red-400 bg-red-50 @enderror">
                                    @error('telephone')
                                        <p class="text-red-500 text-xs mt-2 flex items-center gap-1">
                                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-map-marker-alt text-red-500 text-xs"></i>
                                        Adresse
                                    </label>
                                    <input type="text" name="adresse" value="{{ old('adresse', $client->adresse) }}" 
                                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-red-400 focus:border-red-400 outline-none transition-all bg-gray-50 hover:bg-white @error('adresse') border-red-400 bg-red-50 @enderror">
                                    @error('adresse')
                                        <p class="text-red-500 text-xs mt-2 flex items-center gap-1">
                                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Sécurité --}}
                        <div class="mt-8">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center shadow-lg">
                                    <i class="fas fa-lock text-white text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-800">Sécurité</h3>
                                    <p class="text-xs text-gray-500">Protégez votre compte</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-key text-amber-500 text-xs"></i>
                                        Nouveau mot de passe
                                    </label>
                                    <input type="password" name="password" id="password" 
                                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-amber-400 focus:border-amber-400 outline-none transition-all bg-gray-50 hover:bg-white @error('password') border-red-400 bg-red-50 @enderror"
                                        placeholder="Laisser vide pour ne pas changer">
                                    @error('password')
                                        <p class="text-red-500 text-xs mt-2 flex items-center gap-1">
                                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fas fa-shield-alt text-orange-500 text-xs"></i>
                                        Confirmer le mot de passe
                                    </label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" 
                                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-orange-400 focus:border-orange-400 outline-none transition-all bg-gray-50 hover:bg-white">
                                    <p id="passwordMatch" class="text-xs mt-2 hidden flex items-center gap-1"></p>
                                </div>
                            </div>

                            <div class="mt-4 p-4 bg-gradient-to-r from-amber-50 to-yellow-50 border border-amber-200 rounded-xl flex items-start gap-3">
                                <div class="w-8 h-8 bg-gradient-to-br from-amber-500 to-yellow-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-info-circle text-white text-sm"></i>
                                </div>
                                <p class="text-sm text-amber-800">Le mot de passe doit contenir au moins 8 caractères.</p>
                            </div>
                        </div>

                        {{-- Boutons --}}
                        <div class="mt-8 pt-6 border-t border-gray-200 flex flex-wrap justify-end gap-3">
                            <a href="{{ route('client.profil.show') }}" 
                               class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl transition-all duration-300 flex items-center gap-2 font-semibold text-sm shadow-sm hover:shadow-md">
                                <i class="fas fa-times"></i> Annuler
                            </a>
                            <button type="submit" 
                                    class="px-6 py-2.5 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white rounded-xl transition-all duration-300 flex items-center gap-2 font-semibold text-sm shadow-md hover:shadow-lg">
                                <i class="fas fa-save"></i> Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Avatar --}}
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 text-center">
                    <div class="relative inline-block group">
                        <div class="w-36 h-36 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center mx-auto border-4 border-white shadow-xl group-hover:scale-105 transition-transform duration-300">
                            <i class="fas fa-user text-6xl text-white"></i>
                        </div>
                        <button type="button" 
                                class="absolute bottom-0 right-0 w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 text-white rounded-full shadow-lg hover:shadow-xl hover:scale-110 transition-all duration-300 flex items-center justify-center border-4 border-white" 
                                title="Changer la photo de profil (fonctionnalité à venir)">
                            <i class="fas fa-camera"></i>
                        </button>
                    </div>
                    
                    <h4 class="mt-5 text-xl font-bold text-gray-800">{{ $client->prenom }} {{ $client->nom }}</h4>
                    <p class="text-sm text-gray-500 mt-1 flex items-center justify-center gap-1">
                        <i class="fas fa-envelope text-blue-500 text-xs"></i>
                        {{ $client->email }}
                    </p>
                    
                    <div class="mt-5 pt-5 border-t border-gray-200 space-y-2">
                        <div class="flex items-center justify-center gap-2 text-xs text-gray-600">
                            <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center">
                                <i class="fas fa-user-check text-blue-500 text-sm"></i>
                            </div>
                            <span>Membre depuis {{ $client->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex items-center justify-center gap-2 text-xs text-gray-600">
                            <div class="w-8 h-8 bg-emerald-50 rounded-lg flex items-center justify-center">
                                <i class="fas fa-clock text-emerald-500 text-sm"></i>
                            </div>
                            <span>Dernière modification : {{ $client->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Conseils --}}
                <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl shadow-lg p-6 text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-3xl"></div>
                    <div class="absolute bottom-0 left-0 w-32 h-32 bg-blue-400/20 rounded-full blur-3xl"></div>
                    
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center">
                                <i class="fas fa-lightbulb text-lg"></i>
                            </div>
                            <h5 class="font-bold text-lg">Conseils</h5>
                        </div>
                        
                        <ul class="space-y-3 text-sm">
                            <li class="flex items-start gap-2">
                                <i class="fas fa-check-circle text-blue-200 mt-0.5 flex-shrink-0"></i>
                                <span>Utilisez un email valide pour recevoir vos confirmations.</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="fas fa-check-circle text-blue-200 mt-0.5 flex-shrink-0"></i>
                                <span>Votre téléphone nous permet de vous contacter en cas d'urgence.</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="fas fa-check-circle text-blue-200 mt-0.5 flex-shrink-0"></i>
                                <span>Choisissez un mot de passe sécurisé (au moins 8 caractères).</span>
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- Progression du profil --}}
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-chart-line text-white"></i>
                        </div>
                        <div>
                            <h5 class="font-bold text-gray-800">Profil complété</h5>
                            <p class="text-xs text-gray-500">Continuez à remplir vos infos</p>
                        </div>
                    </div>
                    
                    @php
                        $progress = 0;
                        $total = 0;
                        if($client->nom) $progress += 15;
                        if($client->prenom) $progress += 15;
                        if($client->email) $progress += 20;
                        if($client->telephone) $progress += 15;
                        if($client->date_naissance) $progress += 10;
                        if($client->genre) $progress += 10;
                        if($client->nationalite) $progress += 10;
                        if($client->adresse) $progress += 5;
                        $progress = min($progress, 100);
                    @endphp
                    
                    <div class="relative">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-2xl font-bold text-gray-800">{{ $progress }}%</span>
                            @if($progress == 100)
                                <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full flex items-center gap-1">
                                    <i class="fas fa-check"></i> Complet
                                </span>
                            @else
                                <span class="text-xs font-semibold text-amber-600 bg-amber-50 px-2 py-1 rounded-full flex items-center gap-1">
                                    <i class="fas fa-exclamation"></i> À compléter
                                </span>
                            @endif
                        </div>
                        
                        <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                            <div class="bg-gradient-to-r from-emerald-500 to-teal-600 h-3 rounded-full transition-all duration-1000 relative overflow-hidden" 
                                 style="width: {{ $progress }}%">
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent animate-shimmer"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Styles personnalisés --}}
@push('styles')
<style>
    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }
    .animate-shimmer {
        animation: shimmer 2s infinite;
    }
</style>
@endpush

{{-- Script pour validation --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const password = document.getElementById('password');
        const passwordConfirm = document.getElementById('password_confirmation');
        const passwordMatch = document.getElementById('passwordMatch');

        function checkPasswordMatch() {
            if (password.value || passwordConfirm.value) {
                if (password.value === passwordConfirm.value) {
                    passwordMatch.innerHTML = '<i class="fas fa-check-circle text-emerald-500"></i> Les mots de passe correspondent.';
                    passwordMatch.className = 'text-xs mt-2 text-emerald-600 flex items-center gap-1';
                    passwordMatch.classList.remove('hidden');
                } else {
                    passwordMatch.innerHTML = '<i class="fas fa-times-circle text-red-500"></i> Les mots de passe ne correspondent pas.';
                    passwordMatch.className = 'text-xs mt-2 text-red-600 flex items-center gap-1';
                    passwordMatch.classList.remove('hidden');
                }
            } else {
                passwordMatch.classList.add('hidden');
            }
        }

        password.addEventListener('input', checkPasswordMatch);
        passwordConfirm.addEventListener('input', checkPasswordMatch);

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
@endpush

@endsection