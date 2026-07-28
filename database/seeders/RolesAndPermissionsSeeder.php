<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Sedes
            'ver sedes',
            'crear sedes',
            'editar sedes',
            'eliminar sedes',
            // Usuarios
            'ver usuarios',
            'crear usuarios',
            'editar usuarios',
            'eliminar usuarios',
            'asignar sedes usuarios',
            // Productos
            'ver productos',
            'crear productos',
            'editar productos',
            'eliminar productos',
            // Inventarios
            'ver inventarios',
            'crear inventarios',
            'editar inventarios',
            'finalizar inventarios',
            'aplicar inventarios',
            // Reportes
            'ver auditoria',
            'exportar inventarios',
            'exportar reportes',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        // ROL: administrador — acceso total
        $roleAdmin = Role::findOrCreate('administrador');
        $roleAdmin->givePermissionTo(Permission::all());

        // ROL: usuario — solo inventarios y reportes de sus sedes
        $roleUsuario = Role::findOrCreate('usuario');
        $roleUsuario->givePermissionTo([
            'ver sedes',
            'ver productos',
            'ver inventarios',
            'crear inventarios',
            'editar inventarios',
            'finalizar inventarios',
            'exportar inventarios',
            'exportar reportes',
        ]);

        // Eliminar rol antiguo si existe
        Role::where('name', 'operario_bodega')->delete();
    }
}
