<!-- resources/views/personnel/layouts/sidebar.blade.php -->
<aside class="w-64 bg-blue-800 text-white flex flex-col">
    <div class="p-4 border-b border-blue-700">
        <h2 class="text-xl font-bold">GPP Portuaire</h2>
        <p class="text-sm text-blue-300">Personnel</p>
    </div>
    
    <nav class="flex-1 p-2">
        <ul class="space-y-1">
            <li>
                <a href="{{ route('personnel.dashboard') }}" 
                   class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-700 transition {{ request()->routeIs('personnel.dashboard') ? 'bg-blue-700' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    Tableau de bord
                </a>
            </li>
        </ul>
    </nav>

    <div class="p-4 border-t border-blue-700">
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