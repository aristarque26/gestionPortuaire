<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trouver mes réservations - KivuPort.com</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .hero-bg {
            background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://images.unsplash.com/photo-1534447677768-be436bb09401?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80');
            background-size: cover;
            background-position: center;
        }
        .transition-smooth { transition: all 0.3s ease; }
    </style>
</head>
<body class="bg-white font-sans">

    {{-- NAVBAR --}}
    <nav class="sticky top-0 z-50 bg-white/90 backdrop-blur-md shadow-md">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="{{ url('/') }}" class="flex items-center space-x-2">
                    <span class="text-3xl">⚓</span>
                    <span class="text-gray-800 font-bold text-xl">KivuPort.com</span>
                </a>
                <div class="hidden md:flex space-x-6 text-gray-600 font-medium">
                    <a href="{{ url('/') }}" class="hover:text-blue-600 transition">Accueil</a>
                    <a href="{{ url('/#services') }}" class="hover:text-blue-600 transition">Services</a>
                    <a href="{{ url('/#offres') }}" class="hover:text-blue-600 transition">Offres</a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-blue-600 transition text-sm font-medium">Se connecter</a>
                    <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700 transition">S'inscrire</a>
                </div>
            </div>
        </div>
    </nav>

    {{-- HERO SECTION --}}
    <div class="hero-bg text-white py-16">
        <div class="container mx-auto px-6 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Trouver mes réservations</h1>
            <p class="text-lg max-w-2xl mx-auto opacity-90">Saisissez votre email pour recevoir un code de vérification et accéder à vos réservations.</p>
        </div>
    </div>

    {{-- FORMULAIRE --}}
    <div class="container mx-auto px-6 py-16">
        <div class="max-w-md mx-auto bg-white rounded-2xl shadow-lg p-8">
            <form method="POST" action="{{ route('find.send') }}">
                @csrf
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Adresse e-mail</label>
                    <input type="email" name="email" required 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="exemple@email.com">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" 
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-semibold transition">
                    Obtenir un code de vérification
                </button>
            </form>
        </div>
    </div>

    {{-- FOOTER STYLE TRIP.COM --}}
    <footer class="bg-gray-900 text-white mt-16">
        <div class="container mx-auto px-6 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">⚓ KivuPort.com</h3>
                    <p class="text-gray-400 text-sm">Solution complète pour la gestion portuaire en Afrique.</p>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Liens rapides</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="{{ url('/') }}" class="hover:text-white transition">Accueil</a></li>
                        <li><a href="{{ url('/#services') }}" class="hover:text-white transition">Services</a></li>
                        <li><a href="{{ url('/#offres') }}" class="hover:text-white transition">Offres</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Assistance</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="#" class="hover:text-white transition">Aide</a></li>
                        <li><a href="#" class="hover:text-white transition">Contact</a></li>
                        <li><a href="#" class="hover:text-white transition">Mentions légales</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Modes de paiement</h4>
                    <div class="flex flex-wrap gap-2">
                        <span class="bg-white text-gray-800 px-3 py-1 rounded text-xs font-semibold">VISA</span>
                        <span class="bg-white text-gray-800 px-3 py-1 rounded text-xs font-semibold">Mastercard</span>
                        <span class="bg-white text-gray-800 px-3 py-1 rounded text-xs font-semibold">PayPal</span>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-6 text-center text-sm text-gray-400">
                &copy; 2025 KivuPort.com. Tous droits réservés.
            </div>
        </div>
    </footer>

</body>
</html>