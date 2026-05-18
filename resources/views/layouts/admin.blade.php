<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="{{ config('settings.theme') == 'dark' ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-900' }} font-sans">
    <div class="min-h-screen">
        <!-- Sidebar fixe -->
        <div class="fixed inset-y-0 left-0 w-64 bg-blue-800 text-white">
            <div class="flex items-center justify-center h-16 border-b border-blue-700">
                <span class="text-xl font-bold">⚓ Gestion Portuaire</span>
            </div>
            <nav class="mt-5 overflow-y-auto" style="max-height: calc(100vh - 80px);">
                {{-- Section principale --}}
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-6 py-3 hover:bg-blue-700 transition">
                    <span class="mr-3">📊</span> Tableau de bord
                </a>
                <a href="{{ route('admin.bateaux.index') }}" class="flex items-center px-6 py-3 hover:bg-blue-700 transition">
                    <span class="mr-3">🚢</span> Bateaux
                </a>
                <a href="{{ route('admin.ports.index') }}" class="flex items-center px-6 py-3 hover:bg-blue-700 transition">
                    <span class="mr-3">⚓</span> Ports
                </a>
                <a href="{{ route('admin.quais.index') }}" class="flex items-center px-6 py-3 hover:bg-blue-700 transition">
                    <span class="mr-3">📌</span> Quais
                </a>
                <a href="{{ route('admin.voyages.index') }}" class="flex items-center px-6 py-3 hover:bg-blue-700 transition">
                    <span class="mr-3">✈️</span> Voyages
                </a>
                <a href="{{ route('admin.pavillons.index') }}" class="flex items-center px-6 py-3 hover:bg-blue-700 transition">
                    <span class="mr-3">🏠</span> Pavillons
                </a>
                <a href="{{ route('admin.admins.index') }}" class="flex items-center px-6 py-3 hover:bg-blue-700 transition">
                    <span class="mr-3">👥</span> Administrateurs
                </a>
                <a href="{{ route('admin.clients.index') }}" class="flex items-center px-6 py-3 hover:bg-blue-700 transition">
                    <span class="mr-3">👤</span> Clients
                </a>
                <a href="{{ route('admin.reservations.index') }}" class="flex items-center px-6 py-3 hover:bg-blue-700 transition">
                        <span class="mr-3">📦</span> Réservations
                        @php
                            $nbEnAttente = App\Http\Controllers\Admin\ReservationController::countEnAttente();
                        @endphp
                        @if($nbEnAttente > 0)
                            <span class="ml-2 bg-red-600 text-white text-xs font-bold px-2 py-0.5 rounded-full">
                                {{ $nbEnAttente }}
                            </span>
                        @endif
                </a>
                <a href="{{ route('admin.paiements.index') }}" class="flex items-center px-6 py-3 hover:bg-blue-700 transition">
                    <span class="mr-3">💰</span> Paiements
                </a>
                <a href="{{ route('admin.trajets.index') }}" class="flex items-center px-6 py-3 hover:bg-blue-700 transition">
                    <span class="mr-3">🗺️</span> Trajets
                </a>

                {{-- Tables associatives --}}
                <div class="border-t border-blue-700 my-2 pt-2"></div>
                <a href="{{ route('admin.conceder.index') }}" class="flex items-center px-6 py-3 hover:bg-blue-700 transition">
                    <span class="mr-3">🔗</span> Conceder (Port ↔ Trajet)
                </a>
                <a href="{{ route('admin.appartenir.index') }}" class="flex items-center px-6 py-3 hover:bg-blue-700 transition">
                    <span class="mr-3">⚓</span> Appartenir (Bateau ↔ Quai)
                </a>
                <a href="{{ route('admin.contiendra.index') }}" class="flex items-center px-6 py-3 hover:bg-blue-700 transition">
                    <span class="mr-3">💰</span> Contiendra (Pavillon ↔ Trajet)
                </a>
                <a href="{{ route('admin.reserve.index') }}" class="flex items-center px-6 py-3 hover:bg-blue-700 transition">
                    <span class="mr-3">📋</span> Reserve (Réservation ↔ Pavillon)
                </a>

                {{-- Séparateur avant Paramètres --}}
                <div class="border-t border-blue-700 my-2 pt-2"></div>
                <a href="{{ route('admin.settings.index') }}" class="flex items-center px-6 py-3 hover:bg-blue-700 transition">
                    <span class="mr-3">⚙️</span> Paramètres
                </a>
            </nav>
        </div>
        
        <!-- Main Content -->
        <div class="ml-64">
            <header class="bg-blue-800 shadow-sm">
                <div class="flex justify-between items-center px-6 py-4">
                    <h1 class="text-2xl font-semibold text-white">@yield('header')</h1>
                    <div class="flex items-center space-x-4">
                        <div class="text-right">
                            <p class="text-sm font-medium text-white">{{ Auth::user()->prenom }} {{ Auth::user()->name }}</p>
                            <p class="text-xs text-blue-200">Administrateur</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg">Déconnexion</button>
                        </form>
                    </div>
                </div>
            </header>
            
            <div class="p-6">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>
