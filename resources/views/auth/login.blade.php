<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - SIMANDIS-PPPA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: { extend: { colors: { navy: '#1E3A8A' } } }
        }
    </script>
    
    <style>
        input::-ms-reveal,
        input::-ms-clear {
            display: none;
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-white rounded-3xl shadow-xl border border-slate-100 p-8">
        <div class="text-center mb-8">
            <img src="{{ asset('logo-simandis.png') }}" alt="Logo SIMANDIS" class="h-14 sm:h-16 w-auto object-contain mx-auto flex items-center justify-center">
            <h1 class="text-2xl font-bold text-navy mt-2">SIMANDIS-PPPA</h1>
            <p class="text-xs text-slate-500 mt-1">Sistem Informasi Monitoring Agenda dan Disposisi PPPA</p>
        </div>

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
                <div class="relative">
                    <input type="password" id="passwordInput" name="password" required placeholder="••••••••" 
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-navy focus:ring-1 focus:ring-navy pr-10">
                    
                    <button type="button" onclick="togglePassword()" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-navy text-sm transition-colors focus:outline-none">
                        <i id="eyeIcon" class="fa-regular fa-eye"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="w-full py-3.5 bg-navy text-white font-semibold rounded-xl text-sm shadow-lg shadow-blue-900/20 hover:bg-blue-900 transition-all">
                Masuk ke Sistem
            </button>
        </form>

        <p class="text-center text-[11px] text-slate-400 mt-8">
            Dinas Pemberdayaan Perempuan dan Perlindungan Anak Kota Banjarmasin © 2026
        </p>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('passwordInput');
            const eyeIcon = document.getElementById('eyeIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye', 'fa-regular');
                eyeIcon.classList.add('fa-eye-slash', 'fa-solid');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash', 'fa-solid');
                eyeIcon.classList.add('fa-eye', 'fa-regular');
            }
        }
    </script>

</body>
</html>