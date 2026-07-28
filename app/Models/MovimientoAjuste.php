<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MovimientoAjuste extends Model
{
    use HasFactory;

    protected $table = 'movimientos_ajustes';

    protected $fillable = [
        'inventario_id',
        'producto_id',
        'tipo',
        'cantidad',
        'usuario_id',
        'fecha_hora',
        'documento_origen',
    ];

    protected $casts = [
        'cantidad' => 'decimal:2',
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
