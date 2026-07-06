@extends('layouts.admin')

@push('styles')
    <style>
        /* Animations personnalisées (complément à Tailwind) */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-10px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes rippleAnim {
            to { transform: scale(4); opacity: 0; }
        }
        .card-animate {
            animation: fadeUp 0.6s ease-out forwards;
            opacity: 0;
        }
        .row-animate {
            animation: slideIn 0.4s ease-out forwards;
            opacity: 0;
        }
        .row-1 { animation-delay: 0.05s; }
        .row-2 { animation-delay: 0.10s; }
        .row-3 { animation-delay: 0.15s; }
        .row-4 { animation-delay: 0.20s; }
        .row-5 { animation-delay: 0.25s; }

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

        /* Personnalisation des toggles et modales (si besoin) */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15,23,42,0.5);
            backdrop-filter: blur(4px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }
        .modal-overlay.active {
            display: flex;
        }
        .modal-box {
            animation: fadeUp 0.3s ease;
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
            @apply bg-green-100 text-green-700;
        }
        .badge-inactif {
            @apply bg-red-100 text-red-700;
        }
        .action-btn {
            @apply inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-sm font-medium transition-all duration-200;
        }
        .action-btn.edit {
            @apply bg-blue-50 text-blue-600 hover:bg-blue-100 hover:-translate-y-0.5;
        }
        .action-btn.delete {
            @apply bg-red-50 text-red-600 hover:bg-red-100 hover:-translate-y-0.5;
        }
    </style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- EN-TÊTE --}}
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
        <a href="{{ route('admin.admins.create') }}" class="btn-ripple bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2.5 rounded-xl shadow-lg transition-all duration-300 flex items-center gap-2 text-sm">
            <i class="fas fa-plus-circle"></i> Ajouter un administrateur
        </a>
    </div>

    {{-- STATISTIQUES --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6 card-animate" style="animation-delay:0.05s;">
        <div class="bg-white rounded-xl shadow-sm p-4 flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="bg-blue-100 p-3 rounded-full text-blue-600"><i class="fas fa-users text-lg"></i></div>
            <div>
                <p class="text-2xl font-bold text-gray-800" id="totalCount">{{ $admins->total() }}</p>
                <p class="text-sm text-gray-500">Total</p>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="bg-green-100 p-3 rounded-full text-green-600"><i class="fas fa-check-circle text-lg"></i></div>
            <div>
                <p class="text-2xl font-bold text-gray-800" id="activeCount">{{ $admins->where('statut', 'actif')->count() }}</p>
                <p class="text-sm text-gray-500">Actifs</p>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="bg-red-100 p-3 rounded-full text-red-600"><i class="fas fa-times-circle text-lg"></i></div>
            <div>
                <p class="text-2xl font-bold text-gray-800" id="inactiveCount">{{ $admins->where('statut', 'inactif')->count() }}</p>
                <p class="text-sm text-gray-500">Inactifs</p>
            </div>
        </div>
    </div>

    {{-- FILTRES --}}
    <div class="flex flex-wrap gap-3 items-center mb-6 card-animate" style="animation-delay:0.10s;">
        <div class="relative flex-1 min-w-[200px]">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input type="text" id="searchInput" placeholder="Rechercher par nom, email ou téléphone..." class="w-full pl-9 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
        </div>
        <select id="statusFilter" class="px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white">
            <option value="all">Tous les statuts</option>
            <option value="actif">Actifs</option>
            <option value="inactif">Inactifs</option>
        </select>
        <button id="resetFilters" class="text-sm text-gray-500 hover:text-gray-700 transition-colors flex items-center gap-1">
            <i class="fas fa-undo-alt"></i> Réinitialiser
        </button>
    </div>

    {{-- TABLEAU --}}
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden card-animate" style="animation-delay:0.15s;">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><i class="fas fa-user mr-2"></i>Nom</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><i class="fas fa-envelope mr-2"></i>Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><i class="fas fa-phone mr-2"></i>Téléphone</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><i class="fas fa-circle mr-2"></i>Statut</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"><i class="fas fa-cog mr-2"></i>Actions</th>
                    </tr>
                </thead>
                <tbody id="adminTableBody" class="divide-y divide-gray-100">
                    @forelse($admins as $index => $admin)
                    <tr class="row-animate row-{{ ($index % 5) + 1 }} hover:bg-gray-50 transition" data-id="{{ $admin->id }}" data-status="{{ $admin->statut }}">
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $admin->prenom }} {{ $admin->name }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $admin->email }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $admin->telephone }}</td>
                        <td class="px-6 py-4">
                            <span class="badge {{ $admin->statut == 'actif' ? 'badge-actif' : 'badge-inactif' }}">
                                <i class="fas fa-{{ $admin->statut == 'actif' ? 'check-circle' : 'times-circle' }}"></i>
                                {{ $admin->statut }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.admins.edit', $admin->id) }}" class="action-btn edit">
                                <i class="fas fa-edit"></i> Modifier
                            </a>
                            <button type="button" class="action-btn delete" data-id="{{ $admin->id }}" data-name="{{ $admin->prenom }} {{ $admin->name }}">
                                <i class="fas fa-trash-alt"></i> Supprimer
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                            <i class="fas fa-user-slash text-4xl block mb-3 opacity-50"></i>
                            <p>Aucun administrateur trouvé.</p>
                            <a href="{{ route('admin.admins.create') }}" class="text-blue-600 hover:underline mt-2 inline-block">
                                <i class="fas fa-plus-circle mr-1"></i> Créer le premier administrateur
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($admins->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 flex flex-wrap items-center justify-between gap-2">
            <div class="text-sm text-gray-500">
                Affichage de {{ $admins->firstItem() }} à {{ $admins->lastItem() }} sur {{ $admins->total() }} résultats
            </div>
            <div class="flex gap-1">
                {{ $admins->links() }}
            </div>
        </div>
        @endif
    </div>
</div>

{{-- MODAL DE SUPPRESSION --}}
<div class="modal-overlay" id="deleteModal">
    <div class="modal-box bg-white rounded-2xl p-6 max-w-md w-full shadow-2xl">
        <div class="w-14 h-14 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-exclamation-triangle text-2xl"></i>
        </div>
        <h3 class="text-center text-xl font-bold text-gray-800 mb-2">Confirmer la suppression</h3>
        <p class="text-center text-gray-500 mb-6">Êtes-vous sûr de vouloir supprimer <strong id="deleteName"></strong> ? Cette action est irréversible.</p>
        <div class="flex gap-3 justify-center">
            <button id="modalCancel" class="px-5 py-2.5 bg-gray-200 hover:bg-gray-300 rounded-xl font-medium transition">Annuler</button>
            <button id="modalConfirm" class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl font-medium transition disabled:opacity-60">
                <span id="modalConfirmText"><i class="fas fa-trash-alt mr-1"></i> Supprimer</span>
                <span id="modalConfirmSpinner" class="hidden"><i class="fas fa-spinner fa-spin mr-1"></i> Suppression...</span>
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ripple effect
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

        // Filtres
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const resetBtn = document.getElementById('resetFilters');
        const rows = document.querySelectorAll('#adminTableBody tr:not(.empty-row)');

        function filterTable() {
            const query = searchInput.value.toLowerCase().trim();
            const status = statusFilter.value;
            let visible = 0;
            rows.forEach(row => {
                const name = row.querySelector('td:first-child')?.textContent?.toLowerCase() || '';
                const email = row.querySelector('td:nth-child(2)')?.textContent?.toLowerCase() || '';
                const phone = row.querySelector('td:nth-child(3)')?.textContent?.toLowerCase() || '';
                const rowStatus = row.dataset.status || '';
                const matchSearch = name.includes(query) || email.includes(query) || phone.includes(query);
                const matchStatus = (status === 'all') || (rowStatus === status);
                if (matchSearch && matchStatus) {
                    row.style.display = '';
                    visible++;
                } else {
                    row.style.display = 'none';
                }
            });
            let emptyMsg = document.querySelector('#adminTableBody .empty-result');
            if (visible === 0 && rows.length > 0) {
                if (!emptyMsg) {
                    const tr = document.createElement('tr');
                    tr.className = 'empty-result';
                    tr.innerHTML = `<td colspan="5" class="px-6 py-12 text-center text-gray-400"><i class="fas fa-search text-4xl block mb-3 opacity-50"></i><p>Aucun administrateur ne correspond à vos critères.</p></td>`;
                    document.querySelector('#adminTableBody').appendChild(tr);
                }
            } else {
                if (emptyMsg) emptyMsg.remove();
            }
            document.getElementById('totalCount').textContent = document.querySelectorAll('#adminTableBody tr:not(.empty-result):not(.empty-row)').length;
            document.getElementById('activeCount').textContent = document.querySelectorAll('#adminTableBody tr:not(.empty-result):not(.empty-row)[data-status="actif"]').length;
            document.getElementById('inactiveCount').textContent = document.querySelectorAll('#adminTableBody tr:not(.empty-result):not(.empty-row)[data-status="inactif"]').length;
        }

        searchInput.addEventListener('input', filterTable);
        statusFilter.addEventListener('change', filterTable);
        resetBtn.addEventListener('click', function(e) {
            e.preventDefault();
            searchInput.value = '';
            statusFilter.value = 'all';
            filterTable();
        });

        // Modal suppression
        const modal = document.getElementById('deleteModal');
        const deleteName = document.getElementById('deleteName');
        const modalCancel = document.getElementById('modalCancel');
        const modalConfirm = document.getElementById('modalConfirm');
        const modalConfirmText = document.getElementById('modalConfirmText');
        const modalConfirmSpinner = document.getElementById('modalConfirmSpinner');
        let deleteUrl = null;
        let deleteButton = null;

        document.querySelectorAll('.action-btn.delete').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                const name = this.dataset.name;
                deleteUrl = "{{ route('admin.admins.destroy', ':id') }}".replace(':id', id);
                deleteName.textContent = name;
                deleteButton = this;
                modal.classList.add('active');
                modalConfirm.disabled = false;
                modalConfirmText.classList.remove('hidden');
                modalConfirmSpinner.classList.add('hidden');
            });
        });

        function closeModal() {
            modal.classList.remove('active');
            deleteUrl = null;
            deleteButton = null;
        }
        modalCancel.addEventListener('click', closeModal);
        modal.addEventListener('click', function(e) { if (e.target === this) closeModal(); });
        document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closeModal(); });

        modalConfirm.addEventListener('click', function() {
            if (!deleteUrl || !deleteButton) return;
            this.disabled = true;
            modalConfirmText.classList.add('hidden');
            modalConfirmSpinner.classList.remove('hidden');

            fetch(deleteUrl, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
            })
            .then(response => {
                if (!response.ok) throw new Error('Erreur');
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    const row = deleteButton.closest('tr');
                    if (row) {
                        row.style.transition = 'opacity 0.3s, transform 0.3s';
                        row.style.opacity = '0';
                        row.style.transform = 'scale(0.95)';
                        setTimeout(() => {
                            row.remove();
                            filterTable();
                        }, 300);
                    }
                    closeModal();
                } else {
                    alert('Erreur : ' + (data.message || 'Une erreur est survenue'));
                    closeModal();
                }
            })
            .catch(() => {
                alert('Une erreur est survenue lors de la suppression.');
                closeModal();
            });
        });
    });
</script>
@endpush
@endsection