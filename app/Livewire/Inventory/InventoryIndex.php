<?php

namespace App\Livewire\Inventory;

use App\Models\Inventario;
use App\Models\InventarioDetalle;
use App\Models\Sede;
use App\Models\Producto;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class InventoryIndex extends Component
{
    use WithPagination;

    // Filters
    public $search = '';
    public $sedeFilter = '';
    public $estadoFilter = '';
    public $dateFrom = '';
    public $dateTo = '';

    // Creation modal state
    public $showCreateModal = false;
    public $newNombre = '';
    public $newSedeId = '';
    public $isBlindCount = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'sedeFilter' => ['except' => ''],
        'estadoFilter' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->newNombre = 'Inventario Físico - ' . date('d/m/Y');
        $user = auth()->user();
        $primeraSedePermitida = $user->esAdministrador()
            ? Sede::where('estado', true)->first()
            : $user->sedes()->where('estado', true)->first();
        $this->newSedeId = $primeraSedePermitida?->id ?? '';
        $this->isBlindCount = false;
        $this->showCreateModal = true;
    }

    public function closeCreateModal()
    {
        $this->dispatch('close-modal');
    }

    public function createInventory()
    {
        $this->validate([
            'newNombre' => 'required|string|max:255',
            'newSedeId' => 'required|exists:sedes,id',
        ], [
            'newNombre.required' => 'El nombre del inventario es obligatorio.',
            'newSedeId.required' => 'Debe seleccionar una sede.',
        ]);

        try {
            DB::beginTransaction();

            // Create inventory
            $inventario = Inventario::create([
                'nombre' => $this->newNombre,
                'sede_id' => $this->newSedeId,
                'usuario_id' => Auth::id(),
                'estado' => 'en_elaboracion',
                'fecha_creacion' => now(),
            ]);

            // Load only active products that are assigned to this Sede
            $products = Producto::whereHas('sedes', function ($q) {
                $q->where('sede_id', $this->newSedeId);
            })->where('estado', true)->get();

            foreach ($products as $product) {
                // Get stock level for this Sede
                $stockSede = DB::table('sede_productos')
                    ->where('sede_id', $this->newSedeId)
                    ->where('producto_id', $product->id)
                    ->first();

                $existencia = $stockSede ? $stockSede->existencia_sistema : 0.00;

                InventarioDetalle::create([
                    'inventario_id' => $inventario->id,
                    'producto_id' => $product->id,
                    'existencia_sistema' => $existencia,
                    'costo_sistema' => $product->costo_actual,
                    'cantidad_fisica' => null, // Not counted yet
                    'costo_contado' => null,  // Will default to system cost when input starts
                    'valor_total' => 0.00,
                ]);
            }

            DB::commit();

            $this->dispatch('alert', ['type' => 'success', 'message' => 'Inventario inicializado correctamente.']);
            $this->closeCreateModal();

            // Redirect to count sheet after showing alert
            $this->dispatch('redirect-after-alert', ['url' => route('inventarios.sheet', $inventario->id)]);
            return;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('alert', ['type' => 'error', 'message' => 'Error al inicializar el inventario: ' . $e->getMessage()]);
        }
    }

    public function deleteInventory($id)
    {
        try {
            DB::beginTransaction();
            $inventario = Inventario::findOrFail($id);
            
            // Si el inventario ya fue aplicado, borrarlo NO revierte el stock, solo borra el historial.
            $inventario->detalles()->delete();
            $inventario->delete();
            
            DB::commit();
            $this->dispatch('alert', ['type' => 'success', 'message' => 'Inventario eliminado exitosamente.']);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('alert', ['type' => 'error', 'message' => 'Error al eliminar el inventario: ' . $e->getMessage()]);
        }
    }

    public function render()
    {
        $user = auth()->user();
        $sedeIdsPermitidos = $user->esAdministrador()
            ? null
            : $user->sedes->pluck('id');

        $query = Inventario::with(['sede', 'usuario', 'detalles']);

        if ($sedeIdsPermitidos !== null) {
            $query->whereIn('sede_id', $sedeIdsPermitidos);
        }

        if ($this->search) {
            $query->where('nombre', 'like', '%' . $this->search . '%');
        }

        if ($this->sedeFilter) {
            $query->where('sede_id', $this->sedeFilter);
        }

        if ($this->estadoFilter) {
            $query->where('estado', $this->estadoFilter);
        }

        if ($this->dateFrom) {
            $query->whereDate('fecha_creacion', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->whereDate('fecha_creacion', '<=', $this->dateTo);
        }

        $inventarios = $query->orderBy('fecha_creacion', 'desc')->paginate(10);

        $sedes = $user->esAdministrador()
            ? Sede::where('estado', true)->get()
            : $user->sedes()->where('estado', true)->get();

        return view('livewire.inventory.inventory-index', [
            'inventarios' => $inventarios,
            'sedes'       => $sedes,
        ])->layout('layouts.app');
    }
}
