<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sede extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'nit',
        'direccion',
        'telefono',
        'email',
        'logo',
        'color',
        'estado',
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];

    /** Usuarios asignados a esta sede */
    public function usuarios(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'sede_user');
    }

    /** Productos con stock asignados a esta sede */
    public function productos(): BelongsToMany
    {
        return $this->belongsToMany(Producto::class, 'sede_productos')
                    ->withPivot(['cantidad', 'costo_unitario'])
                    ->withTimestamps();
    }

    /** Inventarios de esta sede */
    public function inventarios(): HasMany
    {
        return $this->hasMany(Inventario::class);
    }

    /** Scopes */
    public function scopeActivas($query)
    {
        return $query->where('estado', true);
    }

    /** Helpers */
    public function getInicialAttribute(): string
    {
        return strtoupper(substr($this->nombre, 0, 2));
    }

    public function getEstadoTextoAttribute(): string
    {
        return $this->estado ? 'Activa' : 'Inactiva';
    }

    public function getTotalProductosAttribute(): int
    {
        return $this->productos()->count();
    }
}
