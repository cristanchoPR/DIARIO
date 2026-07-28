<div class="py-8" x-data="{ open: false, loading: false }" @close-modal.window="open = false">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Encabezado y Acción -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-aldia-navy dark:text-white tracking-tight transition-colors">Inventarios Físicos</h1>
                <p class="text-sm text-aldia-textSec dark:text-slate-400 mt-1 transition-colors">Gestión multisede, control de existencias, ajustes y auditoría completa.</p>
            </div>
            <div>
                <button 
                    @click="open = true; loading = true; $wire.openCreateModal().then(() => loading = false)"
                    class="inline-flex items-center justify-center px-5 py-2.5 bg-aldia-primary hover:bg-aldia-primaryDark text-white font-semibold rounded-xl shadow-md hover:shadow-lg transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-aldia-primary/50 text-sm gap-2"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Nuevo Inventario
                </button>
            </div>
        </div>

        @if (session()->has('message'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm font-medium">{{ session('message') }}</span>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span class="text-sm font-medium">{{ session('error') }}</span>
            </div>
        @endif

        <!-- Panel de Filtros -->
        <div class="bg-aldia-bgCard dark:bg-[#1E293B] border border-aldia-borderLight dark:border-slate-800 rounded-2xl shadow-sm p-6 mb-8 transition-colors duration-200">
            <h2 class="text-xs font-semibold text-aldia-navy dark:text-slate-300 uppercase tracking-wider mb-4 transition-colors">Filtrar Inventarios</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <!-- Búsqueda -->
                <div>
                    <label class="block text-xs font-semibold text-aldia-textSec dark:text-slate-400 mb-1.5 transition-colors">Nombre / Código</label>
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="search" 
                        placeholder="Buscar por nombre..."
                        class="w-full text-sm bg-aldia-bgLight dark:bg-slate-900/50 border-aldia-borderLight dark:border-slate-700 rounded-xl focus:border-aldia-primary focus:ring-aldia-primary/20 text-aldia-textMain dark:text-white dark:placeholder-slate-500 transition-colors"
                    >
                </div>
                <!-- Sede -->
                <div>
                    <label class="block text-xs font-semibold text-aldia-textSec dark:text-slate-400 mb-1.5 transition-colors">Sede</label>
                    <select 
                        wire:model.live="sedeFilter"
                        class="w-full text-sm bg-aldia-bgLight dark:bg-slate-900/50 border-aldia-borderLight dark:border-slate-700 rounded-xl focus:border-aldia-primary focus:ring-aldia-primary/20 text-aldia-textMain dark:text-white transition-colors"
                    >
                        <option value="">Todas las Sedes</option>
                        @foreach($sedes as $sede)
                            <option value="{{ $sede->id }}">{{ $sede->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- Estado -->
                <div>
                    <label class="block text-xs font-semibold text-aldia-textSec dark:text-slate-400 mb-1.5 transition-colors">Estado</label>
                    <select 
                        wire:model.live="estadoFilter"
                        class="w-full text-sm bg-aldia-bgLight dark:bg-slate-900/50 border-aldia-borderLight dark:border-slate-700 rounded-xl focus:border-aldia-primary focus:ring-aldia-primary/20 text-aldia-textMain dark:text-white transition-colors"
                    >
                        <option value="">Todos los Estados</option>
                        <option value="en_elaboracion">En elaboración</option>
                        <option value="guardado">Guardado</option>
                        <option value="finalizado">Finalizado</option>
                        <option value="aplicado">Aplicado</option>
                    </select>
                </div>
                <!-- Fecha Desde -->
                <div>
                    <label class="block text-xs font-semibold text-aldia-textSec dark:text-slate-400 mb-1.5 transition-colors">Desde</label>
                    <input 
                        type="date" 
                        wire:model.live="dateFrom"
                        class="w-full text-sm bg-aldia-bgLight dark:bg-slate-900/50 border-aldia-borderLight dark:border-slate-700 rounded-xl focus:border-aldia-primary focus:ring-aldia-primary/20 text-aldia-textMain dark:text-white transition-colors"
                    >
                </div>
                <!-- Fecha Hasta -->
                <div>
                    <label class="block text-xs font-semibold text-aldia-textSec dark:text-slate-400 mb-1.5 transition-colors">Hasta</label>
                    <input 
                        type="date" 
                        wire:model.live="dateTo"
                        class="w-full text-sm bg-aldia-bgLight dark:bg-slate-900/50 border-aldia-borderLight dark:border-slate-700 rounded-xl focus:border-aldia-primary focus:ring-aldia-primary/20 text-aldia-textMain dark:text-white transition-colors"
                    >
                </div>
            </div>
        </div>

        <!-- Listado de Inventarios -->
        <div class="bg-aldia-bgCard dark:bg-[#1E293B] border border-aldia-borderLight dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden transition-colors duration-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-aldia-borderLight dark:divide-slate-800/60 text-left transition-colors">
                    <thead class="bg-aldia-bgLight dark:bg-slate-800/50 transition-colors">
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold text-aldia-navy dark:text-slate-400 uppercase tracking-wider transition-colors">Fecha / Hora</th>
                            <th class="px-6 py-4 text-xs font-bold text-aldia-navy dark:text-slate-400 uppercase tracking-wider transition-colors">Nombre del Inventario</th>
                            <th class="px-6 py-4 text-xs font-bold text-aldia-navy dark:text-slate-400 uppercase tracking-wider transition-colors">Sede</th>
                            <th class="px-6 py-4 text-xs font-bold text-aldia-navy dark:text-slate-400 uppercase tracking-wider transition-colors">Operario / Creador</th>
                            <th class="px-6 py-4 text-xs font-bold text-aldia-navy dark:text-slate-400 uppercase tracking-wider text-center transition-colors">Avance</th>
                            <th class="px-6 py-4 text-xs font-bold text-aldia-navy dark:text-slate-400 uppercase tracking-wider text-right transition-colors">Valor Inventariado</th>
                            <th class="px-6 py-4 text-xs font-bold text-aldia-navy dark:text-slate-400 uppercase tracking-wider text-center transition-colors">Estado</th>
                            <th class="px-6 py-4 text-xs font-bold text-aldia-navy dark:text-slate-400 uppercase tracking-wider text-right transition-colors">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-aldia-borderLight dark:divide-slate-800/60 transition-colors">
                        @forelse($inventarios as $inv)
                            @php
                                $totalItems = $inv->detalles->count();
                                $contados = $inv->detalles->whereNotNull('cantidad_physical_or_calculated', 'cantidad_fisica')->count();
                                $progreso = $totalItems > 0 ? round(($contados / $totalItems) * 100) : 0;
                                $valorTotal = $inv->detalles->sum('valor_total');
                            @endphp
                            <tr wire:key="inv-{{ $inv->id }}" class="hover:bg-aldia-bgLight/50 dark:hover:bg-slate-800/50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-aldia-textMain dark:text-slate-300 font-medium transition-colors">
                                    {{ $inv->fecha_creacion->format('d/m/Y') }}
                                    <span class="block text-xs text-aldia-textSec dark:text-slate-500 font-normal transition-colors">{{ $inv->fecha_creacion->format('h:i A') }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-aldia-navy dark:text-slate-100 transition-colors">
                                    {{ $inv->nombre }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-aldia-textMain dark:text-slate-300 transition-colors">
                                    <div class="flex items-center gap-1.5">
                                        <span class="inline-block w-2.5 h-2.5 rounded-full bg-aldia-primary/70"></span>
                                        {{ $inv->sede->nombre }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-aldia-textMain dark:text-slate-300 transition-colors">
                                    {{ $inv->usuario->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <div class="w-20 bg-gray-200 dark:bg-slate-700 rounded-full h-1.5 transition-colors">
                                            <div class="bg-aldia-primary h-1.5 rounded-full" style="width: {{ $progreso }}%"></div>
                                        </div>
                                        <span class="text-xs font-semibold text-aldia-navy dark:text-slate-300 transition-colors">{{ $progreso }}%</span>
                                    </div>
                                    <span class="text-[10px] text-aldia-textSec dark:text-slate-500 block mt-0.5 transition-colors">{{ $contados }} / {{ $totalItems }} Prod.</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-aldia-navy dark:text-slate-200 text-right transition-colors">
                                    ${{ number_format($valorTotal, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if ($inv->estado === 'en_elaboracion')
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-400 border border-slate-200 dark:border-slate-700 transition-colors">En elaboración</span>
                                    @elseif ($inv->estado === 'guardado')
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-aldia-primary/10 dark:bg-aldia-primary/20 text-aldia-primary border border-aldia-primary/20 dark:border-aldia-primary/30 transition-colors">Guardado</span>
                                    @elseif ($inv->estado === 'finalizado')
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-[#fdf2e2] dark:bg-amber-500/10 text-amber-800 dark:text-amber-400 border border-aldia-warm dark:border-amber-500/20 transition-colors">Finalizado</span>
                                    @elseif ($inv->estado === 'aplicado')
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-emerald-100 dark:bg-emerald-500/10 text-emerald-800 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20 transition-colors">Aplicado</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-3">
                                        @if ($inv->estado === 'aplicado')
                                            <a href="{{ route('inventarios.show', $inv->id) }}" wire:navigate class="text-aldia-navy hover:text-aldia-primary flex items-center gap-1 font-semibold">
                                                Ver Detalle
                                            </a>
                                        @else
                                            <a href="{{ route('inventarios.sheet', $inv->id) }}" wire:navigate class="text-white bg-aldia-primary hover:bg-aldia-primaryDark px-3 py-1.5 rounded-lg text-xs font-semibold shadow-sm flex items-center gap-1 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                </svg>
                                                {{ $inv->estado === 'finalizado' ? 'Ver/Aplicar' : 'Conteo Físico' }}
                                            </a>
                                        @endif
                                        <button 
                                            wire:click="deleteInventory({{ $inv->id }})" 
                                            wire:confirm="¿Estás seguro de que deseas eliminar este inventario? Esta acción es irreversible y borrará el historial de conteos de este registro."
                                            class="text-aldia-danger dark:text-rose-400 hover:text-rose-600 dark:hover:text-rose-300 p-1.5 rounded-lg hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-colors"
                                            title="Eliminar Inventario"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-sm text-aldia-textSec dark:text-slate-500 transition-colors">
                                    No se encontraron inventarios físicos con los filtros actuales.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="px-6 py-4 bg-aldia-bgLight/40 dark:bg-slate-800/40 border-t border-aldia-borderLight dark:border-slate-800/60 transition-colors">
                {{ $inventarios->links() }}
            </div>
        </div>

        <!-- Modal Nuevo Inventario — animación premium -->
        <div
            x-show="open"
            x-cloak
            class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4"
            style="display:none;"
        >
            <!-- Backdrop fade -->
            <div
                x-show="open"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="absolute inset-0 bg-black/50 backdrop-blur-sm"
                @click="open = false"
            ></div>

            <!-- Panel slide-up + scale -->
            <div
                x-show="open"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                x-transition:leave-end="opacity-0 translate-y-8 scale-95"
                class="relative bg-white dark:bg-[#1E293B] rounded-t-3xl sm:rounded-3xl shadow-2xl w-full sm:max-w-lg overflow-hidden transition-colors duration-200"
            >
                <!-- Spinner de carga -->
                <div x-show="loading" class="absolute inset-0 bg-white/80 dark:bg-[#1E293B]/80 backdrop-blur-sm flex items-center justify-center z-10 transition-colors">
                    <div class="flex flex-col items-center gap-3">
                        <svg class="animate-spin w-8 h-8 text-aldia-primary" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Preparando…</span>
                    </div>
                </div>

                <!-- Header -->
                <div class="flex items-center justify-between p-6 border-b border-slate-100 dark:border-slate-800 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-aldia-primary/10 dark:bg-aldia-primary/20 flex items-center justify-center flex-shrink-0 transition-colors">
                            <svg class="w-5 h-5 text-aldia-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white transition-colors">Nuevo Inventario Físico</h3>
                            <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5 transition-colors">Define el nombre y la sede a inventariar</p>
                        </div>
                    </div>
                    <button @click="open = false"
                            class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors p-1 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Body -->
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5 transition-colors">Nombre del Inventario *</label>
                        <input type="text"
                               wire:model="newNombre"
                               placeholder="Ej. Inventario General Julio"
                               class="w-full px-3 py-2.5 text-sm border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-aldia-primary/30 focus:border-aldia-primary font-medium bg-white dark:bg-slate-900/50 dark:text-white dark:placeholder-slate-500 transition-colors">
                        @error('newNombre') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5 transition-colors">Sede *</label>
                        <select wire:model="newSedeId"
                                class="w-full px-3 py-2.5 text-sm border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-aldia-primary/30 focus:border-aldia-primary bg-white dark:bg-slate-900/50 dark:text-white transition-colors">
                            @foreach($sedes as $sede)
                                <option value="{{ $sede->id }}">{{ $sede->nombre }}</option>
                            @endforeach
                        </select>
                        @error('newSedeId') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Footer -->
                <div class="flex gap-3 px-6 pb-6">
                    <button type="button" @click="open = false"
                            class="flex-1 py-2.5 text-sm font-semibold text-slate-600 dark:text-slate-300 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 rounded-xl transition-colors">
                        Cancelar
                    </button>
                    <button type="button" wire:click="createInventory"
                            class="flex-1 py-2.5 text-sm font-semibold text-white bg-aldia-primary hover:bg-aldia-primaryDark rounded-xl transition-colors shadow-sm">
                        Iniciar Conteo
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
