<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventarioAuditoria extends Model
{
    use HasFactory;

    protected $table = 'inventario_auditorias';

    protected $fillable = [
        'inventario_id',
        'producto_id',
        'usuario_id',
        'cantidad_anterior',
        'cantidad_nueva',
        'costo_anterior',
        'costo_nuevo',
        'fecha_hora',
    ];

    protected $casts = [
        'cantidad_anterior' => 'decimal:2',
        'cantidad_nueva' => 'decimal:2',
        'costo_anterior' => 'decimal:2',
        'costo_nuevo' => 'decimal:2',
        'fecha_hora' => 'datetime',
    ];

    public function inventario(): BelongsTo
    {
        return $this->belongsTo(Inventario::class);
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
