<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use App\Exports\InventoryExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InventoryExportController extends Controller
{
    public function exportExcel($id)
    {
        $inventario = Inventario::findOrFail($id);
        
        // Check if user is authorized if needed, e.g. via Spatie permissions or policies
        // $this->authorize('exportar inventarios');

        return Excel::download(new InventoryExport($inventario), 'inventario-' . $inventario->id . '-' . now()->format('Ymd') . '.xlsx');
    }

    public function exportPdf($id)
    {
        $inventario = Inventario::with(['sede', 'usuario', 'detalles.producto'])->findOrFail($id);
        
        // $this->authorize('exportar inventarios');

        $pdf = Pdf::loadView('pdf.inventory', compact('inventario'));
        
        // Custom paper size or landscape if needed:
        // $pdf->setPaper('letter', 'portrait');

        return $pdf->download('reporte-inventario-' . $inventario->id . '-' . now()->format('Ymd') . '.pdf');
    }
}
