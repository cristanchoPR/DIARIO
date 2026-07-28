<div x-data="{
    open: false,
    openAsign: false,
    loading: false,
    openNew() { this.open = true; this.loading = false; $wire.resetearFormulario(); },
    openEdit(id) { this.open = true; this.loading = true; $wire.editar(id).then(() => this.loading = false); },
    openAssign(id) { this.openAsign = true; this.loading = true; $wire.abrirAsignacion(id).then(() => this.loading = false); },
    close() { this.open = false; },
    closeAsign() { this.openAsign = false; }
}" @close-modal.window="close()" @close-asign-modal.window="closeAsign()">
    @section('title', 'Productos')

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl font-bold text-slate-900 dark:text-white transition-colors">Catálogo de Productos</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5 transition-colors">Gestiona el inventario maestro de productos</p>
        </div>
        @can('crear productos')
        <button @click="openNew()"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-aldia-primary hover:bg-aldia-primaryDark text-white text-sm font-semibold rounded-xl shadow-sm transition-all hover:shadow-md active:scale-95">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Nuevo Producto
        </button>
        @endcan
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-[#1E293B] rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-4 mb-6 transition-colors duration-200">
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 dark:text-slate-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar por nombre, código o código de barras…"
                       class="w-full pl-9 pr-4 py-2 text-sm border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-aldia-primary/30 focus:border-aldia-primary bg-slate-50 dark:bg-slate-900/50 dark:text-white dark:placeholder-slate-500 transition-colors">
            </div>
            <select wire:model.live="filtroCategoria"
                    class="px-3 py-2 text-sm border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-aldia-primary/30 focus:border-aldia-primary bg-slate-50 dark:bg-slate-900/50 dark:text-white transition-colors min-w-[150px]">
                <option value="">Todas las categorías</option>
                @foreach($categorias as $cat)
                    <option value="{{ $cat }}">{{ $cat }}</option>
                @endforeach
            </select>
            <select wire:model.live="filtroEstado"
                    class="px-3 py-2 text-sm border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-aldia-primary/30 focus:border-aldia-primary bg-slate-50 dark:bg-slate-900/50 dark:text-white transition-colors min-w-[130px]">
                <option value="">Todos</option>
                <option value="activo">Activos</option>
                <option value="inactivo">Inactivos</option>
            </select>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-[#1E293B] rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden transition-colors duration-200">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-slate-800/60 bg-slate-50/50 dark:bg-slate-800/50 transition-colors">
                        <th class="text-left px-5 py-3.5 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Producto</th>
                        <th class="text-left px-5 py-3.5 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Código</th>
                        <th class="text-left px-5 py-3.5 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Categoría</th>
                        <th class="text-left px-5 py-3.5 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Unidad</th>
                        <th class="text-right px-5 py-3.5 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Costo</th>
                        <th class="text-right px-5 py-3.5 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Precio</th>
                        <th class="text-center px-5 py-3.5 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Sedes</th>
                        <th class="text-center px-5 py-3.5 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Estado</th>
                        <th class="px-5 py-3.5"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 transition-colors">
                    @forelse($productos as $producto)
                    <tr wire:key="producto-{{ $producto->id }}" class="hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-colors">
                        <td class="px-5 py-3.5">
                            <div>
                                <div class="font-semibold text-slate-900 dark:text-slate-100 transition-colors">{{ $producto->nombre }}</div>
                                @if($producto->marca)
                                <div class="text-xs text-slate-400 dark:text-slate-500 transition-colors">{{ $producto->marca }}</div>
                                @endif
                            </div>
                        </td>
                        <td class="px-5 py-3.5">
                            <div>
                                <div class="font-mono text-xs font-bold text-slate-700 dark:text-slate-300 transition-colors">{{ $producto->codigo }}</div>
                                @if($producto->codigo_barras)
                                <div class="font-mono text-[10px] text-slate-400 dark:text-slate-500 transition-colors">{{ $producto->codigo_barras }}</div>
                                @endif
                            </div>
                        </td>
                        <td class="px-5 py-3.5">
                            @if($producto->categoria)
                            <span class="px-2.5 py-1 text-[10px] font-bold rounded-full bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400 transition-colors">{{ $producto->categoria }}</span>
                            @else
                            <span class="text-slate-300 dark:text-slate-600 text-xs transition-colors">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-xs text-slate-600 dark:text-slate-400 font-medium transition-colors">{{ $producto->unidad_medida }}</td>
                        <td class="px-5 py-3.5 text-right font-semibold text-slate-800 dark:text-slate-200 transition-colors">${{ number_format($producto->costo_actual, 0, ',', '.') }}</td>
                        <td class="px-5 py-3.5 text-right font-semibold text-emerald-700 dark:text-emerald-400 transition-colors">${{ number_format($producto->precio_venta, 0, ',', '.') }}</td>
                        <td class="px-5 py-3.5 text-center">
                            <button @click="openAssign({{ $producto->id }})"
                                    class="inline-flex items-center gap-1 px-2.5 py-1 text-[10px] font-bold rounded-full transition-colors cursor-pointer
                                           {{ $producto->sedes_count > 0 ? 'bg-aldia-primary/10 text-aldia-primaryDark dark:bg-aldia-primary/20 dark:text-aldia-primary hover:bg-aldia-primary/20 dark:hover:bg-aldia-primary/30' : 'bg-slate-100 text-slate-400 dark:bg-slate-800 dark:text-slate-500 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"/></svg>
                                {{ $producto->sedes_count }} sede(s)
                            </button>
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            <span class="px-2.5 py-1 text-[10px] font-bold rounded-full transition-colors {{ $producto->estado ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400' : 'bg-slate-100 text-slate-400 dark:bg-slate-800 dark:text-slate-500' }}">
                                {{ $producto->estado ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5">
                            @can('editar productos')
                            <div class="flex items-center justify-end gap-1">
                                <button @click="openEdit({{ $producto->id }})"
                                        class="p-1.5 text-slate-400 hover:text-aldia-primary hover:bg-aldia-primary/10 rounded-lg transition-colors" title="Editar">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <button wire:click="toggleEstado({{ $producto->id }})"
                                        class="p-1.5 text-slate-400 hover:text-amber-500 hover:bg-amber-50 rounded-lg transition-colors"
                                        title="{{ $producto->estado ? 'Desactivar' : 'Activar' }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $producto->estado ? 'M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636' : 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' }}"/></svg>
                                </button>
                                @can('eliminar productos')
                                <button wire:click="eliminar({{ $producto->id }})"
                                        wire:confirm="¿Eliminar este producto permanentemente?"
                                        class="p-1.5 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-lg transition-colors" title="Eliminar">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                                @endcan
                            </div>
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-5 py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-14 h-14 rounded-2xl bg-aldia-primary/10 dark:bg-aldia-primary/20 flex items-center justify-center transition-colors">
                                    <svg class="w-7 h-7 text-aldia-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/></svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-600 dark:text-slate-300 transition-colors">No hay productos</p>
                                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-1 transition-colors">Crea el primer producto para comenzar</p>
                                </div>
                                @can('crear productos')
                                <button @click="openNew()" class="px-4 py-2 bg-aldia-primary text-white text-sm font-semibold rounded-xl hover:bg-aldia-primaryDark transition-colors">
                                    Crear primer producto
                                </button>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($productos->hasPages())
        <div class="px-5 py-4 border-t border-slate-100">{{ $productos->links() }}</div>
        @endif
    </div>

    <!-- ==================== MODAL: CREAR / EDITAR ==================== -->
    <div
        x-show="open"
        x-cloak
        class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4"
        style="display:none;"
    >
        <div x-show="open"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="absolute inset-0 bg-black/50 backdrop-blur-sm"
             @click="close()"></div>

        <div x-show="open"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 scale-100" x-transition:leave-end="opacity-0 translate-y-8 scale-95"
             class="relative bg-white dark:bg-[#1E293B] rounded-t-3xl sm:rounded-3xl shadow-2xl w-full sm:max-w-2xl max-h-[90vh] overflow-y-auto transition-colors duration-200">

            <!-- Spinner carga -->
            <div x-show="loading" class="absolute inset-0 bg-white/80 dark:bg-[#1E293B]/80 backdrop-blur-sm flex items-center justify-center rounded-3xl z-10 transition-colors">
                <div class="flex flex-col items-center gap-3">
                    <svg class="animate-spin w-8 h-8 text-aldia-primary" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Cargando datos…</span>
                </div>
            </div>

            <div class="flex items-center justify-between p-6 border-b border-slate-100 dark:border-slate-800 transition-colors">
                <div>
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white transition-colors">{{ $editando ? 'Editar Producto' : 'Nuevo Producto' }}</h2>
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5 transition-colors">{{ $editando ? 'Actualiza los datos del producto' : 'Completa los datos del nuevo producto' }}</p>
                </div>
                <button @click="close()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors p-1 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form wire:submit="guardar" class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5 transition-colors">Código *</label>
                        <input wire:model="codigo" type="text" placeholder="PROD-001"
                               class="w-full px-3 py-2.5 text-sm border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-aldia-primary/30 focus:border-aldia-primary font-mono uppercase bg-white dark:bg-slate-900/50 dark:text-white dark:placeholder-slate-500 transition-colors">
                        @error('codigo') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5 transition-colors">Código de Barras</label>
                        <input wire:model="codigo_barras" type="text" placeholder="7702001000000"
                               class="w-full px-3 py-2.5 text-sm border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-aldia-primary/30 focus:border-aldia-primary font-mono bg-white dark:bg-slate-900/50 dark:text-white dark:placeholder-slate-500 transition-colors">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5 transition-colors">Nombre del producto *</label>
                        <input wire:model="nombre" type="text" placeholder="Nombre completo del producto"
                               class="w-full px-3 py-2.5 text-sm border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-aldia-primary/30 focus:border-aldia-primary bg-white dark:bg-slate-900/50 dark:text-white dark:placeholder-slate-500 transition-colors">
                        @error('nombre') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5 transition-colors">Categoría</label>
                        <input wire:model="categoria" type="text" placeholder="Ej: Abarrotes, Bebidas…"
                               list="categorias-list"
                               class="w-full px-3 py-2.5 text-sm border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-aldia-primary/30 focus:border-aldia-primary bg-white dark:bg-slate-900/50 dark:text-white dark:placeholder-slate-500 transition-colors">
                        <datalist id="categorias-list">
                            @foreach($categorias as $cat)
                                <option value="{{ $cat }}">
                            @endforeach
                        </datalist>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5 transition-colors">Marca</label>
                        <input wire:model="marca" type="text" placeholder="Marca del producto"
                               class="w-full px-3 py-2.5 text-sm border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-aldia-primary/30 focus:border-aldia-primary bg-white dark:bg-slate-900/50 dark:text-white dark:placeholder-slate-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5 transition-colors">Unidad de medida *</label>
                        <select wire:model="unidad_medida"
                                class="w-full px-3 py-2.5 text-sm border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-aldia-primary/30 focus:border-aldia-primary bg-white dark:bg-slate-900/50 dark:text-white transition-colors">
                            <option value="UNIDAD">Unidad</option>
                            <option value="KILOGRAMO">Kilogramo</option>
                            <option value="GRAMO">Gramo</option>
                            <option value="LITRO">Litro</option>
                            <option value="MILILITRO">Mililitro</option>
                            <option value="CAJA">Caja</option>
                            <option value="PAQUETE">Paquete</option>
                            <option value="DOCENA">Docena</option>
                            <option value="METRO">Metro</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5 transition-colors">Costo unitario ($) *</label>
                        <input wire:model="costo_actual" type="number" step="0.01" min="0" placeholder="0.00"
                               class="w-full px-3 py-2.5 text-sm border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-aldia-primary/30 focus:border-aldia-primary bg-white dark:bg-slate-900/50 dark:text-white dark:placeholder-slate-500 transition-colors">
                        @error('costo_actual') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5 transition-colors">Precio de venta ($) *</label>
                        <input wire:model="precio_venta" type="number" step="0.01" min="0" placeholder="0.00"
                               class="w-full px-3 py-2.5 text-sm border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-aldia-primary/30 focus:border-aldia-primary bg-white dark:bg-slate-900/50 dark:text-white dark:placeholder-slate-500 transition-colors">
                        @error('precio_venta') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex items-center gap-3">
                        <input wire:model="estado" type="checkbox" id="estado_prod" class="rounded border-slate-300 dark:border-slate-700 text-aldia-primary focus:ring-aldia-primary/30 h-4 w-4 bg-white dark:bg-slate-900/50 transition-colors">
                        <label for="estado_prod" class="text-sm font-medium text-slate-700 dark:text-slate-300 transition-colors">Producto activo</label>
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="button" @click="close()"
                            class="flex-1 py-2.5 text-sm font-semibold text-slate-600 dark:text-slate-300 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 rounded-xl transition-colors">
                        Cancelar
                    </button>
                    <button type="submit"
                            class="flex-1 py-2.5 text-sm font-semibold text-white bg-aldia-primary hover:bg-aldia-primaryDark rounded-xl transition-colors shadow-sm">
                        {{ $editando ? 'Guardar Cambios' : 'Crear Producto' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ==================== MODAL: ASIGNACIÓN DE SEDES ==================== -->
    <div
        x-show="openAsign"
        x-cloak
        class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4"
        style="display:none;"
    >
        <div x-show="openAsign"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="absolute inset-0 bg-black/50 backdrop-blur-sm"
             @click="closeAsign()"></div>

        <div x-show="openAsign"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 scale-100" x-transition:leave-end="opacity-0 translate-y-8 scale-95"
             class="relative bg-white dark:bg-[#1E293B] rounded-t-3xl sm:rounded-3xl shadow-2xl w-full sm:max-w-lg max-h-[90vh] overflow-y-auto transition-colors duration-200">

            <!-- Spinner carga asignación -->
            <div x-show="loading" class="absolute inset-0 bg-white/80 dark:bg-[#1E293B]/80 backdrop-blur-sm flex items-center justify-center rounded-3xl z-10 transition-colors">
                <div class="flex flex-col items-center gap-3">
                    <svg class="animate-spin w-8 h-8 text-aldia-primary" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Cargando sedes…</span>
                </div>
            </div>

            <div class="flex items-center justify-between p-6 border-b border-slate-100 dark:border-slate-800 transition-colors">
                <div>
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white transition-colors">Asignar a Sedes</h2>
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5 transition-colors">Define las sedes y la existencia inicial</p>
                </div>
                <button @click="closeAsign()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors p-1 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="p-6">
                @php 
                    $userAuth = auth()->user();
                    if ($userAuth->esAdministrador()) {
                        $todasSedes = \App\Models\Sede::activas()->orderBy('nombre')->get(); 
                    } else {
                        $todasSedes = $userAuth->sedes()->activas()->orderBy('nombre')->get();
                    }
                @endphp

                <div class="space-y-3 mb-6">
                    @foreach($todasSedes as $sede)
                    <div class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-slate-700 hover:border-aldia-primary/30 dark:hover:border-aldia-primary/50 hover:bg-aldia-primary/5 dark:hover:bg-aldia-primary/10 transition-all duration-200">
                        <input type="checkbox"
                               id="sede-{{ $sede->id }}"
                               wire:model.live="sedesAsignadas.{{ $sede->id }}.asignado"
                               class="rounded border-slate-300 dark:border-slate-600 text-aldia-primary focus:ring-aldia-primary/30 h-4 w-4 flex-shrink-0 bg-white dark:bg-slate-900/50 transition-colors">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-xs font-bold flex-shrink-0"
                             style="background-color: {{ $sede->color ?? '#5A8FDB' }}">
                            {{ strtoupper(substr($sede->nombre, 0, 2)) }}
                        </div>
                        <label for="sede-{{ $sede->id }}" class="flex-1 font-semibold text-sm text-slate-800 dark:text-slate-200 cursor-pointer transition-colors">
                            {{ $sede->nombre }}
                        </label>
                        @if(!empty($sedesAsignadas[$sede->id]['asignado']))
                        <div class="flex items-center gap-1.5">
                            <span class="text-xs text-slate-400 dark:text-slate-500 transition-colors">Stock:</span>
                            <input type="number" min="0" step="1"
                                   wire:model="sedesAsignadas.{{ $sede->id }}.existencia"
                                   class="w-20 px-2 py-1 text-sm border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-aldia-primary/30 focus:border-aldia-primary text-center font-mono bg-white dark:bg-slate-900/50 dark:text-white transition-colors"
                                   placeholder="0">
                        </div>
                        @endif
                    </div>
                    @endforeach

                    @if($todasSedes->isEmpty())
                    <div class="text-center py-8 text-sm text-slate-400 dark:text-slate-500 transition-colors">
                        No hay sedes activas disponibles. Crea una sede primero.
                    </div>
                    @endif
                </div>

                <div class="flex gap-3">
                    <button type="button" @click="closeAsign()"
                            class="flex-1 py-2.5 text-sm font-semibold text-slate-600 dark:text-slate-300 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 rounded-xl transition-colors">
                        Cancelar
                    </button>
                    <button wire:click="guardarAsignacion"
                            class="flex-1 py-2.5 text-sm font-semibold text-white bg-aldia-primary hover:bg-aldia-primaryDark rounded-xl transition-colors shadow-sm">
                        Guardar asignación
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
