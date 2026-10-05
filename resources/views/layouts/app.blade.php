<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Management PO & Invoice MBG') - Sistem MBG</title>
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            900: '#14532d',
                        },
                        navy: {
                            800: '#1e293b',
                            900: '#0f172a',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        [x-cloak] { display: none !important; }
        .glass-header {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
        }
    </style>
</head>
<body class="bg-slate-50 font-sans text-slate-800 min-h-screen flex flex-col antialiased">

    <!-- App Container -->
    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar Navigation -->
        <aside class="w-64 bg-slate-900 text-slate-300 flex flex-col flex-shrink-0 border-r border-slate-800 transition-all duration-300">
            <!-- Brand Logo Header -->
            <div class="p-5 flex items-center gap-3 border-b border-slate-800">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-emerald-500 to-teal-400 flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-emerald-500/20">
                    <i class="fa-solid fa-bowl-rice"></i>
                </div>
                <div>
                    <h1 class="font-extrabold text-white text-base tracking-wide leading-tight">PO & QC MBG</h1>
                    <span class="text-xs text-emerald-400 font-medium">Makan Bergizi Gratis</span>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 p-4 space-y-1.5 overflow-y-auto">
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider px-3 mb-2">Main Menu</div>
                
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/30' : 'hover:bg-slate-800 hover:text-white text-slate-400' }}">
                    <i class="fa-solid fa-chart-pie w-5 text-center text-lg"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('po.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-all duration-200 {{ request()->routeIs('po.*') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/30' : 'hover:bg-slate-800 hover:text-white text-slate-400' }}">
                    <i class="fa-solid fa-cart-flatbed w-5 text-center text-lg"></i>
                    <span>1. Input PO Bahan</span>
                    <span class="ml-auto text-[10px] bg-slate-800 px-2 py-0.5 rounded-full text-emerald-400 font-semibold border border-emerald-500/20">Akuntan</span>
                </a>

                <a href="{{ route('inspection.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-all duration-200 {{ request()->routeIs('inspection.*') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/30' : 'hover:bg-slate-800 hover:text-white text-slate-400' }}">
                    <i class="fa-solid fa-clipboard-check w-5 text-center text-lg"></i>
                    <span>2. Pengecekan (QC)</span>
                    <span class="ml-auto text-[10px] bg-slate-800 px-2 py-0.5 rounded-full text-teal-400 font-semibold border border-teal-500/20">Quality</span>
                </a>

                <a href="{{ route('invoice.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-all duration-200 {{ request()->routeIs('invoice.*') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/30' : 'hover:bg-slate-800 hover:text-white text-slate-400' }}">
                    <i class="fa-solid fa-file-invoice-dollar w-5 text-center text-lg"></i>
                    <span>3. Invoice Terbit</span>
                    <span class="ml-auto text-[10px] bg-slate-800 px-2 py-0.5 rounded-full text-cyan-400 font-semibold border border-cyan-500/20">Finance</span>
                </a>

                <div class="pt-4 text-xs font-semibold text-slate-500 uppercase tracking-wider px-3 mb-2">Export Data</div>

                <a href="{{ route('export.excel', 'po') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm hover:bg-slate-800 hover:text-emerald-400 text-slate-400 transition-all duration-200">
                    <i class="fa-solid fa-file-excel w-5 text-center text-lg text-emerald-500"></i>
                    <span>Export Excel PO</span>
                </a>

                <a href="{{ route('export.excel', 'inspection') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm hover:bg-slate-800 hover:text-emerald-400 text-slate-400 transition-all duration-200">
                    <i class="fa-solid fa-file-excel w-5 text-center text-lg text-teal-500"></i>
                    <span>Export Excel QC</span>
                </a>

                <a href="{{ route('export.excel', 'invoice') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm hover:bg-slate-800 hover:text-emerald-400 text-slate-400 transition-all duration-200">
                    <i class="fa-solid fa-file-excel w-5 text-center text-lg text-cyan-500"></i>
                    <span>Export Excel Invoice</span>
                </a>

                <div class="pt-4 text-xs font-semibold text-slate-500 uppercase tracking-wider px-3 mb-2">Akun</div>
                <a href="{{ route('logout') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm hover:bg-slate-800 hover:text-emerald-400 text-slate-400 transition-all duration-200">
                    <i class="fa-solid fa-sign-out-alt w-5 text-center text-lg text-cyan-500"></i>
                    <span>Logout</span>
                </a>
            </nav>

            <!-- User Info Footer -->
            <div class="p-4 border-t border-slate-800 bg-slate-950/50 flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center font-bold">
                    <i class="fa-solid fa-user-check"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-white truncate">Tim Akuntansi & QC</p>
                    <p class="text-xs text-slate-400 truncate">Program MBG</p>
                </div>
            </div>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            
            <!-- Top Navbar -->
            <header class="glass-header sticky top-0 z-10 border-b border-slate-200/80 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <h2 class="text-xl font-bold text-slate-800">@yield('page_title', 'Dashboard')</h2>
                </div>
                <div class="flex items-center gap-3">
                    <div class="text-xs text-slate-500 bg-slate-100 px-3 py-1.5 rounded-lg border border-slate-200 font-medium">
                        <i class="fa-regular fa-calendar text-emerald-600 mr-1.5"></i> {{ date('l, d F Y') }}
                    </div>
                </div>
            </header>

            <!-- Page Body Content -->
            <main class="flex-1 overflow-y-auto p-6 md:p-8 bg-slate-50">

                <!-- Alert Flash Messages -->
                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 bg-emerald-50 border border-emerald-200 rounded-2xl p-4 flex items-center justify-between text-emerald-800 shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center font-bold">
                                <i class="fa-solid fa-circle-check"></i>
                            </div>
                            <span class="font-medium text-sm">{{ session('success') }}</span>
                        </div>
                        <button @click="show = false" class="text-emerald-500 hover:text-emerald-800 text-lg">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 bg-rose-50 border border-rose-200 rounded-2xl p-4 flex items-center justify-between text-rose-800 shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl bg-rose-500 text-white flex items-center justify-center font-bold">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                            </div>
                            <span class="font-medium text-sm">{{ session('error') }}</span>
                        </div>
                        <button @click="show = false" class="text-rose-500 hover:text-rose-800 text-lg">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>

    </div>

</body>
</html>
