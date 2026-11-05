<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AirNav DMS - @yield('title', 'Sistem Navigasi & Dokumen')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans antialiased text-gray-800">

    <div class="min-h-screen flex">
        <!-- SIDEBAR UTAMA (RBAC FILTERED) -->
        <aside class="w-64 bg-slate-900 text-slate-300 flex flex-col shrink-0 min-h-screen fixed left-0 top-0 bottom-0 z-20 shadow-lg">
            <!-- Brand -->
            <div class="h-16 flex items-center gap-3 px-6 bg-slate-950/80 border-b border-slate-800">
                <i class="fa-solid fa-plane-departure text-xl text-blue-400"></i>
                <span class="text-lg font-bold text-white tracking-wide">AirNav DMS</span>
            </div>

            <!-- Profile Info -->
            <div class="px-6 py-4 border-b border-slate-800/60 bg-slate-900/40">
                <p class="text-[11px] text-slate-400 uppercase tracking-wider font-semibold">User Terautentikasi</p>
                <p class="text-sm font-semibold text-white mt-0.5 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full {{ in_array(auth()->user()->role ?? '', ['manager_teknik', 'mantek']) ? 'bg-emerald-500' : 'bg-blue-500' }}"></span>
                    {{ auth()->user()->name ?? 'Manager Teknik' }}
                </p>
                <span class="text-[11px] text-slate-400 uppercase tracking-wider">
                    Role: {{ str_replace('_', ' ', auth()->user()->role ?? 'Manager Teknik') }}
                </span>
            </div>

            <!-- Navigasi Sidebar -->
            <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
                <p class="px-3 text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-2">Utama</p>
                
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-gauge w-5 text-center"></i>
                    <span>Dashboard Utama</span>
                </a>

                <p class="px-3 text-[11px] font-semibold text-slate-400 uppercase tracking-wider mt-5 mb-2">Operasional CNS/A</p>

                <a href="{{ route('arsip.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('arsip.*') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-folder-tree w-5 text-center"></i>
                    <span>Arsip Dokumen</span>
                </a>

                <a href="{{ route('logbook.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('logbook.*') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-book-bookmark w-5 text-center"></i>
                    <span>Logbook Harian</span>
                </a>

                <!-- SEKSI KHUSUS MANAGER TEKNIK -->
                @if(in_array(auth()->user()->role ?? 'manager_teknik', ['manager_teknik', 'mantek']))
                <p class="px-3 text-[11px] font-semibold text-emerald-400 uppercase tracking-wider mt-5 mb-2">Wewenang Manager Teknik</p>

                <a href="{{ route('mantek.dashboard') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('mantek.dashboard') ? 'bg-emerald-600 text-white shadow-sm' : 'text-emerald-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-screwdriver-wrench w-5 text-center"></i>
                    <span>Panel & Perintah Mantek</span>
                </a>

                <a href="{{ route('monitoring.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('monitoring.*') ? 'bg-emerald-600 text-white shadow-sm' : 'text-emerald-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-boxes-stacked w-5 text-center"></i>
                    <span>Inventory & Fasilitas</span>
                </a>

                <a href="{{ route('instruksi.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('instruksi.*') ? 'bg-emerald-600 text-white shadow-sm' : 'text-emerald-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-list-check w-5 text-center"></i>
                    <span>Instruksi Kerja Teknisi</span>
                </a>
                @endif
            </nav>

            <!-- Logout -->
            <div class="p-4 border-t border-slate-800">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2 text-xs font-semibold text-rose-300 bg-rose-950/40 border border-rose-800/40 rounded-lg hover:bg-rose-900/60 hover:text-white transition">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- KONTEN -->
        <div class="flex-1 ml-64 flex flex-col min-h-screen">
            <!-- Topbar Global -->
            <header class="h-16 bg-white border-b border-gray-200 px-6 flex items-center justify-between sticky top-0 z-10 shadow-sm">
                <div class="text-sm font-medium text-gray-600 flex items-center gap-2">
                    <i class="fa-solid fa-shield-halved text-blue-600"></i>
                    <span>Portal Monitoring & Perintah Operasional CNS/A</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <span class="block text-[11px] text-gray-400">Masuk sebagai</span>
                        <span class="block text-xs font-bold text-gray-700 capitalize">{{ auth()->user()->name ?? 'Manager Teknik' }}</span>
                    </div>
                    <div class="w-8 h-8 rounded-full bg-slate-800 text-white flex items-center justify-center font-bold text-xs uppercase">
                        {{ substr(auth()->user()->name ?? 'MT', 0, 2) }}
                    </div>
                </div>
            </header>

            <main class="flex-1 p-6 space-y-6">
                @if(session('success'))
                <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-md shadow-sm flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
                        <span class="text-emerald-800 text-sm font-medium">{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 font-bold text-base leading-none">&times;</button>
                </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

</body>
</html>