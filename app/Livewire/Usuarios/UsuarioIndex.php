<?php

namespace App\Livewire\Usuarios;

use App\Models\User;
use App\Models\Sede;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

#[Layout('layouts.app')]
#[Title('Usuarios')]
class UsuarioIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filtroRol = '';
    public bool $showModal = false;
    public bool $editando = false;
    public ?int $userId = null;

    // Form
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $rol = 'usuario';
    public array $sedesSeleccionadas = [];

    protected function rules(): array
    {
        return [
            'name'              => 'required|string|max:100',
            'email'             => 'required|email|unique:users,email' . ($this->editando ? ",{$this->userId}" : ''),
            'password'          => $this->editando ? 'nullable|min:6' : 'required|min:6',
            'rol'               => 'required|in:administrador,admin_sede,usuario',
            'sedesSeleccionadas'=> 'array',
        ];
    }

    public function updatingSearch(): void { $this->resetPage(); }

    public function render()
    {
        $userAuth = auth()->user();

        $usuarios = User::query()
            ->when($this->search, fn($q) => $q->where(function($subq) {
                $subq->where('name', 'like', "%{$this->search}%")
                     ->orWhere('email', 'like', "%{$this->search}%");
            }))
            ->when($this->filtroRol, fn($q) => $q->role($this->filtroRol))
            ->when($userAuth->esAdminSede(), function ($q) use ($userAuth) {
                // Admin de Sede solo puede ver operarios de sus sedes
                $sedesId = $userAuth->sedes()->pluck('sedes.id')->toArray();
                $q->role('usuario')->whereHas('sedes', function ($q2) use ($sedesId) {
                    $q2->whereIn('sedes.id', $sedesId);
                });
            })
            ->with(['roles', 'sedes'])
            ->latest()
            ->paginate(10);

        if ($userAuth->esAdministrador()) {
            $sedes  = Sede::activas()->orderBy('nombre')->get();
            $roles  = Role::whereIn('name', ['administrador', 'admin_sede', 'usuario'])->get();
        } else {
            $sedes  = $userAuth->sedes()->orderBy('nombre')->get(); // admin_sede
            $roles  = Role::whereIn('name', ['usuario'])->get();
        }

        return view('livewire.usuarios.usuario-index', compact('usuarios', 'sedes', 'roles'));
    }

    public function resetearFormulario(): void
    {
        $this->resetForm();
    }

    public function editar(int $id): void
    {
        $user = User::with(['roles', 'sedes'])->findOrFail($id);
        
        // Un admin_sede no puede editar a un administrador u otro admin_sede
        if (auth()->user()->esAdminSede() && !$user->esUsuario()) {
            $this->dispatch('alert', ['type' => 'error', 'message' => 'No tienes permisos para editar este usuario.']);
            return;
        }

        $this->userId             = $user->id;
        $this->name               = $user->name;
        $this->email              = $user->email;
        $this->password           = '';
        $this->rol                = $user->getRoleNames()->first() ?? 'usuario';
        $this->sedesSeleccionadas = $user->sedes->pluck('id')->toArray();
        $this->editando           = true;
        $this->showModal          = true;
    }

    public function guardar(): void
    {
        $this->validate();
        
        $userAuth = auth()->user();

        // Forzar rol y sedes si es admin_sede
        if ($userAuth->esAdminSede()) {
            $this->rol = 'usuario';
            $sedesPermitidas = $userAuth->sedes()->pluck('sedes.id')->toArray();
            $this->sedesSeleccionadas = array_intersect($this->sedesSeleccionadas, $sedesPermitidas);
        }

        $data = [
            'name'  => $this->name,
            'email' => $this->email,
        ];
        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        if ($this->editando && $this->userId) {
            $user = User::findOrFail($this->userId);
            
            // Check if admin_sede is trying to edit an unauthorized user
            if ($userAuth->esAdminSede() && !$user->esUsuario()) {
                $this->dispatch('alert', ['type' => 'error', 'message' => 'Acción no permitida.']);
                return;
            }
            
            $user->update($data);
        } else {
            $user = User::create($data);
        }

        $user->syncRoles([$this->rol]);

        // Sync sedes (solo para admin_sede y usuario)
        if (in_array($this->rol, ['usuario', 'admin_sede'])) {
            $user->sedes()->sync($this->sedesSeleccionadas);
        } else {
            $user->sedes()->detach(); // Administrador accede a todo, no necesita asignación
        }

        $this->dispatch('alert', ['type' => 'success', 'message' => $this->editando ? 'Usuario actualizado.' : 'Usuario creado correctamente.']);
        $this->dispatch('close-modal');
        $this->resetForm();
    }

    public function eliminar(int $id): void
    {
        if ($id === auth()->id()) {
            $this->dispatch('alert', ['type' => 'error', 'message' => 'No puedes eliminarte a ti mismo.']);
            return;
        }
        User::findOrFail($id)->delete();
        $this->dispatch('alert', ['type' => 'success', 'message' => 'Usuario eliminado.']);
    }

    private function resetForm(): void
    {
        $this->userId             = null;
        $this->name               = '';
        $this->email              = '';
        $this->password           = '';
        $this->rol                = 'usuario';
        $this->sedesSeleccionadas = [];
        $this->resetValidation();
    }
}
