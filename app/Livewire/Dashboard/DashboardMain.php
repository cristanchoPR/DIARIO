<?php

namespace App\Livewire\Dashboard;

use App\Models\Sede;
use App\Models\Producto;
use App\Models\User;
use App\Models\Inventario;
use App\Models\InventarioAuditoria;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Dashboard')]
class DashboardMain extends Component
{
    public function render()
    {
        $user = auth()->user();

        if ($user->esAdministrador()) {
            $totalSedes       = Sede::count();
            $sedesActivas     = Sede::where('estado', true)->count();
            $totalProductos   = Producto::count();
            $totalUsuarios    = User::count();
            $inventariosHoy   = Inventario::whereDate('created_at', today())->count();
            $inventariosTotal = Inventario::count();
            $inventariosPendientes = Inventario::whereIn('estado', ['borrador', 'en_proceso'])->count();
            $inventariosFinalizados = Inventario::where('estado', 'aplicado')->count();
            $sedesQuery       = Sede::withCount(['inventarios', 'productos', 'usuarios'])->orderBy('nombre');
        } else {
            $sedeIds          = $user->sedes->pluck('id');
            $totalSedes       = $sedeIds->count();
            $sedesActivas     = Sede::whereIn('id', $sedeIds)->where('estado', true)->count();
            $totalProductos   = 0; // No visible para usuario
            $totalUsuarios    = 0;
            $inventariosHoy   = Inventario::whereIn('sede_id', $sedeIds)->whereDate('created_at', today())->count();
            $inventariosTotal = Inventario::whereIn('sede_id', $sedeIds)->count();
            $inventariosPendientes = Inventario::whereIn('sede_id', $sedeIds)->whereIn('estado', ['borrador', 'en_proceso'])->count();
            $inventariosFinalizados = Inventario::whereIn('sede_id', $sedeIds)->where('estado', 'aplicado')->count();
            $sedesQuery       = Sede::whereIn('id', $sedeIds)->withCount(['inventarios'])->orderBy('nombre');
        }

        $ultimasAuditorias = InventarioAuditoria::with(['inventario.sede', 'usuario', 'producto'])
            ->when(!$user->esAdministrador(), function ($q) use ($user) {
                $sedeIds = $user->sedes->pluck('id');
                return $q->whereHas('inventario', fn($qi) => $qi->whereIn('sede_id', $sedeIds));
            })
            ->latest()
            ->take(5)
            ->get();

        $sedes = $sedesQuery->get();

        return view('livewire.dashboard.dashboard-main', compact(
            'totalSedes', 'sedesActivas', 'totalProductos', 'totalUsuarios',
            'inventariosHoy', 'inventariosTotal', 'inventariosPendientes',
            'inventariosFinalizados', 'ultimasAuditorias', 'sedes'
        ));
    }
}
