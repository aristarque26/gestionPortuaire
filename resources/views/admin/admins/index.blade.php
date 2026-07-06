@push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        /* Réutilisation des styles communs */
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

        /* Bouton avec ripple */
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

        /* Tableau moderne */
        .table-container {
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 10px 30px -10px rgba(0,0,0,0.08);
        }
        .table-container table {
            width: 100%;
            border-collapse: collapse;
        }
        .table-container thead {
            background: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
        }
        .table-container thead th {
            padding: 1rem 1.5rem;
            text-align: left;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
        }
        .table-container tbody tr {
            transition: background 0.2s ease;
            border-bottom: 1px solid #f1f5f9;
        }
        .table-container tbody tr:hover {
            background: #f8fafc;
        }
        .table-container tbody td {
            padding: 1rem 1.5rem;
            vertical-align: middle;
            font-size: 0.9rem;
            color: #1e293b;
        }
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
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
        .action-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.3rem 0.8rem;
            border-radius: 0.5rem;
            font-size: 0.8rem;
            font-weight: 500;
            transition: all 0.2s ease;
            text-decoration: none;
        }
        .action-btn.edit {
            color: #2563eb;
            background: #eff6ff;
        }
        .action-btn.edit:hover {
            background: #dbeafe;
            transform: translateY(-1px);
        }
        .action-btn.delete {
            color: #dc2626;
            background: #fef2f2;
        }
        .action-btn.delete:hover {
            background: #fee2e2;
            transform: translateY(-1px);
        }
        .action-btn.delete form {
            display: inline;
        }
        /* Animation des lignes */
        .row-animate {
            animation: slideIn 0.4s ease-out forwards;
            opacity: 0;
            transform: translateX(-10px);
        }
        @keyframes slideIn {
            to { opacity: 1; transform: translateX(0); }
        }
        .row-1 { animation-delay: 0.05s; }
        .row-2 { animation-delay: 0.10s; }
        .row-3 { animation-delay: 0.15s; }
        .row-4 { animation-delay: 0.20s; }
        .row-5 { animation-delay: 0.25s; }
        /* etc. On peut utiliser un compteur JS pour gérer plus proprement, mais pour l'exemple c'est suffisant */

        /* Responsive */
        @media (max-width: 768px) {
            .table-container table {
                font-size: 0.8rem;
            }
            .table-container thead th,
            .table-container tbody td {
                padding: 0.75rem 1rem;
            }
            .action-btn {
                padding: 0.2rem 0.5rem;
                font-size: 0.7rem;
            }
        }
    </style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto">
    {{-- En-tête avec titre et bouton d'ajout --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6 card-animate">
        <div class="flex items-center gap-3">
            <div class="bg-indigo-100 p-3 rounded-full text-indigo-600">
                <i class="fas fa-users-cog text-xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Administrateurs</h2>
                <p class="text-gray-500 text-sm">Gérez les comptes administrateurs de la plateforme</p>
            </div>
        </div>
        <a href="{{ route('admin.admins.create') }}" 
           class="btn-ripple bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2.5 rounded-xl shadow-lg transition-all duration-300 flex items-center gap-2 text-sm">
            <i class="fas fa-plus-circle"></i> Ajouter un administrateur
        </a>
    </div>

    {{-- Tableau --}}
    <div class="table-container card-animate" style="animation-delay: 0.15s;">
        <table>
            <thead>
                <tr>
                    <th><i class="fas fa-user mr-2"></i>Nom</th>
                    <th><i class="fas fa-envelope mr-2"></i>Email</th>
                    <th><i class="fas fa-phone mr-2"></i>Téléphone</th>
                    <th><i class="fas fa-circle mr-2"></i>Statut</th>
                    <th class="text-right"><i class="fas fa-cog mr-2"></i>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($admins as $index => $admin)
                <tr class="row-animate row-{{ ($index % 5) + 1 }}">
                    <td class="font-medium">{{ $admin->prenom }} {{ $admin->name }}</td>
                    <td>{{ $admin->email }}</td>
                    <td>{{ $admin->telephone }}</td>
                    <td>
                        <span class="badge {{ $admin->statut == 'actif' ? 'badge-actif' : 'badge-inactif' }}">
                            <i class="fas fa-{{ $admin->statut == 'actif' ? 'check-circle' : 'times-circle' }}"></i>
                            {{ $admin->statut }}
                        </span>
                    </td>
                    <td class="text-right">
                        <a href="{{ route('admin.admins.edit', $admin->id) }}" class="action-btn edit">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                        <form action="{{ route('admin.admins.destroy', $admin->id) }}" method="POST" class="inline-block" 
                              onsubmit="return confirm('Supprimer définitivement {{ $admin->prenom }} {{ $admin->name }} ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn delete">
                                <i class="fas fa-trash-alt"></i> Supprimer
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Si vous avez une pagination, placez-la ici --}}
    {{-- {{ $admins->links() }} --}}
</div>

{{-- Script ripple pour le bouton d'ajout --}}
@push('scripts')
<script>
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
</script>
@endpush
@endsection