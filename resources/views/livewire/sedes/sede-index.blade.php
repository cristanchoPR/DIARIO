<div x-data="{
    open: false,
    loading: false,
    openNew() { this.open = true; this.loading = false; $wire.resetearFormulario(); },
    openEdit(id) { this.open = true; this.loading = true; $wire.editar(id).then(() => this.loading = false); },
    close() { this.open = false; }
}" @close-modal.window="close()">
    @section('title', 'Sedes')

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl font-bold text-slate-900 dark:text-white transition-colors">Sedes</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5 transition-colors">Gestiona las sedes de la organización</p>
        </div>
        @can('crear sedes')
        <button @click="openNew()"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-aldia-primary hover:bg-aldia-primaryDark text-white text-sm font-semibold rounded-xl shadow-sm transition-all hover:shadow-aldia-primary/25 hover:shadow-md active:scale-95">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Nueva Sede
        </button>
        @endcan
    </div>

    <!-- Filters Bar -->
    <div class="bg-white dark:bg-[#1E293B] rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-4 mb-6 transition-colors duration-200">
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 dark:text-slate-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar por nombre o NIT…"
                       class="w-full pl-9 pr-4 py-2 text-sm border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-aldia-primary/30 focus:border-aldia-primary bg-slate-50 dark:bg-slate-900/50 dark:text-white dark:placeholder-slate-500 transition-colors">
            </div>
            <select wire:model.live="filtroEstado"
                    class="px-3 py-2 text-sm border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-aldia-primary/30 focus:border-aldia-primary bg-slate-50 dark:bg-slate-900/50 dark:text-white transition-colors min-w-[130px]">
                <option value="">Todas las sedes</option>
                <option value="activa">Activas</option>
                <option value="inactiva">Inactivas</option>
            </select>
        </div>
    </div>

    <!-- Cards Grid -->
    @if($sedes->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 mb-6">
        @foreach($sedes as $sede)
        <div wire:key="sede-{{ $sede->id }}" class="bg-white dark:bg-[#1E293B] rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden hover:shadow-md hover:border-slate-300 dark:hover:border-slate-600 transition-all duration-200 group">
            <!-- Card color strip -->
            <div class="h-1.5 w-full" style="background-color: {{ $sede->color ?? '#5A8FDB' }}"></div>

            <div class="p-5">
                <!-- Header -->
                <div class="flex items-start justify-between gap-2 mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white font-bold text-sm flex-shrink-0"
                             style="background-color: {{ $sede->color ?? '#5A8FDB' }}">
                            {{ strtoupper(substr($sede->nombre, 0, 2)) }}
                        </div>
                        <div class="min-w-0">
                            <h3 class="font-bold text-slate-900 dark:text-slate-100 text-sm truncate transition-colors">{{ $sede->nombre }}</h3>
                            @if($sede->nit)
                            <p class="text-xs text-slate-400 dark:text-slate-500 font-mono transition-colors">NIT: {{ $sede->nit }}</p>
                            @endif
                        </div>
                    </div>
                    <!-- Status badge -->
                    <span class="flex-shrink-0 px-2 py-0.5 rounded-full text-[10px] font-bold transition-colors {{ $sede->estado ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400' : 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400' }}">
                        {{ $sede->estado ? 'Activa' : 'Inactiva' }}
                    </span>
                </div>

                <!-- Meta info -->
                <div class="space-y-1.5 mb-4">
                    @if($sede->direccion)
                    <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 transition-colors">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span class="truncate">{{ $sede->direccion }}</span>
                    </div>
                    @endif
                    @if($sede->telefono)
                    <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 transition-colors">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <span>{{ $sede->telefono }}</span>
                    </div>
                    @endif
                </div>

                <!-- Stats row -->
                <div class="flex items-center gap-4 py-3 border-t border-slate-100 dark:border-slate-800 transition-colors">
                    <div class="flex items-center gap-1.5 text-xs text-slate-500 dark:text-slate-400 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
                        <span><strong class="text-slate-800 dark:text-slate-200">{{ $sede->usuarios_count }}</strong> usuarios</span>
                    </div>
                    <div class="flex items-center gap-1.5 text-xs text-slate-500 dark:text-slate-400 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        <span><strong class="text-slate-800 dark:text-slate-200">{{ $sede->inventarios_count }}</strong> inventarios</span>
                    </div>
                </div>

                <!-- Actions -->
                @can('editar sedes')
                <div class="flex items-center gap-2 mt-3">
                    <button @click="openEdit({{ $sede->id }})"
                            class="flex-1 flex items-center justify-center gap-1.5 py-2 text-xs font-semibold text-aldia-primaryDark bg-aldia-primary/10 hover:bg-aldia-primary/20 dark:text-aldia-primary dark:bg-aldia-primary/20 dark:hover:bg-aldia-primary/30 rounded-lg transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Editar
                    </button>
                    <button wire:click="toggleEstado({{ $sede->id }})"
                            class="px-3 py-2 text-xs font-semibold rounded-lg transition-colors {{ $sede->estado ? 'text-amber-700 bg-amber-50 hover:bg-amber-100 dark:text-amber-400 dark:bg-amber-500/10 dark:hover:bg-amber-500/20' : 'text-emerald-700 bg-emerald-50 hover:bg-emerald-100 dark:text-emerald-400 dark:bg-emerald-500/10 dark:hover:bg-emerald-500/20' }}">
                        {{ $sede->estado ? 'Desactivar' : 'Activar' }}
                    </button>
                    @can('eliminar sedes')
                    <button wire:click="eliminar({{ $sede->id }})"
                            wire:confirm="¿Estás seguro de que deseas eliminar esta sede? Esta acción no se puede deshacer."
                            class="px-3 py-2 text-xs font-semibold text-rose-700 bg-rose-50 hover:bg-rose-100 dark:text-rose-400 dark:bg-rose-500/10 dark:hover:bg-rose-500/20 rounded-lg transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                    @endcan
                </div>
                @endcan
            </div>
        </div>
        @endforeach
    </div>
    {{ $sedes->links() }}
    @else
    <!-- Empty state -->
    <div class="flex flex-col items-center justify-center py-20 text-center">
        <div class="w-16 h-16 rounded-2xl bg-aldia-primary/10 dark:bg-aldia-primary/20 flex items-center justify-center mb-4 transition-colors">
            <svg class="w-8 h-8 text-aldia-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
        </div>
        <h3 class="font-bold text-slate-700 dark:text-slate-300 mb-1 transition-colors">No hay sedes registradas</h3>
        <p class="text-sm text-slate-400 dark:text-slate-500 mb-4 transition-colors">Crea la primera sede para empezar a gestionar inventarios</p>
        @can('crear sedes')
        <button @click="openNew()" class="px-4 py-2 bg-aldia-primary text-white text-sm font-semibold rounded-xl hover:bg-aldia-primaryDark transition-colors">
            Crear primera sede
        </button>
        @endcan
    </div>
    @endif


    <!-- Modal con animación premium -->
    <div
        x-show="open"
        x-cloak
        class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4"
        style="display:none;"
    >
        <!-- Backdrop con fade -->
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="absolute inset-0 bg-black/50 backdrop-blur-sm"
            @click="close()"
        ></div>

        <!-- Panel con slide-up + scale -->
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-8 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-8 scale-95"
            class="relative bg-white dark:bg-[#1E293B] rounded-t-3xl sm:rounded-3xl shadow-2xl w-full sm:max-w-lg max-h-[90vh] overflow-y-auto transition-colors duration-200"
        >
            <div class="flex items-center justify-between p-6 border-b border-slate-100 dark:border-slate-800 transition-colors">
                <div>
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white transition-colors">{{ $editando ? 'Editar Sede' : 'Nueva Sede' }}</h2>
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5 transition-colors">{{ $editando ? 'Actualiza los datos de la sede' : 'Completa los datos para registrar una sede' }}</p>
                </div>
                <button @click="close()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors p-1 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Modal Body -->
            <form wire:submit="guardar" class="p-6 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5 transition-colors">Nombre *</label>
                        <input wire:model="nombre" type="text" placeholder="Nombre de la sede"
                               class="w-full px-3 py-2.5 text-sm border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-aldia-primary/30 focus:border-aldia-primary bg-white dark:bg-slate-900/50 dark:text-white dark:placeholder-slate-500 transition-colors">
                        @error('nombre') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5 transition-colors">NIT</label>
                        <input wire:model="nit" type="text" placeholder="900.000.000-0"
                               class="w-full px-3 py-2.5 text-sm border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-aldia-primary/30 focus:border-aldia-primary bg-white dark:bg-slate-900/50 dark:text-white dark:placeholder-slate-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5 transition-colors">Teléfono</label>
                        <input wire:model="telefono" type="text" placeholder="+57 300 000 0000"
                               class="w-full px-3 py-2.5 text-sm border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-aldia-primary/30 focus:border-aldia-primary bg-white dark:bg-slate-900/50 dark:text-white dark:placeholder-slate-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5 transition-colors">Correo</label>
                        <input wire:model="email" type="email" placeholder="sede@empresa.com"
                               class="w-full px-3 py-2.5 text-sm border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-aldia-primary/30 focus:border-aldia-primary bg-white dark:bg-slate-900/50 dark:text-white dark:placeholder-slate-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5 transition-colors">Color de Sede</label>
                        <div class="flex items-center gap-2">
                            <input wire:model.live="color" type="color" class="w-10 h-10 rounded-lg border border-slate-200 dark:border-slate-700 cursor-pointer p-1 bg-white dark:bg-slate-900/50 transition-colors">
                            <span class="text-sm text-slate-500 dark:text-slate-400 font-mono transition-colors">{{ $color }}</span>
                        </div>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5 transition-colors">Dirección</label>
                        <input wire:model="direccion" type="text" placeholder="Calle 0 # 0 - 0"
                               class="w-full px-3 py-2.5 text-sm border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-aldia-primary/30 focus:border-aldia-primary bg-white dark:bg-slate-900/50 dark:text-white dark:placeholder-slate-500 transition-colors">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5 transition-colors">Descripción</label>
                        <textarea wire:model="descripcion" rows="2" placeholder="Descripción opcional de la sede"
                                  class="w-full px-3 py-2.5 text-sm border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-aldia-primary/30 focus:border-aldia-primary resize-none bg-white dark:bg-slate-900/50 dark:text-white dark:placeholder-slate-500 transition-colors"></textarea>
                    </div>
                    <div class="flex items-center gap-3">
                        <input wire:model="estado" type="checkbox" id="estado_sede" class="rounded border-slate-300 dark:border-slate-700 text-aldia-primary focus:ring-aldia-primary/30 h-4 w-4 bg-white dark:bg-slate-900/50 transition-colors">
                        <label for="estado_sede" class="text-sm font-medium text-slate-700 dark:text-slate-300 transition-colors">Sede activa</label>
                    </div>
                </div>

                    <!-- Spinner de carga para edición -->
                    <div x-show="loading" class="absolute inset-0 bg-white/80 dark:bg-[#1E293B]/80 backdrop-blur-sm flex items-center justify-center rounded-3xl z-10 transition-colors">
                        <div class="flex flex-col items-center gap-3">
                            <svg class="animate-spin w-8 h-8 text-aldia-primary" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                            <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Cargando datos…</span>
                        </div>
                    </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" @click="close()"
                            class="flex-1 py-2.5 text-sm font-semibold text-slate-600 dark:text-slate-300 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 rounded-xl transition-colors">
                        Cancelar
                    </button>
                    <button type="submit"
                            class="flex-1 py-2.5 text-sm font-semibold text-white bg-aldia-primary hover:bg-aldia-primaryDark rounded-xl transition-colors shadow-sm">
                        {{ $editando ? 'Guardar Cambios' : 'Crear Sede' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

