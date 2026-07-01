<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Lac Kivu · Goma · Galerie</title>
    <script src="https://cdn.tailwindcss.com">
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
    <style>
        /* ── Reset & base ── */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Inter', sans-serif;
            background: #f0f4f8;
            transition: background 0.3s, color 0.3s;
            overflow-x: hidden;
        }

        /* ── Mode sombre ── */
        body.dark {
            background: #0b1120;
            color: #e5e7eb;
        }
        body.dark .glass-card {
            background: rgba(30, 41, 59, 0.85) !important;
            border-color: rgba(255, 255, 255, 0.08) !important;
            color: #e5e7eb;
        }
        body.dark .glass-card .text-gray-600,
        body.dark .glass-card .text-gray-500 {
            color: #9ca3af !important;
        }
        body.dark .glass-card .text-gray-800 {
            color: #f3f4f6 !important;
        }
        body.dark nav {
            background: rgba(17, 24, 39, 0.9) !important;
            border-bottom-color: rgba(255, 255, 255, 0.05) !important;
        }
        body.dark nav .text-gray-800 {
            color: #f3f4f6;
        }
        body.dark nav .text-gray-600 {
            color: #9ca3af;
        }
        body.dark nav .bg-blue-100 {
            background: #1e3a5f;
            color: #93c5fd;
        }
        body.dark .text-gray-800 {
            color: #f3f4f6;
        }
        body.dark .text-gray-500 {
            color: #9ca3af;
        }
        body.dark footer {
            background: #0b1120;
            border-top: 1px solid #1e293b;
        }
        body.dark .bg-white\/80 {
            background: rgba(17, 24, 39, 0.85) !important;
        }
        body.dark .shadow-sm {
            --tw-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.5);
        }
        body.dark #searchInput {
            background: rgba(30, 41, 59, 0.7) !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
            color: #e5e7eb;
        }
        body.dark .weather-card {
            background: rgba(30, 41, 59, 0.85) !important;
            border-color: rgba(255, 255, 255, 0.05) !important;
        }

        /* ── Animations ── */
        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: scale(1.02);
            }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }
        @keyframes slideUp {
            0% {
                opacity: 0;
                transform: translateY(40px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @keyframes float {
            0%,
            100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-14px);
            }
        }
        @keyframes pulseGlow {
            0%,
            100% {
                box-shadow: 0 0 20px rgba(59, 130, 246, 0.2);
            }
            50% {
                box-shadow: 0 0 50px rgba(59, 130, 246, 0.5);
            }
        }

        .animate-fade {
            animation: fadeIn 1.2s ease forwards;
        }
        .animate-slideUp {
            animation: slideUp 0.9s ease forwards;
        }
        .animate-float {
            animation: float 4s ease-in-out infinite;
        }
        .animate-pulseGlow {
            animation: pulseGlow 3s ease-in-out infinite;
        }

        /* ── Hero Carrousel ── */
        .hero-carousel {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            isolation: isolate;
            overflow: hidden;
        }
        .hero-slide {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center 35%;
            opacity: 0;
            transition: opacity 1.8s ease-in-out;
            z-index: 0;
        }
        .hero-slide.active {
            opacity: 1;
        }
        .hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(145deg,
                    rgba(10, 25, 47, 0.75) 0%,
                    rgba(20, 50, 80, 0.55) 100%);
            z-index: 1;
        }
        .hero-overlay::after {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at 30% 50%, rgba(59, 130, 246, 0.15), transparent 70%);
            pointer-events: none;
        }
        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            padding: 2rem 1.5rem;
            max-width: 1100px;
            margin: 0 auto;
        }

        .carousel-dots {
            position: absolute;
            bottom: 2.5rem;
            left: 50%;
            transform: translateX(-50%);
            z-index: 3;
            display: flex;
            gap: 14px;
        }
        .carousel-dots .dot {
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.35);
            cursor: pointer;
            transition: all 0.4s ease;
            border: 2px solid rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(4px);
        }
        .carousel-dots .dot.active {
            background: #3b82f6;
            transform: scale(1.3);
            box-shadow: 0 0 30px rgba(59, 130, 246, 0.6);
            border-color: #3b82f6;
        }
        .carousel-dots .dot:hover {
            background: rgba(255, 255, 255, 0.7);
            transform: scale(1.15);
        }

        /* ── Glassmorphism ── */
        .glass-card {
            background: rgba(255, 255, 255, 0.78);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.35);
            transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
        }
        .glass-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 60px -12px rgba(0, 20, 40, 0.25);
            border-color: rgba(59, 130, 246, 0.3);
        }

        .floating-badge {
            animation: float 5s ease-in-out infinite;
            display: inline-block;
        }

        .footer-link {
            position: relative;
            transition: color 0.3s;
        }
        .footer-link::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -2px;
            width: 0;
            height: 2px;
            background: #3b82f6;
            transition: width 0.3s ease;
        }
        .footer-link:hover::after {
            width: 100%;
        }
        .footer-link:hover {
            color: #fff;
        }

        /* ── Lightbox ── */
        .lightbox {
            position: fixed;
            inset: 0;
            z-index: 1000;
            background: rgba(0, 0, 0, 0.85);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s;
            backdrop-filter: blur(8px);
        }
        .lightbox.open {
            opacity: 1;
            pointer-events: auto;
        }
        .lightbox img {
            max-width: 90vw;
            max-height: 85vh;
            object-fit: contain;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.8);
            transform: scale(0.95);
            transition: transform 0.3s;
        }
        .lightbox.open img {
            transform: scale(1);
        }
        .lightbox .close {
            position: absolute;
            top: 30px;
            right: 40px;
            color: #fff;
            font-size: 2.8rem;
            cursor: pointer;
            transition: 0.2s;
        }
        .lightbox .close:hover {
            transform: rotate(90deg);
            color: #3b82f6;
        }
        .lightbox .nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            color: #fff;
            font-size: 2.8rem;
            cursor: pointer;
            padding: 0 20px;
            user-select: none;
            transition: 0.2s;
        }
        .lightbox .nav:hover {
            color: #3b82f6;
        }
        .lightbox .nav.prev {
            left: 20px;
        }
        .lightbox .nav.next {
            right: 20px;
        }

        /* ── Like button ── */
        .like-btn {
            transition: 0.2s;
        }
        .like-btn.liked {
            color: #ef4444;
        }
        .like-btn:hover {
            transform: scale(1.2);
        }

        /* ── Weather ── */
        .weather-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .weather-icon {
            font-size: 3rem;
        }

        /* ── Search ── */
        #searchInput {
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(4px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: 0.3s;
        }
        #searchInput:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
        }

        /* ── Gallery hover overlay ── */
        .gallery-item .overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.6), transparent 60%);
            opacity: 0;
            transition: opacity 0.3s;
            display: flex;
            align-items: flex-end;
            padding: 1rem;
        }
        .gallery-item:hover .overlay {
            opacity: 1;
        }

        /* ── Scroll to top ── */
        #scrollTop {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            z-index: 50;
            background: #3b82f6;
            color: #fff;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 24px rgba(59, 130, 246, 0.4);
            cursor: pointer;
            opacity: 0;
            transform: scale(0.8);
            transition: all 0.3s;
            border: none;
        }
        #scrollTop.visible {
            opacity: 1;
            transform: scale(1);
        }
        #scrollTop:hover {
            background: #2563eb;
            transform: scale(1.1);
        }

        @media (max-width: 768px) {
            .hero-carousel {
                min-height: 85vh;
            }
            .carousel-dots {
                bottom: 1.5rem;
                gap: 10px;
            }
            .carousel-dots .dot {
                width: 11px;
                height: 11px;
            }
            .hero-content h1 {
                font-size: 2.5rem !important;
            }
            .lightbox .nav {
                font-size: 1.8rem;
            }
            .lightbox .close {
                top: 15px;
                right: 20px;
                font-size: 2rem;
            }
            #scrollTop {
                width: 40px;
                height: 40px;
                bottom: 1.5rem;
                right: 1.5rem;
            }
        }
    </style>
</head>
<body>

    <!-- ============================================================ -->
    <!-- NAVBAR                                                        -->
    <!-- ============================================================ -->
    <nav class="fixed top-0 left-0 w-full z-50 bg-white/80 backdrop-blur-md border-b border-white/30 shadow-sm transition-colors">
        <div class="container mx-auto px-6 py-3 flex items-center justify-between flex-wrap gap-2">
            <div class="flex items-center gap-2">
                <i class="fas fa-water text-blue-600 text-2xl"></i>
                <span class="text-2xl font-extrabold text-gray-800 tracking-tight">
                    Kivu<span class="text-blue-600">Port</span>
                </span>
                <span class="text-xs bg-blue-100 text-blue-700 px-3 py-1 rounded-full font-medium"><i class="fas fa-map-pin mr-1"></i>Goma · RDC</span>
            </div>
            <div class="flex items-center gap-4 text-sm text-gray-600">
                <a href="#" class="hover:text-blue-600 transition font-medium"><i class="fas fa-home mr-1"></i> Accueil</a>
                <a href="{{ route('login') }}" class="hover:text-blue-600 transition font-medium"><i class="fas fa-sign-in-alt mr-1"></i> Connexion</a>
                <a href="{{ route('register') }}" class="hover:text-blue-600 transition font-medium"><i class="fas fa-user-plus mr-1"></i> Inscription</a>
                <button id="darkToggle" class="text-xl hover:text-blue-600 transition p-1" aria-label="Basculer le mode sombre">
                    <i class="fas fa-moon"></i>
                </button>
            </div>
        </div>
    </nav>

    <!-- ============================================================ -->
    <!-- HERO · CARROUSEL (PHOTOS RÉELLES DU LAC KIVU)               -->
    <!-- ============================================================ -->
    <section class="hero-carousel" id="heroCarousel">
        <!-- Photos authentiques du lac Kivu et bateaux (Unsplash - libre de droit) -->
        <div class="hero-slide active" style="background-image: url('https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=1920&q=80');"></div>
        <div class="hero-slide" style="background-image: url('https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=1920&q=80');"></div>
        <div class="hero-slide" style="background-image: url('https://images.unsplash.com/photo-1534447677768-be436bb09401?w=1920&q=80');"></div>
        <div class="hero-slide" style="background-image: url('https://images.unsplash.com/photo-1501785888041-af3ef285b470?w=1920&q=80');"></div>
        <div class="hero-slide" style="background-image: url('https://images.unsplash.com/photo-1580587771525-78b9dba3b914?w=1920&q=80');"></div>

        <div class="hero-overlay"></div>

        <div class="hero-content text-white">
            <div class="inline-block bg-white/10 backdrop-blur-sm rounded-full px-6 py-2 text-xs font-medium tracking-widest border border-white/20 mb-6 floating-badge">
                <i class="fas fa-camera mr-2"></i> Lac Kivu · Goma
            </div>

            <h1 class="text-5xl md:text-7xl font-extrabold leading-tight mb-4 animate-fade">
                Entre volcans <br />
                <span class="bg-gradient-to-r from-blue-200 to-cyan-100 bg-clip-text text-transparent">et eaux calmes</span>
            </h1>

            <p class="text-lg md:text-xl text-white/85 max-w-2xl mx-auto mb-6 animate-slideUp" style="animation-delay:0.15s">
                <i class="fas fa-ship mr-2"></i> Plongez dans la beauté authentique du lac Kivu et de ses bateaux, reflet de la vie à Goma.
            </p>

            <div class="text-white/60 text-sm mt-2">
                <span id="slideCounter">1</span> / <span id="totalSlides">5</span>
            </div>
        </div>

        <div class="carousel-dots" id="carouselDots"></div>
    </section>

    <!-- ============================================================ -->
    <!-- SECTION : Présentation (icônes)                              -->
    <!-- ============================================================ -->
    <section class="container mx-auto px-6 -mt-6 relative z-10">
        <div class="grid md:grid-cols-3 gap-6">

            <div class="glass-card rounded-2xl p-6 text-center shadow-lg animate-slideUp" style="animation-delay:0.1s">
                <i class="fas fa-ship text-4xl text-blue-600 mb-3"></i>
                <h3 class="font-bold text-gray-800 text-lg">Bateaux de pêche</h3>
                <p class="text-gray-600 text-sm">Les pirogues et barques colorées qui animent les rives du lac Kivu au quotidien.</p>
            </div>

            <div class="glass-card rounded-2xl p-6 text-center shadow-lg animate-slideUp" style="animation-delay:0.2s">
                <i class="fas fa-mountain text-4xl text-blue-600 mb-3"></i>
                <h3 class="font-bold text-gray-800 text-lg">Vue sur les volcans</h3>
                <p class="text-gray-600 text-sm">Le lac est dominé par les volcans Nyiragongo et Nyamulagira, offrant un panorama unique.</p>
            </div>

            <div class="glass-card rounded-2xl p-6 text-center shadow-lg animate-slideUp" style="animation-delay:0.3s">
                <i class="fas fa-sun text-4xl text-blue-600 mb-3"></i>
                <h3 class="font-bold text-gray-800 text-lg">Couchers de soleil</h3>
                <p class="text-gray-600 text-sm">Les eaux calmes du lac Kivu reflètent des couleurs flamboyantes au crépuscule.</p>
            </div>

        </div>
    </section>

    <!-- ============================================================ -->
    <!-- SECTION : Galerie (avec photos réelles)                     -->
    <!-- ============================================================ -->
    <section class="container mx-auto px-6 py-16">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-4">
            <div>
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-800 mb-2">
                    <i class="fas fa-images text-blue-600 mr-2"></i> Instantanés du lac
                </h2>
                <p class="text-gray-500 max-w-lg">Chaque photo capture l'âme du lac Kivu et la vie qui l'anime autour de Goma.</p>
            </div>
            <div class="relative w-full md:w-64">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="searchInput" placeholder="Rechercher..." class="w-full pl-10 pr-4 py-2 rounded-full border border-gray-200/60 bg-white/60 backdrop-blur-sm focus:border-blue-500 transition shadow-sm" />
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4" id="galleryGrid">
            <!-- Chaque carte : image (réelle) + titre + like -->
            <div class="gallery-item rounded-xl overflow-hidden shadow-md hover:shadow-2xl transition-all duration-500 hover:scale-[1.02] group relative" data-title="Vue du lac depuis Goma">
                <img src="https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=600&q=80" alt="Lac Kivu depuis Goma" class="w-full h-48 object-cover cursor-pointer" loading="lazy" data-full="https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=1200&q=80" />
                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-3 flex justify-between items-end">
                    <span class="text-white text-sm font-medium">Vue panoramique</span>
                    <button class="like-btn text-white/80 hover:text-red-400 transition text-lg" data-id="0">
                        <i class="far fa-heart"></i> <span class="like-count text-xs">0</span>
                    </button>
                </div>
            </div>
            <div class="gallery-item rounded-xl overflow-hidden shadow-md hover:shadow-2xl transition-all duration-500 hover:scale-[1.02] group relative" data-title="Bateau sur le lac">
                <img src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=600&q=80" alt="Bateau sur le lac Kivu" class="w-full h-48 object-cover cursor-pointer" loading="lazy" data-full="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=1200&q=80" />
                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-3 flex justify-between items-end">
                    <span class="text-white text-sm font-medium">Bateau de pêche</span>
                    <button class="like-btn text-white/80 hover:text-red-400 transition text-lg" data-id="1">
                        <i class="far fa-heart"></i> <span class="like-count text-xs">0</span>
                    </button>
                </div>
            </div>
            <div class="gallery-item rounded-xl overflow-hidden shadow-md hover:shadow-2xl transition-all duration-500 hover:scale-[1.02] group relative" data-title="Lac Kivu au coucher du soleil">
                <img src="https://images.unsplash.com/photo-1534447677768-be436bb09401?w=600&q=80" alt="Lac Kivu" class="w-full h-48 object-cover cursor-pointer" loading="lazy" data-full="https://images.unsplash.com/photo-1534447677768-be436bb09401?w=1200&q=80" />
                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-3 flex justify-between items-end">
                    <span class="text-white text-sm font-medium">Crépuscule doré</span>
                    <button class="like-btn text-white/80 hover:text-red-400 transition text-lg" data-id="2">
                        <i class="far fa-heart"></i> <span class="like-count text-xs">0</span>
                    </button>
                </div>
            </div>
            <div class="gallery-item rounded-xl overflow-hidden shadow-md hover:shadow-2xl transition-all duration-500 hover:scale-[1.02] group relative" data-title="Pirogue traditionnelle">
                <img src="https://images.unsplash.com/photo-1580587771525-78b9dba3b914?w=600&q=80" alt="Pirogue sur le lac Kivu" class="w-full h-48 object-cover cursor-pointer" loading="lazy" data-full="https://images.unsplash.com/photo-1580587771525-78b9dba3b914?w=1200&q=80" />
                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-3 flex justify-between items-end">
                    <span class="text-white text-sm font-medium">Pirogue artisanale</span>
                    <button class="like-btn text-white/80 hover:text-red-400 transition text-lg" data-id="3">
                        <i class="far fa-heart"></i> <span class="like-count text-xs">0</span>
                    </button>
                </div>
            </div>
        </div>

        <p id="noResult" class="text-center text-gray-500 mt-6 hidden">Aucune photo ne correspond à votre recherche.</p>
    </section>

    <!-- ============================================================ -->
    <!-- SECTION : MÉTÉO RÉELLE (wttr.in)                             -->
    <!-- ============================================================ -->
    <section class="container mx-auto px-6 pb-16">
        <div class="weather-card rounded-3xl shadow-lg p-6 md:p-8 glass-card">
            <h3 class="text-2xl font-bold text-gray-800 mb-4"><i class="fas fa-cloud-sun text-blue-600 mr-2"></i> Météo à Goma</h3>
            <div id="weatherContainer" class="flex flex-wrap items-center gap-6">
                <div class="flex items-center gap-4">
                    <i class="fas fa-cloud-sun weather-icon text-blue-600" id="weatherIcon"></i>
                    <div>
                        <div class="text-3xl font-bold text-gray-800" id="weatherTemp">--°C</div>
                        <div class="text-sm text-gray-500" id="weatherDesc">Chargement...</div>
                    </div>
                </div>
                <div class="text-sm text-gray-600 space-y-1">
                    <div><i class="fas fa-tint mr-2 text-blue-400"></i> Humidité : <span id="weatherHumidity">--</span></div>
                    <div><i class="fas fa-wind mr-2 text-blue-400"></i> Vent : <span id="weatherWind">--</span></div>
                </div>
                <div class="ml-auto text-xs text-gray-400 italic">
                    <i class="fas fa-sync-alt mr-1"></i> Données en direct
                </div>
            </div>
            <div id="weatherError" class="text-red-500 text-sm mt-2 hidden"><i class="fas fa-exclamation-triangle mr-1"></i> Impossible de charger la météo. Réessayez plus tard.</div>
        </div>
    </section>

    <!-- ============================================================ -->
    <!-- SECTION : Citation                                            -->
    <!-- ============================================================ -->
    <section class="container mx-auto px-6 pb-16">
        <div class="relative bg-gradient-to-r from-blue-900 to-blue-700 rounded-3xl overflow-hidden shadow-2xl shadow-blue-500/20">
            <div class="absolute inset-0 opacity-10">
                <img src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=1920&q=80" alt="" class="w-full h-full object-cover" />
            </div>
            <div class="relative p-10 md:p-14 text-white text-center">
                <blockquote class="text-xl md:text-2xl font-light max-w-2xl mx-auto leading-relaxed">
                    <i class="fas fa-quote-left mr-2 opacity-60"></i> Le lac Kivu est une merveille naturelle. Ses eaux profondes et ses bateaux racontent l'histoire d'une région fière et accueillante. <i class="fas fa-quote-right ml-2 opacity-60"></i>
                </blockquote>
                <p class="mt-4 font-semibold"><i class="fas fa-user mr-2"></i> — Pêcheur de Goma</p>
                <div class="mt-6">
                    <a href="#galleryGrid" class="inline-block bg-white text-blue-700 px-8 py-3 rounded-full font-bold hover:bg-gray-100 transition shadow-lg shadow-black/20">
                        <i class="fas fa-images mr-2"></i> Explorer la galerie
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================ -->
    <!-- LIGHTBOX                                                      -->
    <!-- ============================================================ -->
    <div class="lightbox" id="lightbox">
        <span class="close" id="lightboxClose"><i class="fas fa-times"></i></span>
        <span class="nav prev" id="lightboxPrev"><i class="fas fa-chevron-left"></i></span>
        <span class="nav next" id="lightboxNext"><i class="fas fa-chevron-right"></i></span>
        <img src="" alt="Agrandissement" id="lightboxImg" />
    </div>

    <!-- ============================================================ -->
    <!-- BOUTON RETOUR EN HAUT                                        -->
    <!-- ============================================================ -->
    <button id="scrollTop" aria-label="Retour en haut">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- ============================================================ -->
    <!-- FOOTER                                                        -->
    <!-- ============================================================ -->
    <footer class="bg-gray-900 text-gray-400 mt-8">
        <div class="container mx-auto px-6 py-12">
            <div class="flex flex-col md:flex-row justify-between items-center text-sm">
                <p><i class="far fa-copyright mr-1"></i> 2026 LacKivu · Galerie photographique - Goma, RDC</p>
                <div class="flex gap-6 mt-4 md:mt-0">
                    <a href="#" class="footer-link"><i class="fas fa-info-circle mr-1"></i> À propos</a>
                    <a href="#" class="footer-link"><i class="fas fa-file-alt mr-1"></i> Mentions</a>
                    <a href="#" class="footer-link"><i class="fas fa-envelope mr-1"></i> Contact</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- ============================================================ -->
    <!-- SCRIPTS                                                       -->
    <!-- ============================================================ -->
    <script>
        (function() {
            'use strict';

            // ─── CARROUSEL ──────────────────────────────────────────────
            const slides = document.querySelectorAll('.hero-slide');
            const dotsContainer = document.getElementById('carouselDots');
            const slideCounter = document.getElementById('slideCounter');
            const totalSlides = document.getElementById('totalSlides');
            let currentIndex = 0;
            let interval;

            if (totalSlides) totalSlides.textContent = slides.length;

            slides.forEach((_, i) => {
                const dot = document.createElement('span');
                dot.classList.add('dot');
                if (i === 0) dot.classList.add('active');
                dot.dataset.index = i;
                dot.addEventListener('click', () => goToSlide(i));
                dotsContainer.appendChild(dot);
            });
            const dots = dotsContainer.querySelectorAll('.dot');

            function goToSlide(index) {
                slides.forEach((slide, i) => {
                    slide.classList.toggle('active', i === index);
                });
                dots.forEach((dot, i) => {
                    dot.classList.toggle('active', i === index);
                });
                currentIndex = index;
                if (slideCounter) slideCounter.textContent = index + 1;
            }

            function nextSlide() {
                const next = (currentIndex + 1) % slides.length;
                goToSlide(next);
            }

            function startCarousel() {
                stopCarousel();
                interval = setInterval(nextSlide, 4000);
            }

            function stopCarousel() {
                if (interval) {
                    clearInterval(interval);
                    interval = null;
                }
            }

            const hero = document.getElementById('heroCarousel');
            hero.addEventListener('mouseenter', stopCarousel);
            hero.addEventListener('mouseleave', startCarousel);
            startCarousel();

            // ─── MODE SOMBRE ────────────────────────────────────────────
            const darkToggle = document.getElementById('darkToggle');
            const body = document.body;
            const icon = darkToggle.querySelector('i');

            if (localStorage.getItem('darkMode') === 'true') {
                body.classList.add('dark');
                icon.classList.replace('fa-moon', 'fa-sun');
            }

            darkToggle.addEventListener('click', () => {
                body.classList.toggle('dark');
                const isDark = body.classList.contains('dark');
                localStorage.setItem('darkMode', isDark);
                icon.classList.replace(isDark ? 'fa-moon' : 'fa-sun', isDark ? 'fa-sun' : 'fa-moon');
            });

            // ─── LIGHTBOX ──────────────────────────────────────────────
            const lightbox = document.getElementById('lightbox');
            const lightboxImg = document.getElementById('lightboxImg');
            const closeBtn = document.getElementById('lightboxClose');
            const prevBtn = document.getElementById('lightboxPrev');
            const nextBtn = document.getElementById('lightboxNext');
            let currentImageIndex = 0;
            const galleryImages = document.querySelectorAll('.gallery-item img');

            function openLightbox(index) {
                const img = galleryImages[index];
                if (!img) return;
                lightboxImg.src = img.dataset.full || img.src;
                lightbox.classList.add('open');
                currentImageIndex = index;
                document.body.style.overflow = 'hidden';
            }

            function closeLightbox() {
                lightbox.classList.remove('open');
                document.body.style.overflow = '';
            }

            function navigateLightbox(direction) {
                const newIndex = (currentImageIndex + direction + galleryImages.length) % galleryImages.length;
                const img = galleryImages[newIndex];
                if (img) {
                    lightboxImg.src = img.dataset.full || img.src;
                    currentImageIndex = newIndex;
                }
            }

            galleryImages.forEach((img, idx) => {
                img.addEventListener('click', (e) => {
                    e.stopPropagation();
                    openLightbox(idx);
                });
            });

            closeBtn.addEventListener('click', closeLightbox);
            prevBtn.addEventListener('click', () => navigateLightbox(-1));
            nextBtn.addEventListener('click', () => navigateLightbox(1));

            document.addEventListener('keydown', (e) => {
                if (!lightbox.classList.contains('open')) return;
                if (e.key === 'Escape') closeLightbox();
                if (e.key === 'ArrowLeft') navigateLightbox(-1);
                if (e.key === 'ArrowRight') navigateLightbox(1);
            });

            lightbox.addEventListener('click', (e) => {
                if (e.target === lightbox) closeLightbox();
            });

            // ─── LIKES (localStorage) ──────────────────────────────────
            const likeBtns = document.querySelectorAll('.like-btn');
            const likeCounts = {};

            likeBtns.forEach((btn) => {
                const id = btn.dataset.id;
                const stored = localStorage.getItem('like_' + id);
                const count = stored ? parseInt(stored, 10) : 0;
                likeCounts[id] = count;
                const span = btn.querySelector('.like-count');
                if (span) span.textContent = count;
                if (localStorage.getItem('liked_' + id) === 'true') {
                    btn.classList.add('liked');
                    btn.querySelector('i').classList.replace('far', 'fas');
                }
            });

            likeBtns.forEach((btn) => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const id = btn.dataset.id;
                    const icon = btn.querySelector('i');
                    const span = btn.querySelector('.like-count');
                    const isLiked = btn.classList.contains('liked');

                    if (isLiked) {
                        likeCounts[id] = (likeCounts[id] || 0) - 1;
                        btn.classList.remove('liked');
                        icon.classList.replace('fas', 'far');
                        localStorage.setItem('liked_' + id, 'false');
                    } else {
                        likeCounts[id] = (likeCounts[id] || 0) + 1;
                        btn.classList.add('liked');
                        icon.classList.replace('far', 'fas');
                        localStorage.setItem('liked_' + id, 'true');
                    }
                    if (span) span.textContent = likeCounts[id];
                    localStorage.setItem('like_' + id, likeCounts[id]);
                });
            });

            // ─── RECHERCHE ──────────────────────────────────────────────
            const searchInput = document.getElementById('searchInput');
            const galleryItems = document.querySelectorAll('.gallery-item');
            const noResult = document.getElementById('noResult');

            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase().trim();
                let visibleCount = 0;
                galleryItems.forEach((item) => {
                    const title = item.dataset.title.toLowerCase();
                    const match = title.includes(query);
                    item.style.display = match ? '' : 'none';
                    if (match) visibleCount++;
                });
                noResult.classList.toggle('hidden', visibleCount > 0);
            });

            // ─── MÉTÉO RÉELLE (wttr.in) ──────────────────────────────
            const weatherTemp = document.getElementById('weatherTemp');
            const weatherDesc = document.getElementById('weatherDesc');
            const weatherHumidity = document.getElementById('weatherHumidity');
            const weatherWind = document.getElementById('weatherWind');
            const weatherIcon = document.getElementById('weatherIcon');
            const weatherError = document.getElementById('weatherError');

            function fetchWeather() {
                const url = 'https://wttr.in/Goma?format=j1&lang=fr';
                fetch(url)
                    .then(response => {
                        if (!response.ok) throw new Error('Erreur réseau');
                        return response.json();
                    })
                    .then(data => {
                        const current = data.current_condition[0];
                        const temp = current.temp_C;
                        const desc = current.weatherDesc[0].value;
                        const humid = current.humidity;
                        const wind = current.windSpeed;
                        const code = current.weatherCode;

                        weatherTemp.textContent = temp + '°C';
                        weatherDesc.textContent = desc;
                        weatherHumidity.textContent = humid + '%';
                        weatherWind.textContent = wind + ' km/h';

                        let iconClass = 'fa-cloud-sun';
                        if (code === 113) iconClass = 'fa-sun';
                        else if (code === 116 || code === 119) iconClass = 'fa-cloud';
                        else if (code === 122 || code === 143) iconClass = 'fa-cloud-sun-rain';
                        else if (code === 176 || code === 182 || code === 185 || code === 200) iconClass = 'fa-cloud-rain';
                        else if (code === 227 || code === 230) iconClass = 'fa-snowflake';
                        else if (code === 248 || code === 260) iconClass = 'fa-smog';
                        weatherIcon.className = 'fas ' + iconClass + ' weather-icon text-blue-600';

                        weatherError.classList.add('hidden');
                    })
                    .catch(error => {
                        console.warn('Erreur météo:', error);
                        weatherError.classList.remove('hidden');
                        weatherTemp.textContent = '--°C';
                        weatherDesc.textContent = 'Indisponible';
                        weatherHumidity.textContent = '--';
                        weatherWind.textContent = '--';
                    });
            }

            fetchWeather();
            setInterval(fetchWeather, 600000);

            // ─── SCROLL TO TOP ─────────────────────────────────────────
            const scrollBtn = document.getElementById('scrollTop');
            window.addEventListener('scroll', () => {
                if (window.scrollY > 300) {
                    scrollBtn.classList.add('visible');
                } else {
                    scrollBtn.classList.remove('visible');
                }
            });
            scrollBtn.addEventListener('click', () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });

            console.log('✅ Toutes les fonctionnalités sont chargées !');
        })();
    </script>
</body>
</html>