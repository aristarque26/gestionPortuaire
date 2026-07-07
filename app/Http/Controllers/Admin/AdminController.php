<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Afficher la liste des administrateurs
     */
    public function index()
    {
        $admins = User::where('role', 'admin')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.admins.index', compact('admins'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        return view('admin.admins.create');
    }

    /**
     * Enregistrer un nouvel administrateur
     */
    public function store(Request $request)
    {
        $request->validate([
            'prenom' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'telephone' => 'required|string|max:20',
            'password' => 'required|min:8|confirmed',
            'statut' => 'required|in:actif,inactif',
        ]);

        User::create([
            'prenom' => $request->prenom,
            'name' => $request->name,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'password' => Hash::make($request->password),
            'role' => 'admin',
            'statut' => $request->statut,
        ]);

        return redirect()->route('admin.admins.index')
            ->with('success', 'Administrateur créé avec succès.');
    }

    /**
     * Afficher les détails d'un administrateur
     */
    public function show(string $id)
    {
        $admin = User::where('role', 'admin')->findOrFail($id);
        return view('admin.admins.show', compact('admin'));
    }

    /**
     * Afficher le formulaire de modification
     */
    public function edit(string $id)
    {
        $admin = User::where('role', 'admin')->findOrFail($id);
        return view('admin.admins.edit', compact('admin'));
    }

    /**
     * Mettre à jour un administrateur
     */
    public function update(Request $request, string $id)
    {
        $admin = User::where('role', 'admin')->findOrFail($id);

        $request->validate([
            'prenom' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'telephone' => 'required|string|max:20',
            'password' => 'nullable|min:8|confirmed',
            'statut' => 'required|in:actif,inactif',
        ]);

        $data = [
            'prenom' => $request->prenom,
            'name' => $request->name,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'statut' => $request->statut,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $admin->update($data);

        return redirect()->route('admin.admins.index')
            ->with('success', 'Administrateur modifié avec succès.');
    }

    /**
     * Supprimer un administrateur
     */
    public function destroy(string $id)
    {
        $admin = User::where('role', 'admin')->findOrFail($id);
        
        // Empêcher la suppression de son propre compte
        if ($admin->idutilisateur == auth()->id()) {
            return redirect()->route('admin.admins.index')
                ->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $admin->delete();

        return redirect()->route('admin.admins.index')
            ->with('success', 'Administrateur supprimé avec succès.');
    }
}