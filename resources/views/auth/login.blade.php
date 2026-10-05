@extends('layouts.guest')

@section('title', 'Masuk Akun')

@section('content')
<!-- Header Branding -->
<div class="text-center mb-8">
    <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-tr from-emerald-500 to-teal-400 text-white font-bold text-3xl shadow-xl shadow-emerald-500/30 mb-4 ring-8 ring-slate-800/50">
        <i class="fa-solid fa-bowl-rice"></i>
    </div>
    <h1 class="text-2xl font-extrabold text-white tracking-wide">PO & QC MBG</h1>
    <p class="text-xs text-emerald-400 font-semibold tracking-wider uppercase mt-1">Sistem Management Makan Bergizi Gratis</p>
</div>

<!-- Login Card Container -->
<div class="glass-card bg-white/95 rounded-3xl p-6 sm:p-8 shadow-2xl border border-slate-700/30 relative">
    <div class="mb-6">
        <h2 class="text-xl font-bold text-slate-800">Selamat Datang</h2>
        <p class="text-xs text-slate-500 mt-1">Masukkan kredensial Anda untuk mengakses sistem</p>
    </div>

    <!-- Alert Flash Messages -->
    @if(session('success'))
        <div class="mb-5 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-medium flex items-center gap-2.5">
            <i class="fa-solid fa-circle-check text-emerald-500 text-sm"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-5 p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-medium flex items-start gap-2.5">
            <i class="fa-solid fa-circle-exclamation text-rose-500 text-sm mt-0.5"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" x-data="{ showPassword: false }" class="space-y-5">
        @csrf

        <!-- Username / Email Input -->
        <div>
            <label for="email" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                Email / Username
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-envelope text-sm"></i>
                </div>
                <input type="text" 
                       id="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       required 
                       autofocus
                       placeholder="nama@domain.com"
                       class="w-full pl-10 pr-4 py-2.5 bg-slate-50/80 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:bg-white transition-all duration-200">
            </div>
        </div>

        <!-- Password Input -->
        <div>
            <div class="flex items-center justify-between mb-2">
                <label for="password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">
                    Kata Sandi
                </label>
            </div>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-lock text-sm"></i>
                </div>
                <input :type="showPassword ? 'text' : 'password'" 
                       id="password" 
                       name="password" 
                       required
                       placeholder="••••••••"
                       class="w-full pl-10 pr-10 py-2.5 bg-slate-50/80 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:bg-white transition-all duration-200">
                <button type="button" 
                        @click="showPassword = !showPassword" 
                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none">
                    <i class="fa-solid text-sm" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                </button>
            </div>
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between text-xs pt-1">
            <label class="flex items-center gap-2 cursor-pointer select-none">
                <input type="checkbox" name="remember" class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 border-slate-300">
                <span class="text-slate-600 font-medium">Ingat Saya</span>
            </label>
        </div>

        <!-- Submit Button -->
        <button type="submit" 
                class="w-full py-3 px-4 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-bold rounded-xl shadow-lg shadow-emerald-600/30 hover:shadow-emerald-600/40 active:scale-[0.99] transition-all duration-200 flex items-center justify-center gap-2 text-sm mt-2">
            <span>Masuk ke Sistem</span>
            <i class="fa-solid fa-arrow-right text-xs"></i>
        </button>
    </form>
</div>

<!-- Footer Info -->
<div class="mt-8 text-center">
    <p class="text-xs text-slate-400 font-medium">&copy; {{ date('Y') }} MBG - Management PO & Quality Control</p>
</div>
@endsection
