<!DOCTYPE html>
<html lang="id" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — SMK3 JNE</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#0b1220] text-gray-100 min-h-screen overflow-hidden relative">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(239,68,68,0.25),_transparent_25%),radial-gradient(circle_at_bottom_right,_rgba(14,165,233,0.18),_transparent_20%)]"></div>
    <div class="relative min-h-screen flex items-center justify-center px-4 py-10">
        <div class="grid w-full max-w-6xl gap-8 lg:grid-cols-[1.2fr_1fr]">
            <div class="rounded-[2rem] overflow-hidden bg-white/5 border border-white/10 shadow-2xl backdrop-blur-xl p-8 flex flex-col justify-between">
                <div class="space-y-6">
                    <div class="inline-flex items-center gap-3 px-4 py-2 rounded-full bg-red-600/15 text-red-200 text-sm font-semibold">
                        <span class="w-10 h-10 rounded-xl bg-red-600/90 flex items-center justify-center">K3</span>
                        SMK3 JNE
                    </div>
                    <div class="space-y-4">
                        <h1 class="text-5xl font-extrabold tracking-tight text-white">Selamat Datang Kembali</h1>
                        <p class="max-w-xl text-gray-300 text-base leading-7">Masuk untuk melanjutkan monitoring SOP, audit, dan manajemen K3.</p>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="rounded-3xl bg-white/10 p-5 border border-white/10">
                            <p class="text-sm text-gray-300">Keamanan</p>
                            <p class="mt-2 text-xl font-semibold text-white">Login aman</p>
                        </div>
                        <div class="rounded-3xl bg-white/10 p-5 border border-white/10">
                            <p class="text-sm text-gray-300">Kontrol Akses</p>
                            <p class="mt-2 text-xl font-semibold text-white">Role sesuai</p>
                        </div>
                    </div>
                </div>
                <div class="mt-10 text-sm text-gray-400">
                    <p class="mb-3 font-semibold text-white">Catatan</p>
                    <ul class="space-y-2 list-disc list-inside text-gray-300">
                        <li>Gunakan akun resmi untuk akses data audit dan SOP.</li>
                        <li>Hubungi admin jika mengalami masalah login.</li>
                    </ul>
                </div>
            </div>

            <div class="rounded-[2rem] overflow-hidden bg-slate-950/90 border border-white/10 shadow-2xl backdrop-blur-xl p-8">
                <div class="mb-8">
                    <p class="text-sm text-sky-400 uppercase tracking-[0.3em]">Masuk</p>
                    <h2 class="mt-3 text-3xl font-semibold text-white">Masuk ke Akun Anda</h2>
                    <p class="mt-2 text-sm text-slate-400">Gunakan email dan password yang sudah terdaftar.</p>
                </div>

                @if(session('error'))
                <div class="mb-6 rounded-3xl border border-red-500/20 bg-red-500/10 p-4 text-sm text-red-100">
                    {{ session('error') }}
                </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-slate-300">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="mt-2 w-full rounded-3xl border border-white/10 bg-slate-950/80 px-4 py-3 text-slate-100 outline-none transition focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20" required autofocus>
                        @error('email') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-300">Password</label>
                        <input type="password" name="password" class="mt-2 w-full rounded-3xl border border-white/10 bg-slate-950/80 px-4 py-3 text-slate-100 outline-none transition focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20" required>
                        @error('password') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex items-center justify-between gap-3 text-sm text-slate-400">
                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" name="remember" id="remember" class="h-4 w-4 rounded border-slate-500 bg-slate-900 text-sky-500 focus:ring-sky-400">
                            Ingat saya
                        </label>
                        <a href="{{ route('password.request') }}" class="text-sky-300 hover:text-sky-100">Lupa password?</a>
                    </div>
                    <button type="submit" class="w-full rounded-3xl bg-gradient-to-r from-red-600 to-rose-500 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-red-500/20 transition hover:opacity-95">Masuk</button>
                </form>

                <div class="mt-6 text-center text-sm text-slate-400">
                    Belum punya akun? <a href="{{ route('register') }}" class="font-semibold text-sky-300 hover:text-sky-100">Daftar sekarang</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>