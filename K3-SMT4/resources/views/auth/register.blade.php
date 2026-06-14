<!DOCTYPE html>
<html lang="id" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — SMK3 JNE</title>
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
                        <h1 class="text-5xl font-extrabold tracking-tight text-white">Daftar Akun Baru</h1>
                        <p class="max-w-xl text-gray-300 text-base leading-7">Buat akun untuk mengakses seluruh fitur sistem manajemen K3. Mudah, cepat, dan aman.</p>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="rounded-3xl bg-white/10 p-5 border border-white/10">
                            <p class="text-sm text-gray-300">Akses Karyawan</p>
                            <p class="mt-2 text-xl font-semibold text-white">Pantau SOP & Audit</p>
                        </div>
                        <div class="rounded-3xl bg-white/10 p-5 border border-white/10">
                            <p class="text-sm text-gray-300">Role Manager</p>
                            <p class="mt-2 text-xl font-semibold text-white">Kelola Departemen & User</p>
                        </div>
                    </div>
                </div>
                <div class="mt-10 text-sm text-gray-400">
                    <p class="mb-3 font-semibold text-white">Tips cepat</p>
                    <ul class="space-y-2 list-disc list-inside text-gray-300">
                        <li>Gunakan email institusi agar akun mudah diverifikasi.</li>
                        <li>Password minimal 8 karakter dengan kombinasi huruf dan angka.</li>
                    </ul>
                </div>
            </div>

            <div class="rounded-[2rem] overflow-hidden bg-slate-950/90 border border-white/10 shadow-2xl backdrop-blur-xl p-8">
                <div class="mb-8">
                    <p class="text-sm text-sky-400 uppercase tracking-[0.3em]">Akun Baru</p>
                    <h2 class="mt-3 text-3xl font-semibold text-white">Buat akun Anda</h2>
                    <p class="mt-2 text-sm text-slate-400">Isi data berikut untuk memulai.</p>
                </div>

                @if($errors->any())
                    <div class="mb-6 rounded-3xl border border-red-500/20 bg-red-500/10 p-4 text-sm text-red-100">
                        <ul class="space-y-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-slate-300">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name') }}" required autofocus class="mt-2 w-full rounded-3xl border border-white/10 bg-slate-950/80 px-4 py-3 text-slate-100 outline-none transition focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-300">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="mt-2 w-full rounded-3xl border border-white/10 bg-slate-950/80 px-4 py-3 text-slate-100 outline-none transition focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20" />
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-slate-300">Password</label>
                            <input type="password" name="password" required class="mt-2 w-full rounded-3xl border border-white/10 bg-slate-950/80 px-4 py-3 text-slate-100 outline-none transition focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-300">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" required class="mt-2 w-full rounded-3xl border border-white/10 bg-slate-950/80 px-4 py-3 text-slate-100 outline-none transition focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20" />
                        </div>
                    </div>

                    <div class="flex items-center justify-between gap-3 text-sm text-slate-400">
                        <a href="{{ route('login') }}" class="text-sky-300 hover:text-sky-100">Sudah punya akun? Masuk</a>
                    </div>

                    <button type="submit" class="w-full rounded-3xl bg-gradient-to-r from-red-600 to-rose-500 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-red-500/20 transition hover:opacity-95">Daftar Sekarang</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
