<?php

namespace App\Livewire\Inventory;

use App\Models\Inventario;
use App\Models\InventarioDetalle;
use App\Models\InventarioAuditoria;
use App\Models\MovimientoAjuste;
use App\Models\SedeProducto;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class InventorySheet extends Component
{
    use WithPagination;

    public $inventarioId;
    
    // Page state
    public $search = '';
    public $categoriaFilter = '';
    public $marcaFilter = '';
    public $blindCount = false;
    public $highContrast = false;

    // Totals cached for footer (so we don't have to load all products)
    public $totalSku = 0;
    public $skuContados = 0;
    public $unidadesSistema = 0;
    public $unidadesContadas = 0;
    public $valorSistema = 0;
    public $valorContado = 0;
    public $diferenciaDinero = 0;
    public $diferenciaUnidades = 0;

    protected $listeners = ['refreshSheet' => '$refresh'];

    public function mount($id)
    {
        $this->inventarioId = $id;
        $inventario = Inventario::findOrFail($id);
        
        // If applied, redirect to view detail
        if ($inventario->estado === 'aplicado') {
            return $this->redirect(route('inventarios.show', $this->inventarioId), navigate: true);
        }

        $this->recalculateTotals();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedCategoriaFilter()
    {
        $this->resetPage();
    }

    public function updatedMarcaFilter()
    {
        $this->resetPage();
    }

    public function recalculateTotals()
    {
        $detalles = DB::table('inventario_detalles')
            ->where('inventario_id', $this->inventarioId)
            ->get();

        $this->totalSku = $detalles->count();
        $this->skuContados = $detalles->whereNotNull('cantidad_fisica')->count();
        
        $this->unidadesSistema = (float) $detalles->sum('existencia_sistema');
        $this->unidadesContadas = (float) $detalles->whereNotNull('cantidad_fisica')->sum('cantidad_fisica');
        
        // System Value = existencia_sistema * costo_sistema
        $this->valorSistema = (float) $detalles->sum(fn($item) => $item->existencia_sistema * $item->costo_sistema);
        
        // Contado Value = sum of valor_total
        $this->valorContado = (float) $detalles->sum('valor_total');
        
        $this->diferenciaDinero = $this->valorContado - $this->valorSistema;
        $this->diferenciaUnidades = $this->unidadesContadas - $this->unidadesSistema;
    }

    /**
     * Save an edited row. Called via Alpine / blur event.
     */
    public function updateRow($detailId, $cantidadFisica, $costoContado)
    {
        $detalle = InventarioDetalle::findOrFail($detailId);
        $inventario = $detalle->inventario;

        // Check if locked
        if ($inventario->estado === 'aplicado') {
            return;
        }

        // Clean input
        $cantidadFisica = ($cantidadFisica === '' || $cantidadFisica === null) ? null : (float) $cantidadFisica;
        $costoContado = ($costoContado === '' || $costoContado === null) ? (float) $detalle->costo_sistema : (float) $costoContado;

        // If nothing changed, return
        if ($detalle->cantidad_fisica === $cantidadFisica && (float)$detalle->costo_contado === $costoContado) {
            return;
        }

        // Capture previous values for audit
        $cantAnterior = $detalle->cantidad_fisica;
        $costoAnterior = $detalle->costo_contado ?? $detalle->costo_sistema;

        // Calculate valor total
        $valorTotal = $cantidadFisica !== null ? $cantidadFisica * $costoContado : 0.00;

        // Update
        $detalle->update([
            'cantidad_fisica' => $cantidadFisica,
            'costo_contado' => $costoContado,
            'valor_total' => $valorTotal,
        ]);

        // Write Audit log
        InventarioAuditoria::create([
            'inventario_id' => $this->inventarioId,
            'producto_id' => $detalle->producto_id,
            'usuario_id' => Auth::id(),
            'cantidad_anterior' => $cantAnterior,
            'cantidad_nueva' => $cantidadFisica ?? 0.00,
            'costo_anterior' => $costoAnterior,
            'costo_nuevo' => $costoContado,
            'fecha_hora' => now(),
        ]);

        // If status is 'en_elaboracion', update it to 'guardado' to show progress has started
        if ($inventario->estado === 'en_elaboracion') {
            $inventario->update(['estado' => 'guardado']);
        }

        $this->recalculateTotals();
    }

    /**
     * Finalize count phase (transitions to 'finalizado').
     */
    public function finalizarInventario()
    {
        \Illuminate\Support\Facades\Log::info('Finalizar conteo called for ID: ' . $this->inventarioId);
        $inventario = Inventario::findOrFail($this->inventarioId);
        
        if ($inventario->estado === 'aplicado') {
            return;
        }

        $inventario->update([
            'estado' => 'finalizado',
        ]);

        $this->inventario = $inventario->fresh(); // Actualizar propiedad pública para re-render UI

        session()->flash('message', 'Conteo finalizado. ¡Listo para aplicar!');
        $this->dispatch('alert', ['type' => 'success', 'message' => 'Conteo finalizado. ¡Listo para aplicar!']);
    }

    /**
     * Reopen inventory to continue editing (from finalizado back to guardado)
     */
    public function reabrirInventario()
    {
        $inventario = Inventario::findOrFail($this->inventarioId);
        
        if ($inventario->estado !== 'finalizado') {
            return;
        }

        $inventario->update([
            'estado' => 'guardado',
        ]);

        $this->inventario = $inventario->fresh(); // Actualizar propiedad pública para re-render UI

        session()->flash('message', 'Inventario reabierto. Puedes continuar el conteo.');
        $this->dispatch('alert', ['type' => 'info', 'message' => 'Inventario reabierto. Puedes continuar el conteo.']);
    }

    /**
     * Apply physical inventory adjustments and sync real stocks.
     */
    public function aplicarInventario()
    {
        $inventario = Inventario::findOrFail($this->inventarioId);

        if ($inventario->estado !== 'finalizado') {
            $this->dispatch('alert', ['type' => 'error', 'message' => 'El inventario debe estar finalizado para aplicarse.']);
            return;
        }

        try {
            DB::beginTransaction();

            $detalles = $inventario->detalles;

            foreach ($detalles as $detalle) {
                $cantidadFisica = $detalle->cantidad_fisica ?? 0.00;
                $costoContado   = $detalle->costo_contado ?? $detalle->costo_sistema;

                $sedeProducto = SedeProducto::where('sede_id', $inventario->sede_id)
                    ->where('producto_id', $detalle->producto_id)
                    ->first();

                $existenciaAnterior = $sedeProducto ? (float) $sedeProducto->existencia_sistema : 0.00;
                $diff = $cantidadFisica - $existenciaAnterior;

                if ($diff != 0) {
                    MovimientoAjuste::create([
                        'inventario_id'    => $inventario->id,
                        'producto_id'      => $detalle->producto_id,
                        'tipo'             => $diff > 0 ? 'positivo' : 'negativo',
                        'cantidad'         => abs($diff),
                        'usuario_id'       => Auth::id(),
                        'fecha_hora'       => now(),
                        'documento_origen' => 'INV-' . str_pad($inventario->id, 6, '0', STR_PAD_LEFT),
                    ]);
                }

                // Crea o actualiza la existencia en la sede con lo que se contó
                SedeProducto::updateOrCreate(
                    [
                        'sede_id'    => $inventario->sede_id,
                        'producto_id' => $detalle->producto_id,
                    ],
                    [
                        'existencia_sistema' => $cantidadFisica,
                    ]
                );

                // Actualizar el costo del producto maestro
                $detalle->producto->update([
                    'costo_actual' => $costoContado,
                ]);
            }

            // Bloquear inventario
            $inventario->update([
                'estado'            => 'aplicado',
                'fecha_aplicacion'  => now(),
            ]);

            DB::commit();

            $this->inventario = $inventario->fresh(); // Actualizar propiedad pública para re-render UI

            session()->flash('message', 'Inventario aplicado exitosamente.');
            return $this->redirect(route('inventarios.show', $this->inventarioId), navigate: true);

        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('alert', ['type' => 'error', 'message' => 'Error al aplicar el inventario: ' . $e->getMessage()]);
        }
    }

    public function render()
    {
        $inventario = Inventario::with(['sede', 'usuario'])->findOrFail($this->inventarioId);

        // Fetch query for details
        $query = InventarioDetalle::with('producto')
            ->where('inventario_id', $this->inventarioId);

        if ($this->search) {
            $query->whereHas('producto', function ($q) {
                $q->where('nombre', 'like', '%' . $this->search . '%')
                  ->orWhere('codigo', 'like', '%' . $this->search . '%')
                  ->orWhere('codigo_barras', 'like', '%' . $this->search . '%')
                  ->orWhere('categoria', 'like', '%' . $this->search . '%')
                  ->orWhere('marca', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->categoriaFilter) {
            $query->whereHas('producto', function ($q) {
                $q->where('categoria', $this->categoriaFilter);
            });
        }

        if ($this->marcaFilter) {
            $query->whereHas('producto', function ($q) {
                $q->where('marca', $this->marcaFilter);
            });
        }

        // Get filter lists
        $categorias = DB::table('productos')->distinct()->whereNotNull('categoria')->pluck('categoria');
        $marcas = DB::table('productos')->distinct()->whereNotNull('marca')->pluck('marca');

        // High contrast stylesheet setting
        $detalles = $query->paginate(25);

        return view('livewire.inventory.inventory-sheet', [
            'inventario' => $inventario,
            'detalles' => $detalles,
            'categorias' => $categorias,
            'marcas' => $marcas,
        ])->layout('layouts.app');
    }
}
