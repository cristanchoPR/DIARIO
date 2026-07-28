<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventarioDetalle extends Model
{
    use HasFactory;

    protected $table = 'inventario_detalles';

    protected $fillable = [
        'inventario_id',
        'producto_id',
        'existencia_sistema',
        'costo_sistema',
        'cantidad_fisica',
        'costo_contado',
        'valor_total',
    ];

    protected $casts = [
        'existencia_sistema' => 'decimal:2',
        'costo_sistema' => 'decimal:2',
        'cantidad_fisica' => 'decimal:2',
        'costo_contado' => 'decimal:2',
        'valor_total' => 'decimal:2',
    ];

    public function inventario(): BelongsTo
    {
        return $this->belongsTo(Inventario::class);
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    // Accessors for differences
    public function getDiferenciaUnidadesAttribute(): ?float
    {
        if ($this->cantidad_fisica === null) {
            return null;
        }
        return (float) $this->cantidad_fisica - (float) $this->existencia_sistema;
    }

    public function getDiferenciaDineroAttribute(): ?float
    {
        if ($this->cantidad_fisica === null) {
            return null;
        }
        // Let's use costo_contado or costo_sistema. Costo contado is preferred, otherwise costo_sistema
        $costo = $this->costo_contado ?? $this->costo_sistema;
        $valor_fisico = $this->cantidad_fisica * $costo;
        $valor_sistema = $this->existencia_sistema * $this->costo_sistema;
        return (float) $valor_fisico - (float) $valor_sistema;
    }
}
