<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos';

    protected $fillable = [
        'codigo',
        'codigo_barras',
        'nombre',
        'categoria',
        'marca',
        'unidad_medida',
        'costo_actual',
        'precio_venta',
        'estado',
    ];

    protected $casts = [
        'costo_actual' => 'decimal:2',
        'precio_venta' => 'decimal:2',
        'estado' => 'boolean',
    ];

    public function sedes(): BelongsToMany
    {
        return $this->belongsToMany(Sede::class, 'sede_productos')
                    ->withPivot('existencia_sistema')
                    ->withTimestamps();
    }

    public function inventarioDetalles(): HasMany
    {
        return $this->hasMany(InventarioDetalle::class);
    }
}
