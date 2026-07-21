<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMANDIS-PPPA - Dinas PPPA Kota Banjarmasin</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: '#1E3A8A',
                        pka: '#8B5CF6',
                        pp: '#EC4899',
                        pha: '#10B981',
                        khp: '#06B6D4',
                    }
                }
            }
        }
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-slate-50 font-sans antialiased text-slate-800">

    <div class="flex min-h-screen">
        <!-- 📌 SIDEBAR LEFT NAVIGATION (FIXED 260px) -->
        <aside class="w-64 bg-white border-r border-slate-200 flex flex-col justify-between fixed h-full z-20">
            <div>
                <!-- Logo & Brand -->
                <div class="p-5 flex items-center gap-3 border-b border-slate-100">
                    <div class="w-10 h-10 bg-navy rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-md">
                        S
                    </div>
                    <div>
                        <h1 class="font-bold text-navy text-lg leading-tight">SIMANDIS-PPPA</h1>
                        <p class="text-xs text-slate-400">DP3A Banjarmasin Portal</p>
                    </div>
                </div>

                <!-- Navigation Menu -->
                <nav class="p-4 space-y-1">
                    <a href="{{ route('mading.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm transition-all {{ request()->routeIs('mading.index') ? 'bg-blue-50 text-navy font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">
                        <i class="fa-solid fa-clipboard-list w-5"></i>
                        <span>Mading Utama (Global)</span>
                    </a>

                    <!-- Sidebar Filter Bidang -->
                    <div class="pt-4 pb-2 px-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">
                        Filter Bidang
                    </div>
                    <a href="{{ route('mading.index', ['bidang' => 1]) }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm text-slate-600 hover:bg-purple-50 hover:text-pka transition-all">
                        <span class="w-2.5 h-2.5 rounded-full bg-pka"></span>
                        <span>Bidang PKA</span>
                    </a>
                    <a href="{{ route('mading.index', ['bidang' => 2]) }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm text-slate-600 hover:bg-pink-50 hover:text-pp transition-all">
                        <span class="w-2.5 h-2.5 rounded-full bg-pp"></span>
                        <span>Bidang PP</span>
                    </a>
                    <a href="{{ route('mading.index', ['bidang' => 3]) }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm text-slate-600 hover:bg-emerald-50 hover:text-pha transition-all">
                        <span class="w-2.5 h-2.5 rounded-full bg-pha"></span>
                        <span>Bidang PHA</span>
                    </a>
                    <a href="{{ route('mading.index', ['bidang' => 4]) }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm text-slate-600 hover:bg-cyan-50 hover:text-khp transition-all">
                        <span class="w-2.5 h-2.5 rounded-full bg-khp"></span>
                        <span>Bidang KHP</span>
                    </a>
                </nav>
            </div>

            <!-- User Profile Bottom Badge & Logout -->
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-navy text-white flex items-center justify-center font-bold text-sm">
                            {{ strtoupper(substr(Auth::user()->name ?? Auth::user()->email ?? 'U', 0, 2)) }}
                        </div>
                        <div class="truncate max-w-[120px]">
                            <p class="text-xs font-bold text-slate-800 truncate">{{ Auth::user()->name ?? 'Pengguna' }}</p>
                            <span class="inline-block px-2 py-0.5 text-[10px] font-semibold uppercase rounded bg-blue-100 text-navy">
                                {{ Auth::user()->role ?? 'User' }}
                            </span>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-slate-400 hover:text-red-500 p-2 transition-colors" title="Keluar">
                            <i class="fa-solid fa-right-from-bracket"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- 📌 MAIN CONTENT AREA -->
        <main class="flex-1 ml-64 p-8">
            <!-- Flash Message Success -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-circle-check text-emerald-500 text-lg"></i>
                        <p class="text-sm font-medium">{{ session('success') }}</p>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

</body>
</html>