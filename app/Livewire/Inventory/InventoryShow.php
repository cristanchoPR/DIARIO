<?php

namespace App\Livewire\Inventory;

use App\Models\Inventario;
use App\Models\InventarioDetalle;
use App\Models\InventarioAuditoria;
use App\Models\MovimientoAjuste;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class InventoryShow extends Component
{
    use WithPagination;

    public $inventarioId;

    // Filters for details inside show
    public $search = '';
    public $categoriaFilter = '';
    public $marcaFilter = '';

    // Summary totals (loaded once in mount since they are frozen)
    public $totalSku = 0;
    public $skuContados = 0;
    public $unidadesSistema = 0;
    public $unidadesContadas = 0;
    public $valorSistema = 0;
    public $valorContado = 0;
    public $diferenciaDinero = 0;
    public $diferenciaUnidades = 0;

    public function mount($id)
    {
        $this->inventarioId = $id;
        $inventario = Inventario::findOrFail($id);

        // Populate summary metrics (frozen details)
        $detalles = $inventario->detalles;
        $this->totalSku = $detalles->count();
        $this->skuContados = $detalles->whereNotNull('cantidad_fisica')->count();
        $this->unidadesSistema = (float) $detalles->sum('existencia_sistema');
        $this->unidadesContadas = (float) $detalles->whereNotNull('cantidad_fisica')->sum('cantidad_fisica');
        
        $this->valorSistema = (float) $detalles->sum(function($det) {
            return $det->existencia_sistema * $det->costo_sistema;
        });
        $this->valorContado = (float) $detalles->sum('valor_total');
        $this->diferenciaDinero = $this->valorContado - $this->valorSistema;
        $this->diferenciaUnidades = $this->unidadesContadas - $this->unidadesSistema;
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

    /**
     * Download Excel
     */
    public function exportExcel()
    {
        return redirect()->route('inventarios.export.excel', $this->inventarioId);
    }

    /**
     * Download PDF
     */
    public function exportPdf()
    {
        return redirect()->route('inventarios.export.pdf', $this->inventarioId);
    }

    public function render()
    {
        $inventario = Inventario::with(['sede', 'usuario'])->findOrFail($this->inventarioId);

        // Fetch paginated details
        $query = InventarioDetalle::with('producto')
            ->where('inventario_id', $this->inventarioId);

        if ($this->search) {
            $query->whereHas('producto', function ($q) {
                $q->where('nombre', 'like', '%' . $this->search . '%')
                  ->orWhere('codigo', 'like', '%' . $this->search . '%')
                  ->orWhere('codigo_barras', 'like', '%' . $this->search . '%');
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

        $detalles = $query->paginate(15);

        // Load audit logs and adjustments for tabs
        $auditorias = InventarioAuditoria::with(['producto', 'usuario'])
            ->where('inventario_id', $this->inventarioId)
            ->orderBy('fecha_hora', 'desc')
            ->limit(50)
            ->get();

        $ajustes = MovimientoAjuste::with(['producto', 'usuario'])
            ->where('inventario_id', $this->inventarioId)
            ->orderBy('id', 'asc')
            ->get();

        $categorias = DB::table('productos')->distinct()->whereNotNull('categoria')->pluck('categoria');
        $marcas = DB::table('productos')->distinct()->whereNotNull('marca')->pluck('marca');

        return view('livewire.inventory.inventory-show', [
            'inventario' => $inventario,
            'detalles' => $detalles,
            'auditorias' => $auditorias,
            'ajustes' => $ajustes,
            'categorias' => $categorias,
            'marcas' => $marcas,
        ])->layout('layouts.app');
    }
}
