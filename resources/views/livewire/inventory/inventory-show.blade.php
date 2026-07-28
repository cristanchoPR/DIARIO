<div class="py-8 font-sans bg-aldia-bgLight dark:bg-slate-900 text-aldia-textMain dark:text-slate-300 transition-colors duration-200" x-data="{ tab: 'detalles' }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Panel -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8 bg-white dark:bg-[#1E293B] border border-aldia-borderLight dark:border-slate-800 rounded-2xl shadow-sm p-6 transition-colors duration-200">
            <div class="flex items-center gap-4">
                <a href="{{ route('inventarios.index') }}" wire:navigate class="p-2 bg-aldia-bgLight dark:bg-slate-800 hover:bg-aldia-borderLight dark:hover:bg-slate-700 rounded-xl text-aldia-navy dark:text-slate-300 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-2xl font-extrabold text-aldia-navy dark:text-white tracking-tight transition-colors">{{ $inventario->nombre }}</h1>
                        <span class="px-2.5 py-0.5 text-xs font-bold rounded-full bg-emerald-100 dark:bg-emerald-500/10 text-emerald-800 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20 transition-colors">Aplicado</span>
                    </div>
                    <p class="text-xs text-aldia-textSec dark:text-slate-400 mt-1 transition-colors">
                        Sede: <span class="font-bold text-aldia-navy dark:text-slate-200">{{ $inventario->sede->nombre }}</span> | 
                        Aplicado por: <span class="font-bold text-aldia-navy dark:text-slate-200">{{ $inventario->usuario->name }}</span> | 
                        Fecha Aplicación: <span class="font-bold text-slate-700 dark:text-slate-300">{{ $inventario->fecha_aplicacion->format('d/m/Y h:i A') }}</span>
                    </p>
                </div>
            </div>

            <!-- Export Buttons -->
            <div class="flex items-center gap-3 w-full md:w-auto">
                <a 
                    href="{{ route('inventarios.export.excel', $inventario->id) }}"
                    target="_blank"
                    class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-sm text-xs transition-all flex items-center gap-1.5"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Exportar Excel
                </a>
                <a 
                    href="{{ route('inventarios.export.pdf', $inventario->id) }}"
                    target="_blank"
                    class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-xl shadow-sm text-xs transition-all flex items-center gap-1.5"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    Exportar PDF
                </a>
            </div>
        </div>

        <!-- Frozen Totals Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total SKU -->
            <div class="bg-white dark:bg-[#1E293B] border border-aldia-borderLight dark:border-slate-800 rounded-2xl shadow-sm p-6 flex flex-col justify-between transition-colors duration-200">
                <span class="text-xs font-semibold text-aldia-textSec dark:text-slate-400 uppercase tracking-wider transition-colors">Productos Inventariados</span>
                <span class="text-3xl font-extrabold text-aldia-navy dark:text-white mt-2 transition-colors">{{ $skuContados }} <span class="text-sm font-normal text-aldia-textSec dark:text-slate-500">/ {{ $totalSku }} SKUs</span></span>
            </div>
            
            <!-- Unidades Contadas -->
            <div class="bg-white dark:bg-[#1E293B] border border-aldia-borderLight dark:border-slate-800 rounded-2xl shadow-sm p-6 flex flex-col justify-between transition-colors duration-200">
                <span class="text-xs font-semibold text-aldia-textSec dark:text-slate-400 uppercase tracking-wider transition-colors">Total Unidades Contadas</span>
                <span class="text-3xl font-extrabold text-aldia-navy dark:text-white mt-2 transition-colors">{{ number_format($unidadesContadas, 2) }}</span>
                <span class="text-xs text-aldia-textSec dark:text-slate-400 mt-1 transition-colors">Diferencia: <span class="font-bold {{ $diferenciaUnidades >= 0 ? 'text-aldia-success dark:text-emerald-400' : 'text-aldia-danger dark:text-rose-400' }}">{{ $diferenciaUnidades >= 0 ? '+' : '' }}{{ number_format($diferenciaUnidades, 2) }}</span></span>
            </div>

            <!-- Valor Inventariado -->
            <div class="bg-white dark:bg-[#1E293B] border border-aldia-borderLight dark:border-slate-800 rounded-2xl shadow-sm p-6 flex flex-col justify-between transition-colors duration-200">
                <span class="text-xs font-semibold text-aldia-textSec dark:text-slate-400 uppercase tracking-wider transition-colors">Valor Total Inventariado</span>
                <span class="text-3xl font-extrabold text-aldia-navy dark:text-white mt-2 transition-colors">${{ number_format($valorContado, 2) }}</span>
                <span class="text-xs text-aldia-textSec dark:text-slate-400 mt-1 transition-colors">Sistema: ${{ number_format($valorSistema, 2) }}</span>
            </div>

            <!-- Diferencia Total -->
            <div class="bg-white dark:bg-[#1E293B] border border-aldia-borderLight dark:border-slate-800 rounded-2xl shadow-sm p-6 flex flex-col justify-between transition-colors duration-200">
                <span class="text-xs font-semibold text-aldia-textSec dark:text-slate-400 uppercase tracking-wider transition-colors">Diferencia Neta Dinero</span>
                <span class="text-3xl font-extrabold mt-2 {{ $diferenciaDinero >= 0 ? 'text-aldia-success dark:text-emerald-400' : 'text-aldia-danger dark:text-rose-400' }}">
                    {{ $diferenciaDinero >= 0 ? '+' : '' }}${{ number_format($diferenciaDinero, 2) }}
                </span>
                <span class="text-xs text-aldia-textSec dark:text-slate-400 mt-1 transition-colors">Trazabilidad de Ajuste</span>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="border-b border-aldia-borderLight dark:border-slate-800 mb-6 flex gap-6 transition-colors duration-200">
            <button 
                @click="tab = 'detalles'"
                :class="tab === 'detalles' ? 'border-aldia-primary text-aldia-primary font-bold' : 'border-transparent text-aldia-textSec dark:text-slate-400 hover:text-aldia-navy dark:hover:text-white'"
                class="pb-3 text-sm font-semibold border-b-2 transition-all"
            >
                Detalles del Conteo
            </button>
            <button 
                @click="tab = 'ajustes'"
                :class="tab === 'ajustes' ? 'border-aldia-primary text-aldia-primary font-bold' : 'border-transparent text-aldia-textSec dark:text-slate-400 hover:text-aldia-navy dark:hover:text-white'"
                class="pb-3 text-sm font-semibold border-b-2 transition-all"
            >
                Movimientos de Ajuste ({{ $ajustes->count() }})
            </button>
            <button 
                @click="tab = 'auditoria'"
                :class="tab === 'auditoria' ? 'border-aldia-primary text-aldia-primary font-bold' : 'border-transparent text-aldia-textSec dark:text-slate-400 hover:text-aldia-navy dark:hover:text-white'"
                class="pb-3 text-sm font-semibold border-b-2 transition-all"
            >
                Bitácora de Auditoría ({{ $auditorias->count() }})
            </button>
        </div>

        <!-- Tab 1: Detalles del Conteo -->
        <div x-show="tab === 'detalles'" class="space-y-6">
            <!-- Filtros locales -->
            <div class="bg-white dark:bg-[#1E293B] border border-aldia-borderLight dark:border-slate-800 rounded-2xl shadow-sm p-6 transition-colors duration-200">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="search" 
                        placeholder="Buscar por Nombre o Código..."
                        class="w-full text-sm bg-aldia-bgLight dark:bg-slate-900/50 border-aldia-borderLight dark:border-slate-700 rounded-xl focus:border-aldia-primary focus:ring-aldia-primary/20 text-aldia-textMain dark:text-white dark:placeholder-slate-500 transition-colors"
                    >
                    <select 
                        wire:model.live="categoriaFilter"
                        class="w-full text-sm bg-aldia-bgLight dark:bg-slate-900/50 border-aldia-borderLight dark:border-slate-700 rounded-xl focus:border-aldia-primary focus:ring-aldia-primary/20 text-aldia-textMain dark:text-white transition-colors"
                    >
                        <option value="">Todas las Categorías</option>
                        @foreach($categorias as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                    </select>
                    <select 
                        wire:model.live="marcaFilter"
                        class="w-full text-sm bg-aldia-bgLight dark:bg-slate-900/50 border-aldia-borderLight dark:border-slate-700 rounded-xl focus:border-aldia-primary focus:ring-aldia-primary/20 text-aldia-textMain dark:text-white transition-colors"
                    >
                        <option value="">Todas las Marcas</option>
                        @foreach($marcas as $m)
                            <option value="{{ $m }}">{{ $m }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Grilla Detalle Congelada -->
            <div class="bg-white dark:bg-[#1E293B] border border-aldia-borderLight dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden transition-colors duration-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-aldia-borderLight dark:divide-slate-800/60 text-left transition-colors">
                        <thead class="bg-aldia-bgLight dark:bg-slate-800/50 transition-colors">
                            <tr>
                                <th class="px-6 py-4 text-xs font-bold text-aldia-navy dark:text-slate-400 uppercase tracking-wider transition-colors">Código</th>
                                <th class="px-6 py-4 text-xs font-bold text-aldia-navy dark:text-slate-400 uppercase tracking-wider transition-colors">Producto / Detalle</th>
                                <th class="px-6 py-4 text-xs font-bold text-aldia-navy dark:text-slate-400 uppercase tracking-wider text-right transition-colors">Exist. Sistema</th>
                                <th class="px-6 py-4 text-xs font-bold text-aldia-navy dark:text-slate-400 uppercase tracking-wider text-right transition-colors">Costo Sistema</th>
                                <th class="px-6 py-4 text-xs font-bold text-aldia-navy dark:text-slate-400 uppercase tracking-wider text-right transition-colors">Cantidad Física</th>
                                <th class="px-6 py-4 text-xs font-bold text-aldia-navy dark:text-slate-400 uppercase tracking-wider text-right transition-colors">Costo Aplicado</th>
                                <th class="px-6 py-4 text-xs font-bold text-aldia-navy dark:text-slate-400 uppercase tracking-wider text-right transition-colors">Total Valor</th>
                                <th class="px-6 py-4 text-xs font-bold text-aldia-navy dark:text-slate-400 uppercase tracking-wider text-right transition-colors">Diferencia</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-aldia-borderLight dark:divide-slate-800/60 transition-colors">
                            @forelse($detalles as $det)
                                @php
                                    $diffU = $det->cantidad_fisica !== null ? ($det->cantidad_fisica - $det->existencia_sistema) : 0;
                                    $diffD = $det->cantidad_fisica !== null ? ($det->valor_total - ($det->existencia_sistema * $det->costo_sistema)) : 0;
                                    $hasDiff = $det->cantidad_fisica !== null && $diffU != 0;
                                @endphp
                                <tr class="hover:bg-aldia-bgLight/30 dark:hover:bg-slate-800/30 transition-colors {{ $hasDiff ? ($diffU > 0 ? 'bg-emerald-50/20 dark:bg-emerald-500/5' : 'bg-rose-50/20 dark:bg-rose-500/5') : '' }}">
                                    <td class="px-6 py-4 text-sm font-semibold text-aldia-navy dark:text-slate-100 whitespace-nowrap transition-colors">
                                        {{ $det->producto->codigo }}
                                        <span class="block text-[10px] text-aldia-textSec dark:text-slate-500 font-normal transition-colors">{{ $det->producto->codigo_barras }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-aldia-textMain dark:text-slate-300 transition-colors">
                                        <div class="font-bold text-aldia-navy dark:text-white transition-colors">{{ $det->producto->nombre }}</div>
                                        <div class="text-[11px] text-aldia-textSec dark:text-slate-400 transition-colors">{{ $det->producto->categoria }} | Marca: {{ $det->producto->marca }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-right font-medium text-aldia-textMain dark:text-slate-300 transition-colors">
                                        {{ number_format($det->existencia_sistema, 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-right text-aldia-textSec dark:text-slate-400 transition-colors">
                                        ${{ number_format($det->costo_sistema, 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-bold text-right text-aldia-navy dark:text-slate-200 transition-colors">
                                        {{ $det->cantidad_fisica !== null ? number_format($det->cantidad_fisica, 2) : '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-right font-semibold text-aldia-navy dark:text-slate-200 transition-colors">
                                        ${{ number_format($det->costo_contado ?? $det->costo_sistema, 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-bold text-right text-aldia-navy dark:text-slate-200 transition-colors">
                                        ${{ number_format($det->valor_total, 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-right font-bold whitespace-nowrap transition-colors {{ $diffU > 0 ? 'text-aldia-success dark:text-emerald-400' : ($diffU < 0 ? 'text-aldia-danger dark:text-rose-400' : 'text-aldia-textSec dark:text-slate-500') }}">
                                        @if ($det->cantidad_fisica !== null)
                                            {{ $diffU > 0 ? '+' : '' }}{{ number_format($diffU, 2) }} Uni.
                                            <span class="block text-[10px]">{{ $diffD > 0 ? '+' : '' }}${{ number_format($diffD, 2) }}</span>
                                        @else
                                            No contado
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center text-sm text-aldia-textSec dark:text-slate-500 transition-colors">
                                        No se encontraron registros.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 bg-aldia-bgLight/40 dark:bg-slate-800/40 border-t border-aldia-borderLight dark:border-slate-800/60 transition-colors">
                    {{ $detalles->links() }}
                </div>
            </div>
        </div>

        <!-- Tab 2: Movimientos de Ajuste -->
        <div x-show="tab === 'ajustes'">
            <div class="bg-white dark:bg-[#1E293B] border border-aldia-borderLight dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden transition-colors duration-200">
                <table class="min-w-full divide-y divide-aldia-borderLight dark:divide-slate-800/60 text-left transition-colors">
                    <thead class="bg-aldia-bgLight dark:bg-slate-800/50 transition-colors">
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold text-aldia-navy dark:text-slate-400 uppercase tracking-wider transition-colors">Código Producto</th>
                            <th class="px-6 py-4 text-xs font-bold text-aldia-navy dark:text-slate-400 uppercase tracking-wider transition-colors">Producto</th>
                            <th class="px-6 py-4 text-xs font-bold text-aldia-navy dark:text-slate-400 uppercase tracking-wider text-center transition-colors">Tipo</th>
                            <th class="px-6 py-4 text-xs font-bold text-aldia-navy dark:text-slate-400 uppercase tracking-wider text-right transition-colors">Cantidad</th>
                            <th class="px-6 py-4 text-xs font-bold text-aldia-navy dark:text-slate-400 uppercase tracking-wider transition-colors">Documento Origen</th>
                            <th class="px-6 py-4 text-xs font-bold text-aldia-navy dark:text-slate-400 uppercase tracking-wider transition-colors">Fecha / Hora</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-aldia-borderLight dark:divide-slate-800/60 transition-colors">
                        @forelse($ajustes as $ajuste)
                            <tr class="hover:bg-aldia-bgLight/30 dark:hover:bg-slate-800/30 transition-colors">
                                <td class="px-6 py-4 text-sm font-semibold text-aldia-navy dark:text-slate-100 whitespace-nowrap transition-colors">
                                    {{ $ajuste->producto->codigo }}
                                </td>
                                <td class="px-6 py-4 text-sm text-aldia-textMain dark:text-slate-300 transition-colors">
                                    {{ $ajuste->producto->nombre }}
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    @if ($ajuste->tipo === 'positivo')
                                        <span class="px-2 py-0.5 text-xs font-bold rounded bg-emerald-100 dark:bg-emerald-500/10 text-emerald-800 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20 transition-colors">Ajuste Positivo (+)</span>
                                    @else
                                        <span class="px-2 py-0.5 text-xs font-bold rounded bg-rose-100 dark:bg-rose-500/10 text-rose-800 dark:text-rose-400 border border-rose-200 dark:border-rose-500/20 transition-colors">Ajuste Negativo (-)</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm font-bold text-right text-aldia-navy dark:text-slate-200 transition-colors">
                                    {{ number_format($ajuste->cantidad, 2) }}
                                </td>
                                <td class="px-6 py-4 text-sm text-aldia-textMain dark:text-slate-400 font-mono transition-colors">
                                    {{ $ajuste->documento_origen }}
                                </td>
                                <td class="px-6 py-4 text-sm text-aldia-textSec dark:text-slate-500 transition-colors">
                                    {{ $ajuste->fecha_hora->format('d/m/Y h:i A') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-sm text-aldia-textSec dark:text-slate-500 transition-colors">
                                    No se generaron movimientos de ajuste para este inventario (todas las existencias coincidían perfectamente).
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tab 3: Bitácora de Auditoría -->
        <div x-show="tab === 'auditoria'">
            <div class="bg-white dark:bg-[#1E293B] border border-aldia-borderLight dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden transition-colors duration-200">
                <table class="min-w-full divide-y divide-aldia-borderLight dark:divide-slate-800/60 text-left transition-colors">
                    <thead class="bg-aldia-bgLight dark:bg-slate-800/50 transition-colors">
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold text-aldia-navy dark:text-slate-400 uppercase tracking-wider transition-colors">Fecha / Hora</th>
                            <th class="px-6 py-4 text-xs font-bold text-aldia-navy dark:text-slate-400 uppercase tracking-wider transition-colors">Operario</th>
                            <th class="px-6 py-4 text-xs font-bold text-aldia-navy dark:text-slate-400 uppercase tracking-wider transition-colors">Producto</th>
                            <th class="px-6 py-4 text-xs font-bold text-aldia-navy dark:text-slate-400 uppercase tracking-wider text-right transition-colors">Cant. Anterior</th>
                            <th class="px-6 py-4 text-xs font-bold text-aldia-navy dark:text-slate-400 uppercase tracking-wider text-right transition-colors">Cant. Nueva</th>
                            <th class="px-6 py-4 text-xs font-bold text-aldia-navy dark:text-slate-400 uppercase tracking-wider text-right transition-colors">Costo Anterior</th>
                            <th class="px-6 py-4 text-xs font-bold text-aldia-navy dark:text-slate-400 uppercase tracking-wider text-right transition-colors">Costo Nuevo</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-aldia-borderLight dark:divide-slate-800/60 transition-colors">
                        @forelse($auditorias as $aud)
                            <tr class="hover:bg-aldia-bgLight/30 dark:hover:bg-slate-800/30 transition-colors">
                                <td class="px-6 py-4 text-sm text-aldia-textMain dark:text-slate-300 whitespace-nowrap transition-colors">
                                    {{ $aud->fecha_hora->format('d/m/Y h:i A') }}
                                </td>
                                <td class="px-6 py-4 text-sm font-semibold text-aldia-navy dark:text-slate-100 whitespace-nowrap transition-colors">
                                    {{ $aud->usuario->name }}
                                </td>
                                <td class="px-6 py-4 text-sm text-aldia-textMain dark:text-slate-300 transition-colors">
                                    {{ $aud->producto->nombre }}
                                    <span class="block text-[10px] text-aldia-textSec dark:text-slate-500 transition-colors">{{ $aud->producto->codigo }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-right text-aldia-textSec dark:text-slate-400 transition-colors">
                                    {{ $aud->cantidad_anterior !== null ? number_format($aud->cantidad_anterior, 2) : '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-right font-bold text-aldia-navy dark:text-slate-200 transition-colors">
                                    {{ number_format($aud->cantidad_nueva, 2) }}
                                </td>
                                <td class="px-6 py-4 text-sm text-right text-aldia-textSec dark:text-slate-400 transition-colors">
                                    ${{ number_format($aud->costo_anterior, 2) }}
                                </td>
                                <td class="px-6 py-4 text-sm text-right font-bold text-aldia-navy dark:text-slate-200 transition-colors">
                                    ${{ number_format($aud->costo_nuevo, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-sm text-aldia-textSec dark:text-slate-500 transition-colors">
                                    No hay registros de auditoría de cambios.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
