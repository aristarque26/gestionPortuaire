<x-guest-layout>
    <div class="min-h-[80vh] flex flex-col justify-center items-center px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 transition-all duration-300 transform hover:scale-[1.01]">
            
            <div class="text-center mb-6">
                <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 mb-4">
                    <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.02 5.912L14.55 15.26a.75.75 0 01-1.06 0l-1.06-1.06a.75.75 0 010-1.06l1.06-1.06A.75.75 0 0014 11.386V9.309a6 6 0 014.75-5.807z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                    {{ __('Mot de passe oublié ?') }}
                </h2>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400 max-w-sm mx-auto leading-relaxed">
                    {{ __('Pas de soucis. Entrez votre adresse email et nous vous enverrons un lien pour en choisir un nouveau.') }}
                </p>
            </div>

            <x-auth-session-status class="mb-4 p-4 rounded-xl bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 text-sm shadow-sm" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                @csrf

                <div>
                    <x-input-label for="email" :value="__('Adresse Email')" class="text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400" />
                    <div class="mt-1.5 relative rounded-md shadow-sm">
                        <x-text-input 
                            id="email" 
                            class="block w-full px-4 py-3 rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-indigo-500 focus:ring-indigo-500 transition-colors duration-200" 
                            type="email" 
                            name="email" 
                            :value="old('email')" 
                            placeholder="exemple@domaine.com"
                            required 
                            autofocus 
                        />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs text-red-500" />
                </div>

                <div class="space-y-3 pt-2">
                    <x-primary-button class="w-full justify-center py-3 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-medium shadow-md hover:shadow-indigo-200 dark:hover:shadow-none transition-all duration-150 transform active:scale-[0.98]">
                        {{ __('Envoyer le lien de réinitialisation') }}
                    </x-primary-button>

                    <div class="text-center">
                        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors inline-flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                            </svg>
                            {{ __('Retour à la connexion') }}
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>