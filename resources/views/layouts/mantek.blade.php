<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AirNav DMS - @yield('title', 'Manager Teknik')</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans antialiased text-gray-800">

    <div class="min-h-screen flex">
        <!-- SIDEBAR -->
        <aside class="w-64 bg-slate-900 text-slate-300 flex flex-col shrink-0 min-h-screen fixed left-0 top-0 bottom-0 z-20">
            <!-- Brand / Logo -->
            <div class="h-16 flex items-center gap-3 px-6 bg-slate-950/70 border-b border-slate-800">
                <i class="fa-solid fa-plane-departure text-xl text-blue-400"></i>
                <span class="text-lg font-bold text-white tracking-wide">AirNav DMS</span>
            </div>

            <!-- Profile Ringkas -->
            <div class="px-6 py-4 border-b border-slate-800/60 bg-slate-900/50">
                <p class="text-xs text-slate-400 uppercase tracking-wider">Peran Pengguna</p>
                <p class="text-sm font-semibold text-white mt-0.5 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Manager Teknik
                </p>
            </div>

            <!-- Menu Navigasi -->
            <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
                <p class="px-3 text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-2">Utama</p>
                
                <a href="{{ url('/mantek/dashboard') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-medium transition {{ request()->is('mantek/dashboard*') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-gauge w-5 text-center"></i>
                    <span>Dashboard</span>
                </a>

                <p class="px-3 text-[11px] font-semibold text-slate-400 uppercase tracking-wider mt-5 mb-2">Operasional</p>

                <a href="#" class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white transition">
                    <i class="fa-solid fa-list-check w-5 text-center"></i>
                    <span>Instruksi Kerja</span>
                </a>

                <a href="#" class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white transition">
                    <i class="fa-solid fa-triangle-exclamation w-5 text-center"></i>
                    <span>Monitoring Barang</span>
                </a>

                <a href="#" class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white transition">
                    <i class="fa-solid fa-folder-tree w-5 text-center"></i>
                    <span>Arsip Dokumen</span>
                </a>

                <a href="#" class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white transition">
                    <i class="fa-solid fa-book-bookmark w-5 text-center"></i>
                    <span>Logbook CNS/A</span>
                </a>
            </nav>

            <!-- Logout Bottom -->
            <div class="p-4 border-t border-slate-800">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-rose-300 bg-rose-950/40 border border-rose-800/40 rounded-lg hover:bg-rose-900/50 hover:text-white transition">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- KONTEN KANAN (Diberi margin-left 64 agar tidak tertutup sidebar) -->
        <div class="flex-1 ml-64 flex flex-col min-h-screen">
            <!-- TOPBAR -->
            <header class="h-16 bg-white border-b border-gray-200 px-6 flex items-center justify-between sticky top-0 z-10">
                <div class="flex items-center gap-2 text-sm text-gray-500">
                    <i class="fa-solid fa-shield-halved text-blue-600"></i>
                    <span>Panel Khusus Pengawasan & Koordinasi Teknik</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <span class="block text-xs text-gray-400">Login Sebagai</span>
                        <span class="block text-sm font-semibold text-gray-800">Manager Teknik</span>
                    </div>
                    <div class="w-9 h-9 rounded-full bg-slate-800 text-white flex items-center justify-center font-bold text-sm">
                        MT
                    </div>
                </div>
            </header>

            <!-- KONTEN HALAMAN -->
            <main class="flex-1 p-6 space-y-6">
                @yield('content')
            </main>
        </div>
    </div>

</body>
</html>