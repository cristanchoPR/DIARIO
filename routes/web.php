<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Dashboard\DashboardMain;
use App\Livewire\Sedes\SedeIndex;
use App\Livewire\Usuarios\UsuarioIndex;
use App\Livewire\Productos\ProductoIndex;
use App\Livewire\Inventory\InventoryIndex;
use App\Livewire\Inventory\InventorySheet;
use App\Livewire\Inventory\InventoryShow;
use App\Livewire\Reportes\ReporteIndex;
use App\Http\Controllers\InventoryExportController;

Route::middleware(['auth'])->group(function () {

    // Dashboard principal
    Route::get('/', DashboardMain::class)->name('dashboard');

    // ======= ADMINISTRACIÓN (solo admin) =======
    Route::middleware(['role:administrador'])->group(function () {
        Route::get('/sedes', SedeIndex::class)->name('sedes.index');
    });
    
    // ======= USUARIOS Y PRODUCTOS (admin y admin_sede) =======
    Route::middleware(['role:administrador|admin_sede'])->group(function () {
        Route::get('/usuarios', UsuarioIndex::class)->name('usuarios.index');
        Route::get('/productos', ProductoIndex::class)->name('productos.index');
    });

    // ======= INVENTARIOS (admin y usuario) =======
    Route::get('/inventarios', InventoryIndex::class)->name('inventarios.index');
    Route::get('/inventarios/{id}/conteo', InventorySheet::class)->name('inventarios.sheet');
    Route::get('/inventarios/{id}', InventoryShow::class)->name('inventarios.show');

    // ======= REPORTES =======
    Route::get('/reportes', ReporteIndex::class)->name('reportes.index');

    // ======= EXPORTACIONES =======
    Route::get('/inventarios/{id}/export/excel', [InventoryExportController::class, 'exportExcel'])->name('inventarios.export.excel');
    Route::get('/inventarios/{id}/export/pdf', [InventoryExportController::class, 'exportPdf'])->name('inventarios.export.pdf');

});

// Route::view('profile', 'profile')
//     ->middleware(['auth'])
//     ->name('profile');

require __DIR__.'/auth.php';
