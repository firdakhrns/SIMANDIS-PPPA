<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMANDIS-PPPA - Portal Resmi DINAS PEMBERDAYAAN PEREMPUAN DAN PERLINDUNGAN ANAK KOTA BANJARMASIN Kota Banjarmasin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: '#0C2340',
                        brandBlue: '#1E3A8A',
                    }
                }
            }
        }
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-slate-50 font-sans text-slate-800 antialiased selection:bg-brandBlue selection:text-white">

    <header class="bg-white border-b border-slate-100 sticky top-0 z-50 shadow-xs">
        <div class="max-w-7xl mx-auto px-6 h-24 flex items-center justify-between">
            <a href="{{ route('landing') }}" class="flex items-center gap-4 group">
                <img src="{{ asset('logo-simandis.png') }}" alt="Logo SIMANDIS" class="h-14 sm:h-16 w-auto object-contain transition-transform group-hover:scale-105">
                <div>
                    <h1 class="font-black text-brandBlue text-xl sm:text-2xl leading-none tracking-tight">SIMANDIS-PPPA</h1>
                    <p class="text-[10px] sm:text-[11px] font-bold text-slate-400 tracking-wider uppercase mt-1">DINAS PEMBERDAYAAN PEREMPUAN DAN PERLINDUNGAN ANAK KOTA BANJARMASIN</p>
                </div>
            </a>

            @auth
                <a href="{{ Auth::user()->role === 'user' ? route('mading.index') : route('dashboard') }}" 
                   class="px-6 py-3 bg-brandBlue text-white text-xs font-bold rounded-xl hover:bg-blue-900 transition-all shadow-md">
                    Buka Dashboard <i class="fa-solid fa-arrow-right ml-2"></i>
                </a>
            @else
                <a href="{{ route('login') }}" 
                   class="px-6 py-3 bg-brandBlue text-white text-xs font-bold rounded-xl hover:bg-blue-900 transition-all shadow-md">
                    Masuk ke Sistem
                </a>
            @endauth
        </div>
    </header>

    <section class="relative min-h-[600px] lg:min-h-[680px] flex items-center bg-cover bg-center bg-no-repeat overflow-hidden" 
             style="background-image: url('{{ asset('bg-landing.png') }}');">
        
        <div class="absolute inset-0 bg-gradient-to-r from-slate-950/85 via-slate-900/70 to-slate-950/40"></div>
        <div class="absolute inset-x-0 bottom-0 h-32 bg-gradient-to-t from-slate-900 via-slate-900/40 to-transparent"></div>

        <div class="relative max-w-7xl mx-auto px-6 py-20 w-full z-10 space-y-6">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white text-xs font-semibold">
                <span>PORTAL RESMI DINAS PEMBERDAYAAN PEREMPUAN DAN PERLINDUNGAN ANAK KOTA BANJARMASIN KOTA BANJARMASIN</span>
            </div>

            <div class="max-w-2xl space-y-3">
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-white tracking-tight leading-tight">
                    SIMANDIS-PPPA
                </h1>
                <h2 class="text-xl sm:text-2xl font-bold text-slate-200">
                    Sistem Informasi Monitoring Agenda dan Disposisi PPPA
                </h2>
                <p class="text-sm sm:text-base text-slate-300 leading-relaxed font-normal pt-2">
                    Portal Informasi Terpadu Mading Agenda Internal, Monitoring Disposisi Pimpinan, dan Realisasi Kegiatan Dinas Pemberdayaan Perempuan dan Perlindungan Anak Kota Banjarmasin.
                </p>
            </div>

            <div class="pt-4">
                <a href="{{ route('login') }}" 
                   class="inline-flex items-center gap-3 px-8 py-3.5 bg-brandBlue hover:bg-blue-700 text-white text-xs font-extrabold rounded-xl transition-all shadow-lg hover:shadow-blue-500/25 transform hover:-translate-y-0.5">
                    <span>MASUK KE SISTEM PORTAL</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>

            <div class="pt-16 grid grid-cols-1 sm:grid-cols-3 gap-6 max-w-2xl border-t border-white/10">
                <div>
                    <h3 class="text-lg font-bold text-white">Real-time</h3>
                    <p class="text-xs text-slate-400 font-medium">MONITORING DATA</p>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-white">Terpadu</h3>
                    <p class="text-xs text-slate-400 font-medium">SISTEM INFORMASI</p>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-white">Akurat</h3>
                    <p class="text-xs text-slate-400 font-medium">PELAPORAN KEGIATAN</p>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-slate-50 border-t border-slate-200/80 py-12 text-xs text-slate-600">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
            <div class="space-y-3">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('logo-simandis.png') }}" alt="Logo SIMANDIS" class="h-16 w-auto">
                </div>
                <p class="max-w-sm text-slate-500 leading-relaxed font-medium">
                    Sistem Informasi Manajemen Data Strategis Pemberdayaan Perempuan dan Perlindungan Anak Kota Banjarmasin.
                </p>
            </div>

            <div class="space-y-2">
                <h4 class="font-bold text-slate-800 uppercase tracking-wider text-[11px]">ALAMAT KANTOR</h4>
                <p class="text-slate-500 leading-relaxed">
                    Dinas Pemberdayaan Perempuan dan Perlindungan Anak<br>
                    Gedung Kantor Disdukcapil Lantai III, Jl. Sultan Adam No. 18 RT 28 Kelurahan Surgi Mufti, Kecamatan Banjarmasin Utara, Kota Banjarmasin, Kalimantan Selatan 70122
                </p>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-6 pt-6 border-t border-slate-200/60 text-center text-[11px] text-slate-400">
            &copy; {{ date('Y') }} SIMANDIS-PPPA. Hak Cipta Dilindungi Undang-Undang.
        </div>
    </footer>

</body>
</html>