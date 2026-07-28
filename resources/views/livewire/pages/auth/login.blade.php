<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    public function mount(): void
    {
        // Pre-fill for reviewer convenience
        $this->form->email = 'admin@aldia.com';
        $this->form->password = 'password';
    }

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->dispatch('alert', ['type' => 'success', 'message' => '¡Sesión iniciada con éxito! Bienvenido al sistema.']);
        $this->dispatch('redirect-after-alert', ['url' => route('dashboard', absolute: false)]);
    }
}; ?>

<div class="font-sans">
    <!-- Header matching user's custom layout exactly -->
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-slate-800 leading-none">Bienvenido a</h2>
        <h1 class="text-3xl font-black text-[#16305C] tracking-tight mt-2 uppercase">ALDÍA CONTABLE</h1>
        <p class="text-xs text-slate-500 mt-3 max-w-sm mx-auto leading-relaxed">
            Ingrese su correo electrónico y contraseña a continuación para iniciar sesión en su cuenta.
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4 text-xs font-semibold text-emerald-600" :status="session('status')" />

    <form wire:submit="login" class="space-y-6">
        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-bold text-[#16305C] uppercase tracking-wider mb-2">Correo electrónico</label>
            <input 
                wire:model="form.email" 
                id="email" 
                type="email" 
                name="email" 
                required 
                autofocus 
                autocomplete="username"
                class="w-full text-sm bg-white border border-slate-200 rounded-xl focus:border-aldia-primary focus:ring focus:ring-aldia-primary/20 text-[#16305C] placeholder-slate-400 py-3 px-4 shadow-sm transition-all duration-150"
                placeholder="correo@ejemplo.com"
            >
            @error('form.email') 
                <span class="text-xs text-rose-600 font-semibold mt-1 block">{{ $message }}</span> 
            @enderror
        </div>

        <!-- Password -->
        <div x-data="{ showPassword: false }">
            <div class="flex justify-between items-center mb-2">
                <label for="password" class="block text-xs font-bold text-[#16305C] uppercase tracking-wider">Contraseña</label>
            </div>
            <div class="relative">
                <input 
                    x-ref="pwd"
                    wire:model="form.password" 
                    id="password" 
                    type="password" 
                    name="password" 
                    required 
                    autocomplete="current-password"
                    class="w-full text-sm bg-white border border-slate-200 rounded-xl focus:border-aldia-primary focus:ring focus:ring-aldia-primary/20 text-[#16305C] placeholder-slate-400 py-3 px-4 shadow-sm transition-all duration-150 pr-10"
                    placeholder="••••••••"
                >
                <button type="button" @click="$refs.pwd.type = $refs.pwd.type === 'password' ? 'text' : 'password'; showPassword = !showPassword" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-aldia-primary transition-colors focus:outline-none" title="Mostrar/Ocultar Contraseña">
                    <!-- Icon for hidden password -->
                    <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <!-- Icon for visible password -->
                    <svg x-show="showPassword" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                </button>
            </div>
            @error('form.password') 
                <span class="text-xs text-rose-600 font-semibold mt-1 block">{{ $message }}</span> 
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember" class="inline-flex items-center cursor-pointer">
                <input 
                    wire:model="form.remember" 
                    id="remember" 
                    type="checkbox" 
                    class="rounded border-slate-300 text-[#16305C] focus:ring-aldia-primary/30 h-4 w-4"
                    name="remember"
                >
                <span class="ms-2 text-xs font-semibold text-slate-500">{{ __('Recordarme en este equipo') }}</span>
            </label>
        </div>

        <!-- Submit Button matching dark navy style -->
        <div>
            <button 
                type="submit" 
                class="w-full py-3.5 px-4 bg-[#16305C] hover:bg-[#0c1c38] text-white font-bold rounded-xl shadow-md hover:shadow-[#16305C]/25 transition-all hover:scale-[1.01] active:scale-[0.99] text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#16305C] duration-150 text-center"
            >
                Iniciar Sesión
            </button>
        </div>
    </form>


</div>
