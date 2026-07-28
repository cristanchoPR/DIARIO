<?php

namespace App\Livewire\Productos;

use App\Models\Producto;
use App\Models\Sede;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.app')]
#[Title('Productos')]
class ProductoIndex extends Component
{
    use WithPagination;

    public string $search        = '';
    public string $filtroCategoria = '';
    public string $filtroEstado  = '';
    public bool $showModal       = false;
    public bool $editando        = false;
    public bool $showAsignModal  = false;
    public ?int $productoId      = null;
    public ?int $asignProductoId = null;

    // Campos del formulario
    public string $codigo        = '';
    public string $codigo_barras = '';
    public string $nombre        = '';
    public string $categoria     = '';
    public string $marca         = '';
    public string $unidad_medida = 'UNIDAD';
    public string $costo_actual  = '';
    public string $precio_venta  = '';
    public bool   $estado        = true;

    // Asignación de sedes
    public array  $sedesAsignadas = [];   // [sede_id => existencia]

    protected function rules(): array
    {
        $uniqueCodigo = 'required|string|max:50|unique:productos,codigo' . ($this->editando ? ",{$this->productoId}" : '');
        return [
            'codigo'        => $uniqueCodigo,
            'codigo_barras' => 'nullable|string|max:50',
            'nombre'        => 'required|string|max:150',
            'categoria'     => 'nullable|string|max:80',
            'marca'         => 'nullable|string|max:80',
            'unidad_medida' => 'required|string|max:30',
            'costo_actual'  => 'required|numeric|min:0',
            'precio_venta'  => 'required|numeric|min:0',
            'estado'        => 'boolean',
        ];
    }

    public function updatingSearch(): void { $this->resetPage(); }

    public function render()
    {
        $categorias = Producto::distinct()->orderBy('categoria')->pluck('categoria')->filter()->values();

        $productos = Producto::query()
            ->when($this->search, fn($q) => $q->where('nombre', 'like', "%{$this->search}%")
                                              ->orWhere('codigo', 'like', "%{$this->search}%")
                                              ->orWhere('codigo_barras', 'like', "%{$this->search}%"))
            ->when($this->filtroCategoria, fn($q) => $q->where('categoria', $this->filtroCategoria))
            ->when($this->filtroEstado !== '', fn($q) => $q->where('estado', $this->filtroEstado === 'activo'))
            ->withCount('sedes')
            ->latest()
            ->paginate(15);

        return view('livewire.productos.producto-index', compact('productos', 'categorias'));
    }

    public function resetearFormulario(): void
    {
        $this->resetForm();
    }

    public function editar(int $id): void
    {
        $p = Producto::findOrFail($id);
        $this->productoId    = $p->id;
        $this->codigo        = $p->codigo;
        $this->codigo_barras = $p->codigo_barras ?? '';
        $this->nombre        = $p->nombre;
        $this->categoria     = $p->categoria ?? '';
        $this->marca         = $p->marca ?? '';
        $this->unidad_medida = $p->unidad_medida;
        $this->costo_actual  = $p->costo_actual;
        $this->precio_venta  = $p->precio_venta;
        $this->estado        = $p->estado;
        $this->editando      = true;
        $this->showModal     = true;
    }

    public function guardar(): void
    {
        $this->validate();

        $data = [
            'codigo'        => strtoupper($this->codigo),
            'codigo_barras' => $this->codigo_barras ?: null,
            'nombre'        => $this->nombre,
            'categoria'     => $this->categoria ?: null,
            'marca'         => $this->marca ?: null,
            'unidad_medida' => $this->unidad_medida,
            'costo_actual'  => $this->costo_actual,
            'precio_venta'  => $this->precio_venta,
            'estado'        => $this->estado,
        ];

        if ($this->editando && $this->productoId) {
            Producto::findOrFail($this->productoId)->update($data);
            $this->dispatch('alert', ['type' => 'success', 'message' => 'Producto actualizado correctamente.']);
        } else {
            Producto::create($data);
            $this->dispatch('alert', ['type' => 'success', 'message' => 'Producto creado correctamente.']);
        }

        $this->dispatch('close-modal');
        $this->resetForm();
    }

    public function toggleEstado(int $id): void
    {
        $p = Producto::findOrFail($id);
        $p->update(['estado' => !$p->estado]);
        $this->dispatch('alert', ['type' => 'success', 'message' => 'Estado del producto actualizado.']);
    }

    public function eliminar(int $id): void
    {
        $p = Producto::findOrFail($id);
        if ($p->sedes()->count() > 0) {
            $this->dispatch('alert', ['type' => 'error', 'message' => 'No puedes eliminar un producto asignado a sedes. Desasígnalo primero.']);
            return;
        }
        $p->delete();
        $this->dispatch('alert', ['type' => 'success', 'message' => 'Producto eliminado.']);
    }

    /** Abre modal de asignación de sedes */
    public function abrirAsignacion(int $id): void
    {
        $this->asignProductoId = $id;
        $producto = Producto::with('sedes')->findOrFail($id);
        $userAuth = auth()->user();

        // Inicializar array: sede_id => existencia actual
        $this->sedesAsignadas = [];
        foreach ($producto->sedes as $sede) {
            if ($userAuth->esAdministrador() || $userAuth->tieneAccesoSede($sede->id)) {
                $this->sedesAsignadas[$sede->id] = [
                    'asignado'   => true,
                    'existencia' => $sede->pivot->existencia_sistema ?? 0,
                ];
            }
        }

        $this->showAsignModal = true;
    }

    public function guardarAsignacion(): void
    {
        $sync = [];
        foreach ($this->sedesAsignadas as $sedeId => $datos) {
            if (!empty($datos['asignado'])) {
                $sync[$sedeId] = ['existencia_sistema' => $datos['existencia'] ?? 0];
            }
        }

        $producto = Producto::findOrFail($this->asignProductoId);
        $userAuth = auth()->user();

        if ($userAuth->esAdministrador()) {
            $producto->sedes()->sync($sync);
        } else {
            // Admin de sede: preservar asignaciones de otras sedes
            $sedesActuales = $producto->sedes->pluck('pivot.existencia_sistema', 'id')->toArray();
            $misSedes = $userAuth->sedes()->pluck('sedes.id')->toArray();
            
            $finalSync = [];
            foreach ($sedesActuales as $sId => $exist) {
                if (!in_array($sId, $misSedes)) {
                    $finalSync[$sId] = ['existencia_sistema' => $exist];
                }
            }
            foreach ($sync as $sId => $pivotData) {
                if (in_array($sId, $misSedes)) {
                    $finalSync[$sId] = $pivotData;
                }
            }
            $producto->sedes()->sync($finalSync);
        }

        $this->dispatch('close-asign-modal');
        $this->asignProductoId = null;
        $this->sedesAsignadas  = [];
        $this->dispatch('alert', ['type' => 'success', 'message' => 'Asignación de sedes actualizada.']);
    }

    private function resetForm(): void
    {
        $this->productoId    = null;
        $this->codigo        = '';
        $this->codigo_barras = '';
        $this->nombre        = '';
        $this->categoria     = '';
        $this->marca         = '';
        $this->unidad_medida = 'UNIDAD';
        $this->costo_actual  = '';
        $this->precio_venta  = '';
        $this->estado        = true;
        $this->resetValidation();
    }
}
