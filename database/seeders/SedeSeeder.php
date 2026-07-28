<?php

namespace Database\Seeders;

use App\Models\Sede;
use Illuminate\Database\Seeder;

class SedeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sedes = [
            ['nombre' => 'Sede Principal - Centro', 'estado' => true],
            ['nombre' => 'Sede Norte - Supermercado', 'estado' => true],
            ['nombre' => 'Sede Sur - Distribuidora', 'estado' => true],
        ];

        foreach ($sedes as $sede) {
            Sede::create($sede);
        }
    }
}
