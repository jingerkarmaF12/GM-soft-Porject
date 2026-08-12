<?php

namespace Database\Seeders;

use App\Models\Utilisateur;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {   
        $this->call([
        RoleSeeder::class,
         ]);
        Utilisateur::create([
            'nom' => 'Admin',
            'prenom' => 'System',
            'email' => 'Admin@GMAO.com',
            'mot_de_passe' => Hash::make('Admin@GMAO@069'),
            'telephone' => null,
            'photo_profil' => null,
            'statut' => 'actif',
            'id_role' => 1,
            'id_specialite' => null,
        ]);
        
    }
}