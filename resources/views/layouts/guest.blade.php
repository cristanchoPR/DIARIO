<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>DIARIO</title>
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Bubble Floating Animation Styles for Left Panel -->
        <style>
            .bubbles {
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
            .bubbles li {
                position: absolute;
                list-style: none;
                display: block;
                width: 20px;
                height: 20px;
                background: rgba(90, 143, 219, 0.08); /* Aldia primary blue */
                bottom: -150px;
                animation: bubbleUp 22s infinite linear;
                border-radius: 50%;
                filter: blur(1px);
            }
            .bubbles li:nth-child(1) { left: 25%; width: 80px; height: 80px; animation-delay: 0s; }
            .bubbles li:nth-child(2) { left: 10%; width: 35px; height: 35px; animation-delay: 2s; animation-duration: 12s; }
            .bubbles li:nth-child(3) { left: 70%; width: 25px; height: 25px; animation-delay: 4s; }
            .bubbles li:nth-child(4) { left: 40%; width: 60px; height: 60px; animation-delay: 0s; animation-duration: 18s; }
            .bubbles li:nth-child(5) { left: 65%; width: 40px; height: 40px; animation-delay: 8s; }
            .bubbles li:nth-child(6) { left: 15%; width: 110px; height: 110px; animation-delay: 3s; background: rgba(243, 217, 174, 0.05); /* Aldia warm peach */ }
            .bubbles li:nth-child(7) { left: 85%; width: 130px; height: 130px; animation-delay: 7s; background: rgba(90, 143, 219, 0.04); }
            .bubbles li:nth-child(8) { left: 50%; width: 30px; height: 30px; animation-delay: 12s; animation-duration: 35s; }

            @keyframes bubbleUp {
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
    </head>
    <body class="font-sans antialiased min-h-screen bg-white">
        <div class="min-h-screen grid grid-cols-1 lg:grid-cols-12">
            
            <!-- Left Side: Dark animated branding panel (5 cols on large desktop) -->
            <div class="hidden lg:flex lg:col-span-5 bg-[#0B132B] relative overflow-hidden flex-col justify-between p-12">
                <!-- Background Glowing Circles -->
                <div class="absolute top-[-20%] left-[-20%] w-[80%] h-[80%] rounded-full bg-aldia-primary/20 blur-[130px] pointer-events-none z-0"></div>
                <div class="absolute bottom-[-20%] right-[-20%] w-[80%] h-[80%] rounded-full bg-aldia-warm/15 blur-[130px] pointer-events-none z-0"></div>
                
                <!-- Floating Bubbles -->
                <ul class="bubbles">
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                </ul>

                <!-- Top Left Logo representation or subtitle -->
                <div class="relative z-10 flex items-center gap-2">
                    <span class="inline-block w-2.5 h-2.5 rounded-full bg-aldia-primary animate-pulse"></span>
                    <span class="text-xs font-bold uppercase tracking-widest text-slate-400">Aldia ERP Suite</span>
                </div>

                <!-- Center: Transparent Logo directly on the dark background -->
                <div class="relative z-10 my-auto flex justify-center w-full px-6">
                    <div class="hover:scale-[1.03] transition-transform duration-300 w-full max-w-sm">
                        <x-application-logo class="w-full h-auto" />
                    </div>
                </div>

                <!-- Footer details left -->
                <div class="relative z-10 flex justify-between items-center text-[10px] text-slate-400 font-medium">
                    <span>Módulo de Inventarios Físicos</span>
                    <span>v1.0.0</span>
                </div>
            </div>

            <!-- Right Side: Clean login form section (7 cols) -->
            <div class="col-span-1 lg:col-span-7 bg-[#F7F9FC] flex flex-col justify-center items-center p-8 md:p-16 min-h-screen relative">
                
                <!-- Mobile brand logo -->
                <div class="lg:hidden mb-8 flex justify-center">
                    <x-application-logo class="h-12 w-auto" />
                </div>

                <!-- Main clean card containing the form -->
                <div class="w-full max-w-md bg-white border border-aldia-borderLight rounded-3xl p-8 md:p-10 shadow-lg relative">
                    {{ $slot }}
                </div>

                <!-- Footer copyright right -->
                <div class="mt-8 text-center text-xs text-slate-500 font-medium">
                    © {{ date('Y') }} Aldia ERP. Todos los derechos reservados.
                </div>
            </div>

        </div>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            function showToast(type, message) {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    background: '#FFFFFF',
                    color: '#1E293B',
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

            window.addEventListener('alert', event => {
                const data = event.detail[0] || event.detail;
                showToast(data.type || 'success', data.message || '');
            });

            window.addEventListener('redirect-after-alert', event => {
                const data = event.detail[0] || event.detail;
                setTimeout(() => {
                    Livewire.navigate(data.url);
                }, 1000);
            });
        </script>
    </body>
</html>
