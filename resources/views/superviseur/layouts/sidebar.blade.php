<!-- resources/views/superviseur/layouts/sidebar.blade.php -->
<aside class="w-64 bg-blue-800 text-white min-h-screen flex-shrink-0">
    <div class="p-4 border-b border-blue-700">
        <h2 class="text-xl font-bold">GPP Portuaire</h2>
        <p class="text-sm text-blue-300">Superviseur</p>
    </div>
    
    <nav class="p-2">
        <ul class="space-y-1">
            <!-- Dashboard -->
            <li>
                <a href="{{ route('superviseur.dashboard') }}" 
                   class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->routeIs('superviseur.dashboard') ? 'bg-blue-700' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    Tableau de bord
                </a>
            </li>

            <!-- Réservations -->
            <li>
                <a href="{{ route('superviseur.reservations.index') }}" 
                   class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->routeIs('superviseur.reservations.*') ? 'bg-blue-700' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    Réservations
                    <span class="ml-auto bg-red-500 text-xs px-2 py-1 rounded-full">{{ App\Models\Reservation::where('statut', 'en_attente')->count() }}</span>
                </a>
            </li>

            <!-- Personnel -->
            <li>
                <a href="{{ route('superviseur.personnel.index') }}" 
                   class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->routeIs('superviseur.personnel.*') ? 'bg-blue-700' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    Personnel
                </a>
            </li>

            <!-- Quais -->
            <li>
                <a href="{{ route('superviseur.quais.index') }}" 
                   class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->routeIs('superviseur.quais.*') ? 'bg-blue-700' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0a1 1 0 01-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 01-1 1h-2z"/>
                    </svg>
                    Quais
                </a>
            </li>

            <!-- Voyages -->
            <li>
                <a href="{{ route('superviseur.voyages.index') }}" 
                   class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->routeIs('superviseur.voyages.*') ? 'bg-blue-700' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    Voyages
                </a>
            </li>

            <!-- Bateaux -->
            <li>
                <a href="{{ route('superviseur.bateaux.index') }}" 
                   class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->routeIs('superviseur.bateaux.*') ? 'bg-blue-700' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Bateaux
                </a>
            </li>

            <!-- Statistiques -->
            <li>
                <a href="{{ route('superviseur.statistiques.index') }}" 
                   class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->routeIs('superviseur.statistiques.*') ? 'bg-blue-700' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Statistiques
                </a>
            </li>

            <!-- Rapports -->
            <li>
                <a href="{{ route('superviseur.rapports.index') }}" 
                   class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->routeIs('superviseur.rapports.*') ? 'bg-blue-700' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Rapports
                </a>
            </li>
        </ul>
    </nav>

    <div class="absolute bottom-0 w-64 p-4 border-t border-blue-700">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-blue-300 hover:text-white transition">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Déconnexion
            </button>
        </form>
    </div>
</aside>