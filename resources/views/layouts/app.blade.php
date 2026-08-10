<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMANDIS-PPPA - DPPPA Kota Banjarmasin</title>
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
<body class="bg-[#F8FAFC] font-sans text-slate-800 antialiased">

    <div class="flex min-h-screen">
        <aside class="w-64 bg-white border-r border-slate-200 flex flex-col justify-between fixed h-full z-20">
            <div>
                <div class="p-5 flex items-center gap-3 border-b border-slate-100">
                    <img src="{{ asset('logo-simandis.png') }}" alt="Logo SIMANDIS" class="w-auto h-16 object-contain shadow-xs rounded-xl">
                    <div>
                        <h1 class="font-black text-[#1E3A8A] text-base leading-tight">SIMANDIS-PPPA</h1>
                        <p class="text-[9px] text-slate-400 font-bold tracking-wider uppercase">Sistem Informasi Monitoring Agenda dan Disposisi</p>
                    </div>
                </div>

                <nav class="p-4 space-y-2">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-wider px-3 mb-1">MAIN MENU</p>

                    @if(Auth::user()->role === 'user')
                        @php
                            $bidangList = [
                                1 => 'Bidang PKA',
                                2 => 'Bidang PP',
                                3 => 'Bidang PHA',
                                4 => 'Bidang KHP'
                            ];
                            $userBidangName = $bidangList[Auth::user()->bidang_id] ?? 'Bidang Saya';
                        @endphp

                        <a href="{{ route('mading.index') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('mading.index') && !request('bidang') ? 'bg-blue-50 text-[#1E3A8A]' : 'text-slate-600 hover:bg-slate-50' }}">
                            <i class="fa-regular fa-calendar-check text-sm w-4"></i>
                            <span>Mading Utama</span>
                        </a>

                        <a href="{{ route('mading.bidang') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('mading.bidang') ? 'bg-blue-50 text-[#1E3A8A]' : 'text-slate-600 hover:bg-slate-50' }}">
                            <i class="fa-solid fa-users text-sm w-4"></i>
                            <span>{{ $userBidangName }}</span>
                        </a>
                    @else
                        <a href="{{ route('mading.index') }}" 
                           class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('mading.index') && !request('bidang') ? 'bg-blue-50 text-[#1E3A8A]' : 'text-slate-600 hover:bg-slate-50' }}">
                            <div class="flex items-center gap-3">
                                <i class="fa-regular fa-calendar text-sm w-4"></i>
                                <span>Mading Utama</span>
                            </div>
                            @if(request()->routeIs('mading.index') && !request('bidang'))
                                <span class="w-1.5 h-6 bg-[#1E3A8A] rounded-full -mr-3.5"></span>
                            @endif
                        </a>

                        <a href="{{ route('dashboard') }}" 
                           class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-[#1E3A8A]' : 'text-slate-600 hover:bg-slate-50' }}">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-border-all text-sm w-4"></i>
                                <span>Dashboard Monitor</span>
                            </div>
                            @if(request()->routeIs('dashboard'))
                                <span class="w-1.5 h-6 bg-[#1E3A8A] rounded-full -mr-3.5"></span>
                            @endif
                        </a>

                        @if(Auth::user()->role === 'admin')
                        <a href="{{ route('surat.index') }}" 
                           class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('surat.*') ? 'bg-blue-50 text-[#1E3A8A]' : 'text-slate-600 hover:bg-slate-50' }}">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-box-archive text-sm w-4"></i>
                                <span>Arsip Surat Undangan</span>
                            </div>
                            @if(request()->routeIs('surat.*'))
                                <span class="w-1.5 h-6 bg-[#1E3A8A] rounded-full -mr-3.5"></span>
                            @endif
                        </a>
                        @endif
                    @endif
                </nav>
            </div>

            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-9 h-9 rounded-full bg-navy text-white flex items-center justify-center font-bold text-xs">
                        {{ strtoupper(substr(Auth::user()->nama ?? Auth::user()->name ?? 'U', 0, 2)) }}
                    </div>
                    <div class="truncate">
                        <!-- Nama User Dinamis -->
                        <p class="text-xs font-bold text-slate-800 truncate">
                            {{ Auth::user()->nama ?? Auth::user()->name ?? 'Pengguna' }}
                        </p>
                        
                        <p class="text-[10px] font-extrabold text-navy uppercase">
                            @if(Auth::user()->role === 'kadis')
                                KEPALA DINAS
                            @elseif(Auth::user()->role === 'admin')
                                SEKRETARIS
                            @else
                                STAF USER
                            @endif
                        </p>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full py-2 bg-rose-700 hover:bg-rose-800 text-white rounded-xl text-xs font-bold transition-colors flex items-center justify-center gap-2 shadow-sm">
                        <i class="fa-solid fa-right-from-bracket"></i> Logout
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 ml-64 p-8 flex flex-col justify-between min-h-screen">
            <div>
                @yield('content')
            </div>

            <footer class="mt-12 pt-6 border-t border-slate-200/60 flex flex-col md:flex-row items-center justify-between text-[11px] text-slate-400 gap-4">
                <div>
                    &copy; {{ date('Y') }} Dinas Pemberdayaan Perempuan dan Anak Kota Banjarmasin - Sistem Informasi Manajemen Agenda & Disposisi
                </div>
                <div class="flex items-center gap-6 font-semibold">
                    <a href="#" class="hover:text-slate-600 transition-colors">Panduan Pengguna</a>
                    <a href="#" class="hover:text-slate-600 transition-colors">Pusat Bantuan</a>
                    <a href="#" class="hover:text-slate-600 transition-colors">Hubungi IT</a>
                </div>
            </footer>
        </main>
    </div>

</body>
</html>