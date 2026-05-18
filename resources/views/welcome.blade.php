<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KivuPort.com - Gestion Portuaire</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-white font-sans">
    <div class="min-h-screen">
        {{-- Navbar --}}
        <nav class="bg-white shadow-sm border-b border-gray-100">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center space-x-2">
                        <span class="text-2xl">⚓</span>
                        <span class="text-gray-800 font-bold text-xl">KivuPort.com</span>
                    </div>
                    <div class="flex-1 max-w-xl mx-4">
                        <div class="relative">
                            <input type="text" placeholder="Destination, port, bateau, etc." class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            <button class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400">🔍</button>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4 text-gray-600">
                        <div class="flex items-center space-x-2 cursor-pointer" id="btnPreferences">
                            <span class="text-xl">🇨🇩</span>
                            <span class="text-sm font-medium">FC ▼</span>
                        </div>
                        <div class="h-5 w-px bg-gray-300"></div>
                        <span class="cursor-pointer text-sm">Aide</span>
                        <a href="{{ route('find.form') }}" class="cursor-pointer text-sm hover:text-blue-600 transition">Trouver des réservations</a>
                        <div class="h-5 w-px bg-gray-300"></div>
                        <a href="{{ route('login') }}" class="text-sm font-medium hover:text-blue-600 transition">Se connecter</a>
                        <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700 transition">S'inscrire</a>
                    </div>
                </div>
            </div>
        </nav>

        {{-- Hero Section maritime --}}
        <div class="bg-gradient-to-r from-blue-900 to-blue-700 text-white">
            <div class="container mx-auto px-6 py-12">
                <div class="text-center">
                    <h1 class="text-4xl md:text-5xl font-bold mb-4">Votre voyage maritime commence ici</h1>
                    <div class="flex flex-wrap justify-center gap-6 text-sm mb-8">
                        <span>✅ Paiement sécurisé</span>
                        <span>🕒 Support 24h/24 et 7j/7</span>
                        <span>⭐ Prime</span>
                    </div>
                    <div class="flex flex-wrap justify-center gap-2 bg-white/10 rounded-lg p-2 mb-6">
                        <button class="px-4 py-2 rounded-lg bg-blue-600 text-white">Traversées</button>
                        <button class="px-4 py-2 rounded-lg hover:bg-white/20">Cargaisons</button>
                        <button class="px-4 py-2 rounded-lg hover:bg-white/20">Pavillons</button>
                        <button class="px-4 py-2 rounded-lg hover:bg-white/20">Ports</button>
                        <button class="px-4 py-2 rounded-lg hover:bg-white/20">Bateaux</button>
                        <button class="px-4 py-2 rounded-lg hover:bg-white/20">Croisières</button>
                    </div>
                    <div class="max-w-2xl mx-auto relative">
                        <input type="text" placeholder="Recherchez un port, un bateau ou une destination" 
                               class="w-full px-4 py-3 rounded-lg text-gray-900">
                        <button class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-blue-600 text-white px-4 py-1 rounded-lg">Rechercher</button>
                    </div>
                    <div class="mt-8 bg-white/10 rounded-lg p-4 inline-block">
                        <p class="text-sm">⭐ Excellent ★★★★★</p>
                        <p class="text-xs">4.4 sur 5 basé sur 1 200 avis</p>
                        <p class="text-xs font-semibold">Trustpilot</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section Offres portuaires --}}
        <div class="container mx-auto px-6 py-12">
            {{-- Exclusivité nouveaux utilisateurs --}}
            <div class="bg-gradient-to-r from-blue-700 to-blue-500 rounded-2xl p-6 text-white mb-12">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold mb-2">⚓ Exclusivité pour les nouveaux utilisateurs</h2>
                        <p class="mb-4">Des réductions spéciales sur vos premiers voyages maritimes... <a href="{{ route('register') }}" class="underline font-semibold">Créer un compte et profiter</a></p>
                        <div class="flex flex-wrap gap-4">
                            <div class="bg-white/20 rounded-lg px-4 py-2 text-center">
                                <span class="text-2xl font-bold">-10%</span>
                                <p class="text-sm">Traversées</p>
                                <a href="{{ route('register') }}" class="text-sm underline">Profiter</a>
                            </div>
                            <div class="bg-white/20 rounded-lg px-4 py-2 text-center">
                                <span class="text-2xl font-bold">-5%</span>
                                <p class="text-sm">Cargaisons</p>
                                <a href="{{ route('register') }}" class="text-sm underline">Profiter</a>
                            </div>
                            <div class="bg-white/20 rounded-lg px-4 py-2 text-center">
                                <span class="text-2xl font-bold">-5%</span>
                                <p class="text-sm">Pavillons VIP</p>
                                <a href="{{ route('register') }}" class="text-sm underline">Profiter</a>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <span class="text-6xl">⚓</span>
                    </div>
                </div>
            </div>

            {{-- Grille des offres --}}
            <div class="grid md:grid-cols-3 gap-8 mb-12">
                <div class="bg-white rounded-xl shadow-md overflow-hidden border border-blue-100 hover:shadow-lg transition">
                    <img src="https://images.unsplash.com/photo-1534447677768-be436bb09401?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Bateau" class="w-full h-40 object-cover">
                    <div class="p-4">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">🚢 Traversées coup de cœur</h3>
                        <p class="text-gray-500 text-sm mb-4">Offres valables pour une durée limitée</p>
                        <a href="{{ route('client.voyages.index') }}" class="inline-block bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700">Je réserve</a>
                        <p class="text-xs text-gray-400 mt-2">*Offres sous réserve de disponibilité.</p>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-md overflow-hidden border border-blue-100 hover:shadow-lg transition">
                    <img src="https://images.unsplash.com/photo-1533107862482-7e6b060d5d6c?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Port" class="w-full h-40 object-cover">
                    <div class="p-4">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">🌊 Destinations maritimes</h3>
                        <p class="text-gray-500 text-sm mb-4">Offres vers les plus beaux ports d'Afrique</p>
                        <a href="{{ route('client.voyages.index') }}" class="inline-block bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700">J'en profite</a>
                        <p class="text-xs text-gray-400 mt-2">*Offres sous réserve de disponibilité.</p>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-md overflow-hidden border border-blue-100 hover:shadow-lg transition">
                    <img src="https://images.unsplash.com/photo-1544551763-46a013bb70d5?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Pavillon" class="w-full h-40 object-cover">
                    <div class="p-4">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">🏠 Pavillons coup de cœur</h3>
                        <p class="text-gray-500 text-sm mb-4">Nos meilleures classes à prix abordables</p>
                        <a href="{{ route('client.voyages.index') }}" class="inline-block bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700">Je réserve</a>
                        <p class="text-xs text-gray-400 mt-2">*Offres sous réserve de disponibilité.</p>
                    </div>
                </div>
            </div>

            {{-- Inspiration --}}
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-2">⚓ Laissez-vous inspirer pour votre prochain voyage maritime</h2>
                <p class="text-gray-500">Découvrez nos destinations, nos bateaux et nos offres sur mesure</p>
            </div>
        </div>

        {{-- Features --}}
        <div class="container mx-auto px-6 py-12">
            <div class="grid md:grid-cols-3 gap-6">
                <div class="bg-white shadow-md rounded-xl p-6 text-center border border-blue-100">
                    <div class="text-4xl mb-4">🚢</div>
                    <h3 class="text-xl font-semibold">Gestion des Bateaux</h3>
                    <p class="text-gray-500">Suivez votre flotte en temps réel</p>
                </div>
                <div class="bg-white shadow-md rounded-xl p-6 text-center border border-blue-100">
                    <div class="text-4xl mb-4">📦</div>
                    <h3 class="text-xl font-semibold">Suivi des Cargaisons</h3>
                    <p class="text-gray-500">Contrôle total des marchandises</p>
                </div>
                <div class="bg-white shadow-md rounded-xl p-6 text-center border border-blue-100">
                    <div class="text-4xl mb-4">🎫</div>
                    <h3 class="text-xl font-semibold">Réservations</h3>
                    <p class="text-gray-500">Gérez les voyages et passagers</p>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <footer class="bg-gray-900 text-gray-300 mt-16">
            <div class="container mx-auto px-6 py-12">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-8">
                    <div><h3 class="text-white font-semibold mb-4">Nous contacter</h3><ul class="space-y-2 text-sm"><li>Service clients</li><li>Garantie de service</li></ul></div>
                    <div><h3 class="text-white font-semibold mb-4">À propos</h3><ul class="space-y-2 text-sm"><li>Qui sommes-nous ?</li><li>Actualités</li><li>Emplois</li></ul></div>
                    <div><h3 class="text-white font-semibold mb-4">Autres services</h3><ul class="space-y-2 text-sm"><li>Partenaires</li><li>Sécurité</li></ul></div>
                    <div><h3 class="text-white font-semibold mb-4">Modes de paiement</h3><div class="flex flex-wrap gap-2"><span class="bg-white text-gray-800 px-2 py-1 rounded text-xs">VISA</span><span class="bg-white text-gray-800 px-2 py-1 rounded text-xs">Mastercard</span><span class="bg-white text-gray-800 px-2 py-1 rounded text-xs">PayPal</span></div></div>
                    <div><h3 class="text-white font-semibold mb-4">Nos partenaires</h3><ul class="space-y-2 text-sm"><li>Google</li><li>Tripadvisor</li></ul></div>
                </div>
                <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm">
                    <p>&copy; 2025 KivuPort.com. Tous droits réservés.</p>
                </div>
            </div>
        </footer>
    </div>

    {{-- Modale --}}
    <div id="modalPreferences" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl max-w-4xl mx-4 p-6">
            <div class="flex justify-between border-b pb-3"><h2 class="text-xl font-bold">🌐 Langues & 💰 Devises</h2><button id="closeModal" class="text-2xl">&times;</button></div>
            <div class="grid md:grid-cols-2 gap-8 mt-4">
                <div><h3 class="font-semibold mb-2">Langues</h3><a href="#" class="block py-1">🇫🇷 Français</a><a href="#" class="block py-1">🇨🇩 Swahili</a><a href="#" class="block py-1">🇨🇩 Lingala</a><a href="#" class="block py-1">🇬🇧 English</a></div>
                <div><h3 class="font-semibold mb-2">Devises</h3><a href="#" class="block py-1">🇨🇩 Franc congolais (FC)</a><a href="#" class="block py-1">🇺🇸 Dollar américain (USD)</a></div>
            </div>
        </div>
    </div>

    <script>
        const modal = document.getElementById('modalPreferences');
        const btn = document.getElementById('btnPreferences');
        const close = document.getElementById('closeModal');
        btn.onclick = () => modal.classList.remove('hidden');
        close.onclick = () => modal.classList.add('hidden');
        window.onclick = (e) => { if (e.target === modal) modal.classList.add('hidden'); }
    </script>
</body>
</html>