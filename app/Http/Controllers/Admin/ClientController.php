<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::with('utilisateur')->get();
        return view('admin.clients.index', compact('clients'));
    }

    public function show($id)
    {
        $client = Client::with('utilisateur', 'reservations')->findOrFail($id);
        return view('admin.clients.show', compact('client'));
    }

    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        $client->delete();

        return redirect()->route('admin.clients.index')
            ->with('success', 'Client supprimé avec succès.');
    }
}