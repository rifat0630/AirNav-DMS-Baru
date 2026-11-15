@php
    $user = auth()->user();
    $roleRaw = strtolower($user->role ?? 'teknisi');
    $isMantek = in_array($roleRaw, ['manager teknik', 'manager_teknik', 'mantek']);
    $isAdmin = in_array($roleRaw, ['admin', 'administrator']);

    $pendingLogbook = \Illuminate\Support\Facades\Schema::hasTable('logbooks')
        ? \App\Models\FacilityLogbook::where('status', 'Menunggu Paraf Mantek')->count()
        : 0;
@endphp

<aside class="w-64 bg-[#0f172a] text-slate-200 flex flex-col min-h-screen shrink-0 select-none">
    {{-- Brand Header --}}
    <div class="px-5 py-4 border-b border-slate-800 flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center text-white font-bold">
            ✈️
        </div>
        <span class="font-bold text-lg tracking-tight text-white">AirNav DMS</span>
    </div>

    {{-- Profil Pengguna Terautentikasi --}}
    <div class="px-5 py-4 border-b border-slate-800 bg-slate-900/50">
        <p class="text-[10px] font-semibold tracking-wider text-slate-400 uppercase mb-1">
            Pengguna Terautentikasi
        </p>
        <div class="flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
            <span class="text-sm font-bold text-white truncate">{{ $user->name ?? 'Pengguna' }}</span>
        </div>
        <p class="text-[11px] text-emerald-400 mt-0.5 leading-snug">
            {{ ucwords(str_replace('_', ' ', $user->role ?? 'Teknisi CNS/A')) }} AirNav Indonesia Cabang Banjarmasin
        </p>
    </div>

    {{-- Daftar Menu Navigasi --}}
    <nav class="flex-1 px-3 py-4 space-y-5 overflow-y-auto">
        {{-- Bagian: UTAMA --}}
        <div>
            <p class="px-3 text-[10px] font-bold tracking-wider text-slate-400 uppercase mb-2">
                Utama
            </p>
            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <span>⏱️</span>
                <span>Dashboard Utama</span>
            </a>
        </div>

        {{-- Bagian: OPERASIONAL CNS/A (Semua Role: Teknisi, Mantek, Admin) --}}
        <div>
            <p class="px-3 text-[10px] font-bold tracking-wider text-slate-400 uppercase mb-2">
                Operasional CNS/A
            </p>
            <div class="space-y-1">
                <a href="{{ url('/arsip-dokumen') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->is('arsip-dokumen*') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <span>📁</span>
                    <span>Arsip Dokumen</span>
                </a>

                {{-- Menu Logbook Harian + Badge Pending Paraf Mantek --}}
                <a href="{{ route('logbook.index') }}"
                   class="flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('logbook.*') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <span class="flex items-center gap-3">
                        <span>📘</span>
                        <span>Logbook Harian</span>
                    </span>
                    @if($pendingLogbook > 0)
                        <span class="bg-amber-400 text-slate-900 text-[10px] font-bold px-2 py-0.5 rounded-full">
                            {{ $pendingLogbook }}
                        </span>
                    @endif
                </a>

                <a href="{{ url('/monitoring-fasilitas') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->is('monitoring-fasilitas*') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <span>🛠️</span>
                    <span>Inventory & Fasilitas</span>
                </a>

                <a href="{{ url('/instruksi-kerja') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->is('instruksi-kerja*') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <span>📋</span>
                    <span>Instruksi Kerja Teknisi</span>
                </a>
            </div>
        </div>

        {{-- Bagian: WEWENANG MANAGER TEKNIK --}}
        @if($isMantek || $isAdmin)
            <div>
                <p class="px-3 text-[10px] font-bold tracking-wider text-emerald-400 uppercase mb-2">
                    Wewenang Manager Teknik
                </p>
                <div class="space-y-1">
                    <a href="{{ url('/manager-teknik/dashboard') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->is('manager-teknik/dashboard*') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span>🔧</span>
                        <span>Panel & Perintah Dinas</span>
                    </a>

                    <a href="{{ url('/manager-teknik/handover') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->is('manager-teknik/handover*') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span>📝</span>
                        <span>Memori Serah Terima</span>
                    </a>
                </div>
            </div>
        @endif

        {{-- Bagian: ADMINISTRASI SISTEM --}}
        @if($isAdmin || $isMantek)
            <div>
                <p class="px-3 text-[10px] font-bold tracking-wider text-amber-400 uppercase mb-2">
                    Administrasi Sistem
                </p>
                <div class="space-y-1">
                    <a href="{{ url('/activity-log') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->is('activity-log*') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span>🕒</span>
                        <span>Activity Logs</span>
                    </a>
                </div>
            </div>
        @endif
    </nav>

    {{-- Tombol Logout --}}
    <div class="p-3 border-t border-slate-800">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg bg-rose-950/60 hover:bg-rose-900/80 border border-rose-800/60 text-rose-300 text-sm font-medium transition cursor-pointer">
                <span>🚪</span>
                <span>Logout</span>
            </button>
        </form>
    </div>
</aside>