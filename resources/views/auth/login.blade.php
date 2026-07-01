<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes" />
    <title>Connexion | Votre Application</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <style>
        @keyframes fadeInUp {
            0% { opacity: 0; transform: translateY(15px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        @keyframes popIn {
            0% { opacity: 0; transform: translateY(12px) scale(0.98); }
            100% { opacity: 1; transform: translateY(0) scale(1); }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-4px); }
        }
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        @keyframes pulse-ring {
            0% { transform: scale(1); opacity: 0.8; }
            50% { transform: scale(1.08); opacity: 0.5; }
            100% { transform: scale(1); opacity: 0.8; }
        }

        .animate-fade-in-up { animation: fadeInUp 0.5s ease forwards; }
        .animate-pop-in { animation: popIn 0.5s cubic-bezier(0.22, 1, 0.36, 1) forwards; }
        .animate-float { animation: float 4s ease-in-out infinite; }
        .animate-pulse-ring { animation: pulse-ring 3.5s ease-in-out infinite; }
        .delay-1 { animation-delay: 0.04s; }
        .delay-2 { animation-delay: 0.08s; }
        .delay-3 { animation-delay: 0.12s; }
        .delay-4 { animation-delay: 0.16s; }

        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: #0b0f1a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .bg-gradient-custom {
            position: relative;
            overflow: hidden;
            background: linear-gradient(145deg, rgba(15, 23, 42, 0.58), rgba(30, 64, 175, 0.28), rgba(14, 165, 233, 0.18), rgba(15, 23, 42, 0.72));
            background-attachment: fixed;
        }

        .bg-gradient-custom::before {
            content: '';
            position: absolute;
            inset: -20px;
            background:
                url('https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1920&q=80') center/cover no-repeat;
            filter: blur(12px) saturate(0.7);
            transform: scale(1.08);
            z-index: 0;
        }

        .bg-gradient-custom > * {
            position: relative;
            z-index: 1;
        }

        .card-glass {
            background: rgba(255, 255, 255, 0.93);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 20px 50px -15px rgba(0, 0, 0, 0.5);
            width: 100%;
            max-width: 390px;
            margin: 0 auto;
            overflow: hidden;
        }

        .input-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 0.9rem;
            transition: color 0.2s;
            pointer-events: none;
            line-height: 1;
        }
        .input-group:focus-within .input-icon { color: #7c3aed; }
        .input-with-icon { padding-left: 2.2rem !important; }
        .input-focus-ring:focus {
            border-color: #7c3aed;
            box-shadow: 0 0 0 3px rgba(124,58,237,0.2);
            outline: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #7c3aed, #ec4899);
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
        }
        .btn-primary:hover {
            transform: translateY(-1px) scale(1.01);
            box-shadow: 0 8px 20px -6px rgba(124,58,237,0.4);
        }
        .btn-primary:active { transform: scale(0.98); }
        .btn-shimmer {
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
            background-size: 200% 100%;
            animation: shimmer 3s infinite;
            pointer-events: none;
        }

        .btn-google {
            background: #fff;
            border: 1.5px solid #e2e8f0;
            transition: all 0.2s ease;
            color: #1e293b;
        }
        .btn-google:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        }
        .btn-google:active { transform: scale(0.98); }
        .btn-google svg { width: 18px; height: 18px; flex-shrink: 0; }

        .link-underline {
            position: relative;
            font-weight: 500;
            text-decoration: none;
        }
        .link-underline::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 0;
            height: 1.5px;
            background: #7c3aed;
            transition: width 0.3s;
        }
        .link-underline:hover::after { width: 100%; }

        .custom-checkbox {
            appearance: none;
            width: 16px;
            height: 16px;
            border: 2px solid #cbd5e1;
            border-radius: 4px;
            background: white;
            cursor: pointer;
            transition: all 0.2s;
            flex-shrink: 0;
            margin-top: 1px;
            position: relative;
        }
        .custom-checkbox:checked {
            background: #7c3aed;
            border-color: #7c3aed;
        }
        .custom-checkbox:checked::after {
            content: '\f00c';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            font-size: 10px;
            color: white;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        .custom-checkbox:focus {
            box-shadow: 0 0 0 3px rgba(124,58,237,0.2);
            outline: none;
        }

        .divider-text {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: #94a3b8;
            font-size: 11px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .divider-text::before,
        .divider-text::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        @media (max-width: 400px) {
            .card-glass { padding: 1.25rem 1rem !important; }
            .input-with-icon { padding-left: 2rem !important; }
        }
    </style>
</head>
<body>
    <div class="bg-gradient-custom w-full min-h-screen flex items-center justify-center py-4 px-3">
        <div class="card-glass relative rounded-2xl p-5 sm:p-6 animate-pop-in animate-fade-in-up">
            <button type="button" onclick="window.location.href='{{ url('/') }}'" aria-label="Fermer le formulaire" class="absolute top-3 right-3 z-10 inline-flex h-8 w-8 items-center justify-center rounded-full text-gray-400 hover:text-purple-600 hover:bg-purple-50 transition-all duration-200">
                <i class="fas fa-times text-base"></i>
            </button>
            <div class="text-center animate-fade-in-up delay-1">
                <div class="mx-auto h-11 w-11 bg-gradient-to-br from-purple-600 to-pink-500 rounded-xl flex items-center justify-center shadow-md shadow-purple-500/30 animate-float animate-pulse-ring">
                    <i class="fas fa-anchor text-white text-lg"></i>
                </div>
                <h2 class="mt-2 text-xl sm:text-2xl font-extrabold text-gray-800 tracking-tight">Connexion</h2>
                <p class="text-[11px] sm:text-xs text-gray-500 font-medium mt-0.5">
                    <i class="fas fa-sparkles text-purple-500 mr-0.5"></i>
                    Retrouvez votre espace personnel
                </p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="mt-4 space-y-3">
                @csrf

                <div class="animate-fade-in-up delay-2">
                    <label for="email" class="block text-xs font-semibold text-gray-700 mb-1">Adresse email</label>
                    <div class="relative input-group">
                        <i class="fas fa-envelope input-icon"></i>
                        <input id="email" name="email" type="email" required autofocus
                            class="input-focus-ring input-with-icon w-full px-2.5 py-1.5 sm:py-2 text-sm bg-gray-50/80 border border-gray-200 rounded-xl text-gray-800 placeholder-gray-400 transition-all duration-200"
                            placeholder="jean.dupont@email.com" value="{{ old('email') }}">
                    </div>
                    @error('email')
                        <p class="mt-0.5 text-[10px] text-red-500"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                <div class="animate-fade-in-up delay-3">
                    <div class="flex items-center justify-between mb-1">
                        <label for="password" class="block text-xs font-semibold text-gray-700">Mot de passe</label>
                        @if(Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-[10px] font-medium text-purple-600 hover:text-purple-700 transition-colors">
                                <i class="fas fa-question-circle mr-0.5"></i>Mot de passe oublié ?
                            </a>
                        @endif
                    </div>
                    <div class="relative input-group">
                        <i class="fas fa-key input-icon"></i>
                        <input id="password" name="password" type="password" required
                            class="input-focus-ring input-with-icon w-full px-2.5 py-1.5 sm:py-2 text-sm bg-gray-50/80 border border-gray-200 rounded-xl text-gray-800 placeholder-gray-400 transition-all duration-200"
                            placeholder="••••••••">
                    </div>
                    @error('password')
                        <p class="mt-0.5 text-[10px] text-red-500"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-2 animate-fade-in-up delay-3 pt-0.5">
                    <input id="remember" name="remember" type="checkbox" class="custom-checkbox" {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember" class="text-xs text-gray-600 cursor-pointer select-none">Se souvenir de moi</label>
                </div>

                <div class="animate-fade-in-up delay-4 pt-1">
                    <button type="submit" class="btn-primary w-full flex items-center justify-center gap-2 py-2 px-4 rounded-xl text-white font-semibold text-sm shadow-md shadow-purple-500/20 group">
                        <span class="btn-shimmer"></span>
                        <i class="fas fa-sign-in-alt text-sm group-hover:rotate-6 transition-transform duration-200"></i>
                        Se connecter
                        <i class="fas fa-arrow-right text-xs opacity-70 group-hover:translate-x-0.5 transition-transform duration-200"></i>
                    </button>
                </div>

                <div class="text-center animate-fade-in-up delay-4 pt-0.5">
                    <p class="text-xs text-gray-600">Pas encore de compte ? <a href="{{ route('register') }}" class="link-underline text-purple-600 hover:text-purple-700 ml-0.5">Inscrivez-vous</a></p>
                </div>
            </form>

            <div class="my-3 animate-fade-in-up delay-4">
                <div class="divider-text"><span>ou</span></div>
            </div>

            <a href="{{ route('auth.google') }}" class="btn-google w-full flex items-center justify-center gap-2.5 py-2 px-4 rounded-xl text-sm font-semibold transition-all duration-200 animate-fade-in-up delay-4">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" class="w-4 h-4">
                    <path fill="#FFC107" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"/>
                    <path fill="#FF3D00" d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"/>
                    <path fill="#4CAF50" d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z"/>
                    <path fill="#1976D2" d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z"/>
                </svg>
                Continuer avec Google
            </a>

            <div class="mt-4 text-center text-[10px] text-gray-400 animate-fade-in-up delay-4 border-t border-gray-100 pt-2">
                <i class="fas fa-shield-alt text-purple-400 mr-0.5"></i> Sécurisé SSL
            </div>

        </div>
    </div>
</body>
</html>
