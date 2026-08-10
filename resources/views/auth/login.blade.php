<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - SIMANDIS-PPPA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: { colors: { navy: '#1E3A8A' } } }
        }
    </script>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-white rounded-3xl shadow-xl border border-slate-100 p-8">
        <!-- Logo & Title -->
        <div class="text-center mb-8">
            <img src="{{ asset('logo-tanpa-nama.png') }}" alt="Logo SIMANDIS" class="h-14 sm:h-16 w-auto object-contain transition-transform group-hover:scale-200 mx-auto flex items-center justify-center">
            <h1 class="text-2xl font-bold text-navy">SIMANDIS-PPPA</h1>
            <p class="text-xs text-slate-500 mt-1">Sistem Informasi Monitoring Agenda dan Disposisi PPPA</p>
        </div>

        <!-- Alert Error -->
        @if($errors->any())
            <div class="mb-6 p-3 bg-red-50 border border-red-200 text-red-600 text-xs rounded-xl">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ url('/login') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Email / ID Akun</label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="masukkan email akun" 
                       class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-navy focus:ring-1 focus:ring-navy">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Kata Sandi</label>
                <input type="password" name="password" required placeholder="••••••••" 
                       class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-navy focus:ring-1 focus:ring-navy">
            </div>

            <button type="submit" class="w-full py-3.5 bg-navy text-white font-semibold rounded-xl text-sm shadow-lg shadow-blue-900/20 hover:bg-blue-900 transition-all">
                Masuk ke Sistem
            </button>
        </form>

        <p class="text-center text-[11px] text-slate-400 mt-8">
            Dinas PPPA Kota Banjarmasin © 2026
        </p>
    </div>

</body>
</html>