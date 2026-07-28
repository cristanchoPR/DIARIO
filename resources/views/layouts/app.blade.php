<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full {{ ($_COOKIE['theme'] ?? '') === 'dark' ? 'dark' : '' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>DIARIO</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }

        /* Bubble animation for sidebar */
        .sidebar-bubbles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
            overflow: hidden;
            pointer-events: none;
            margin: 0;
            padding: 0;
        }
        .sidebar-bubbles li {
            position: absolute;
            list-style: none;
            display: block;
            width: 15px;
            height: 15px;
            background: rgba(90, 143, 219, 0.12); /* Aldia primary blue */
            bottom: -150px;
            animation: sidebarBubbleUp 22s infinite linear;
            border-radius: 50%;
            filter: blur(1px);
        }
        .sidebar-bubbles li:nth-child(1) { left: 20%; width: 45px; height: 45px; animation-delay: 0s; }
        .sidebar-bubbles li:nth-child(2) { left: 10%; width: 25px; height: 25px; animation-delay: 2s; animation-duration: 12s; }
        .sidebar-bubbles li:nth-child(3) { left: 70%; width: 20px; height: 20px; animation-delay: 4s; }
        .sidebar-bubbles li:nth-child(4) { left: 40%; width: 40px; height: 40px; animation-delay: 0s; animation-duration: 18s; }
        .sidebar-bubbles li:nth-child(5) { left: 65%; width: 30px; height: 30px; animation-delay: 8s; }
        .sidebar-bubbles li:nth-child(6) { left: 15%; width: 60px; height: 60px; animation-delay: 3s; background: rgba(243, 217, 174, 0.08); /* Aldia warm peach */ }
        .sidebar-bubbles li:nth-child(7) { left: 80%; width: 75px; height: 75px; animation-delay: 6s; background: rgba(90, 143, 219, 0.06); }
        .sidebar-bubbles li:nth-child(8) { left: 50%; width: 22px; height: 22px; animation-delay: 11s; animation-duration: 28s; }

        @keyframes sidebarBubbleUp {
            0% {
                transform: translateY(0) scale(1) rotate(0deg);
                opacity: 0;
            }
            15% {
                opacity: 1;
            }
            85% {
                opacity: 1;
            }
            100% {
                transform: translateY(-1100px) scale(1.1) rotate(360deg);
                opacity: 0;
            }
        }
    </style>
    @stack('styles')
    <!-- Script para inicializar el modo oscuro antes de renderizar y evitar "flash" blanco -->
    <script>
        function applyTheme() {
            const storedTheme = localStorage.getItem('theme');
            if (storedTheme === 'dark' || (storedTheme !== 'light' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }
        applyTheme();
        document.addEventListener('livewire:navigated', applyTheme);
    </script>
</head>
<body class="h-full font-sans antialiased bg-[#F7F9FC] dark:bg-[#0F172A] text-slate-800 dark:text-slate-200 transition-colors duration-200" 
      x-data="{ 
          sidebarOpen: false, 
          sidebarCollapsed: false,
          theme: localStorage.getItem('theme') === 'dark' || (localStorage.getItem('theme') !== 'light' && window.matchMedia('(prefers-color-scheme: dark)').matches) ? 'dark' : 'light',
          toggleTheme() {
              this.theme = this.theme === 'light' ? 'dark' : 'light';
              if (this.theme === 'dark') {
                  document.documentElement.classList.add('dark');
                  localStorage.setItem('theme', 'dark');
                  document.cookie = 'theme=dark; path=/; max-age=31536000; SameSite=Lax';
              } else {
                  document.documentElement.classList.remove('dark');
                  localStorage.setItem('theme', 'light');
                  document.cookie = 'theme=light; path=/; max-age=31536000; SameSite=Lax';
              }
          }
      }">

<div class="flex h-screen overflow-hidden">

    <!-- ========================= SIDEBAR ========================= -->
    <!-- Mobile overlay -->
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
         class="fixed inset-0 z-20 bg-black/60 backdrop-blur-sm lg:hidden" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

    <aside :class="sidebarCollapsed ? 'w-[72px]' : 'w-64'"
           class="fixed inset-y-0 left-0 z-30 flex flex-col bg-[#0B132B] transition-all duration-300 ease-in-out lg:static lg:translate-x-0 relative overflow-hidden"
           :style="!sidebarOpen && window.innerWidth < 1024 ? 'transform: translateX(-100%)' : ''"
    >
        <!-- Background Glowing Circles -->
        <div class="absolute top-[-20%] left-[-20%] w-[100%] h-[50%] rounded-full bg-aldia-primary/20 blur-[100px] pointer-events-none z-0"></div>
        <div class="absolute bottom-[-20%] right-[-20%] w-[100%] h-[50%] rounded-full bg-aldia-warm/15 blur-[100px] pointer-events-none z-0"></div>

        <!-- Floating Bubbles -->
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

        <!-- Logo Area -->
        <div class="flex items-center h-20 px-4 border-b border-white/10 flex-shrink-0 relative z-10 transition-all duration-300 justify-center">
            <a href="{{ route('dashboard') }}" class="flex items-center min-w-0 group" wire:navigate title="Aldia ERP">
                <!-- Expanded view: Official Aldia logo vector -->
                <div x-show="!sidebarCollapsed" class="py-1" x-transition>
                    <x-application-logo class="h-10 w-auto text-white drop-shadow-sm transition-transform duration-200 group-hover:scale-105" />
                </div>
                <!-- Collapsed view: Aldia 'A' Icon -->
                <div x-show="sidebarCollapsed" class="w-10 h-10 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform" x-cloak>
                    <svg viewBox="0 0 25 35" xmlns="http://www.w3.org/2000/svg" class="h-8 w-auto">
                        <g transform="translate(-2, 0)">
                            <circle cx="12" cy="22" r="8.5" stroke="#7CB3F5" stroke-width="2.8" fill="none" />
                            <line x1="20.5" y1="13.5" x2="20.5" y2="30.5" stroke="#7CB3F5" stroke-width="2.8" stroke-linecap="round" />
                        </g>
                    </svg>
                </div>
            </a>
        </div>

        <!-- Nav Links -->
        <nav class="flex-1 overflow-y-auto py-4 px-2 space-y-1 relative z-10">

            {{-- Toggle Button always at the top of nav --}}
            <div class="hidden lg:flex justify-center mb-4 text-center">
                <button @click="sidebarCollapsed = !sidebarCollapsed" 
                        class="w-full flex items-center justify-center py-2.5 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 transition-all group"
                        title="Alternar menú">
                    <svg x-show="!sidebarCollapsed" class="w-5 h-5 text-slate-400 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
                    </svg>
                    <svg x-show="sidebarCollapsed" class="w-5 h-5 text-aldia-primary group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>

            {{-- Panel Principal --}}
            <x-nav-link href="{{ route('dashboard') }}" icon="chart-bar" :active="request()->routeIs('dashboard')">
                Panel Principal
            </x-nav-link>

            {{-- Administración --}}
            @hasanyrole('administrador|admin_sede')
            <div class="pt-4 pb-1" x-show="!sidebarCollapsed">
                <span class="px-3 text-[10px] font-bold uppercase tracking-widest text-slate-500">Administración</span>
            </div>

            @role('administrador')
            <x-nav-link href="{{ route('sedes.index') }}" icon="office-building" :active="request()->routeIs('sedes.*')">
                Sedes
            </x-nav-link>
            @endrole

            <x-nav-link href="{{ route('productos.index') }}" icon="cube" :active="request()->routeIs('productos.*')">
                Productos
            </x-nav-link>

            <x-nav-link href="{{ route('usuarios.index') }}" icon="users" :active="request()->routeIs('usuarios.*')">
                Usuarios
            </x-nav-link>
            @endhasanyrole

            {{-- Inventario --}}
            <div class="pt-4 pb-1" x-show="!sidebarCollapsed">
                <span class="px-3 text-[10px] font-bold uppercase tracking-widest text-slate-500">Inventario</span>
            </div>

            <x-nav-link href="{{ route('inventarios.index') }}" icon="clipboard-list" :active="request()->routeIs('inventarios.*')">
                Inventarios
            </x-nav-link>

            <x-nav-link href="{{ route('reportes.index') }}" icon="document-report" :active="request()->routeIs('reportes.*')">
                Reportes
            </x-nav-link>

        </nav>

        <!-- User Profile -->
        <div class="flex-shrink-0 border-t border-white/10 p-3 relative z-10">
            <div class="flex items-center gap-3 rounded-xl p-2 hover:bg-white/5 transition-colors cursor-pointer" x-show="!sidebarCollapsed">
                <div class="w-8 h-8 rounded-full bg-aldia-primary flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <div class="text-xs font-semibold text-white truncate">{{ auth()->user()->name }}</div>
                    <div class="text-[10px] text-slate-400 truncate">{{ auth()->user()->getRoleNames()->first() }}</div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="button" 
                            @click="
                                Swal.fire({
                                    title: '¿Cerrar Sesión?',
                                    text: '¿Está seguro de que desea salir del sistema?',
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#E11D48',
                                    cancelButtonColor: '#475569',
                                    confirmButtonText: 'Sí, salir',
                                    cancelButtonText: 'Cancelar',
                                    background: document.documentElement.classList.contains('dark') ? '#1E293B' : '#FFFFFF',
                                    color: document.documentElement.classList.contains('dark') ? '#F1F5F9' : '#1E293B'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        $el.closest('form').submit();
                                    }
                                })
                            "
                            class="text-slate-400 hover:text-rose-400 transition-colors" 
                            title="Cerrar sesión">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
            </div>
            <div x-show="sidebarCollapsed" class="flex justify-center">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="button" 
                            @click="
                                Swal.fire({
                                    title: '¿Cerrar Sesión?',
                                    text: '¿Está seguro de que desea salir del sistema?',
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#E11D48',
                                    cancelButtonColor: '#475569',
                                    confirmButtonText: 'Sí, salir',
                                    cancelButtonText: 'Cancelar',
                                    background: document.documentElement.classList.contains('dark') ? '#1E293B' : '#FFFFFF',
                                    color: document.documentElement.classList.contains('dark') ? '#F1F5F9' : '#1E293B'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        $el.closest('form').submit();
                                    }
                                })
                            "
                            class="text-slate-400 hover:text-rose-400 transition-colors p-2" 
                            title="Cerrar sesión">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- ========================= MAIN CONTENT ========================= -->
    <div class="flex-1 flex flex-col min-w-0 overflow-auto">

        <!-- Top Bar -->
        <header class="flex-shrink-0 bg-white dark:bg-[#1E293B] border-b border-slate-200 dark:border-slate-800 h-16 flex items-center justify-between px-4 lg:px-6 shadow-sm transition-colors duration-200">
            <!-- Mobile menu toggle -->
            <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>

            <!-- Breadcrumb -->
            <div class="hidden lg:flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400">
                <span class="font-semibold text-slate-800 dark:text-slate-200">@yield('title', 'Panel Principal')</span>
            </div>

            <!-- Right actions -->
            <div class="flex items-center gap-3">
                <!-- Theme Toggle Button -->
                <button @click="toggleTheme()" class="p-2 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 dark:hover:text-white dark:hover:bg-slate-800 transition-colors" title="Alternar tema">
                    <svg x-show="theme === 'light'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                    <svg x-show="theme === 'dark'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </button>
                
                <!-- Current time -->
                <span class="hidden sm:block text-xs text-slate-400 font-mono">{{ now()->format('d/m/Y H:i') }}</span>
                <!-- Role badge -->
                <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-full
                    {{ auth()->user()->hasRole('administrador') ? 'bg-aldia-primary/15 text-aldia-primaryDark dark:text-aldia-primary' : 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400' }}">
                    {{ auth()->user()->getRoleNames()->first() }}
                </span>
                <!-- Profile icon (non-clickable) -->
                <div class="w-8 h-8 rounded-full bg-aldia-primary flex items-center justify-center text-white text-xs font-bold" title="{{ auth()->user()->name }}">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                </div>
            </div>
        </header>

        <!-- Hidden session flash data for SweetAlert2 -->
        @if (session('success'))
            <div id="flash-success-data" data-message="{{ session('success') }}" class="hidden"></div>
        @endif
        @if (session('error'))
            <div id="flash-error-data" data-message="{{ session('error') }}" class="hidden"></div>
        @endif

        <!-- Page Content -->
        <main class="flex-1 overflow-auto p-4 lg:p-6">
            {{ $slot }}
        </main>
    </div>

</div>

@stack('modals')
@stack('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function showToast(type, message) {
        const isDark = document.documentElement.classList.contains('dark');
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            background: isDark ? '#1E293B' : '#FFFFFF',
            color: isDark ? '#F1F5F9' : '#1E293B',
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });
        Toast.fire({
            icon: type,
            title: message
        });
    }

    // Escucha de alertas directas de Livewire
    window.addEventListener('alert', event => {
        const data = event.detail[0] || event.detail;
        showToast(data.type || 'success', data.message || '');
    });

    // Redirección retardada para permitir ver el alert
    window.addEventListener('redirect-after-alert', event => {
        const data = event.detail[0] || event.detail;
        setTimeout(() => {
            Livewire.navigate(data.url);
        }, 1000); // 1 segundo para leer la alerta
    });

    // Función para leer divs de sesión
    function checkSessionFlashes() {
        const successEl = document.getElementById('flash-success-data');
        const errorEl = document.getElementById('flash-error-data');
        if (successEl) {
            showToast('success', successEl.dataset.message);
            successEl.remove();
        }
        if (errorEl) {
            showToast('error', errorEl.dataset.message);
            errorEl.remove();
        }
    }

    // Verificar en carga inicial y después de cada navegación de Livewire
    document.addEventListener('DOMContentLoaded', checkSessionFlashes);
    document.addEventListener('livewire:navigated', checkSessionFlashes);
</script>
</body>
</html>
