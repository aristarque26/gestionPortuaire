<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Google Font : Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">

    <style>
        /* --- POLICE GOOGLE --- */
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            font-size: 16px;
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* --- VARIABLES COULEURS (portuaire premium) --- */
        :root {
            --sidebar-start: #0f172a;
            --sidebar-mid: #1e293b;
            --sidebar-end: #334155;
            --accent-gold: #f59e0b;
            --accent-gold-light: #fbbf24;
            --accent-blue: #3b82f6;
            --accent-indigo: #6366f1;
            --danger-red: #ef4444;
            --success-green: #10b981;
        }

        /* --- TRANSITIONS & RESET --- */
        * { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }

        /* --- SIDEBAR PREMIUM --- */
        .sidebar {
            width: 280px;
            transition: width 0.3s ease, transform 0.3s ease;
            background: linear-gradient(180deg, var(--sidebar-start) 0%, var(--sidebar-mid) 50%, var(--sidebar-end) 100%);
            z-index: 50;
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.15);
        }
        .sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at top right, rgba(245, 158, 11, 0.05) 0%, transparent 50%);
            pointer-events: none;
        }
        .sidebar.collapsed { width: 80px; }
        .sidebar.collapsed .link-text,
        .sidebar.collapsed .user-name,
        .sidebar.collapsed .user-email,
        .sidebar.collapsed .brand-text,
        .sidebar.collapsed .section-title { display: none; }
        .sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 0.875rem;
        }
        .sidebar.collapsed .nav-link i {
            margin-right: 0;
            font-size: 1.25rem;
        }
        .sidebar.collapsed .user-info {
            flex-direction: column;
            align-items: center;
        }
        .sidebar.collapsed .logout-btn span { display: none; }
        .sidebar.collapsed .logout-btn i { margin-right: 0; }
        .sidebar.collapsed .badge-notif { display: none; }

        /* --- OVERLAY MOBILE --- */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            z-index: 40;
        }
        .sidebar-overlay.active { display: block; }

        /* --- MOBILE --- */
        @media (max-width: 1023px) {
            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                bottom: 0;
                transform: translateX(-100%);
                width: 280px;
            }
            .sidebar.mobile-open { transform: translateX(0); }
            .sidebar.collapsed {
                width: 280px !important;
            }
            .sidebar.collapsed .link-text,
            .sidebar.collapsed .user-name,
            .sidebar.collapsed .user-email,
            .sidebar.collapsed .brand-text,
            .sidebar.collapsed .section-title { display: inline !important; }
            .sidebar.collapsed .nav-link {
                justify-content: flex-start !important;
                padding: 0.875rem 1.5rem !important;
            }
            .sidebar.collapsed .nav-link i {
                margin-right: 0.75rem !important;
                font-size: 1rem !important;
            }
            .sidebar.collapsed .user-info {
                flex-direction: row !important;
            }
            .sidebar.collapsed .logout-btn span {
                display: inline !important;
            }
            .sidebar.collapsed .logout-btn i {
                margin-right: 0.5rem !important;
            }
            .sidebar.collapsed .badge-notif { display: inline-block !important; }
        }

        /* --- SECTION TITLES --- */
        .section-title {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: rgba(255, 255, 255, 0.4);
            font-weight: 600;
            padding: 0.5rem 1rem;
            margin-top: 0.5rem;
        }

        /* --- LIENS ACTIFS & SURVOL --- */
        .nav-link {
            position: relative;
            overflow: hidden;
        }
        .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 0;
            background: linear-gradient(90deg, var(--accent-gold) 0%, transparent 100%);
            transition: width 0.3s ease;
        }
        .nav-link.active::before {
            width: 4px;
        }
        .nav-link.active {
            background: rgba(245, 158, 11, 0.15);
            color: #fff;
        }
        .nav-link.active i { 
            color: var(--accent-gold);
            filter: drop-shadow(0 0 8px rgba(245, 158, 11, 0.5));
        }
        .nav-link:hover {
            background: rgba(255, 255, 255, 0.08);
            transform: translateX(6px);
        }
        .nav-link:hover i {
            transform: scale(1.1);
        }
        .sidebar.collapsed .nav-link:hover { transform: translateX(0); }

        /* --- BOUTON TOGGLE --- */
        .toggle-btn {
            background: rgba(255,255,255,0.08);
            border: none;
            cursor: pointer;
            backdrop-filter: blur(8px);
        }
        .toggle-btn:hover { 
            background: rgba(255,255,255,0.18);
            transform: scale(1.1);
        }

        /* --- TOOLTIP --- */
        .nav-link .tooltip {
            display: none;
            position: absolute;
            left: 70px;
            top: 50%;
            transform: translateY(-50%);
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            color: #fff;
            padding: 0.375rem 0.875rem;
            border-radius: 0.5rem;
            font-size: 0.75rem;
            white-space: nowrap;
            pointer-events: none;
            z-index: 60;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .nav-link .tooltip::before {
            content: '';
            position: absolute;
            left: -6px;
            top: 50%;
            transform: translateY(-50%);
            border: 6px solid transparent;
            border-right-color: #1e293b;
        }
        .sidebar.collapsed .nav-link { position: relative; }
        .sidebar.collapsed .nav-link:hover .tooltip { display: block; }
        @media (max-width: 1023px) {
            .nav-link .tooltip { display: none !important; }
        }

        /* --- LOGO --- */
        .logo-icon {
            background: linear-gradient(135deg, var(--accent-gold) 0%, var(--accent-gold-light) 100%);
            color: var(--sidebar-start);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        }

        /* --- DÉCONNEXION --- */
        .logout-btn {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.9) 0%, rgba(220, 38, 38, 0.9) 100%);
            transition: 0.3s;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .logout-btn:hover {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            transform: scale(1.03);
            box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
        }

        /* --- AVATAR --- */
        .user-avatar {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.25) 0%, rgba(251, 191, 36, 0.25) 100%);
            color: var(--accent-gold-light);
            border: 2px solid rgba(245, 158, 11, 0.3);
        }

        /* --- BADGE NOTIFICATION --- */
        .badge-notif {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: #fff;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 0.15rem 0.6rem;
            border-radius: 9999px;
            margin-left: auto;
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.4);
            animation: pulse-badge 2s infinite;
        }
        @keyframes pulse-badge {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        .sidebar.collapsed .badge-notif {
            display: none;
        }
        @media (max-width: 1023px) {
            .badge-notif { display: inline-block !important; }
        }

        /* --- MAIN CONTENT --- */
        .main-content {
            margin-left: 280px;
            transition: margin-left 0.3s ease;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }
        .sidebar.collapsed ~ .main-content {
            margin-left: 80px;
        }
        @media (max-width: 1023px) {
            .main-content {
                margin-left: 0 !important;
            }
        }

        /* --- HEADER PREMIUM --- */
        .header-premium {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
        }

        /* --- MESSAGES FLASH --- */
        .flash-success {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            border-left: 4px solid var(--success-green);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15);
        }
        .flash-error {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            border-left: 4px solid var(--danger-red);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.15);
        }
    </style>
</head>
<body class="bg-gray-100 font-sans antialiased">

    <!-- Overlay mobile -->
    <div id="sidebarOverlay" class="sidebar-overlay" onclick="closeMobileSidebar()"></div>

    <div class="min-h-screen flex">
        <!-- SIDEBAR -->
        <aside id="sidebar" class="sidebar flex flex-col text-white shadow-2xl fixed top-0 left-0 h-full">
            <!-- En-tête -->
            <div class="flex items-center justify-between p-5 border-b border-white/10 relative z-10">
                <div class="flex items-center space-x-3">
                    <div class="logo-icon w-10 h-10 rounded-xl flex items-center justify-center text-xl font-bold shadow-lg">
                        <i class="fas fa-ship"></i>
                    </div>
                    <span class="brand-text font-bold text-lg tracking-tight">Portuaire</span>
                </div>
                <button id="toggleSidebarBtn" class="toggle-btn hidden lg:flex items-center justify-center w-8 h-8 rounded-lg text-white/60 hover:text-white">
                    <i class="fas fa-chevron-left" id="toggleIcon"></i>
                </button>
                <button class="lg:hidden text-white/60 hover:text-white" onclick="closeMobileSidebar()">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto relative z-10">
                {{-- Section principale --}}
                <div class="section-title">Principal</div>
                
                <a href="{{ route('admin.dashboard') }}" 
                   class="nav-link flex items-center px-4 py-3 rounded-xl text-gray-300 hover:text-white @if(request()->routeIs('admin.dashboard')) active @endif">
                    <i class="fas fa-tachometer-alt w-5 text-center"></i>
                    <span class="link-text ml-3">Tableau de bord</span>
                    <span class="tooltip">Tableau de bord</span>
                </a>
                <a href="{{ route('admin.bateaux.index') }}" 
                   class="nav-link flex items-center px-4 py-3 rounded-xl text-gray-300 hover:text-white @if(request()->routeIs('admin.bateaux.*')) active @endif">
                    <i class="fas fa-ship w-5 text-center"></i>
                    <span class="link-text ml-3">Bateaux</span>
                    <span class="tooltip">Bateaux</span>
                </a>
                <a href="{{ route('admin.ports.index') }}" 
                   class="nav-link flex items-center px-4 py-3 rounded-xl text-gray-300 hover:text-white @if(request()->routeIs('admin.ports.*')) active @endif">
                    <i class="fas fa-anchor w-5 text-center"></i>
                    <span class="link-text ml-3">Ports</span>
                    <span class="tooltip">Ports</span>
                </a>
                <a href="{{ route('admin.quais.index') }}" 
                   class="nav-link flex items-center px-4 py-3 rounded-xl text-gray-300 hover:text-white @if(request()->routeIs('admin.quais.*')) active @endif">
                    <i class="fas fa-map-pin w-5 text-center"></i>
                    <span class="link-text ml-3">Quais</span>
                    <span class="tooltip">Quais</span>
                </a>
                <a href="{{ route('admin.voyages.index') }}" 
                   class="nav-link flex items-center px-4 py-3 rounded-xl text-gray-300 hover:text-white @if(request()->routeIs('admin.voyages.*')) active @endif">
                    <i class="fas fa-plane w-5 text-center"></i>
                    <span class="link-text ml-3">Voyages</span>
                    <span class="tooltip">Voyages</span>
                </a>
                <a href="{{ route('admin.pavillons.index') }}" 
                   class="nav-link flex items-center px-4 py-3 rounded-xl text-gray-300 hover:text-white @if(request()->routeIs('admin.pavillons.*')) active @endif">
                    <i class="fas fa-flag w-5 text-center"></i>
                    <span class="link-text ml-3">Pavillons</span>
                    <span class="tooltip">Pavillons</span>
                </a>

                <div class="section-title">Gestion</div>

                <a href="{{ route('admin.admins.index') }}" 
                   class="nav-link flex items-center px-4 py-3 rounded-xl text-gray-300 hover:text-white @if(request()->routeIs('admin.admins.*')) active @endif">
                    <i class="fas fa-users-cog w-5 text-center"></i>
                    <span class="link-text ml-3">Administrateurs</span>
                    <span class="tooltip">Administrateurs</span>
                </a>
                <a href="{{ route('admin.clients.index') }}" 
                   class="nav-link flex items-center px-4 py-3 rounded-xl text-gray-300 hover:text-white @if(request()->routeIs('admin.clients.*')) active @endif">
                    <i class="fas fa-user-friends w-5 text-center"></i>
                    <span class="link-text ml-3">Clients</span>
                    <span class="tooltip">Clients</span>
                </a>
                <a href="{{ route('admin.reservations.index') }}" 
                   class="nav-link flex items-center px-4 py-3 rounded-xl text-gray-300 hover:text-white @if(request()->routeIs('admin.reservations.*')) active @endif">
                    <i class="fas fa-clipboard-list w-5 text-center"></i>
                    <span class="link-text ml-3">Réservations</span>
                    <span class="tooltip">Réservations</span>
                    @php
                        $nbEnAttente = App\Http\Controllers\Admin\ReservationController::countEnAttente();
                    @endphp
                    @if($nbEnAttente > 0)
                        <span class="badge-notif">{{ $nbEnAttente }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.paiements.index') }}" 
                   class="nav-link flex items-center px-4 py-3 rounded-xl text-gray-300 hover:text-white @if(request()->routeIs('admin.paiements.*')) active @endif">
                    <i class="fas fa-coins w-5 text-center"></i>
                    <span class="link-text ml-3">Paiements</span>
                    <span class="tooltip">Paiements</span>
                </a>
                <a href="{{ route('admin.trajets.index') }}" 
                   class="nav-link flex items-center px-4 py-3 rounded-xl text-gray-300 hover:text-white @if(request()->routeIs('admin.trajets.*')) active @endif">
                    <i class="fas fa-route w-5 text-center"></i>
                    <span class="link-text ml-3">Trajets</span>
                    <span class="tooltip">Trajets</span>
                </a>

                <div class="section-title">Relations</div>

                <a href="{{ route('admin.conceder.index') }}" 
                   class="nav-link flex items-center px-4 py-3 rounded-xl text-gray-300 hover:text-white @if(request()->routeIs('admin.conceder.*')) active @endif">
                    <i class="fas fa-link w-5 text-center"></i>
                    <span class="link-text ml-3">Conceder</span>
                    <span class="tooltip">Conceder (Port ↔ Trajet)</span>
                </a>
                <a href="{{ route('admin.appartenir.index') }}" 
                   class="nav-link flex items-center px-4 py-3 rounded-xl text-gray-300 hover:text-white @if(request()->routeIs('admin.appartenir.*')) active @endif">
                    <i class="fas fa-merge w-5 text-center"></i>
                    <span class="link-text ml-3">Appartenir</span>
                    <span class="tooltip">Appartenir (Bateau ↔ Quai)</span>
                </a>
                <a href="{{ route('admin.contiendra.index') }}" 
                   class="nav-link flex items-center px-4 py-3 rounded-xl text-gray-300 hover:text-white @if(request()->routeIs('admin.contiendra.*')) active @endif">
                    <i class="fas fa-money-bill-wave w-5 text-center"></i>
                    <span class="link-text ml-3">Contiendra</span>
                    <span class="tooltip">Contiendra (Pavillon ↔ Trajet)</span>
                </a>
                <a href="{{ route('admin.reserve.index') }}" 
                   class="nav-link flex items-center px-4 py-3 rounded-xl text-gray-300 hover:text-white @if(request()->routeIs('admin.reserve.*')) active @endif">
                    <i class="fas fa-pen-fancy w-5 text-center"></i>
                    <span class="link-text ml-3">Reserve</span>
                    <span class="tooltip">Reserve (Réservation ↔ Pavillon)</span>
                </a>

                <div class="section-title">Système</div>

                <a href="{{ route('admin.settings.index') }}" 
                   class="nav-link flex items-center px-4 py-3 rounded-xl text-gray-300 hover:text-white @if(request()->routeIs('admin.settings.*')) active @endif">
                    <i class="fas fa-cog w-5 text-center"></i>
                    <span class="link-text ml-3">Paramètres</span>
                    <span class="tooltip">Paramètres</span>
                </a>
            </nav>

            <!-- Bas : utilisateur + déconnexion -->
            <div class="p-4 border-t border-white/10 relative z-10">
                <div class="user-info flex items-center space-x-3 mb-4">
                    <div class="user-avatar w-10 h-10 rounded-full flex items-center justify-center shadow-lg">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div class="user-details">
                        <p class="user-name text-sm font-semibold">{{ Auth::user()->prenom ?? '' }} {{ Auth::user()->name ?? 'Admin' }}</p>
                        <p class="user-email text-xs text-gray-400">Administrateur</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn flex items-center w-full justify-center px-4 py-2.5 text-white font-medium rounded-xl">
                        <i class="fas fa-sign-out-alt"></i>
                        <span class="ml-2">Déconnexion</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- CONTENU PRINCIPAL -->
        <div class="main-content flex-1 min-w-0">
            <!-- Header premium -->
            <header class="header-premium sticky top-0 z-30">
                <div class="px-6 py-4 flex items-center justify-between">
                    <div class="flex items-center">
                        <button id="hamburgerBtn" class="lg:hidden mr-4 text-gray-600 hover:text-gray-800 text-xl" onclick="openMobileSidebar()">
                            <i class="fas fa-bars"></i>
                        </button>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800 truncate">@yield('header')</h1>
                            <p class="text-xs text-gray-500 mt-0.5">Administration du système portuaire</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="hidden lg:flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-100">
                            <i class="fas fa-user-shield text-blue-600"></i>
                            <span class="text-sm font-semibold text-gray-700">{{ Auth::user()->prenom ?? '' }} {{ Auth::user()->name ?? 'Admin' }}</span>
                        </div>
                    </div>
                </div>
            </header>

            <main class="p-4 md:p-6">
                @if(session('success'))
                    <div class="flash-success px-5 py-4 rounded-2xl mb-6 flex items-start gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-green-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
                            <i class="fas fa-check-circle text-white"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-emerald-800">Succès !</p>
                            <p class="text-sm text-emerald-700 mt-1">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif
                @if(session('error'))
                    <div class="flash-error px-5 py-4 rounded-2xl mb-6 flex items-start gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-pink-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
                            <i class="fas fa-exclamation-circle text-white"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-red-800">Erreur !</p>
                            <p class="text-sm text-red-700 mt-1">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>

    <!-- JavaScript pour la gestion de la sidebar -->
    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const toggleBtn = document.getElementById('toggleSidebarBtn');
        const toggleIcon = document.getElementById('toggleIcon');
        let isCollapsed = false;

        if (toggleBtn) {
            toggleBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                isCollapsed = !isCollapsed;
                sidebar.classList.toggle('collapsed', isCollapsed);
                toggleIcon.classList.toggle('fa-chevron-left', !isCollapsed);
                toggleIcon.classList.toggle('fa-chevron-right', isCollapsed);
                localStorage.setItem('sidebarCollapsed', isCollapsed);
            });
        }

        const saved = localStorage.getItem('sidebarCollapsed');
        if (saved === 'true' && window.innerWidth >= 1024) {
            isCollapsed = true;
            sidebar.classList.add('collapsed');
            if (toggleIcon) {
                toggleIcon.classList.remove('fa-chevron-left');
                toggleIcon.classList.add('fa-chevron-right');
            }
        }

        function openMobileSidebar() {
            sidebar.classList.add('mobile-open');
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeMobileSidebar() {
            sidebar.classList.remove('mobile-open');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        }

        document.getElementById('hamburgerBtn').addEventListener('click', openMobileSidebar);

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeMobileSidebar();
        });

        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1024) {
                if (sidebar.classList.contains('mobile-open')) {
                    closeMobileSidebar();
                }
                const savedState = localStorage.getItem('sidebarCollapsed');
                if (savedState === 'true') {
                    sidebar.classList.add('collapsed');
                } else {
                    sidebar.classList.remove('collapsed');
                }
            } else {
                sidebar.classList.remove('collapsed');
            }
        });

        sidebar.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    </script>
</body>
</html>