@push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
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
        .btn-ripple {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        .btn-ripple:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(0,0,0,0.15);
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
        .detail-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            padding: 1rem 0;
            border-bottom: 1px solid #f1f5f9;
        }
        .detail-item:last-of-type {
            border-bottom: none;
        }
        .detail-icon {
            width: 2.5rem;
            height: 2.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f1f5f9;
            border-radius: 0.75rem;
            color: #3b82f6;
            font-size: 1.1rem;
            flex-shrink: 0;
        }
        .detail-content {
            flex: 1;
        }
        .detail-label {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #94a3b8;
            margin-bottom: 0.15rem;
        }
        .detail-value {
            font-size: 1.1rem;
            font-weight: 500;
            color: #1e293b;
        }
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: capitalize;
        }
        .badge-actif {
            background: #dcfce7;
            color: #166534;
        }
        .badge-inactif {
            background: #fee2e2;
            color: #991b1b;
        }
        .action-buttons {
            display: flex;
            gap: 0.75rem;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 2px solid #f1f5f9;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.6rem 1.5rem;
            border-radius: 0.75rem;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            text-decoration: none;
            border: none;
            cursor: pointer;
        }
        .btn-primary {
            background: #3b82f6;
            color: white;
        }
        .btn-primary:hover {
            background: #2563eb;
            box-shadow: 0 8px 15px -5px rgba(59,130,246,0.4);
        }
        .btn-secondary {
            background: #e2e8f0;
            color: #475569;
        }
        .btn-secondary:hover {
            background: #cbd5e1;
            transform: translateY(-2px);
        }
        @media (max-width: 640px) {
            .detail-item {
                flex-direction: column;
                gap: 0.25rem;
            }
            .detail-icon {
                width: 2rem;
                height: 2rem;
                font-size: 0.9rem;
            }
            .action-buttons {
                flex-direction: column;
            }
            .btn {
                justify-content: center;
            }
        }
    </style>
@endpush

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl shadow-xl p-6 sm:p-8 card-animate">
        {{-- En-tête --}}
        <div class="flex items-center gap-3 mb-6">
            <div class="bg-purple-100 p-3 rounded-full text-purple-600">
                <i class="fas fa-user-circle text-xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Détails de l'administrateur</h2>
                <p class="text-gray-500 text-sm">Informations complètes du compte</p>
            </div>
        </div>

        {{-- Détails --}}
        <div class="divide-y divide-gray-100">
            {{-- Prénom --}}
            <div class="detail-item">
                <div class="detail-icon"><i class="fas fa-user"></i></div>
                <div class="detail-content">
                    <div class="detail-label">Prénom</div>
                    <div class="detail-value">{{ $admin->prenom }}</div>
                </div>
            </div>

            {{-- Nom --}}
            <div class="detail-item">
                <div class="detail-icon"><i class="fas fa-user-tag"></i></div>
                <div class="detail-content">
                    <div class="detail-label">Nom</div>
                    <div class="detail-value">{{ $admin->name }}</div>
                </div>
            </div>

            {{-- Email --}}
            <div class="detail-item">
                <div class="detail-icon"><i class="fas fa-envelope"></i></div>
                <div class="detail-content">
                    <div class="detail-label">Adresse email</div>
                    <div class="detail-value">{{ $admin->email }}</div>
                </div>
            </div>

            {{-- Téléphone --}}
            <div class="detail-item">
                <div class="detail-icon"><i class="fas fa-phone"></i></div>
                <div class="detail-content">
                    <div class="detail-label">Téléphone</div>
                    <div class="detail-value">{{ $admin->telephone }}</div>
                </div>
            </div>

            {{-- Statut --}}
            <div class="detail-item">
                <div class="detail-icon"><i class="fas fa-circle"></i></div>
                <div class="detail-content">
                    <div class="detail-label">Statut</div>
                    <div class="detail-value">
                        <span class="badge {{ $admin->statut == 'actif' ? 'badge-actif' : 'badge-inactif' }}">
                            <i class="fas fa-{{ $admin->statut == 'actif' ? 'check-circle' : 'times-circle' }}"></i>
                            {{ $admin->statut }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Date de création --}}
            <div class="detail-item">
                <div class="detail-icon"><i class="fas fa-calendar-alt"></i></div>
                <div class="detail-content">
                    <div class="detail-label">Date de création</div>
                    <div class="detail-value">{{ $admin->created_at->format('d/m/Y \à H:i') }}</div>
                </div>
            </div>
        </div>

        {{-- Boutons d'action --}}
        <div class="action-buttons">
            <a href="{{ route('admin.admins.edit', $admin->id) }}" class="btn btn-primary btn-ripple">
                <i class="fas fa-edit"></i> Modifier
            </a>
            <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
        </div>
    </div>
</div>

{{-- Script ripple pour le bouton Modifier --}}
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