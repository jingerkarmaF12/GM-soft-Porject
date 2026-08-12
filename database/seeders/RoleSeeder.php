<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->insert([
            [
                'id_role' => 1,
                'nom_role' => 'Admin',
                'description' => 'Administrateur',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_role' => 2,
                'nom_role' => 'Responsable',
                'description' => 'Responsable de maintenance',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_role' => 3,
                'nom_role' => 'Technicien',
                'description' => 'Technicien de maintenance',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_role' => 4,
                'nom_role' => 'Demandeur',
                'description' => 'Utilisateur standard',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}