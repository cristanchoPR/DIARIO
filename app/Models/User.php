<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /** Sedes asignadas a este usuario */
    public function sedes(): BelongsToMany
    {
        return $this->belongsToMany(Sede::class, 'sede_user');
    }

    /** Helpers de rol */
    public function esAdministrador(): bool
    {
        return $this->hasRole('administrador');
    }

    public function esUsuario(): bool
    {
        return $this->hasRole('usuario');
    }

    public function esAdminSede(): bool
    {
        return $this->hasRole('admin_sede');
    }

    /** Devuelve las sedes que este usuario puede ver */
    public function sedesPermitidas()
    {
        if ($this->esAdministrador()) {
            return Sede::query();
        }
        return $this->sedes();
    }

    /** Verifica si tiene acceso a una sede específica */
    public function tieneAccesoSede(int $sedeId): bool
    {
        if ($this->esAdministrador()) {
            return true;
        }
        return $this->sedes()->where('sedes.id', $sedeId)->exists();
    }
}
