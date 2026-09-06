<nav x-data="{ open: false }" class="bg-blue-800 text-white shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('superviseur.dashboard') }}" class="flex items-center">
                    <svg class="h-8 w-8 mr-2 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span class="text-xl font-bold">GPP Portuaire</span>
                </a>
            </div>

            <!-- Menu Desktop -->
            <div class="hidden md:flex items-center space-x-6">
                @auth
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="text-blue-200 hover:text-white transition px-3 py-2 rounded-lg hover:bg-blue-700">Dashboard</a>
                    @elseif(Auth::user()->role === 'personnel')
                        @php
                            $user = Auth::user();
                            $user->load('personnel');
                            $role = $user->personnel->personnel_role ?? 'agent_portuaire';
                        @endphp
                        @if($role === 'superviseur')
                            <a href="{{ route('superviseur.dashboard') }}" class="text-blue-200 hover:text-white transition px-3 py-2 rounded-lg hover:bg-blue-700">Dashboard</a>
                        @elseif($role === 'comptable')
                            <a href="{{ route('comptable.dashboard') }}" class="text-blue-200 hover:text-white transition px-3 py-2 rounded-lg hover:bg-blue-700">Dashboard</a>
                        @elseif($role === 'caissier')
                            <a href="{{ route('caissier.dashboard') }}" class="text-blue-200 hover:text-white transition px-3 py-2 rounded-lg hover:bg-blue-700">Dashboard</a>
                        @elseif($role === 'gestionnaire')
                            <a href="{{ route('gestionnaire.dashboard') }}" class="text-blue-200 hover:text-white transition px-3 py-2 rounded-lg hover:bg-blue-700">Dashboard</a>
                        @else
                            <a href="{{ route('personnel.dashboard') }}" class="text-blue-200 hover:text-white transition px-3 py-2 rounded-lg hover:bg-blue-700">Dashboard</a>
                        @endif
                    @elseif(Auth::user()->role === 'client')
                        <a href="{{ route('client.dashboard') }}" class="text-blue-200 hover:text-white transition px-3 py-2 rounded-lg hover:bg-blue-700">Dashboard</a>
                    @endif
                @endauth

                <!-- Profil -->
                <div class="relative group">
                    <button class="flex items-center space-x-2 text-blue-200 hover:text-white transition px-3 py-2 rounded-lg hover:bg-blue-700">
                        <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center border-2 border-blue-400">
                            <span class="text-sm font-bold">{{ substr(Auth::user()->name ?? 'U', 0, 1) }}{{ substr(Auth::user()->prenom ?? '', 0, 1) }}</span>
                        </div>
                        <span>{{ Auth::user()->name ?? 'Utilisateur' }}</span>
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 hidden group-hover:block z-50">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">👤 Profil</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                🚪 Déconnexion
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button @click="open = !open" class="text-blue-200 hover:text-white focus:outline-none p-2 rounded-lg hover:bg-blue-700">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Menu Mobile -->
    <div x-show="open" class="md:hidden bg-blue-900 border-t border-blue-700">
        <div class="px-2 pt-2 pb-3 space-y-1">
            @auth
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-blue-200 hover:text-white hover:bg-blue-700">Dashboard</a>
                @elseif(Auth::user()->role === 'personnel')
                    @php
                        $user = Auth::user();
                        $user->load('personnel');
                        $role = $user->personnel->personnel_role ?? 'agent_portuaire';
                    @endphp
                    @if($role === 'superviseur')
                        <a href="{{ route('superviseur.dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-blue-200 hover:text-white hover:bg-blue-700">Dashboard</a>
                    @elseif($role === 'comptable')
                        <a href="{{ route('comptable.dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-blue-200 hover:text-white hover:bg-blue-700">Dashboard</a>
                    @elseif($role === 'caissier')
                        <a href="{{ route('caissier.dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-blue-200 hover:text-white hover:bg-blue-700">Dashboard</a>
                    @elseif($role === 'gestionnaire')
                        <a href="{{ route('gestionnaire.dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-blue-200 hover:text-white hover:bg-blue-700">Dashboard</a>
                    @else
                        <a href="{{ route('personnel.dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-blue-200 hover:text-white hover:bg-blue-700">Dashboard</a>
                    @endif
                @elseif(Auth::user()->role === 'client')
                    <a href="{{ route('client.dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-blue-200 hover:text-white hover:bg-blue-700">Dashboard</a>
                @endif
            @endauth
            <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded-md text-base font-medium text-blue-200 hover:text-white hover:bg-blue-700">👤 Profil</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-red-400 hover:text-red-200 hover:bg-blue-700">
                    🚪 Déconnexion
                </button>
            </form>
        </div>
    </div>
</nav>