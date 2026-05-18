<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class ProfilController extends Controller
{
    public function show()
    {
        $client = Auth::user()->client;
        return view('client.profil.show', compact('client'));
    }

    public function edit()
    {
        $client = Auth::user()->client;
        return view('client.profil.edit', compact('client'));
    }

    public function update(Request $request)
    {
        $client = Auth::user()->client;
        $user = Auth::user();
        
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'telephone' => 'required|string|max:20',
            'adresse' => 'nullable|string',
            'nationalite' => 'nullable|string|max:100',
            'genre' => 'nullable|in:Homme,Femme,Autre',
        ]);
        
        $client->update([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'adresse' => $request->adresse,
            'nationalite' => $request->nationalite,
            'genre' => $request->genre,
        ]);
        
        $user->update([
            'name' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'telephone' => $request->telephone,
        ]);

        return redirect()->route('client.profil.show')
            ->with('success', 'Profil mis à jour avec succès.');
    }
}