<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="w-64 bg-blue-800 text-white flex flex-col">
            <div class="p-4 border-b border-blue-700">
                <h1 class="text-xl font-bold">⚓ Gestion Portuaire</h1>
                <p class="text-sm text-blue-300">Espace client</p>
            </div>
            <nav class="flex-1 mt-4">
                <a href="{{ route('client.dashboard') }}" class="flex items-center px-4 py-3 hover:bg-blue-700 transition">
                    <span class="mr-3">🏠</span> Tableau de bord
                </a>
                <a href="{{ route('client.voyages.index') }}" class="flex items-center px-4 py-3 hover:bg-blue-700 transition">
                    <span class="mr-3">✈️</span> Voyages disponibles
                </a>
                <a href="{{ route('client.reservations.index') }}" class="flex items-center px-4 py-3 hover:bg-blue-700 transition">
                    <span class="mr-3">📋</span> Mes réservations
                </a>
                <a href="{{ route('client.paiements.index') }}" class="flex items-center px-4 py-3 hover:bg-blue-700 transition">
                    <span class="mr-3">💰</span> Mes paiements
                </a>

                {{-- Séparateur avant Paramètres --}}
                <div class="border-t border-blue-700 my-2 pt-2"></div>
                <a href="{{ route('client.settings.index') }}" class="flex items-center px-4 py-3 hover:bg-blue-700 transition">
                    <span class="mr-3">⚙️</span> Paramètres
                </a>
            </nav>
            <div class="p-4 border-t border-blue-700">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center w-full px-4 py-2 bg-red-600 hover:bg-red-700 rounded-lg transition">
                        <span class="mr-3">🔓</span> Déconnexion
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1">
            <header class="bg-white shadow-sm">
                <div class="px-6 py-4">
                    <h1 class="text-2xl font-semibold text-gray-800">@yield('header')</h1>
                </div>
            </header>
            <main class="p-6">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
                        {{ session('error') }}
                    </div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>