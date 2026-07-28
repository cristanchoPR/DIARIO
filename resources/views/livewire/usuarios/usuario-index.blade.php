<div x-data="{
    open: false,
    loading: false,
    openNew() { this.open = true; this.loading = false; $wire.resetearFormulario(); },
    openEdit(id) { this.open = true; this.loading = true; $wire.editar(id).then(() => this.loading = false); },
    close() { this.open = false; }
}" @close-modal.window="close()">
    @section('title', 'Usuarios')

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl font-bold text-slate-900 dark:text-white transition-colors">Usuarios</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5 transition-colors">Gestiona usuarios y asignación de sedes</p>
        </div>
        @can('crear usuarios')
        <button @click="openNew()"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-aldia-primary hover:bg-aldia-primaryDark text-white text-sm font-semibold rounded-xl shadow-sm transition-all hover:shadow-md active:scale-95">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Nuevo Usuario
        </button>
        @endcan
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-[#1E293B] rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-4 mb-6 transition-colors duration-200">
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 dark:text-slate-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar por nombre o correo…"
                       class="w-full pl-9 pr-4 py-2 text-sm border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-aldia-primary/30 focus:border-aldia-primary bg-slate-50 dark:bg-slate-900/50 dark:text-white dark:placeholder-slate-500 transition-colors">
            </div>
            <select wire:model.live="filtroRol"
                    class="px-3 py-2 text-sm border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-aldia-primary/30 focus:border-aldia-primary bg-slate-50 dark:bg-slate-900/50 dark:text-white transition-colors min-w-[150px]">
                <option value="">Todos los roles</option>
                <option value="administrador">Administrador</option>
                <option value="usuario">Usuario</option>
            </select>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-[#1E293B] rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden transition-colors duration-200">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-slate-800/60 bg-slate-50/50 dark:bg-slate-800/50 transition-colors">
                        <th class="text-left px-5 py-3.5 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Usuario</th>
                        <th class="text-left px-5 py-3.5 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Rol</th>
                        <th class="text-left px-5 py-3.5 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Sedes Asignadas</th>
                        <th class="text-left px-5 py-3.5 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Registrado</th>
                        <th class="px-5 py-3.5"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 transition-colors">
                    @forelse($usuarios as $usuario)
                    <tr wire:key="usuario-{{ $usuario->id }}" class="hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-colors">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-aldia-primary flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                    {{ strtoupper(substr($usuario->name, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="font-semibold text-slate-900 dark:text-slate-100 transition-colors">{{ $usuario->name }}</div>
                                    <div class="text-xs text-slate-400 dark:text-slate-500 transition-colors">{{ $usuario->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            @php $rol = $usuario->getRoleNames()->first(); @endphp
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide transition-colors
                                {{ $rol === 'administrador' ? 'bg-aldia-primary/15 text-aldia-primaryDark dark:bg-aldia-primary/20 dark:text-aldia-primary' : 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400' }}">
                                {{ $rol ?? 'Sin rol' }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            @if($usuario->hasRole('administrador'))
                                <span class="text-xs text-slate-400 dark:text-slate-500 italic transition-colors">Acceso completo</span>
                            @elseif($usuario->sedes->count() > 0)
                                <div class="flex flex-wrap gap-1">
                                    @foreach($usuario->sedes->take(3) as $sede)
                                        <span class="px-2 py-0.5 text-[10px] font-semibold rounded-full bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400 transition-colors">{{ $sede->nombre }}</span>
                                    @endforeach
                                    @if($usuario->sedes->count() > 3)
                                        <span class="px-2 py-0.5 text-[10px] font-semibold rounded-full bg-slate-100 text-slate-400 dark:bg-slate-800 dark:text-slate-500 transition-colors">+{{ $usuario->sedes->count() - 3 }}</span>
                                    @endif
                                </div>
                            @else
                                <button @click="openEdit({{ $usuario->id }})" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg border border-dashed border-slate-300 dark:border-slate-600 text-xs text-slate-500 hover:text-aldia-primary hover:border-aldia-primary hover:bg-aldia-primary/5 transition-colors group">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                    Asignar sede
                                </button>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-xs text-slate-400 dark:text-slate-500 transition-colors">{{ $usuario->created_at->format('d/m/Y') }}</td>
                        <td class="px-5 py-4">
                            @can('editar usuarios')
                            <div class="flex items-center justify-end gap-2">
                                <button @click="openEdit({{ $usuario->id }})"
                                        class="p-1.5 text-slate-400 hover:text-aldia-primary hover:bg-aldia-primary/10 rounded-lg transition-colors" title="Editar">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                @if($usuario->id !== auth()->id())
                                @can('eliminar usuarios')
                                <button wire:click="eliminar({{ $usuario->id }})"
                                        wire:confirm="¿Eliminar este usuario permanentemente?"
                                        class="p-1.5 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-lg transition-colors" title="Eliminar">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                                @endcan
                                @endif
                            </div>
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-16 text-center">
                            <div class="flex flex-col items-center gap-2">
                                <svg class="w-8 h-8 text-slate-300 dark:text-slate-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                <span class="text-sm text-slate-400 dark:text-slate-500 transition-colors">No se encontraron usuarios</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($usuarios->hasPages())
        <div class="px-5 py-4 border-t border-slate-100">{{ $usuarios->links() }}</div>
        @endif
    </div>

    <!-- Modal con animación premium -->
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
             class="relative bg-white dark:bg-[#1E293B] rounded-t-3xl sm:rounded-3xl shadow-2xl w-full sm:max-w-lg max-h-[90vh] overflow-y-auto transition-colors duration-200">

            <!-- Spinner carga edición -->
            <div x-show="loading" class="absolute inset-0 bg-white/80 dark:bg-[#1E293B]/80 backdrop-blur-sm flex items-center justify-center rounded-3xl z-10 transition-colors">
                <div class="flex flex-col items-center gap-3">
                    <svg class="animate-spin w-8 h-8 text-aldia-primary" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Cargando datos…</span>
                </div>
            </div>

            <div class="flex items-center justify-between p-6 border-b border-slate-100 dark:border-slate-800 transition-colors">
                <div>
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white transition-colors">{{ $editando ? 'Editar Usuario' : 'Nuevo Usuario' }}</h2>
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5 transition-colors">{{ $editando ? 'Actualiza datos, rol y sedes' : 'Completa los datos del nuevo usuario' }}</p>
                </div>
                <button @click="close()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors p-1 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form wire:submit="guardar" class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5 transition-colors">Nombre completo *</label>
                    <input wire:model="name" type="text" placeholder="Nombre del usuario"
                           class="w-full px-3 py-2.5 text-sm border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-aldia-primary/30 focus:border-aldia-primary bg-white dark:bg-slate-900/50 dark:text-white dark:placeholder-slate-500 transition-colors">
                    @error('name') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5 transition-colors">Correo electrónico *</label>
                    <input wire:model="email" type="email" placeholder="correo@empresa.com"
                           class="w-full px-3 py-2.5 text-sm border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-aldia-primary/30 focus:border-aldia-primary bg-white dark:bg-slate-900/50 dark:text-white dark:placeholder-slate-500 transition-colors">
                    @error('email') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5 transition-colors">
                        Contraseña {{ $editando ? '(dejar vacío para no cambiar)' : '*' }}
                    </label>
                    <input wire:model="password" type="password" placeholder="{{ $editando ? 'Nueva contraseña (opcional)' : 'Mínimo 6 caracteres' }}"
                           class="w-full px-3 py-2.5 text-sm border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-aldia-primary/30 focus:border-aldia-primary bg-white dark:bg-slate-900/50 dark:text-white dark:placeholder-slate-500 transition-colors">
                    @error('password') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>
                @if(auth()->user()->esAdministrador())
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5 transition-colors">Rol *</label>
                    <select wire:model.live="rol" class="w-full px-3 py-2.5 text-sm border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-aldia-primary/30 focus:border-aldia-primary bg-white dark:bg-slate-900/50 dark:text-white transition-colors">
                        <option value="usuario">Operario — Inventarios y reportes</option>
                        <option value="admin_sede">Admin Sede — Gestión de sucursal</option>
                        <option value="administrador">Súper Administrador — Acceso total</option>
                    </select>
                </div>
                @else
                <div class="hidden">
                    <input type="hidden" wire:model="rol" value="usuario">
                </div>
                @endif

                {{-- Asignación de sedes (para usuario y admin_sede) --}}
                <div x-data="{ get showSedes() { return $wire.rol !== 'administrador' } }">
                    <div x-show="showSedes" x-cloak>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2 transition-colors">Sedes asignadas</label>
                        <div class="space-y-2 max-h-40 overflow-y-auto border border-slate-200 dark:border-slate-700 rounded-xl p-3 bg-slate-50 dark:bg-slate-900/50 transition-colors">
                            @foreach($sedes as $sede)
                            <label class="flex items-center gap-2.5 cursor-pointer hover:bg-white dark:hover:bg-slate-800 rounded-lg px-2 py-1.5 transition-colors">
                                <input type="checkbox" wire:model="sedesSeleccionadas" value="{{ $sede->id }}"
                                       class="rounded border-slate-300 dark:border-slate-600 text-aldia-primary focus:ring-aldia-primary/30 h-4 w-4 bg-white dark:bg-slate-900/50 transition-colors">
                                <div class="flex items-center gap-2">
                                    <div class="w-5 h-5 rounded flex items-center justify-center text-white text-[9px] font-bold"
                                         style="background-color: {{ $sede->color ?? '#5A8FDB' }}">
                                        {{ strtoupper(substr($sede->nombre, 0, 2)) }}
                                    </div>
                                    <span class="text-sm font-medium text-slate-700 dark:text-slate-200 transition-colors">{{ $sede->nombre }}</span>
                                </div>
                            </label>
                            @endforeach
                        </div>
                        <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1 transition-colors">El usuario gestionará únicamente las sedes marcadas.</p>
                    </div>

                    <div x-show="!showSedes" x-cloak class="flex items-center gap-2 p-3 bg-aldia-primary/5 dark:bg-aldia-primary/10 border border-aldia-primary/20 dark:border-aldia-primary/30 rounded-xl transition-colors">
                        <svg class="w-4 h-4 text-aldia-primary flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="text-xs text-aldia-primaryDark dark:text-aldia-primary font-medium transition-colors">El Administrador tiene acceso a todas las sedes automáticamente.</span>
                    </div>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" @click="close()"
                            class="flex-1 py-2.5 text-sm font-semibold text-slate-600 dark:text-slate-300 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 rounded-xl transition-colors">
                        Cancelar
                    </button>
                    <button type="submit"
                            class="flex-1 py-2.5 text-sm font-semibold text-white bg-aldia-primary hover:bg-aldia-primaryDark rounded-xl transition-colors shadow-sm">
                        {{ $editando ? 'Guardar Cambios' : 'Crear Usuario' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

