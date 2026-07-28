<div>
    @section('title', 'Panel Principal')

    <!-- Floating Bubbles for Dashboard Background -->
    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden opacity-50 dark:opacity-20">
        <ul class="sidebar-bubbles">
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
        </ul>
    </div>

    <div class="relative z-10">

    <!-- Greeting Header -->
    <div class="mb-6">
        <h1 class="text-xl font-bold text-slate-900 dark:text-white transition-colors duration-200">
            Bienvenido, {{ explode(' ', auth()->user()->name)[0] }} !!
        </h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5 transition-colors duration-200">
            {{ ucfirst(now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY')) }} — Vista general del sistema
        </p>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Sedes -->
        <div class="bg-white dark:bg-[#1E293B] rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-5 hover:shadow-md transition-all duration-200">
            <div class="flex items-start justify-between mb-4">
                <div class="w-10 h-10 rounded-xl bg-aldia-primary/10 flex items-center justify-center">
                    <svg class="w-5 h-5 text-aldia-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
                </div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-600 bg-emerald-50 dark:bg-emerald-500/10 dark:text-emerald-400 px-2 py-0.5 rounded-full transition-colors">{{ $sedesActivas }} activas</span>
            </div>
            <div class="text-3xl font-black text-slate-900 dark:text-white transition-colors">{{ $totalSedes }}</div>
            <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-1 transition-colors">Sedes {{ auth()->user()->esAdministrador() ? 'registradas' : 'asignadas' }}</div>
        </div>

        @if(auth()->user()->esAdministrador())
        <!-- Productos (solo admin) -->
        <div class="bg-white dark:bg-[#1E293B] rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-5 hover:shadow-md transition-all duration-200">
            <div class="flex items-start justify-between mb-4">
                <div class="w-10 h-10 rounded-xl bg-purple-50 dark:bg-purple-500/10 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5 text-purple-500 dark:text-purple-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/></svg>
                </div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-purple-600 bg-purple-50 dark:bg-purple-500/10 dark:text-purple-400 px-2 py-0.5 rounded-full transition-colors">Catálogo</span>
            </div>
            <div class="text-3xl font-black text-slate-900 dark:text-white transition-colors">{{ $totalProductos }}</div>
            <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-1 transition-colors">Productos registrados</div>
        </div>

        <!-- Usuarios (solo admin) -->
        <div class="bg-white dark:bg-[#1E293B] rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-5 hover:shadow-md transition-all duration-200">
            <div class="flex items-start justify-between mb-4">
                <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-500/10 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5 text-amber-500 dark:text-amber-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-amber-600 bg-amber-50 dark:bg-amber-500/10 dark:text-amber-400 px-2 py-0.5 rounded-full transition-colors">Sistema</span>
            </div>
            <div class="text-3xl font-black text-slate-900 dark:text-white transition-colors">{{ $totalUsuarios }}</div>
            <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-1 transition-colors">Usuarios del sistema</div>
        </div>
        @endif

        <!-- Inventarios Hoy -->
        <div class="bg-white dark:bg-[#1E293B] rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-5 hover:shadow-md transition-all duration-200">
            <div class="flex items-start justify-between mb-4">
                <div class="w-10 h-10 rounded-xl bg-sky-50 dark:bg-sky-500/10 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5 text-sky-500 dark:text-sky-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-sky-600 bg-sky-50 dark:bg-sky-500/10 dark:text-sky-400 px-2 py-0.5 rounded-full transition-colors">Hoy</span>
            </div>
            <div class="text-3xl font-black text-slate-900 dark:text-white transition-colors">{{ $inventariosHoy }}</div>
            <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-1 transition-colors">Inventarios creados hoy</div>
        </div>

        <!-- Inventarios Total -->
        <div class="bg-white dark:bg-[#1E293B] rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-5 hover:shadow-md transition-all duration-200">
            <div class="flex items-start justify-between mb-4">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5 text-emerald-500 dark:text-emerald-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="flex items-end gap-3">
                <div>
                    <div class="text-3xl font-black text-slate-900 dark:text-white transition-colors">{{ $inventariosFinalizados }}</div>
                    <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-1 transition-colors">Aplicados</div>
                </div>
                <div class="text-slate-300 dark:text-slate-600 text-2xl font-light mb-1 transition-colors">/</div>
                <div>
                    <div class="text-2xl font-black text-slate-400 dark:text-slate-500 transition-colors">{{ $inventariosTotal }}</div>
                    <div class="text-xs font-semibold text-slate-400 dark:text-slate-500 mt-1 transition-colors">Total</div>
                </div>
            </div>
        </div>

        <!-- Pendientes -->
        <div class="bg-white dark:bg-[#1E293B] rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-5 hover:shadow-md transition-all duration-200">
            <div class="flex items-start justify-between mb-4">
                <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-500/10 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5 text-amber-500 dark:text-amber-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="text-3xl font-black text-slate-900 dark:text-white transition-colors">{{ $inventariosPendientes }}</div>
            <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-1 transition-colors">En proceso / Borradores</div>
        </div>
    </div>

    <!-- Bottom Grid: Sedes + Actividad -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

        <!-- Sedes Quick Access -->
        <div class="bg-white dark:bg-[#1E293B] rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden transition-colors duration-200">
            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 dark:border-slate-800/60 transition-colors">
                <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">Sedes</h2>
                <a href="{{ route('inventarios.index') }}" class="text-xs font-semibold text-aldia-primary hover:text-aldia-primaryDark dark:hover:text-aldia-warm transition-colors" wire:navigate>Ver inventarios →</a>
            </div>
            <div class="divide-y divide-slate-100 dark:divide-slate-800/60 transition-colors">
                @forelse($sedes as $sede)
                <div class="flex items-center gap-3 px-5 py-3 hover:bg-slate-50/60 dark:hover:bg-slate-800/50 transition-colors">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white font-bold text-xs flex-shrink-0"
                         style="background-color: {{ $sede->color ?? '#5A8FDB' }}">
                        {{ strtoupper(substr($sede->nombre, 0, 2)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-semibold text-slate-800 dark:text-slate-200 truncate transition-colors">{{ $sede->nombre }}</div>
                        <div class="text-xs text-slate-400 dark:text-slate-500 transition-colors">{{ $sede->inventarios_count }} inventario(s)</div>
                    </div>
                    <span class="px-2 py-0.5 text-[10px] font-bold rounded-full transition-colors {{ $sede->estado ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400' : 'bg-slate-100 text-slate-500 dark:bg-slate-700/50 dark:text-slate-400' }}">
                        {{ $sede->estado ? 'Activa' : 'Inactiva' }}
                    </span>
                </div>
                @empty
                <div class="px-5 py-8 text-center text-sm text-slate-400 dark:text-slate-500 transition-colors">No hay sedes disponibles.</div>
                @endforelse
            </div>
        </div>

        <!-- Actividad Reciente -->
        <div class="bg-white dark:bg-[#1E293B] rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden transition-colors duration-200">
            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 dark:border-slate-800/60 transition-colors">
                <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">Actividad Reciente</h2>
                <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider transition-colors">Últimas acciones</span>
            </div>
            <div class="divide-y divide-slate-100 dark:divide-slate-800/60 transition-colors">
                @forelse($ultimasAuditorias as $auditoria)
                <div class="flex items-start gap-3 px-5 py-3 hover:bg-slate-50/40 dark:hover:bg-slate-800/30 transition-colors">
                    <div class="w-7 h-7 rounded-full bg-aldia-primary/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-3.5 h-3.5 text-aldia-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs text-slate-700 dark:text-slate-300 transition-colors">
                            <span class="font-semibold text-slate-900 dark:text-white">{{ is_object($auditoria->usuario) ? $auditoria->usuario->name : ($auditoria->usuario ?? 'Sistema') }}</span>
                            actualizó el producto 
                            <span class="font-semibold text-slate-900 dark:text-white">{{ $auditoria->producto?->nombre ?? '' }}</span>
                            en el conteo de
                            <span class="font-semibold text-slate-900 dark:text-white">{{ $auditoria->inventario?->sede?->nombre ?? 'Sede' }}</span>
                        </p>
                        <div class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5 transition-colors">{{ $auditoria->created_at->diffForHumans() }}</div>
                    </div>
                </div>
                @empty
                <div class="px-5 py-8 text-center text-sm text-slate-400 dark:text-slate-500 transition-colors">
                    <svg class="w-8 h-8 text-slate-300 dark:text-slate-600 mx-auto mb-2 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Sin actividad reciente
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
</div>
