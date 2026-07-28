<div x-data="{ contrast: false, blind: false }">
<style>
    /* === MODO CONTRASTE AZUL (estilo del sistema Aldia) === */
    .high-contrast-mode {
        background-color: #0B132B !important;
        color: #E2E8F0 !important;
    }

    /* Paneles, tarjetas y contenedores */
    .high-contrast-mode > div,
    .high-contrast-mode .bg-white,
    .high-contrast-mode .bg-aldia-bgLight,
    .high-contrast-mode .bg-aldia-bgCard {
        background-color: #0F1E3D !important;
        border-color: #1E3A6A !important;
    }

    /* Encabezado principal */
    .high-contrast-mode [class*="rounded-2xl"],
    .high-contrast-mode [class*="rounded-xl"],
    .high-contrast-mode [class*="rounded-3xl"] {
        background-color: #0F1E3D !important;
        border-color: #1E3A6A !important;
    }

    /* Tabla */
    .high-contrast-mode table {
        background-color: #0B132B !important;
    }
    .high-contrast-mode thead tr,
    .high-contrast-mode thead th {
        background-color: #091024 !important;
        color: #7CB3F5 !important;
        border-color: #1E3A6A !important;
    }
    .high-contrast-mode tbody tr {
        background-color: #0B132B !important;
        border-color: #1A2E55 !important;
    }
    .high-contrast-mode tbody tr:hover {
        background-color: #102248 !important;
    }
    .high-contrast-mode td,
    .high-contrast-mode th {
        color: #E2E8F0 !important;
        border-color: #1E3A6A !important;
    }

    /* Inputs y selects */
    .high-contrast-mode input,
    .high-contrast-mode select {
        background-color: #0F1E3D !important;
        color: #FFFFFF !important;
        border: 2px solid #3B7DD8 !important;
    }
    .high-contrast-mode input:focus,
    .high-contrast-mode select:focus {
        border-color: #7CB3F5 !important;
        outline: none;
        box-shadow: 0 0 0 3px rgba(90, 143, 219, 0.3) !important;
    }
    .high-contrast-mode input::placeholder {
        color: #5A7BA8 !important;
    }

    /* Botones */
    .high-contrast-mode button {
        background-color: #1E3A6A !important;
        color: #FFFFFF !important;
        border: 1.5px solid #3B7DD8 !important;
    }
    .high-contrast-mode button:hover {
        background-color: #2A4F8A !important;
        border-color: #7CB3F5 !important;
    }

    /* Iconos */
    .high-contrast-mode svg {
        color: #7CB3F5 !important;
    }

    /* Textos de colores especiales */
    .high-contrast-mode .text-aldia-success,
    .high-contrast-mode .text-emerald-500,
    .high-contrast-mode .text-emerald-400,
    .high-contrast-mode .text-[#2FAE7A],
    .high-contrast-mode .text-emerald-800 {
        color: #34D399 !important;
    }
    .high-contrast-mode .text-aldia-danger,
    .high-contrast-mode .text-rose-500,
    .high-contrast-mode .text-rose-400,
    .high-contrast-mode .text-[#E5584D],
    .high-contrast-mode .text-rose-800 {
        color: #FB7185 !important;
    }

    /* Badges y etiquetas */
    .high-contrast-mode span[class*="rounded-full"],
    .high-contrast-mode span[class*="bg-slate"],
    .high-contrast-mode span[class*="bg-emerald"],
    .high-contrast-mode span[class*="bg-amber"],
    .high-contrast-mode span[class*="bg-rose"] {
        background-color: #1A2E55 !important;
        color: #A8C5F0 !important;
        border-color: #2A4F8A !important;
    }

    /* Barra de progreso y footer */
    .high-contrast-mode [class*="bg-aldia-primary"] {
        background-color: #3B7DD8 !important;
    }
    .high-contrast-mode .bg-aldia-navy {
        background-color: #091024 !important;
    }
</style>
    <div class="py-8 font-sans min-h-screen" :class="contrast ? 'high-contrast-mode' : 'bg-aldia-bgLight text-aldia-textMain'">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Barra de Acciones Superior -->
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-8 bg-aldia-bgCard dark:bg-[#1E293B] border border-aldia-borderLight dark:border-slate-800 rounded-2xl shadow-sm p-6 transition-colors duration-200">
                <div class="flex items-center gap-4">
                    <a href="{{ route('inventarios.index') }}" wire:navigate class="p-2 bg-aldia-bgLight dark:bg-slate-800 hover:bg-aldia-borderLight dark:hover:bg-slate-700 rounded-xl text-aldia-navy dark:text-slate-300 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-2xl font-extrabold text-aldia-navy dark:text-white tracking-tight transition-colors">{{ $inventario->nombre }}</h1>
                            @if ($inventario->estado === 'en_elaboracion')
                                <span class="px-2.5 py-0.5 text-xs font-bold rounded-full bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-400 border border-slate-200 dark:border-slate-700 transition-colors">En elaboración</span>
                            @elseif ($inventario->estado === 'guardado')
                                <span class="px-2.5 py-0.5 text-xs font-bold rounded-full bg-aldia-primary/10 dark:bg-aldia-primary/20 text-aldia-primary border border-aldia-primary/20 dark:border-aldia-primary/30 transition-colors">Guardado</span>
                            @elseif ($inventario->estado === 'finalizado')
                                <span class="px-2.5 py-0.5 text-xs font-bold rounded-full bg-[#fdf2e2] dark:bg-amber-500/10 text-amber-800 dark:text-amber-400 border border-aldia-warm dark:border-amber-500/20 transition-colors">Finalizado</span>
                            @endif
                        </div>
                        <p class="text-xs text-aldia-textSec dark:text-slate-400 mt-1 transition-colors">Sede: <span class="font-bold text-aldia-navy dark:text-slate-200">{{ $inventario->sede->nombre }}</span> | Creado por: <span class="font-bold text-aldia-navy dark:text-slate-200">{{ $inventario->usuario->name }}</span></p>
                    </div>
                </div>

                <!-- Botones de Control de Estados -->
                <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
                    <!-- Toggles protegidos de Livewire morph -->
                    <div wire:ignore class="flex items-center gap-3">
                        <!-- Modo Alto Contraste -->
                        <button 
                            type="button"
                            @mousedown.prevent
                            @click="contrast = !contrast"
                            class="px-4 py-2 rounded-xl text-xs font-bold border transition-all shadow-sm flex items-center gap-1.5"
                            :class="contrast ? 'border-amber-400 bg-amber-500/10 text-amber-300' : 'border-aldia-borderLight dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-aldia-bgLight dark:hover:bg-slate-700 text-aldia-textMain dark:text-slate-300'"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m12.728 12.728l.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z" />
                            </svg>
                            Contraste
                        </button>

                        <!-- Conteo Ciego Toggle (100% Alpine) -->
                        <button
                            type="button"
                            @mousedown.prevent
                            @click="blind = !blind"
                            class="inline-flex items-center cursor-pointer px-4 py-2 rounded-xl border shadow-sm text-xs font-bold transition-all"
                            :class="blind ? 'border-aldia-primary bg-aldia-primary/10 text-aldia-primary' : 'border-aldia-borderLight dark:border-slate-700 bg-white dark:bg-slate-800 text-aldia-textMain dark:text-slate-300'"
                        >
                            <div class="relative w-9 h-5 rounded-full transition-colors duration-200"
                                 :class="blind ? 'bg-aldia-primary' : 'bg-gray-200 dark:bg-slate-700'">
                                <div class="absolute top-[2px] start-[2px] bg-white border border-gray-300 rounded-full h-4 w-4 transition-transform duration-200"
                                     :class="blind ? 'translate-x-full border-white' : ''"></div>
                            </div>
                            <span class="ms-2">Conteo Ciego</span>
                        </button>
                    </div>

                    <!-- Finalizar/Reabrir/Aplicar -->
                    <div class="flex items-center gap-2">
                        @if ($inventario->estado === 'en_elaboracion' || $inventario->estado === 'guardado')
                            <button 
                                type="button"
                                wire:key="btn-finalizar-{{ $inventario->id }}"
                                @click="$wire.finalizarInventario()"
                                wire:loading.attr="disabled"
                                wire:target="finalizarInventario"
                                class="px-5 py-2 bg-[#E3B77E] hover:bg-aldia-warmDark text-aldia-navy font-bold rounded-xl shadow-md text-xs transition-all flex items-center gap-1.5 cursor-pointer disabled:opacity-50"
                            >
                                <svg wire:loading.remove wire:target="finalizarInventario" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                                <svg wire:loading wire:target="finalizarInventario" class="animate-spin h-4 w-4 text-aldia-navy" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Finalizar Conteo
                            </button>
                        @elseif ($inventario->estado === 'finalizado')
                            <button 
                                type="button"
                                wire:key="btn-reabrir-{{ $inventario->id }}"
                                @click="$wire.reabrirInventario()"
                                wire:loading.attr="disabled"
                                wire:target="reabrirInventario"
                                class="px-5 py-2 bg-slate-200 hover:bg-slate-300 text-slate-800 font-bold rounded-xl shadow-sm text-xs transition-all flex items-center gap-1.5 cursor-pointer disabled:opacity-50"
                            >
                                <svg wire:loading.remove wire:target="reabrirInventario" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 8H18.2" />
                                </svg>
                                <svg wire:loading wire:target="reabrirInventario" class="animate-spin h-4 w-4 text-slate-800" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Continuar Conteo
                            </button>
                            
                            <button 
                                type="button"
                                wire:key="btn-aplicar-{{ $inventario->id }}"
                                @click="$wire.aplicarInventario()"
                                wire:loading.attr="disabled"
                                wire:target="aplicarInventario"
                                class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-md text-xs transition-all flex items-center gap-1.5 cursor-pointer disabled:opacity-50"
                            >
                                <svg wire:loading.remove wire:target="aplicarInventario" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <svg wire:loading wire:target="aplicarInventario" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Aplicar Inventario
                            </button>
                        @elseif ($inventario->estado === 'aplicado')
                            <a 
                                href="{{ route('inventarios.show', $inventario->id) }}"
                                wire:navigate
                                class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-md text-xs transition-all flex items-center gap-1.5"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Ver Detalle de Aplicación
                            </a>
                        @endif
                    </div>
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

            <!-- Filtros y Buscador -->
            <div class="bg-aldia-bgCard dark:bg-[#1E293B] border border-aldia-borderLight dark:border-slate-800 rounded-2xl shadow-sm p-6 mb-6 transition-colors duration-200">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Buscador rápido -->
                    <div class="relative">
                        <span class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-4 h-4 text-aldia-textSec dark:text-slate-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </span>
                        <input 
                            type="text" 
                            wire:model.live.debounce.300ms="search" 
                            placeholder="Buscar por Nombre, Código o Barras..."
                            class="w-full text-sm bg-aldia-bgLight dark:bg-slate-900/50 border-aldia-borderLight dark:border-slate-700 rounded-xl ps-10 focus:border-aldia-primary focus:ring-aldia-primary/20 text-aldia-textMain dark:text-white dark:placeholder-slate-500 transition-colors"
                        >
                    </div>
                    <!-- Categoría -->
                    <div>
                        <select 
                            wire:model.live="categoriaFilter"
                            class="w-full text-sm bg-aldia-bgLight dark:bg-slate-900/50 border-aldia-borderLight dark:border-slate-700 rounded-xl focus:border-aldia-primary focus:ring-aldia-primary/20 text-aldia-textMain dark:text-white transition-colors"
                        >
                            <option value="">Todas las Categorías</option>
                            @foreach($categorias as $cat)
                                <option value="{{ $cat }}">{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Marca -->
                    <div>
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
            </div>

            <!-- Grilla de Conteo Físico -->
            <div class="bg-aldia-bgCard dark:bg-[#1E293B] border border-aldia-borderLight dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden mb-24 transition-colors duration-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-aldia-borderLight dark:divide-slate-800/60 text-left transition-colors">
                        <thead class="bg-aldia-bgLight dark:bg-slate-800/50 transition-colors">
                            <tr>
                                <th class="px-6 py-4 text-xs font-bold text-aldia-navy dark:text-slate-400 uppercase tracking-wider w-32 transition-colors">Código</th>
                                <th class="px-6 py-4 text-xs font-bold text-aldia-navy dark:text-slate-400 uppercase tracking-wider transition-colors">Producto / Detalle</th>
                                <th x-show="!blind" class="px-6 py-4 text-xs font-bold text-aldia-navy dark:text-slate-400 uppercase tracking-wider text-right w-28 transition-colors">Stock Sistema</th>
                                <th x-show="!blind" class="px-6 py-4 text-xs font-bold text-aldia-navy dark:text-slate-400 uppercase tracking-wider text-right w-28 transition-colors">Costo Sistema</th>
                                <th class="px-6 py-4 text-xs font-bold text-aldia-navy dark:text-slate-400 uppercase tracking-wider text-center w-36 transition-colors">Cant. Física</th>
                                <th class="px-6 py-4 text-xs font-bold text-aldia-navy dark:text-slate-400 uppercase tracking-wider text-center w-36 transition-colors">Costo Conteo</th>
                                <th class="px-6 py-4 text-xs font-bold text-aldia-navy dark:text-slate-400 uppercase tracking-wider text-right w-36 transition-colors">Total Contado</th>
                                <th x-show="!blind" class="px-6 py-4 text-xs font-bold text-aldia-navy dark:text-slate-400 uppercase tracking-wider text-right w-36 transition-colors">Diferencia</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-aldia-borderLight dark:divide-slate-800/60 transition-colors" x-data="{ activeIndex: null }">
                            @forelse($detalles as $index => $det)
                                @php
                                    $readOnly = $inventario->estado === 'finalizado';
                                @endphp
                                <tr 
                                    x-data="{
                                        cantidad: {{ $det->cantidad_fisica !== null ? $det->cantidad_fisica : 'null' }},
                                        costo: {{ $det->costo_contado !== null ? $det->costo_contado : $det->costo_sistema }},
                                    existencia: {{ $det->existencia_sistema }},
                                    costo_sis: {{ $det->costo_sistema }},
                                    
                                    get totalContado() {
                                        return this.cantidad !== null ? (this.cantidad * this.costo) : 0;
                                    },
                                    get diffUnidades() {
                                        return this.cantidad !== null ? (this.cantidad - this.existencia) : 0;
                                    },
                                    get diffDinero() {
                                        return this.cantidad !== null ? (this.totalContado - (this.existencia * this.costo_sis)) : 0;
                                    }
                                }"
                                :class="{
                                    'bg-emerald-50/40 hover:bg-emerald-50/60': !blind && cantidad !== null && diffUnidades > 0,
                                    'bg-rose-50/40 hover:bg-rose-50/60': !blind && cantidad !== null && diffUnidades < 0,
                                    'hover:bg-aldia-bgLight/40': cantidad === null || blind || diffUnidades == 0
                                }"
                                class="transition-colors duration-150 align-middle"
                            >
                                <!-- Código / Barras -->
                                <td class="px-6 py-4 text-sm font-semibold text-aldia-navy dark:text-slate-100 whitespace-nowrap transition-colors">
                                    {{ $det->producto->codigo }}
                                    <span class="block text-[10px] text-aldia-textSec dark:text-slate-500 font-normal transition-colors">{{ $det->producto->codigo_barras }}</span>
                                </td>
                                <!-- Nombre / Detalles -->
                                <td class="px-6 py-4 text-sm text-aldia-textMain dark:text-slate-300 transition-colors">
                                    <div class="font-bold text-aldia-navy dark:text-white transition-colors">{{ $det->producto->nombre }}</div>
                                    <div class="text-xs text-aldia-textSec dark:text-slate-400 flex items-center gap-2 mt-0.5 transition-colors">
                                        <span class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 px-1.5 py-0.5 rounded transition-colors">{{ $det->producto->categoria }}</span>
                                        <span>Marca: {{ $det->producto->marca }}</span>
                                        <span>UM: {{ $det->producto->unidad_medida }}</span>
                                    </div>
                                </td>
                                <!-- Stock Sistema (Alpine: oculto en modo ciego) -->
                                <td x-show="!blind" class="px-6 py-4 text-sm text-right font-medium text-aldia-textMain dark:text-slate-300 whitespace-nowrap transition-colors">
                                    {{ number_format($det->existencia_sistema, 2) }}
                                </td>
                                <!-- Costo Sistema (Alpine: oculto en modo ciego) -->
                                <td x-show="!blind" class="px-6 py-4 text-sm text-right text-aldia-textSec dark:text-slate-500 whitespace-nowrap transition-colors">
                                    ${{ number_format($det->costo_sistema, 2) }}
                                </td>
                                <!-- Cantidad Física Input -->
                                <td class="px-6 py-4 text-center">
                                    <input 
                                        type="number" 
                                        step="0.01" 
                                        id="qty-{{ $det->id }}"
                                        x-model.number="cantidad"
                                        @focus="$el.select()"
                                        @blur="$wire.updateRow({{ $det->id }}, cantidad, costo)"
                                        @keydown.enter.prevent="
                                            let inputs = Array.from(document.querySelectorAll('input[id^=\'qty-\']'));
                                            let idx = inputs.indexOf($el);
                                            if (inputs[idx + 1]) { inputs[idx + 1].focus(); inputs[idx + 1].select(); }
                                        "
                                        @keydown.arrow-down.prevent="
                                            let inputs = Array.from(document.querySelectorAll('input[id^=\'qty-\']'));
                                            let idx = inputs.indexOf($el);
                                            if (inputs[idx + 1]) { inputs[idx + 1].focus(); inputs[idx + 1].select(); }
                                        "
                                        @keydown.arrow-up.prevent="
                                            let inputs = Array.from(document.querySelectorAll('input[id^=\'qty-\']'));
                                            let idx = inputs.indexOf($el);
                                            if (inputs[idx - 1]) { inputs[idx - 1].focus(); inputs[idx - 1].select(); }
                                        "
                                        placeholder="-"
                                        {{ $readOnly ? 'disabled' : '' }}
                                        class="w-24 text-center text-sm font-bold border-aldia-borderLight dark:border-slate-600 rounded-xl focus:border-aldia-primary focus:ring-aldia-primary/20 bg-white dark:bg-slate-900/50 text-aldia-navy dark:text-white py-1.5 focus:shadow-sm transition-colors"
                                    >
                                </td>
                                <!-- Costo Conteo Input -->
                                <td class="px-6 py-4 text-center">
                                    <div class="relative inline-block">
                                        <span class="absolute inset-y-0 start-0 flex items-center ps-2.5 text-xs text-aldia-textSec dark:text-slate-500 font-semibold pointer-events-none transition-colors">$</span>
                                        <input 
                                            type="number" 
                                            step="0.01" 
                                            id="cost-{{ $det->id }}"
                                            x-model.number="costo"
                                            @focus="$el.select()"
                                            @blur="$wire.updateRow({{ $det->id }}, cantidad, costo)"
                                            @keydown.enter.prevent="
                                                let inputs = Array.from(document.querySelectorAll('input[id^=\'qty-\']'));
                                                let idx = inputs.indexOf($el.closest('tr').querySelector('input[id^=\'qty-\']'));
                                                if (inputs[idx + 1]) { inputs[idx + 1].focus(); inputs[idx + 1].select(); }
                                            "
                                            placeholder="Costo"
                                            {{ $readOnly ? 'disabled' : '' }}
                                            class="w-28 text-right text-sm font-semibold border-aldia-borderLight dark:border-slate-600 rounded-xl focus:border-aldia-primary focus:ring-aldia-primary/20 bg-white dark:bg-slate-900/50 text-aldia-navy dark:text-white ps-5 py-1.5 focus:shadow-sm transition-colors"
                                        >
                                    </div>
                                </td>
                                <!-- Total Contado (Alpine Reactivo) -->
                                <td class="px-6 py-4 text-sm font-bold text-aldia-navy dark:text-slate-200 text-right whitespace-nowrap transition-colors">
                                    $<span x-text="totalContado.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})">0.00</span>
                                </td>
                                <!-- Diferencia (Alpine Reactivo, oculto en modo ciego) -->
                                <td x-show="!blind" class="px-6 py-4 text-sm text-right whitespace-nowrap font-bold" :class="diffUnidades > 0 ? 'text-aldia-success' : (diffUnidades < 0 ? 'text-aldia-danger' : 'text-aldia-textSec')">
                                    <div x-show="cantidad !== null">
                                        <span x-text="diffUnidades > 0 ? '+' : ''"></span>
                                        <span x-text="diffUnidades.toFixed(2)"></span> Uni.
                                        <span class="block text-[10px] font-medium" :class="diffDinero > 0 ? 'text-aldia-success' : (diffDinero < 0 ? 'text-aldia-danger' : 'text-aldia-textSec')">
                                            $<span x-text="diffDinero.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span>
                                        </span>
                                    </div>
                                    <div x-show="cantidad === null" class="text-xs text-slate-400 font-normal">
                                        Sin contar
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-sm text-aldia-textSec dark:text-slate-500 transition-colors">
                                    No se encontraron detalles para este conteo.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginador -->
            <div class="px-6 py-4 bg-aldia-bgLight/40 dark:bg-slate-800/40 border-t border-aldia-borderLight dark:border-slate-800/60 transition-colors">
                {{ $detalles->links() }}
            </div>
        </div>

        <!-- Sticky Footer con Totales Generales -->
        <div class="fixed bottom-0 left-0 right-0 z-40 bg-aldia-navy text-white shadow-2xl border-t border-slate-700 py-4 px-6">
            <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-4 text-center md:text-left">
                <!-- Estadísticas de avance -->
                <div class="flex items-center gap-6">
                    <div>
                        <div class="text-[10px] uppercase font-bold text-slate-400">Total SKU Contados</div>
                        <div class="text-lg font-extrabold text-aldia-warm">{{ $skuContados }} <span class="text-xs text-white font-normal">/ {{ $totalSku }}</span></div>
                    </div>
                    <div class="h-8 w-px bg-slate-700"></div>
                    <div>
                        <div class="text-[10px] uppercase font-bold text-slate-400">Unidades Contadas</div>
                        <div class="text-lg font-extrabold text-white">
                            {{ number_format($unidadesContadas, 2) }}
                        </div>
                    </div>
                </div>

                <!-- Diferencias / Dinero -->
                <!-- Footer diferencias - ocultas en modo ciego con Alpine -->
                <div x-show="!blind" class="flex items-center gap-6">
                    <div>
                        <div class="text-[10px] uppercase font-bold text-slate-400">Valor Total Contado</div>
                        <div class="text-lg font-extrabold text-white">${{ number_format($valorContado, 2) }}</div>
                    </div>
                    <div class="h-8 w-px bg-slate-700"></div>
                    <div>
                        <div class="text-[10px] uppercase font-bold text-slate-400">Diferencia Dinero</div>
                        <div class="text-lg font-extrabold flex items-center justify-center gap-1 {{ $diferenciaDinero >= 0 ? 'text-[#2FAE7A]' : 'text-[#E5584D]' }}">
                            {{ $diferenciaDinero >= 0 ? '+' : '' }}${{ number_format($diferenciaDinero, 2) }}
                        </div>
                    </div>
                    <div class="h-8 w-px bg-slate-700"></div>
                    <div>
                        <div class="text-[10px] uppercase font-bold text-slate-400">Diferencia Unidades</div>
                        <div class="text-lg font-extrabold {{ $diferenciaUnidades >= 0 ? 'text-[#2FAE7A]' : 'text-[#E5584D]' }}">
                            {{ $diferenciaUnidades >= 0 ? '+' : '' }}{{ number_format($diferenciaUnidades, 2) }}
                        </div>
                    </div>
                </div>
                <div x-show="blind" class="text-slate-400 text-xs font-semibold italic">
                    ※ Modo Conteo Ciego activo. Datos de existencias y diferencias ocultos.
                </div>

                <!-- Autoguardado e Indicación -->
                <div class="flex items-center gap-2">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    <span class="text-xs font-medium text-slate-300">Autoguardado al perder foco o presionar Enter</span>
                </div>
            </div>
        </div>

    </div>
</div>
