<?php

namespace App\Livewire\Reportes;

use App\Models\Inventario;
use App\Models\Sede;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Reportes')]
class ReporteIndex extends Component
{
    use WithPagination;

    public string $filtroSede = '';
    public string $filtroEstado = '';
    public string $filtroFechaDesde = '';
    public string $filtroFechaHasta = '';

    public function updatingFiltroSede(): void { $this->resetPage(); }

    public function render()
    {
        $user    = auth()->user();
        $sedeIds = $user->esAdministrador() ? null : $user->sedes->pluck('id');

        $inventarios = Inventario::with(['sede'])
            ->when($sedeIds, fn($q) => $q->whereIn('sede_id', $sedeIds))
            ->when($this->filtroSede, fn($q) => $q->where('sede_id', $this->filtroSede))
            ->when($this->filtroEstado, fn($q) => $q->where('estado', $this->filtroEstado))
            ->when($this->filtroFechaDesde, fn($q) => $q->whereDate('created_at', '>=', $this->filtroFechaDesde))
            ->when($this->filtroFechaHasta, fn($q) => $q->whereDate('created_at', '<=', $this->filtroFechaHasta))
            ->where('estado', 'aplicado') // Reportes solo de inventarios aplicados
            ->latest()
            ->paginate(15);

        $sedes = $user->esAdministrador()
            ? Sede::activas()->orderBy('nombre')->get()
            : $user->sedes()->where('estado', true)->orderBy('nombre')->get();

        return view('livewire.reportes.reporte-index', compact('inventarios', 'sedes'));
    }
}
