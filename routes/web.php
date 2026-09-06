<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BateauController;
use App\Http\Controllers\Admin\PortController;
use App\Http\Controllers\Admin\QuaiController;
use App\Http\Controllers\Admin\VoyageController;
use App\Http\Controllers\Admin\PavillonController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\ReservationController;
use App\Http\Controllers\Admin\PaiementController;
use App\Http\Controllers\Admin\TrajetController;
use App\Http\Controllers\Admin\ConcederController;
use App\Http\Controllers\Admin\AppartenirController;
use App\Http\Controllers\Admin\ContiendraController;
use App\Http\Controllers\Admin\ReserveController;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;
use App\Http\Controllers\Client\DashboardController as ClientDashboardController;
use App\Http\Controllers\Client\ReservationController as ClientReservationController;
use App\Http\Controllers\Client\PaiementController as ClientPaiementController;
use App\Http\Controllers\Client\ProfilController;
use App\Http\Controllers\Client\VoyageController as ClientVoyageController;
use App\Http\Controllers\Client\SettingsController as ClientSettingsController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\FacebookController;
use App\Http\Controllers\FindReservationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Pavillon;
use App\Exports\ReservationsExport;
use Maatwebsite\Excel\Facades\Excel;
use Laravel\Socialite\Facades\Socialite;

Route::get('/', function () {
    return view('welcome');
});

// ========== API ROUTES (pour vérification dynamique) ==========
Route::get('/api/verifier-disponibilite-pavillon', function (Request $request) {
    $voyageId = $request->voyage;
    $pavillonId = $request->pavillon;
    
    $pavillon = Pavillon::find($pavillonId);
    
    // Places restantes
    $placesReservees = Reservation::where('idvoyage', $voyageId)
                        ->where('idpavillon', $pavillonId)
                        ->count();
    $placesRestantes = max(0, ($pavillon->capacite_max ?? 0) - $placesReservees);
    
    // Tonnes restantes
    $tonnesReservees = Reservation::where('idvoyage', $voyageId)
                        ->where('idpavillon', $pavillonId)
                        ->sum('poids_cargaison');
    $tonnesRestantes = max(0, ($pavillon->capacite_max ?? 0) - $tonnesReservees);
    
    return response()->json([
        'places_restantes' => $placesRestantes,
        'tonnes_restantes' => $tonnesRestantes
    ]);
})->name('api.verifier.disponibilite');

// ========== ROUTES D'AUTHENTIFICATION (directes) ==========
Route::get('/login', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'store']);
Route::get('/register', [App\Http\Controllers\Auth\RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [App\Http\Controllers\Auth\RegisteredUserController::class, 'store']);

// ========== ROUTES GOOGLE AUTH ==========
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// ========== ROUTES FACEBOOK AUTH ==========
Route::get('/auth/facebook/redirect', [FacebookController::class, 'redirectToFacebook'])->name('auth.facebook.redirect');
Route::get('/auth/facebook/callback', [FacebookController::class, 'handleFacebookCallback'])->name('auth.facebook.callback');

// ========== ROUTES TROUVER DES RÉSERVATIONS ==========
Route::get('/trouver-reservations', [FindReservationController::class, 'showForm'])->name('find.form');
Route::post('/trouver-reservations', [FindReservationController::class, 'sendCode'])->name('find.send');
Route::get('/verifier-code', [FindReservationController::class, 'showVerifyForm'])->name('find.verify.form');
Route::post('/verifier-code', [FindReservationController::class, 'verifyCode'])->name('find.verify');
// ===========================================================

// Routes protégées par authentification
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // ========== DASHBOARD ADMIN ==========
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('admins', AdminController::class);
        Route::resource('bateaux', BateauController::class);
        Route::resource('ports', PortController::class);
        Route::resource('quais', QuaiController::class);
        Route::resource('voyages', VoyageController::class);
        Route::resource('pavillons', PavillonController::class);
        Route::resource('clients', ClientController::class);
        Route::resource('reservations', ReservationController::class);
        // Route pour confirmer une réservation
        Route::patch('reservations/{id}/confirmer', [ReservationController::class, 'confirmer'])->name('reservations.confirmer');
        Route::resource('paiements', PaiementController::class);
        Route::resource('trajets', TrajetController::class);
        
        // Export Excel
        Route::get('/export-reservations', function () {
            return Excel::download(new ReservationsExport, 'reservations.xlsx');
        })->name('export.reservations');
        Route::get('/export', function () {
            return Excel::download(new ReservationsExport, 'reservations.xlsx');
        })->name('export');
        
        // Tables associatives
        Route::prefix('conceder')->name('conceder.')->group(function () {
            Route::get('/', [ConcederController::class, 'index'])->name('index');
            Route::get('/create', [ConcederController::class, 'create'])->name('create');
            Route::post('/', [ConcederController::class, 'store'])->name('store');
            Route::get('/{idport}/{idtrajet}', [ConcederController::class, 'show'])->name('show');
            Route::get('/{idport}/{idtrajet}/edit', [ConcederController::class, 'edit'])->name('edit');
            Route::put('/{idport}/{idtrajet}', [ConcederController::class, 'update'])->name('update');
            Route::delete('/{idport}/{idtrajet}', [ConcederController::class, 'destroy'])->name('destroy');
        });
        
        Route::prefix('appartenir')->name('appartenir.')->group(function () {
            Route::get('/', [AppartenirController::class, 'index'])->name('index');
            Route::get('/create', [AppartenirController::class, 'create'])->name('create');
            Route::post('/', [AppartenirController::class, 'store'])->name('store');
            Route::get('/{idbateau}/{idquai}', [AppartenirController::class, 'show'])->name('show');
            Route::get('/{idbateau}/{idquai}/edit', [AppartenirController::class, 'edit'])->name('edit');
            Route::put('/{idbateau}/{idquai}', [AppartenirController::class, 'update'])->name('update');
            Route::delete('/{idbateau}/{idquai}', [AppartenirController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('contiendra')->name('contiendra.')->group(function () {
            Route::get('/', [ContiendraController::class, 'index'])->name('index');
            Route::get('/create', [ContiendraController::class, 'create'])->name('create');
            Route::post('/', [ContiendraController::class, 'store'])->name('store');
            Route::get('/{idpavillon}/{idtrajet}', [ContiendraController::class, 'show'])->name('show');
            Route::get('/{idpavillon}/{idtrajet}/edit', [ContiendraController::class, 'edit'])->name('edit');
            Route::put('/{idpavillon}/{idtrajet}', [ContiendraController::class, 'update'])->name('update');
            Route::delete('/{idpavillon}/{idtrajet}', [ContiendraController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('reserve')->name('reserve.')->group(function () {
            Route::get('/', [ReserveController::class, 'index'])->name('index');
            Route::get('/{idreservation}/{idpavillon}', [ReserveController::class, 'show'])->name('show');
            Route::get('/{idreservation}/{idpavillon}/edit', [ReserveController::class, 'edit'])->name('edit');
            Route::put('/{idreservation}/{idpavillon}', [ReserveController::class, 'update'])->name('update');
            Route::delete('/{idreservation}/{idpavillon}', [ReserveController::class, 'destroy'])->name('destroy');
        });

        // Paramètres admin
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [AdminSettingsController::class, 'index'])->name('index');
            Route::get('/general', [AdminSettingsController::class, 'general'])->name('general');
            Route::get('/notifications', [AdminSettingsController::class, 'notifications'])->name('notifications');
            Route::get('/securite', [AdminSettingsController::class, 'securite'])->name('securite');
            Route::get('/facturation', [AdminSettingsController::class, 'facturation'])->name('facturation');
            Route::get('/apparence', [AdminSettingsController::class, 'apparence'])->name('apparence');
            Route::get('/avance', [AdminSettingsController::class, 'avance'])->name('avance');
            
            Route::post('/general', [AdminSettingsController::class, 'updateGeneral'])->name('update.general');
            Route::post('/notifications', [AdminSettingsController::class, 'updateNotifications'])->name('update.notifications');
            Route::post('/securite', [AdminSettingsController::class, 'updateSecurite'])->name('update.securite');
            Route::post('/facturation', [AdminSettingsController::class, 'updateFacturation'])->name('update.facturation');
            Route::post('/apparence', [AdminSettingsController::class, 'updateApparence'])->name('update.apparence');
            Route::post('/avance', [AdminSettingsController::class, 'updateAvance'])->name('update.avance');
        });
    });
    
    // ========== DASHBOARD CLIENT ==========
    Route::middleware('role:client')->prefix('client')->name('client.')->group(function () {
        Route::get('/dashboard', [ClientDashboardController::class, 'index'])->name('dashboard');
        Route::get('/voyages', [ClientVoyageController::class, 'index'])->name('voyages.index');
        
        // ✅ AJOUT : Route pour voir le détail d'un voyage
        Route::get('/voyages/{id}', [ClientVoyageController::class, 'show'])->name('client.voyages.show');
        
        Route::resource('reservations', ClientReservationController::class);
        
        // 🔥 ROUTES DE PAIEMENT AJOUTÉES ICI 🔥
        Route::get('reservation/{id}/paiement', [ClientReservationController::class, 'pagePaiement'])->name('client.reservation.paiement');
        Route::post('reservation/{id}/paiement/effectuer', [ClientReservationController::class, 'effectuerPaiement'])->name('client.reservation.effectuer.paiement');
        
        // ✅ AJOUT : Route avec "reservations" (avec un 's') pour correspondre à ton URL
        Route::get('reservations/{id}/paiement', [ClientReservationController::class, 'pagePaiement'])->name('client.reservations.paiement');
        Route::post('reservations/{id}/paiement/effectuer', [ClientReservationController::class, 'effectuerPaiement'])->name('client.reservations.effectuer.paiement');
        
        // ✅ AJOUT : Route pour télécharger la facture (PDF)
        Route::get('reservation/{id}/facture', [ClientReservationController::class, 'telechargerFacture'])->name('client.reservation.facture');
        
        Route::resource('paiements', ClientPaiementController::class);
        Route::get('/profil', [ProfilController::class, 'show'])->name('profil.show');
        Route::get('/profil/edit', [ProfilController::class, 'edit'])->name('profil.edit');
        Route::put('/profil', [ProfilController::class, 'update'])->name('profil.update');
        
        // Paramètres client
        Route::get('/settings', [ClientSettingsController::class, 'index'])->name('settings.index');
        Route::get('/settings/profile', [ClientSettingsController::class, 'profile'])->name('settings.profile');
        Route::post('/settings/profile', [ClientSettingsController::class, 'updateProfile'])->name('settings.update.profile');
        Route::get('/settings/theme', [ClientSettingsController::class, 'theme'])->name('settings.theme');
        Route::post('/settings/theme', [ClientSettingsController::class, 'updateTheme'])->name('settings.update.theme');
        
        // AJOUT : Routes pour la gestion du mot de passe (CORRIGÉES)
        Route::get('/settings/password', [ClientSettingsController::class, 'password'])->name('settings.password');
        Route::post('/settings/password', [ClientSettingsController::class, 'updatePassword'])->name('settings.update.password');
    });
    
    // ========== DASHBOARD PERSONNEL (Générique - Fallback) ==========
    Route::middleware(['auth', 'role:personnel'])->prefix('personnel')->name('personnel.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Personnel\DashboardController::class, 'index'])->name('dashboard');
    });

    // ========== GESTION DU PERSONNEL (ADMIN) ==========
    Route::middleware(['auth', 'role:admin'])->prefix('admin/personnel')->name('admin.personnel.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\PersonnelController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Admin\PersonnelController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Admin\PersonnelController::class, 'store'])->name('store');
        Route::get('/{id}', [App\Http\Controllers\Admin\PersonnelController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [App\Http\Controllers\Admin\PersonnelController::class, 'edit'])->name('edit');
        Route::put('/{id}', [App\Http\Controllers\Admin\PersonnelController::class, 'update'])->name('update');
        Route::delete('/{id}', [App\Http\Controllers\Admin\PersonnelController::class, 'destroy'])->name('destroy');
    });

    // ================================================================
    // ========== 🚢 DASHBOARD SUPERVISEUR ==========
    // ================================================================
    Route::middleware(['auth', 'role:personnel,superviseur'])
         ->prefix('superviseur')
         ->name('superviseur.')
         ->group(function () {
             
        // ===== DASHBOARD =====
        Route::get('/dashboard', [App\Http\Controllers\Superviseur\DashboardController::class, 'index'])
             ->name('dashboard');

        // ===== RÉSERVATIONS =====
        Route::prefix('reservations')->name('reservations.')->group(function () {
            Route::get('/', [App\Http\Controllers\Superviseur\ReservationController::class, 'index'])
                 ->name('index');
            Route::get('/export', [App\Http\Controllers\Superviseur\ReservationController::class, 'export'])
                 ->name('export');
            Route::get('/{id}', [App\Http\Controllers\Superviseur\ReservationController::class, 'show'])
                 ->name('show');
            Route::put('/{id}/confirmer', [App\Http\Controllers\Superviseur\ReservationController::class, 'confirmer'])
                 ->name('confirmer');
            Route::put('/{id}/annuler', [App\Http\Controllers\Superviseur\ReservationController::class, 'annuler'])
                 ->name('annuler');
            Route::put('/{id}/marquer-paye', [App\Http\Controllers\Superviseur\ReservationController::class, 'marquerPayee'])
                 ->name('marquer.paye');
            Route::put('/{id}/marquer-arrivee', [App\Http\Controllers\Superviseur\ReservationController::class, 'marquerArrivee'])
                 ->name('marquer.arrivee');
        });

        // ===== PERSONNEL (RH) =====
        Route::prefix('personnel')->name('personnel.')->group(function () {
            Route::get('/', [App\Http\Controllers\Superviseur\PersonnelController::class, 'index'])
                 ->name('index');
            Route::get('/export', [App\Http\Controllers\Superviseur\PersonnelController::class, 'export'])
                 ->name('export');
            Route::get('/{id}', [App\Http\Controllers\Superviseur\PersonnelController::class, 'show'])
                 ->name('show');
            Route::get('/{id}/edit', [App\Http\Controllers\Superviseur\PersonnelController::class, 'edit'])
                 ->name('edit');
            Route::put('/{id}', [App\Http\Controllers\Superviseur\PersonnelController::class, 'update'])
                 ->name('update');
            Route::put('/{id}/activer', [App\Http\Controllers\Superviseur\PersonnelController::class, 'activer'])
                 ->name('activer');
            Route::put('/{id}/desactiver', [App\Http\Controllers\Superviseur\PersonnelController::class, 'desactiver'])
                 ->name('desactiver');
        });

        // ===== QUAIS =====
        Route::prefix('quais')->name('quais.')->group(function () {
            Route::get('/', [App\Http\Controllers\Superviseur\QuaiController::class, 'index'])
                 ->name('index');
            Route::get('/export', [App\Http\Controllers\Superviseur\QuaiController::class, 'export'])
                 ->name('export');
            Route::get('/{id}', [App\Http\Controllers\Superviseur\QuaiController::class, 'show'])
                 ->name('show');
            Route::get('/{id}/bateaux', [App\Http\Controllers\Superviseur\QuaiController::class, 'bateaux'])
                 ->name('bateaux');
            Route::put('/{id}/statut', [App\Http\Controllers\Superviseur\QuaiController::class, 'updateStatut'])
                 ->name('statut');
            Route::put('/{id}/liberer', [App\Http\Controllers\Superviseur\QuaiController::class, 'liberer'])
                 ->name('liberer');
        });

        // ===== VOYAGES =====
        Route::prefix('voyages')->name('voyages.')->group(function () {
            Route::get('/', [App\Http\Controllers\Superviseur\VoyageController::class, 'index'])
                 ->name('index');
            Route::get('/export', [App\Http\Controllers\Superviseur\VoyageController::class, 'export'])
                 ->name('export');
            Route::get('/{id}', [App\Http\Controllers\Superviseur\VoyageController::class, 'show'])
                 ->name('show');
            Route::get('/{id}/reservations', [App\Http\Controllers\Superviseur\VoyageController::class, 'reservations'])
                 ->name('reservations');
            Route::get('/{id}/trajets', [App\Http\Controllers\Superviseur\VoyageController::class, 'trajets'])
                 ->name('trajets');
        });

        // ===== BATEAUX =====
        Route::prefix('bateaux')->name('bateaux.')->group(function () {
            Route::get('/', [App\Http\Controllers\Superviseur\BateauController::class, 'index'])
                 ->name('index');
            Route::get('/export', [App\Http\Controllers\Superviseur\BateauController::class, 'export'])
                 ->name('export');
            Route::get('/{id}', [App\Http\Controllers\Superviseur\BateauController::class, 'show'])
                 ->name('show');
            Route::get('/{id}/pavillons', [App\Http\Controllers\Superviseur\BateauController::class, 'pavillons'])
                 ->name('pavillons');
            Route::get('/{id}/historique', [App\Http\Controllers\Superviseur\BateauController::class, 'historiqueVoyages'])
                 ->name('historique');
        });

        // ===== STATISTIQUES =====
        Route::prefix('statistiques')->name('statistiques.')->group(function () {
            Route::get('/', [App\Http\Controllers\Superviseur\StatistiqueController::class, 'index'])
                 ->name('index');
            Route::get('/financieres', [App\Http\Controllers\Superviseur\StatistiqueController::class, 'financieres'])
                 ->name('financieres');
            Route::get('/reservations', [App\Http\Controllers\Superviseur\StatistiqueController::class, 'reservations'])
                 ->name('reservations');
            Route::get('/personnel', [App\Http\Controllers\Superviseur\StatistiqueController::class, 'personnel'])
                 ->name('personnel');
            Route::get('/export/{type}', [App\Http\Controllers\Superviseur\StatistiqueController::class, 'export'])
                 ->name('export');
        });

        // ===== RAPPORTS =====
        Route::prefix('rapports')->name('rapports.')->group(function () {
            Route::get('/', [App\Http\Controllers\Superviseur\RapportController::class, 'index'])
                 ->name('index');
            Route::post('/generer', [App\Http\Controllers\Superviseur\RapportController::class, 'generer'])
                 ->name('generer');
        });
    });
    
    // ========== FIN ROUTES SUPERVISEUR ==========
    

    // ========== 🧑‍💼 ROUTES PERSONNEL (PAR RÔLE) ==========   
    // ===== 1. AGENT PORTUAIRE =====
    Route::middleware(['auth', 'role:personnel,agent_portuaire'])
         ->prefix('agent')->name('agent.')->group(function () {
             
        // Dashboard
        Route::get('/dashboard', [App\Http\Controllers\Personnel\AgentController::class, 'dashboard'])
             ->name('dashboard');
        
        // Réservations
        Route::prefix('reservations')->name('reservations.')->group(function () {
            Route::get('/', [App\Http\Controllers\Personnel\AgentController::class, 'reservations'])
                 ->name('index');
            Route::get('/export', [App\Http\Controllers\Personnel\AgentController::class, 'exportReservations'])
                 ->name('export');
            Route::get('/{id}', [App\Http\Controllers\Personnel\AgentController::class, 'showReservation'])
                 ->name('show');
            Route::put('/{id}/confirmer', [App\Http\Controllers\Personnel\AgentController::class, 'confirmerReservation'])
                 ->name('confirmer');
            Route::put('/{id}/annuler', [App\Http\Controllers\Personnel\AgentController::class, 'annulerReservation'])
                 ->name('annuler');
            Route::put('/{id}/marquer-paye', [App\Http\Controllers\Personnel\AgentController::class, 'marquerPayee'])
                 ->name('marquer.paye');
            Route::put('/{id}/marquer-arrivee', [App\Http\Controllers\Personnel\AgentController::class, 'marquerArrivee'])
                 ->name('marquer.arrivee');
        });
    });

    // ===== 2. COMPTABLE =====
    Route::middleware(['auth', 'role:personnel,comptable'])
         ->prefix('comptable')->name('comptable.')->group(function () {
             
        // Dashboard
        Route::get('/dashboard', [App\Http\Controllers\Personnel\ComptableController::class, 'dashboard'])
             ->name('dashboard');
        
        // Paiements
        Route::prefix('paiements')->name('paiements.')->group(function () {
            Route::get('/', [App\Http\Controllers\Personnel\ComptableController::class, 'paiements'])
                 ->name('index');
            Route::get('/export', [App\Http\Controllers\Personnel\ComptableController::class, 'exportPaiements'])
                 ->name('export');
            Route::get('/{id}', [App\Http\Controllers\Personnel\ComptableController::class, 'showPaiement'])
                 ->name('show');
            Route::put('/{id}/valider', [App\Http\Controllers\Personnel\ComptableController::class, 'validerPaiement'])
                 ->name('valider');
            Route::put('/{id}/refuser', [App\Http\Controllers\Personnel\ComptableController::class, 'refuserPaiement'])
                 ->name('refuser');
        });

        // Rapport financier
        Route::get('/rapport-financier', [App\Http\Controllers\Personnel\ComptableController::class, 'rapportFinancier'])
             ->name('rapport.financier');
    });

    // ===== 3. CAISSIER =====
    Route::middleware(['auth', 'role:personnel,caissier'])
         ->prefix('caissier')->name('caissier.')->group(function () {
             
        // Dashboard
        Route::get('/dashboard', [App\Http\Controllers\Personnel\CaissierController::class, 'dashboard'])
             ->name('dashboard');
        
        // Paiements en attente
        Route::get('/paiements-attente', [App\Http\Controllers\Personnel\CaissierController::class, 'paiementsAttente'])
             ->name('paiements.attente');
        
        // Encaisser
        Route::put('/paiements/{id}/encaisser', [App\Http\Controllers\Personnel\CaissierController::class, 'encaisserPaiement'])
             ->name('paiements.encaisser');
        
        // Encaisser multiple
        Route::post('/paiements/encaisser-multiple', [App\Http\Controllers\Personnel\CaissierController::class, 'encaisserMultiple'])
             ->name('paiements.encaisser.multiple');
        
        // Historique
        Route::get('/historique', [App\Http\Controllers\Personnel\CaissierController::class, 'historique'])
             ->name('historique');
        
        // Export
        Route::get('/export', [App\Http\Controllers\Personnel\CaissierController::class, 'exportPaiements'])
             ->name('export');
        
        // Statistiques
        Route::get('/statistiques', [App\Http\Controllers\Personnel\CaissierController::class, 'statistiques'])
             ->name('statistiques');
    });

    // ===== 4. GESTIONNAIRE =====
    Route::middleware(['auth', 'role:personnel,gestionnaire'])
         ->prefix('gestionnaire')->name('gestionnaire.')->group(function () {
             
        // Dashboard
        Route::get('/dashboard', [App\Http\Controllers\Personnel\GestionnaireController::class, 'dashboard'])
             ->name('dashboard');
        
        // Voyages
        Route::prefix('voyages')->name('voyages.')->group(function () {
            Route::get('/', [App\Http\Controllers\Personnel\GestionnaireController::class, 'voyages'])
                 ->name('index');
            Route::get('/create', [App\Http\Controllers\Personnel\GestionnaireController::class, 'createVoyage'])
                 ->name('create');
            Route::post('/', [App\Http\Controllers\Personnel\GestionnaireController::class, 'storeVoyage'])
                 ->name('store');
            Route::get('/export', [App\Http\Controllers\Personnel\GestionnaireController::class, 'exportVoyages'])
                 ->name('export');
            Route::get('/{id}', [App\Http\Controllers\Personnel\GestionnaireController::class, 'showVoyage'])
                 ->name('show');
            Route::get('/{id}/edit', [App\Http\Controllers\Personnel\GestionnaireController::class, 'editVoyage'])
                 ->name('edit');
            Route::put('/{id}', [App\Http\Controllers\Personnel\GestionnaireController::class, 'updateVoyage'])
                 ->name('update');
            Route::delete('/{id}', [App\Http\Controllers\Personnel\GestionnaireController::class, 'deleteVoyage'])
                 ->name('delete');
        });

        // Bateaux
        Route::prefix('bateaux')->name('bateaux.')->group(function () {
            Route::get('/', [App\Http\Controllers\Personnel\GestionnaireController::class, 'bateaux'])
                 ->name('index');
            Route::get('/create', [App\Http\Controllers\Personnel\GestionnaireController::class, 'createBateau'])
                 ->name('create');
            Route::post('/', [App\Http\Controllers\Personnel\GestionnaireController::class, 'storeBateau'])
                 ->name('store');
            Route::get('/{id}', [App\Http\Controllers\Personnel\GestionnaireController::class, 'showBateau'])
                 ->name('show');
            Route::get('/{id}/edit', [App\Http\Controllers\Personnel\GestionnaireController::class, 'editBateau'])
                 ->name('edit');
            Route::put('/{id}', [App\Http\Controllers\Personnel\GestionnaireController::class, 'updateBateau'])
                 ->name('update');
            Route::delete('/{id}', [App\Http\Controllers\Personnel\GestionnaireController::class, 'deleteBateau'])
                 ->name('delete');
        });

        // Ports
        Route::get('/ports', [App\Http\Controllers\Personnel\GestionnaireController::class, 'ports'])
             ->name('ports');

        // Quais
        Route::get('/quais', [App\Http\Controllers\Personnel\GestionnaireController::class, 'quais'])
             ->name('quais');
    });
    // ================================================================
    // ========== FIN ROUTES PERSONNEL ==========
    // ================================================================

});

require base_path('routes/auth.php');