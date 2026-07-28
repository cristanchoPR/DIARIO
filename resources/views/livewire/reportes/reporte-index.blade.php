<div>
    @section('title', 'Reportes')

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl font-bold text-slate-900 dark:text-white transition-colors">Reportes y Respaldos</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5 transition-colors">Genera y descarga reportes de inventarios aplicados</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-[#1E293B] rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-4 mb-6 transition-colors duration-200">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <select wire:model.live="filtroSede"
                    class="px-3 py-2 text-sm border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-aldia-primary/30 focus:border-aldia-primary bg-slate-50 dark:bg-slate-900/50 text-slate-900 dark:text-white transition-colors">
                <option value="">Todas las sedes</option>
                @foreach($sedes as $sede)
                    <option value="{{ $sede->id }}">{{ $sede->nombre }}</option>
                @endforeach
            </select>
            <input wire:model.live="filtroFechaDesde" type="date"
                   class="px-3 py-2 text-sm border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-aldia-primary/30 focus:border-aldia-primary bg-slate-50 dark:bg-slate-900/50 text-slate-900 dark:text-white transition-colors">
            <input wire:model.live="filtroFechaHasta" type="date"
                   class="px-3 py-2 text-sm border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-aldia-primary/30 focus:border-aldia-primary bg-slate-50 dark:bg-slate-900/50 text-slate-900 dark:text-white transition-colors">
            <button wire:click="$set('filtroSede', ''); $set('filtroFechaDesde', ''); $set('filtroFechaHasta', '')"
                    class="px-3 py-2 text-sm font-semibold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-xl transition-colors">
                Limpiar filtros
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-[#1E293B] rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden transition-colors duration-200">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-slate-800/60 bg-slate-50/50 dark:bg-slate-800/50 transition-colors">
                        <th class="text-left px-5 py-3.5 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 transition-colors">#</th>
                        <th class="text-left px-5 py-3.5 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 transition-colors">Sede</th>
                        <th class="text-left px-5 py-3.5 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 transition-colors">Estado</th>
                        <th class="text-left px-5 py-3.5 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 transition-colors">Fecha</th>
                        <th class="text-left px-5 py-3.5 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 transition-colors">Productos</th>
                        <th class="px-5 py-3.5 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-right transition-colors">Exportar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 transition-colors">
                    @forelse($inventarios as $inventario)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                        <td class="px-5 py-4 text-xs text-slate-400 font-mono">#{{ str_pad($inventario->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded flex items-center justify-center text-white text-[9px] font-bold"
                                     style="background-color: {{ $inventario->sede?->color ?? '#5A8FDB' }}">
                                    {{ strtoupper(substr($inventario->sede?->nombre ?? 'S', 0, 2)) }}
                                </div>
                                <span class="font-semibold text-slate-800 dark:text-slate-200 transition-colors">{{ $inventario->sede?->nombre ?? '—' }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide rounded-full bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 transition-colors">
                                Aplicado
                            </span>
                        </td>
                        <td class="px-5 py-4 text-xs text-slate-500 dark:text-slate-400 transition-colors">{{ $inventario->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-5 py-4 text-sm font-semibold text-slate-700 dark:text-slate-300 transition-colors">{{ $inventario->detalles?->count() ?? 0 }}</td>
                        <td class="px-5 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <!-- Ver detalle -->
                                <a href="{{ route('inventarios.show', $inventario->id) }}" wire:navigate
                                   class="p-1.5 text-slate-400 dark:text-slate-500 hover:text-aldia-primary dark:hover:text-aldia-primary hover:bg-aldia-primary/10 rounded-lg transition-colors" title="Ver detalle">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <!-- Excel -->
                                <a href="{{ route('inventarios.export.excel', $inventario->id) }}" target="_blank"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 text-[11px] font-bold text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10 hover:bg-emerald-100 dark:hover:bg-emerald-500/20 rounded-lg transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    Excel
                                </a>
                                <!-- PDF -->
                                <a href="{{ route('inventarios.export.pdf', $inventario->id) }}" target="_blank"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 text-[11px] font-bold text-rose-700 dark:text-rose-400 bg-rose-50 dark:bg-rose-500/10 hover:bg-rose-100 dark:hover:bg-rose-500/20 rounded-lg transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                    PDF
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-16 text-center">
                            <div class="flex flex-col items-center gap-2">
                                <svg class="w-10 h-10 text-slate-300 dark:text-slate-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <p class="text-sm text-slate-400 dark:text-slate-500 transition-colors">No hay reportes disponibles con los filtros actuales.</p>
                                <p class="text-xs text-slate-300 dark:text-slate-600 transition-colors">Los reportes se generan a partir de inventarios aplicados.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($inventarios->hasPages())
        <div class="px-5 py-4 border-t border-slate-100 dark:border-slate-800/60 transition-colors">{{ $inventarios->links() }}</div>
        @endif
    </div>
</div>
