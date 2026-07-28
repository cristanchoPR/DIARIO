<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminOnlySeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name'     => 'Administrador',
            'email'    => 'admin@aldia.com',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('administrador');
        $this->command->info('Admin creado: admin@aldia.com / password');
    }
}
