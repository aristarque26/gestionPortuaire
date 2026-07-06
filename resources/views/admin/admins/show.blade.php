@extends('layouts.admin')

@push('styles')
<style>
    .card-animate {
        animation: fadeUp 0.6s ease-out forwards;
        opacity: 0;
        transform: translateY(20px);
    }
    @keyframes fadeUp {
        to { opacity: 1; transform: translateY(0); }
    }
    .detail-item {
        animation: slideIn 0.5s ease-out forwards;
        opacity: 0;
        transform: translateX(-15px);
        display: flex;
        align-items: flex-start;
        gap: 1.25rem;
        padding: 1.25rem 0;
        border-bottom: 1px solid #f1f5f9;
        transition: background 0.2s, padding 0.2s;
        border-radius: 0.5rem;
        cursor: default;
    }
    .detail-item:last-of-type { border-bottom: none; }
    .detail-item:hover {
        background: #f8fafc;
        padding-left: 0.75rem;
        margin-left: -0.75rem;
    }
    @keyframes slideIn {
        to { opacity: 1; transform: translateX(0); }
    }
    .detail-1 { animation-delay: 0.05s; }
    .detail-2 { animation-delay: 0.10s; }
    .detail-3 { animation-delay: 0.15s; }
    .detail-4 { animation-delay: 0.20s; }
    .detail-5 { animation-delay: 0.25s; }
    .detail-6 { animation-delay: 0.30s; }
    .detail-7 { animation-delay: 0.35s; }

    .avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #3b82f6, #8b5cf6);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
        font-weight: 700;
        flex-shrink: 0;
        box-shadow: 0 8px 20px rgba(59,130,246,0.3);
        user-select: none;
    }
    .badge-actif {
        @apply bg-green-100 text-green-700;
    }
    .badge-inactif {
        @apply bg-red-100 text-red-700;
    }
    .badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.35rem 0.9rem;
        border-radius: 9999px;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: capitalize;
    }
    .badge-actif .pulse {
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #16a34a;
        animation: pulse 2s ease-in-out infinite;
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.4; transform: scale(0.8); }
    }
    .progress-fill {
        transition: width 1.2s ease;
    }
    .toast-copy {
        position: fixed;
        bottom: 30px;
        left: 50%;
        transform: translateX(-50%) translateY(100px);
        background: #0f172a;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 0.75rem;
        font-size: 0.9rem;
        font-weight: 500;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        transition: transform 0.4s ease, opacity 0.4s ease;
        opacity: 0;
        z-index: 9999;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        pointer-events: none;
    }
    .toast-copy.show {
        transform: translateX(-50%) translateY(0);
        opacity: 1;
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
</style>
@endpush

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-2xl shadow-xl p-6 sm:p-8 card-animate">

        {{-- Avatar + infos --}}
        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4 mb-6 pb-6 border-b border-gray-200">
            <div class="avatar">
                {{ strtoupper(substr($admin->prenom, 0, 1)) }}{{ strtoupper(substr($admin->name, 0, 1)) }}
            </div>
            <div class="text-center sm:text-left">
                <h3 class="text-2xl font-bold text-gray-800">{{ $admin->prenom }} {{ $admin->name }}</h3>
                <p class="text-gray-500 text-sm flex items-center justify-center sm:justify-start gap-2">
                    <i class="fas fa-shield-alt text-blue-600"></i>
                    Administrateur {{ $admin->statut == 'actif' ? 'actif' : 'inactif' }}
                    <span class="text-gray-300">•</span>
                    <span><i class="far fa-calendar-alt"></i> Inscrit le {{ $admin->created_at->format('d/m/Y') }}</span>
                </p>
            </div>
        </div>

        {{-- Détails --}}
        <div class="divide-y divide-gray-100">
            <div class="detail-item detail-1">
                <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center text-blue-600 flex-shrink-0"><i class="fas fa-user"></i></div>
                <div>
                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Prénom</div>
                    <div class="text-lg font-medium text-gray-800">{{ $admin->prenom }}</div>
                </div>
            </div>
            <div class="detail-item detail-2">
                <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center text-blue-600 flex-shrink-0"><i class="fas fa-user-tag"></i></div>
                <div>
                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Nom</div>
                    <div class="text-lg font-medium text-gray-800">{{ $admin->name }}</div>
                </div>
            </div>
            <div class="detail-item detail-3">
                <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center text-blue-600 flex-shrink-0"><i class="fas fa-envelope"></i></div>
                <div>
                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider flex items-center gap-2">
                        Adresse email
                        <span class="text-gray-400 font-normal text-[0.6rem]"><i class="fas fa-copy"></i> Cliquer pour copier</span>
                    </div>
                    <div class="text-lg font-medium text-gray-800">
                        <span class="cursor-pointer hover:bg-blue-50 px-2 py-1 rounded-md -mx-2 transition" onclick="copyToClipboard('{{ $admin->email }}', 'Email')">
                            {{ $admin->email }}
                            <span class="text-green-600 text-sm font-semibold opacity-0 transition-opacity" id="emailFeedback"><i class="fas fa-check-circle"></i> Copié !</span>
                        </span>
                    </div>
                </div>
            </div>
            <div class="detail-item detail-4">
                <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center text-blue-600 flex-shrink-0"><i class="fas fa-phone"></i></div>
                <div>
                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider flex items-center gap-2">
                        Téléphone
                        <span class="text-gray-400 font-normal text-[0.6rem]"><i class="fas fa-copy"></i> Cliquer pour copier</span>
                    </div>
                    <div class="text-lg font-medium text-gray-800">
                        <span class="cursor-pointer hover:bg-blue-50 px-2 py-1 rounded-md -mx-2 transition" onclick="copyToClipboard('{{ $admin->telephone }}', 'Téléphone')">
                            {{ $admin->telephone }}
                            <span class="text-green-600 text-sm font-semibold opacity-0 transition-opacity" id="phoneFeedback"><i class="fas fa-check-circle"></i> Copié !</span>
                        </span>
                    </div>
                </div>
            </div>
            <div class="detail-item detail-5">
                <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center text-blue-600 flex-shrink-0"><i class="fas fa-circle"></i></div>
                <div>
                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Statut</div>
                    <div>
                        <span class="badge {{ $admin->statut == 'actif' ? 'badge-actif' : 'badge-inactif' }}">
                            @if($admin->statut == 'actif') <span class="pulse"></span> @else <i class="fas fa-times-circle"></i> @endif
                            {{ $admin->statut }}
                        </span>
                        @if($admin->statut == 'inactif')
                            <span class="text-sm text-gray-400 ml-2"><i class="fas fa-info-circle"></i> Compte désactivé</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="detail-item detail-6">
                <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center text-blue-600 flex-shrink-0"><i class="fas fa-calendar-plus"></i></div>
                <div>
                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Date de création</div>
                    <div class="text-lg font-medium text-gray-800">{{ $admin->created_at->format('l d F Y') }} <span class="text-sm font-normal text-gray-400">à {{ $admin->created_at->format('H:i') }}</span></div>
                </div>
            </div>
            <div class="detail-item detail-7">
                <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center text-blue-600 flex-shrink-0"><i class="fas fa-clock"></i></div>
                <div>
                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Dernière mise à jour</div>
                    <div class="text-lg font-medium text-gray-800">
                        @if($admin->updated_at)
                            {{ $admin->updated_at->format('l d F Y') }} <span class="text-sm font-normal text-gray-400">à {{ $admin->updated_at->format('H:i') }}</span>
                            @if($admin->created_at->diffInDays($admin->updated_at) > 0)
                                <span class="text-sm text-gray-400 ml-2">(modifié)</span>
                            @endif
                        @else
                            <span class="text-gray-400">Non modifié</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Barre de progression du profil --}}
        @php
            $fields = ['prenom' => $admin->prenom, 'name' => $admin->name, 'email' => $admin->email, 'telephone' => $admin->telephone];
            $filled = 0;
            foreach ($fields as $value) if (!empty($value)) $filled++;
            $percentage = round(($filled / count($fields)) * 100);
        @endphp
        <div class="mt-4 pt-4 border-t border-gray-200">
            <div class="flex justify-between text-sm text-gray-500 mb-1">
                <span><i class="fas fa-check-circle text-blue-600"></i> Complétude du profil</span>
                <span class="font-bold">{{ $percentage }}%</span>
            </div>
            <div class="w-full h-2 bg-gray-200 rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-blue-500 to-purple-500 rounded-full progress-fill" style="width: {{ $percentage }}%;"></div>
            </div>
        </div>

        {{-- Boutons d'action --}}
        <div class="flex flex-wrap items-center gap-3 mt-6 pt-6 border-t border-gray-200">
            <a href="{{ route('admin.admins.edit', $admin->id) }}" class="btn-ripple bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2.5 rounded-xl shadow-md transition-all duration-300 flex items-center gap-2">
                <i class="fas fa-edit"></i> Modifier
            </a>
            <a href="{{ route('admin.admins.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold px-6 py-2.5 rounded-xl shadow-md transition-all duration-300 flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
            <form action="{{ route('admin.admins.destroy', $admin->id) }}" method="POST" class="inline ml-auto" onsubmit="return confirm('Supprimer définitivement {{ $admin->prenom }} {{ $admin->name }} ?')">
                @csrf @method('DELETE')
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-semibold px-6 py-2.5 rounded-xl shadow-md transition-all duration-300 flex items-center gap-2">
                    <i class="fas fa-trash-alt"></i> Supprimer
                </button>
            </form>
        </div>
    </div>
</div>

{{-- Toast de copie --}}
<div class="toast-copy" id="toastCopy">
    <i class="fas fa-check-circle text-green-400"></i>
    <span id="toastMessage">Copié dans le presse-papier !</span>
</div>

@push('scripts')
<script>
    // Ripple
    document.querySelectorAll('.btn-ripple').forEach(btn => {
        btn.addEventListener('click', function(e) {
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
    });

    // Copie
    let copyTimeout = null;
    function copyToClipboard(text, label) {
        navigator.clipboard.writeText(text).then(() => {
            const feedbackId = label.toLowerCase() === 'email' ? 'emailFeedback' : 'phoneFeedback';
            const feedback = document.getElementById(feedbackId);
            if (feedback) {
                feedback.classList.add('opacity-100');
                setTimeout(() => feedback.classList.remove('opacity-100'), 2000);
            }
            const toast = document.getElementById('toastCopy');
            document.getElementById('toastMessage').textContent = `${label} copié dans le presse-papier !`;
            toast.classList.add('show');
            clearTimeout(copyTimeout);
            copyTimeout = setTimeout(() => toast.classList.remove('show'), 2000);
        }).catch(() => {
            // Fallback
            const textArea = document.createElement('textarea');
            textArea.value = text;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            const feedbackId = label.toLowerCase() === 'email' ? 'emailFeedback' : 'phoneFeedback';
            const feedback = document.getElementById(feedbackId);
            if (feedback) {
                feedback.classList.add('opacity-100');
                setTimeout(() => feedback.classList.remove('opacity-100'), 2000);
            }
            const toast = document.getElementById('toastCopy');
            document.getElementById('toastMessage').textContent = `${label} copié dans le presse-papier !`;
            toast.classList.add('show');
            clearTimeout(copyTimeout);
            copyTimeout = setTimeout(() => toast.classList.remove('show'), 2000);
        });
    }
</script>
@endpush
@endsection