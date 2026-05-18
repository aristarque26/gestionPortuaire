<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'prenom' => 'Super',
            'email' => 'admin@portuaire.com',
            'telephone' => '0977356358',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'statut' => 'actif',
        ]);
    }
}