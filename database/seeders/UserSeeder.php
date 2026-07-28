<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Sede;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Administrador
        $admin = User::firstOrCreate(
            ['email' => 'admin@aldia.com'],
            [
                'name'     => 'Admin Aldia',
                'password' => Hash::make('password'),
            ]
        );
        $admin->syncRoles(['administrador']);

        // Usuario de prueba — solo algunas sedes
        $usuario = User::firstOrCreate(
            ['email' => 'usuario@aldia.com'],
            [
                'name'     => 'Juan Bodeguero',
                'password' => Hash::make('password'),
            ]
        );
        $usuario->syncRoles(['usuario']);

        // Asignar sedes al usuario de prueba (las 2 primeras)
        $sedes = Sede::take(2)->pluck('id');
        $usuario->sedes()->sync($sedes);
    }
}
