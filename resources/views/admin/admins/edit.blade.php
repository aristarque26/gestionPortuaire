{{-- Mêmes styles que pour la création, mais on peut tout regrouper dans un @push --}}
@push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        /* Réutilisation des mêmes styles que la page de création */
        body {
            font-family: 'Inter', sans-serif;
        }
        .card-animate {
            animation: fadeUp 0.6s ease-out forwards;
            opacity: 0;
            transform: translateY(20px);
        }
        @keyframes fadeUp {
            to { opacity: 1; transform: translateY(0); }
        }
        .form-floating {
            position: relative;
            margin-bottom: 1.5rem;
        }
        .form-floating input,
        .form-floating select {
            width: 100%;
            padding: 1.2rem 1rem 0.6rem 2.8rem;
            border: 2px solid #e2e8f0;
            border-radius: 0.75rem;
            font-size: 1rem;
            background: white;
            transition: all 0.25s ease;
            outline: none;
            color: #1a202c;
        }
        .form-floating input:focus,
        .form-floating select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59,130,246,0.15);
            transform: scale(1.01);
        }
        .form-floating label {
            position: absolute;
            left: 2.8rem;
            top: 0.9rem;
            font-size: 0.95rem;
            color: #718096;
            pointer-events: none;
            transition: all 0.2s ease;
            background: white;
            padding: 0 0.25rem;
        }
        .form-floating input:focus ~ label,
        .form-floating input:not(:placeholder-shown) ~ label,
        .form-floating select:focus ~ label,
        .form-floating select:not([value=""]) ~ label {
            top: -0.55rem;
            left: 2.4rem;
            font-size: 0.75rem;
            font-weight: 600;
            color: #3b82f6;
            background: white;
            padding: 0 0.4rem;
        }
        .form-floating .input-icon {
            position: absolute;
            left: 1rem;
            top: 1rem;
            color: #a0aec0;
            transition: color 0.2s;
            font-size: 1.1rem;
        }
        .form-floating input:focus ~ .input-icon,
        .form-floating select:focus ~ .input-icon {
            color: #3b82f6;
        }
        .btn-ripple {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        .btn-ripple:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(59,130,246,0.4);
        }
        .btn-ripple:active {
            transform: scale(0.96);
        }
        .ripple-effect {
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,0.3);
            transform: scale(0);
            animation: rippleAnim 0.6s linear;
            pointer-events: none;
        }
        @keyframes rippleAnim {
            to { transform: scale(4); opacity: 0; }
        }
        /* Délais d’animation */
        .field-1 { animation-delay: 0.05s; }
        .field-2 { animation-delay: 0.10s; }
        .field-3 { animation-delay: 0.15s; }
        .field-4 { animation-delay: 0.20s; }
        .field-5 { animation-delay: 0.25s; }
        .field-6 { animation-delay: 0.30s; }
        @media (max-width: 640px) {
            .grid-cols-2 { grid-template-columns: 1fr; }
        }
        /* Style pour l’aide sous le champ mot de passe */
        .help-text {
            font-size: 0.8rem;
            color: #718096;
            margin-top: 0.25rem;
            margin-left: 2.8rem;
        }
    </style>
@endpush

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl shadow-xl p-8 card-animate">
        {{-- En-tête --}}
        <div class="flex items-center gap-3 mb-6">
            <div class="bg-amber-100 p-3 rounded-full text-amber-600">
                <i class="fas fa-user-edit text-xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Modifier l'administrateur</h2>
                <p class="text-gray-500 text-sm">Modifiez les informations du compte</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.admins.update', $admin->id) }}" autocomplete="off">
            @csrf
            @method('PUT')

            {{-- Prénom & Nom --}}
            <div class="grid grid-cols-2 gap-4 mb-2">
                <div class="form-floating field-1">
                    <input type="text" name="prenom" id="prenom" required placeholder=" "
                           value="{{ old('prenom', $admin->prenom) }}" autofocus>
                    <i class="fas fa-user input-icon"></i>
                    <label for="prenom">Prénom</label>
                </div>
                <div class="form-floating field-2">
                    <input type="text" name="name" id="name" required placeholder=" "
                           value="{{ old('name', $admin->name) }}">
                    <i class="fas fa-user-tag input-icon"></i>
                    <label for="name">Nom</label>
                </div>
            </div>

            {{-- Email --}}
            <div class="form-floating field-3">
                <input type="email" name="email" id="email" required placeholder=" "
                       value="{{ old('email', $admin->email) }}" autocomplete="email">
                <i class="fas fa-envelope input-icon"></i>
                <label for="email">Adresse email</label>
            </div>

            {{-- Téléphone --}}
            <div class="form-floating field-4">
                <input type="tel" name="telephone" id="telephone" required placeholder=" "
                       value="{{ old('telephone', $admin->telephone) }}" autocomplete="tel">
                <i class="fas fa-phone input-icon"></i>
                <label for="telephone">Numéro de téléphone</label>
            </div>

            {{-- Mot de passe (optionnel) --}}
            <div class="form-floating field-5">
                <input type="password" name="password" id="password" placeholder=" "
                       autocomplete="new-password">
                <i class="fas fa-lock input-icon"></i>
                <label for="password">Nouveau mot de passe</label>
            </div>
            <div class="help-text">
                <i class="fas fa-info-circle mr-1"></i> Laissez vide pour conserver le mot de passe actuel.
            </div>

            {{-- Statut --}}
            <div class="form-floating field-6">
                <select name="statut" id="statut" required>
                    <option value="actif" {{ $admin->statut == 'actif' ? 'selected' : '' }}>Actif</option>
                    <option value="inactif" {{ $admin->statut == 'inactif' ? 'selected' : '' }}>Inactif</option>
                </select>
                <i class="fas fa-toggle-on input-icon"></i>
                <label for="statut">Statut</label>
            </div>

            {{-- Boutons --}}
            <div class="flex items-center justify-end gap-4 mt-6">
                <a href="{{ route('admin.admins.index') }}" 
                   class="text-gray-500 hover:text-gray-700 transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-1"></i> Annuler
                </a>
                <button type="submit" class="btn-ripple bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-3 rounded-xl shadow-lg transition-all duration-300 flex items-center gap-2">
                    <i class="fas fa-sync-alt"></i> Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Script ripple --}}
@push('scripts')
<script>
    document.querySelector('.btn-ripple')?.addEventListener('click', function(e) {
        const rect = this.getBoundingClientRect();
        const ripple = document.createElement('span');
        const size = Math.max(rect.width, rect.height);
        const x = e.clientX - rect.left - size/2;
        const y = e.clientY - rect.top - size/2;
        ripple.style.width = ripple.style.height = size + 'px';
        ripple.style.left = x + 'px';
        ripple.style.top = y + 'px';
        ripple.classList.add('ripple-effect');
        this.appendChild(ripple);
        setTimeout(() => ripple.remove(), 600);
    });
</script>
@endpush
@endsection