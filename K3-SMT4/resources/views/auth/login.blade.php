<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — SMK3 JNE</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#0f172a] text-gray-100 min-h-screen overflow-hidden relative font-sans antialiased">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(37,99,235,0.15),_transparent_25%),radial-gradient(circle_at_bottom_right,_rgba(225,29,72,0.1),_transparent_20%)]"></div>
    
    <div class="relative min-h-screen flex items-center justify-center px-6 py-12">
        <div class="grid w-full max-w-6xl gap-8 lg:grid-cols-[1.2fr_1fr] items-stretch">
            
            <div class="rounded-[2rem] overflow-hidden bg-white/5 border border-white/10 shadow-2xl backdrop-blur-md p-10 flex flex-col justify-between relative">
                <div class="absolute top-0 right-0 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl -z-10 translate-x-1/2 -translate-y-1/2"></div>
                
                <div class="space-y-8 z-10">
                    <div class="inline-flex items-center gap-3 px-4 py-2 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-200 text-sm font-semibold tracking-wide">
                        <span class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center text-white shadow-sm">K3</span>
                        SMK3 JNE
                    </div>
                    
                    <div class="space-y-5">
                        <h1 class="text-5xl lg:text-6xl font-extrabold tracking-tight text-white leading-tight">
                            Keselamatan<br>Kerja Utama
                        </h1>
                        <p class="max-w-md text-slate-300 text-lg leading-relaxed">
                            Sistem terintegrasi untuk manajemen K3, pemantauan SOP, pelaporan insiden, dan audit internal JNE.
                        </p>
                    </div>
                    
                    <div class="grid gap-4 sm:grid-cols-2 pt-4">
                        <div class="rounded-2xl bg-slate-800/50 p-6 border border-slate-700/50 hover:bg-slate-800/80 transition-colors">
                            <div class="w-10 h-10 rounded-full bg-blue-500/20 flex items-center justify-center mb-4 text-blue-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            </div>
                            <p class="text-lg font-semibold text-white mb-1">Keamanan Data</p>
                            <p class="text-sm text-slate-400 leading-relaxed">Akses terenkripsi dan rekam jejak audit tersimpan aman.</p>
                        </div>
                        <div class="rounded-2xl bg-slate-800/50 p-6 border border-slate-700/50 hover:bg-slate-800/80 transition-colors">
                            <div class="w-10 h-10 rounded-full bg-blue-500/20 flex items-center justify-center mb-4 text-blue-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <p class="text-lg font-semibold text-white mb-1">Kontrol Peran</p>
                            <p class="text-sm text-slate-400 leading-relaxed">Hierarki akses sesuai dengan departemen dan jabatan.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-[2rem] bg-[#0b1221] border border-slate-800 shadow-2xl p-10 flex flex-col justify-center">
                <div class="mb-10 text-center">
                    <h2 class="text-3xl font-bold text-white tracking-tight">Login Portal</h2>
                    <p class="mt-3 text-sm text-slate-400">Masukkan kredensial akun Anda untuk mengakses sistem.</p>
                </div>

                @if(session('error'))
                <div class="mb-8 rounded-2xl border border-red-500/30 bg-red-500/10 p-4 text-sm text-red-200 flex items-center gap-3">
                    <svg class="w-5 h-5 text-red-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('error') }}
                </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Alamat Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                            </div>
                            <input type="email" name="email" value="{{ old('email') }}" 
                                class="w-full rounded-2xl border border-slate-700 bg-slate-900/50 pl-11 pr-4 py-3.5 text-slate-100 placeholder-slate-500 outline-none transition-all focus:border-blue-500 focus:ring-1 focus:ring-blue-500" 
                                placeholder="nama@jne.co.id" required autofocus>
                        </div>
                        @error('email') <p class="text-xs text-red-400 mt-2 ml-1">{{ $message }}</p> @enderror
                    </div>
                    
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-sm font-medium text-slate-300">Kata Sandi</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-xs font-medium text-blue-400 hover:text-blue-300 transition-colors">Lupa sandi?</a>
                            @endif
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </div>
                            <input type="password" name="password" 
                                class="w-full rounded-2xl border border-slate-700 bg-slate-900/50 pl-11 pr-4 py-3.5 text-slate-100 placeholder-slate-500 outline-none transition-all focus:border-blue-500 focus:ring-1 focus:ring-blue-500" 
                                placeholder="••••••••" required>
                        </div>
                        @error('password') <p class="text-xs text-red-400 mt-2 ml-1">{{ $message }}</p> @enderror
                    </div>
                    
                    <div class="flex items-center pt-2">
                        <div class="flex items-center h-5">
                            <input id="remember" name="remember" type="checkbox" class="h-4 w-4 rounded border-slate-600 bg-slate-900 text-blue-500 focus:ring-blue-500 focus:ring-offset-slate-900 transition-colors cursor-pointer">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="remember" class="font-medium text-slate-400 cursor-pointer">Ingat saya di perangkat ini</label>
                        </div>
                    </div>

                    <button type="submit" class="w-full rounded-2xl bg-blue-600 hover:bg-blue-500 px-6 py-4 text-sm font-bold text-white shadow-lg shadow-blue-500/25 transition-all transform hover:-translate-y-0.5 mt-4 flex items-center justify-center gap-2">
                        Masuk ke Sistem
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </form>

                @if (Route::has('register'))
                <div class="mt-8 text-center">
                    <p class="text-sm text-slate-400">
                        Belum memiliki akun? 
                        <a href="{{ route('register') }}" class="font-semibold text-white hover:text-blue-400 transition-colors ml-1 border-b border-transparent hover:border-blue-400 pb-0.5">Daftar Sekarang</a>
                    </p>
                </div>
                @endif
            </div>
            
        </div>
    </div>
</body>
</html>