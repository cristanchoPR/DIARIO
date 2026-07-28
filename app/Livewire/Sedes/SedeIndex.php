<?php

namespace App\Livewire\Sedes;

use App\Models\Sede;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Sedes')]
class SedeIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filtroEstado = '';
    public bool $showModal = false;
    public bool $editando = false;
    public ?int $sedeId = null;

    // Form fields
    public string $nombre = '';
    public string $descripcion = '';
    public string $nit = '';
    public string $direccion = '';
    public string $telefono = '';
    public string $email = '';
    public string $color = '#5A8FDB';
    public bool $estado = true;

    protected array $rules = [
        'nombre'      => 'required|string|max:100',
        'descripcion' => 'nullable|string|max:255',
        'nit'         => 'nullable|string|max:20',
        'direccion'   => 'nullable|string|max:150',
        'telefono'    => 'nullable|string|max:20',
        'email'       => 'nullable|email|max:100',
        'color'       => 'nullable|string|max:10',
        'estado'      => 'boolean',
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $sedes = Sede::query()
            ->when($this->search, fn($q) => $q->where('nombre', 'like', "%{$this->search}%")
                                              ->orWhere('nit', 'like', "%{$this->search}%"))
            ->when($this->filtroEstado !== '', fn($q) => $q->where('estado', $this->filtroEstado === 'activa'))
            ->withCount(['usuarios', 'inventarios'])
            ->latest()
            ->paginate(12);

        return view('livewire.sedes.sede-index', compact('sedes'));
    }

    public function abrirModal(): void
    {
        $this->resetForm();
        $this->editando = false;
        $this->showModal = true;
    }

    public function editar(int $id): void
    {
        $sede = Sede::findOrFail($id);
        $this->sedeId     = $sede->id;
        $this->nombre     = $sede->nombre;
        $this->descripcion= $sede->descripcion ?? '';
        $this->nit        = $sede->nit ?? '';
        $this->direccion  = $sede->direccion ?? '';
        $this->telefono   = $sede->telefono ?? '';
        $this->email      = $sede->email ?? '';
        $this->color      = $sede->color ?? '#5A8FDB';
        $this->estado     = $sede->estado;
        $this->editando   = true;
        $this->showModal  = true;
    }

    public function resetearFormulario(): void
    {
        $this->resetForm();
    }

    public function guardar(): void
    {
        $this->validate();

        $data = [
            'nombre'      => $this->nombre,
            'descripcion' => $this->descripcion,
            'nit'         => $this->nit,
            'direccion'   => $this->direccion,
            'telefono'    => $this->telefono,
            'email'       => $this->email,
            'color'       => $this->color,
            'estado'      => $this->estado,
        ];

        if ($this->editando && $this->sedeId) {
            Sede::findOrFail($this->sedeId)->update($data);
            $this->dispatch('alert', ['type' => 'success', 'message' => 'Sede actualizada correctamente.']);
        } else {
            Sede::create($data);
            $this->dispatch('alert', ['type' => 'success', 'message' => 'Sede creada correctamente.']);
        }

        $this->dispatch('close-modal');
        $this->resetForm();
    }

    public function toggleEstado(int $id): void
    {
        $sede = Sede::findOrFail($id);
        $sede->update(['estado' => !$sede->estado]);
        $this->dispatch('alert', ['type' => 'success', 'message' => 'Estado de la sede actualizado.']);
    }

    public function eliminar(int $id): void
    {
        $sede = Sede::findOrFail($id);
        if ($sede->inventarios()->count() > 0) {
            $this->dispatch('alert', ['type' => 'error', 'message' => 'No puedes eliminar una sede con inventarios registrados.']);
            return;
        }
        $sede->delete();
        $this->dispatch('alert', ['type' => 'success', 'message' => 'Sede eliminada correctamente.']);
    }

    private function resetForm(): void
    {
        $this->sedeId     = null;
        $this->nombre     = '';
        $this->descripcion= '';
        $this->nit        = '';
        $this->direccion  = '';
        $this->telefono   = '';
        $this->email      = '';
        $this->color      = '#5A8FDB';
        $this->estado     = true;
        $this->resetValidation();
    }
}
