<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Kredensial — K3-IMS JNE</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#0f172a] text-gray-100 min-h-screen overflow-hidden relative font-sans antialiased">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(37,99,235,0.15),_transparent_25%),radial-gradient(circle_at_bottom_right,_rgba(225,29,72,0.1),_transparent_20%)]"></div>
    
    <div class="relative min-h-screen flex items-center justify-center px-6 py-12">
        <div class="grid w-full max-w-6xl gap-8 lg:grid-cols-[1.2fr_1fr] items-stretch">
            
            <div class="rounded-[2rem] overflow-hidden bg-white/5 border border-white/10 shadow-2xl backdrop-blur-md p-10 flex flex-col justify-between relative hidden lg:flex">
                <div class="absolute top-0 right-0 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl -z-10 translate-x-1/2 -translate-y-1/2"></div>
                
                <div class="space-y-8 z-10">
                    <div class="inline-flex items-center gap-3 px-4 py-2 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-200 text-sm font-semibold tracking-wide">
                        <span class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center text-white shadow-sm">K3</span>
                        K3-IMS JNE
                    </div>
                    
                    <div class="space-y-5">
                        <h1 class="text-5xl lg:text-6xl font-extrabold tracking-tight text-white leading-tight">
                            Permintaan<br>Akses Sistem
                        </h1>
                        <p class="max-w-md text-slate-300 text-lg leading-relaxed">
                            Pendaftaran ini diperuntukkan bagi personel internal JNE. Akses fungsionalitas sistem akan disesuaikan dengan peran departemen Anda.
                        </p>
                    </div>
                    
                    <div class="grid gap-4 sm:grid-cols-2 pt-4">
                        <div class="rounded-2xl bg-slate-800/50 p-6 border border-slate-700/50">
                            <p class="text-sm text-blue-400 font-semibold mb-1">Ketentuan Kata Sandi</p>
                            <p class="text-sm text-slate-300 leading-relaxed">Gunakan kombinasi yang kuat (minimal 8 karakter) untuk melindungi data audit operasional.</p>
                        </div>
                        <div class="rounded-2xl bg-slate-800/50 p-6 border border-slate-700/50">
                            <p class="text-sm text-blue-400 font-semibold mb-1">Verifikasi Email</p>
                            <p class="text-sm text-slate-300 leading-relaxed">Sangat disarankan menggunakan alamat email resmi (nama@jne.co.id) untuk percepatan persetujuan akses.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-[2rem] bg-[#0b1221] border border-slate-800 shadow-2xl p-10 flex flex-col justify-center relative w-full max-w-md mx-auto lg:max-w-none">
                <div class="mb-8 text-center lg:text-left">
                    <h2 class="text-3xl font-bold text-white tracking-tight">Formulir Pendaftaran</h2>
                    <p class="mt-2 text-sm text-slate-400">Silakan lengkapi data diri untuk pembuatan kredensial.</p>
                </div>

                @if($errors->any())
                <div class="mb-6 rounded-2xl border border-red-500/30 bg-red-500/10 p-4 text-sm text-red-200">
                    <ul class="space-y-1 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1.5">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name') }}" 
                            class="w-full rounded-2xl border border-slate-700 bg-slate-900/50 px-4 py-3 text-slate-100 outline-none transition-all focus:border-blue-500 focus:ring-1 focus:ring-blue-500" 
                            placeholder="Sesuai identitas ID Card" required autofocus>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1.5">Alamat Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" 
                            class="w-full rounded-2xl border border-slate-700 bg-slate-900/50 px-4 py-3 text-slate-100 outline-none transition-all focus:border-blue-500 focus:ring-1 focus:ring-blue-500" 
                            placeholder="nama@jne.co.id" required>
                    </div>

                    <div class="rounded-2xl bg-amber-500/10 border border-amber-500/20 p-4 text-sm text-amber-200 flex items-start gap-3">
                        <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Setelah mendaftar, akun Anda akan masuk dalam antrian verifikasi oleh Admin. Anda akan mendapatkan akses penuh setelah disetujui.</span>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-1.5">Kata Sandi</label>
                            <input type="password" name="password" 
                                class="w-full rounded-2xl border border-slate-700 bg-slate-900/50 px-4 py-3 text-slate-100 outline-none transition-all focus:border-blue-500 focus:ring-1 focus:ring-blue-500" 
                                placeholder="••••••••" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-1.5">Konfirmasi Sandi</label>
                            <input type="password" name="password_confirmation" 
                                class="w-full rounded-2xl border border-slate-700 bg-slate-900/50 px-4 py-3 text-slate-100 outline-none transition-all focus:border-blue-500 focus:ring-1 focus:ring-blue-500" 
                                placeholder="••••••••" required>
                        </div>
                    </div>

                    <button type="submit" class="w-full rounded-2xl bg-blue-600 hover:bg-blue-500 px-6 py-4 text-sm font-bold text-white shadow-lg shadow-blue-500/25 transition-all transform hover:-translate-y-0.5 mt-2">
                        Kirim Permintaan Akses
                    </button>
                </form>

                <div class="mt-8 text-center">
                    <p class="text-sm text-slate-400">
                        Sudah memiliki akun? 
                        <a href="{{ route('login') }}" class="font-semibold text-white hover:text-blue-400 transition-colors ml-1 border-b border-transparent hover:border-blue-400 pb-0.5">Kembali ke Login</a>
                    </p>
                </div>
            </div>
            
        </div>
    </div>
</body>
</html>