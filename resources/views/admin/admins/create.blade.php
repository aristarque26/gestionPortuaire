@extends('layouts.admin')

@push('styles')
<style>
    /* Styles personnalisés (floating labels, force mot de passe, etc.) */
    .form-floating {
        position: relative;
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
    .form-floating input.is-invalid,
    .form-floating select.is-invalid {
        border-color: #ef4444;
        box-shadow: 0 0 0 4px rgba(239,68,68,0.15);
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
        z-index: 2;
    }
    .form-floating input:focus ~ .input-icon,
    .form-floating select:focus ~ .input-icon {
        color: #3b82f6;
    }
    .form-floating .toggle-password {
        position: absolute;
        right: 1rem;
        top: 1rem;
        cursor: pointer;
        color: #a0aec0;
        transition: color 0.2s;
        z-index: 2;
    }
    .form-floating .toggle-password:hover {
        color: #3b82f6;
    }
    .password-strength {
        margin-left: 2.8rem;
        margin-top: 0.25rem;
    }
    .strength-bar {
        display: flex;
        gap: 4px;
        height: 4px;
        margin-top: 6px;
    }
    .strength-bar .segment {
        flex: 1;
        background: #e2e8f0;
        border-radius: 4px;
        transition: background 0.3s ease;
    }
    .strength-bar .segment.active.weak { background: #ef4444; }
    .strength-bar .segment.active.medium { background: #f59e0b; }
    .strength-bar .segment.active.strong { background: #10b981; }
    .strength-text {
        font-size: 0.75rem;
        font-weight: 500;
        margin-top: 4px;
    }
    .strength-text.weak { color: #ef4444; }
    .strength-text.medium { color: #f59e0b; }
    .strength-text.strong { color: #10b981; }
    .toggle-switch {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding-left: 2.8rem;
        margin-top: -0.5rem;
        margin-bottom: 1.5rem;
    }
    .toggle-switch input {
        display: none;
    }
    .toggle-switch .slider {
        width: 44px;
        height: 24px;
        background: #cbd5e1;
        border-radius: 12px;
        cursor: pointer;
        transition: background 0.3s;
        position: relative;
        flex-shrink: 0;
    }
    .toggle-switch .slider::after {
        content: '';
        position: absolute;
        top: 2px;
        left: 2px;
        width: 20px;
        height: 20px;
        background: white;
        border-radius: 50%;
        transition: transform 0.3s;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .toggle-switch input:checked + .slider {
        background: #3b82f6;
    }
    .toggle-switch input:checked + .slider::after {
        transform: translateX(20px);
    }
    .btn-ripple {
        position: relative;
        overflow: hidden;
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
    .card-animate {
        animation: fadeUp 0.6s ease-out forwards;
        opacity: 0;
        transform: translateY(20px);
    }
    @keyframes fadeUp {
        to { opacity: 1; transform: translateY(0); }
    }
    .field-1 { animation-delay: 0.05s; }
    .field-2 { animation-delay: 0.10s; }
    .field-3 { animation-delay: 0.15s; }
    .field-4 { animation-delay: 0.20s; }
    .field-5 { animation-delay: 0.25s; }
    .field-6 { animation-delay: 0.30s; }
    .field-7 { animation-delay: 0.35s; }
    .invalid-feedback {
        font-size: 0.8rem;
        color: #ef4444;
        margin-top: 0.2rem;
        margin-left: 2.8rem;
        display: block;
    }
    .toast-success {
        position: fixed;
        top: 20px;
        right: 20px;
        background: #10b981;
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 0.75rem;
        box-shadow: 0 10px 25px rgba(16,185,129,0.3);
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        transform: translateX(calc(100% + 20px));
        transition: transform 0.4s ease;
        z-index: 9999;
    }
    .toast-success.show {
        transform: translateX(0);
    }
</style>
@endpush

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-2xl shadow-xl p-6 sm:p-8 card-animate">
        {{-- En-tête --}}
        <div class="flex items-center gap-3 mb-6">
            <div class="bg-blue-100 p-3 rounded-full text-blue-600">
                <i class="fas fa-user-plus text-xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Nouvel administrateur</h2>
                <p class="text-gray-500 text-sm">Remplissez tous les champs pour créer un compte</p>
            </div>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.admins.store') }}" autocomplete="off" id="createForm">
            @csrf

            {{-- Prénom & Nom --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-2">
                <div class="form-floating field-1">
                    <input type="text" name="prenom" id="prenom" required placeholder=" " value="{{ old('prenom') }}" autofocus class="@error('prenom') is-invalid @enderror">
                    <i class="fas fa-user input-icon"></i>
                    <label for="prenom">Prénom</label>
                    @error('prenom') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
                <div class="form-floating field-2">
                    <input type="text" name="name" id="name" required placeholder=" " value="{{ old('name') }}" class="@error('name') is-invalid @enderror">
                    <i class="fas fa-user-tag input-icon"></i>
                    <label for="name">Nom</label>
                    @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Email --}}
            <div class="form-floating field-3">
                <input type="email" name="email" id="email" required placeholder=" " value="{{ old('email') }}" autocomplete="email" class="@error('email') is-invalid @enderror">
                <i class="fas fa-envelope input-icon"></i>
                <label for="email">Adresse email</label>
                @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            {{-- Téléphone --}}
            <div class="form-floating field-4">
                <input type="tel" name="telephone" id="telephone" required placeholder=" " value="{{ old('telephone') }}" autocomplete="tel" class="@error('telephone') is-invalid @enderror">
                <i class="fas fa-phone input-icon"></i>
                <label for="telephone">Numéro de téléphone</label>
                @error('telephone') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            {{-- Mot de passe --}}
            <div class="form-floating field-5">
                <input type="password" name="password" id="password" required placeholder=" " autocomplete="new-password" class="@error('password') is-invalid @enderror">
                <i class="fas fa-lock input-icon"></i>
                <label for="password">Mot de passe</label>
                <i class="fas fa-eye toggle-password" onclick="togglePassword(this)"></i>
                @error('password') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            {{-- Indicateur de force --}}
            <div class="password-strength" id="passwordStrength">
                <div class="strength-bar">
                    <div class="segment" id="seg1"></div>
                    <div class="segment" id="seg2"></div>
                    <div class="segment" id="seg3"></div>
                    <div class="segment" id="seg4"></div>
                </div>
                <div class="strength-text" id="strengthText"></div>
            </div>

            {{-- Confirmation --}}
            <div class="form-floating field-6">
                <input type="password" name="password_confirmation" id="password_confirmation" required placeholder=" " autocomplete="new-password">
                <i class="fas fa-check-circle input-icon"></i>
                <label for="password_confirmation">Confirmer le mot de passe</label>
                <i class="fas fa-eye toggle-password" onclick="togglePassword(this)"></i>
                <span class="invalid-feedback" id="confirmError"></span>
            </div>

            {{-- Statut (toggle) --}}
            <div class="toggle-switch field-7">
                <input type="checkbox" id="statutToggle" name="statut" value="actif" checked>
                <label for="statutToggle" class="slider"></label>
                <span class="label-text text-gray-700">Statut : <span id="statutLabel" class="font-medium">Actif</span></span>
            </div>
            <input type="hidden" name="statut" id="statutHidden" value="actif">

            {{-- Boutons --}}
            <div class="flex flex-wrap items-center justify-end gap-4 mt-6">
                <a href="{{ route('admin.admins.index') }}" class="text-gray-500 hover:text-gray-700 transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-1"></i> Annuler
                </a>
                <button type="submit" class="btn-ripple bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-3 rounded-xl shadow-lg transition-all duration-300 flex items-center gap-2" id="submitBtn">
                    <span id="btnText"><i class="fas fa-save"></i> Créer l'administrateur</span>
                    <span id="btnSpinner" class="hidden"><i class="fas fa-spinner fa-spin"></i> Création...</span>
                </button>
            </div>
        </form>
    </div>
</div>

@if(session('success'))
<div class="toast-success show" id="successToast">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

@push('scripts')
<script>
    function togglePassword(el) {
        const input = el.parentElement.querySelector('input');
        if (input.type === 'password') {
            input.type = 'text';
            el.classList.remove('fa-eye');
            el.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            el.classList.remove('fa-eye-slash');
            el.classList.add('fa-eye');
        }
    }

    // Indicateur de force
    const passwordInput = document.getElementById('password');
    const seg1 = document.getElementById('seg1');
    const seg2 = document.getElementById('seg2');
    const seg3 = document.getElementById('seg3');
    const seg4 = document.getElementById('seg4');
    const strengthText = document.getElementById('strengthText');

    function checkStrength(password) {
        let score = 0;
        if (password.length >= 8) score++;
        if (/[a-z]/.test(password) && /[A-Z]/.test(password)) score++;
        if (/\d/.test(password)) score++;
        if (/[^a-zA-Z0-9]/.test(password)) score++;
        return score;
    }

    function updateStrength() {
        const password = passwordInput.value;
        const score = checkStrength(password);
        const segments = [seg1, seg2, seg3, seg4];
        segments.forEach((seg, i) => {
            seg.className = 'segment';
            if (i < score) {
                seg.classList.add('active');
                if (score <= 2) seg.classList.add('weak');
                else if (score === 3) seg.classList.add('medium');
                else seg.classList.add('strong');
            }
        });
        if (password.length === 0) {
            strengthText.textContent = '';
            strengthText.className = 'strength-text';
        } else if (score <= 2) {
            strengthText.textContent = 'Faible';
            strengthText.className = 'strength-text weak';
        } else if (score === 3) {
            strengthText.textContent = 'Moyen';
            strengthText.className = 'strength-text medium';
        } else {
            strengthText.textContent = 'Fort';
            strengthText.className = 'strength-text strong';
        }
    }

    passwordInput.addEventListener('input', updateStrength);

    // Validation confirmation
    const confirmInput = document.getElementById('password_confirmation');
    const confirmError = document.getElementById('confirmError');

    function checkConfirm() {
        if (confirmInput.value.length > 0 && confirmInput.value !== passwordInput.value) {
            confirmError.textContent = 'Les mots de passe ne correspondent pas.';
            confirmInput.classList.add('is-invalid');
        } else {
            confirmError.textContent = '';
            confirmInput.classList.remove('is-invalid');
        }
    }
    passwordInput.addEventListener('input', checkConfirm);
    confirmInput.addEventListener('input', checkConfirm);

    // Toggle statut
    const toggle = document.getElementById('statutToggle');
    const statutLabel = document.getElementById('statutLabel');
    const statutHidden = document.getElementById('statutHidden');
    toggle.addEventListener('change', function() {
        if (this.checked) {
            statutLabel.textContent = 'Actif';
            statutHidden.value = 'actif';
        } else {
            statutLabel.textContent = 'Inactif';
            statutHidden.value = 'inactif';
        }
    });

    // Soumission
    const form = document.getElementById('createForm');
    const submitBtn = document.getElementById('submitBtn');
    const btnText = document.getElementById('btnText');
    const btnSpinner = document.getElementById('btnSpinner');

    form.addEventListener('submit', function(e) {
        if (passwordInput.value !== confirmInput.value && confirmInput.value.length > 0) {
            e.preventDefault();
            confirmError.textContent = 'Les mots de passe ne correspondent pas.';
            confirmInput.classList.add('is-invalid');
            return;
        }
        submitBtn.disabled = true;
        btnText.classList.add('hidden');
        btnSpinner.classList.remove('hidden');
    });

    // Ripple
    document.querySelector('.btn-ripple')?.addEventListener('click', function(e) {
        if (this.disabled) return;
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

    // Toast
    const toast = document.getElementById('successToast');
    if (toast) {
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 400);
        }, 4000);
    }
</script>
@endpush
@endsection