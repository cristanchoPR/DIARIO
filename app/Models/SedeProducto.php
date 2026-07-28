<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SedeProducto extends Model
{
    use HasFactory;

    protected $table = 'sede_productos';

    protected $fillable = [
        'sede_id',
        'producto_id',
        'existencia_sistema',
    ];

    protected $casts = [
        'existencia_sistema' => 'decimal:2',
    ];

    public function sede(): BelongsTo
    {
        return $this->belongsTo(Sede::class);
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }
}
