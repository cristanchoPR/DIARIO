<?php

namespace Database\Seeders;

use App\Models\Producto;
use App\Models\Sede;
use App\Models\SedeProducto;
use Illuminate\Database\Seeder;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Skip if products already exist
        if (Producto::count() > 0) {
            $this->command->info('ProductoSeeder: productos ya existen, saltando...');
            return;
        }

        $productos = [
            [
                'codigo' => 'PROD-001',
                'codigo_barras' => '7702001041411',
                'nombre' => 'Arroz Diana Premium 1kg',
                'categoria' => 'Abarrotes',
                'marca' => 'Diana',
                'unidad_medida' => 'KILOGRAMO',
                'costo_actual' => 3200.00,
                'precio_venta' => 4200.00,
                'estado' => true,
            ],
            [
                'codigo' => 'PROD-002',
                'codigo_barras' => '7702001041428',
                'nombre' => 'Aceite Vegetal Premier 1000ml',
                'categoria' => 'Abarrotes',
                'marca' => 'Premier',
                'unidad_medida' => 'LITRO',
                'costo_actual' => 8500.00,
                'precio_venta' => 11000.00,
                'estado' => true,
            ],
            [
                'codigo' => 'PROD-003',
                'codigo_barras' => '7702001041435',
                'nombre' => 'Leche Alquería Entera 900ml',
                'categoria' => 'Lácteos y Huevos',
                'marca' => 'Alquería',
                'unidad_medida' => 'UNIDAD',
                'costo_actual' => 3100.00,
                'precio_venta' => 3900.00,
                'estado' => true,
            ],
            [
                'codigo' => 'PROD-004',
                'codigo_barras' => '7702001041442',
                'nombre' => 'Café Sello Rojo 500g',
                'categoria' => 'Abarrotes',
                'marca' => 'Sello Rojo',
                'unidad_medida' => 'UNIDAD',
                'costo_actual' => 9800.00,
                'precio_venta' => 13500.00,
                'estado' => true,
            ],
            [
                'codigo' => 'PROD-005',
                'codigo_barras' => '7702001041459',
                'nombre' => 'Chocolate Sol 500g',
                'categoria' => 'Abarrotes',
                'marca' => 'Sol',
                'unidad_medida' => 'UNIDAD',
                'costo_actual' => 5200.00,
                'precio_venta' => 6800.00,
                'estado' => true,
            ],
            [
                'codigo' => 'PROD-006',
                'codigo_barras' => '7702001041466',
                'nombre' => 'Detergente Ariel Power Liquid 1.2L',
                'categoria' => 'Aseo del Hogar',
                'marca' => 'Ariel',
                'unidad_medida' => 'UNIDAD',
                'costo_actual' => 16500.00,
                'precio_venta' => 22000.00,
                'estado' => true,
            ],
            [
                'codigo' => 'PROD-007',
                'codigo_barras' => '7702001041473',
                'nombre' => 'Jabón Líquido Palmolive 221ml',
                'categoria' => 'Cuidado Personal',
                'marca' => 'Palmolive',
                'unidad_medida' => 'UNIDAD',
                'costo_actual' => 4500.00,
                'precio_venta' => 6500.00,
                'estado' => true,
            ],
            [
                'codigo' => 'PROD-008',
                'codigo_barras' => '7702001041480',
                'nombre' => 'Crema Dental Colgate Total 12 75ml',
                'categoria' => 'Cuidado Personal',
                'marca' => 'Colgate',
                'unidad_medida' => 'UNIDAD',
                'costo_actual' => 6200.00,
                'precio_venta' => 8900.00,
                'estado' => true,
            ],
            [
                'codigo' => 'PROD-009',
                'codigo_barras' => '7702001041497',
                'nombre' => 'Papel Higiénico Familia Mega Rollo x 4',
                'categoria' => 'Aseo del Hogar',
                'marca' => 'Familia',
                'unidad_medida' => 'UNIDAD',
                'costo_actual' => 7800.00,
                'precio_venta' => 10500.00,
                'estado' => true,
            ],
            [
                'codigo' => 'PROD-010',
                'codigo_barras' => '7702001041503',
                'nombre' => 'Gaseosa Coca-Cola 1.5L',
                'categoria' => 'Bebidas',
                'marca' => 'Coca-Cola',
                'unidad_medida' => 'UNIDAD',
                'costo_actual' => 3800.00,
                'precio_venta' => 4800.00,
                'estado' => true,
            ],
            [
                'codigo' => 'PROD-011',
                'codigo_barras' => '7702001041510',
                'nombre' => 'Jugo Hit Naranja Lulo 1L',
                'categoria' => 'Bebidas',
                'marca' => 'Hit',
                'unidad_medida' => 'UNIDAD',
                'costo_actual' => 2200.00,
                'precio_venta' => 3100.00,
                'estado' => true,
            ],
            [
                'codigo' => 'PROD-012',
                'codigo_barras' => '7702001041527',
                'nombre' => 'Atún Van Camps en Aceite 160g',
                'categoria' => 'Enlatados',
                'marca' => 'Van Camps',
                'unidad_medida' => 'UNIDAD',
                'costo_actual' => 5100.00,
                'precio_venta' => 7200.00,
                'estado' => true,
            ],
            [
                'codigo' => 'PROD-013',
                'codigo_barras' => '7702001041534',
                'nombre' => 'Pasta Doria Espagueti 500g',
                'categoria' => 'Abarrotes',
                'marca' => 'Doria',
                'unidad_medida' => 'UNIDAD',
                'costo_actual' => 1800.00,
                'precio_venta' => 2500.00,
                'estado' => true,
            ],
            [
                'codigo' => 'PROD-014',
                'codigo_barras' => '7702001041541',
                'nombre' => 'Sal Refisal Alta Pureza 1kg',
                'categoria' => 'Abarrotes',
                'marca' => 'Refisal',
                'unidad_medida' => 'UNIDAD',
                'costo_actual' => 1100.00,
                'precio_venta' => 1600.00,
                'estado' => true,
            ],
            [
                'codigo' => 'PROD-015',
                'codigo_barras' => '7702001041558',
                'nombre' => 'Azúcar Manuelita Alta Pureza 1kg',
                'categoria' => 'Abarrotes',
                'marca' => 'Manuelita',
                'unidad_medida' => 'UNIDAD',
                'costo_actual' => 2900.00,
                'precio_venta' => 3800.00,
                'estado' => true,
            ],
        ];

        $sedes = Sede::all();

        foreach ($productos as $prodData) {
            $producto = Producto::create($prodData);
            
            // Create stock levels for each Sede
            foreach ($sedes as $sede) {
                // Generate a random stock level
                $existencia = rand(10, 150) + (rand(0, 4) / 4); // E.g., decimal values ending in .00, .25, .50, .75
                SedeProducto::create([
                    'sede_id' => $sede->id,
                    'producto_id' => $producto->id,
                    'existencia_sistema' => $existencia,
                ]);
            }
        }
    }
}
