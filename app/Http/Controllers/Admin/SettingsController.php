<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SettingsController extends Controller
{
    // Page d'accueil des paramètres
    public function index()
    {
        return view('admin.settings.index');
    }

    // Sous-pages (GET)
    public function general()
    {
        return view('admin.settings.general');
    }

    public function notifications()
    {
        return view('admin.settings.notifications');
    }

    public function securite()
    {
        return view('admin.settings.securite');
    }

    public function facturation()
    {
        return view('admin.settings.facturation');
    }

    public function apparence()
    {
        return view('admin.settings.apparence');
    }

    public function avance()
    {
        return view('admin.settings.avance');
    }

    // Méthodes POST (enregistrement)
    public function updateGeneral(Request $request)
    {
        return redirect()->route('admin.settings.general')->with('success', 'Paramètres généraux mis à jour.');
    }

    public function updateNotifications(Request $request)
    {
        return redirect()->route('admin.settings.notifications')->with('success', 'Notifications mises à jour.');
    }

    public function updateSecurite(Request $request)
    {
        return redirect()->route('admin.settings.securite')->with('success', 'Paramètres de sécurité mis à jour.');
    }

    public function updateFacturation(Request $request)
    {
        return redirect()->route('admin.settings.facturation')->with('success', 'Paramètres de facturation mis à jour.');
    }

    // ⭐ Mise à jour du thème (Apparence)
    public function updateApparence(Request $request)
    {
        $this->updateEnv('APP_THEME', $request->theme);
        Artisan::call('config:clear');
        return redirect()->route('admin.settings.apparence')->with('success', 'Thème mis à jour.');
    }

    public function updateAvance(Request $request)
    {
        return redirect()->route('admin.settings.avance')->with('success', 'Paramètres avancés mis à jour.');
    }

    // Fonction pour modifier le fichier .env
    private function updateEnv($key, $value)
    {
        $path = base_path('.env');
        if (file_exists($path)) {
            file_put_contents($path, preg_replace(
                '/^' . $key . '=.*/m',
                $key . '=' . $value,
                file_get_contents($path)
            ));
        }
    }
}