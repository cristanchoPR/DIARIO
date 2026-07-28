<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inventario extends Model
{
    use HasFactory;

    protected $table = 'inventarios';

    protected $fillable = [
        'nombre',
        'sede_id',
        'usuario_id',
        'estado',
        'fecha_creacion',
        'fecha_aplicacion',
    ];

    protected $casts = [
        'fecha_creacion' => 'datetime',
        'fecha_aplicacion' => 'datetime',
    ];

    public function sede(): BelongsTo
    {
        return $this->belongsTo(Sede::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(InventarioDetalle::class);
    }

    public function auditorias(): HasMany
    {
        return $this->hasMany(InventarioAuditoria::class);
    }

    public function ajustes(): HasMany
    {
        return $this->hasMany(MovimientoAjuste::class);
    }

    // Helper check
    public function isReadOnly(): bool
    {
        return $this->estado === 'aplicado';
    }
}
