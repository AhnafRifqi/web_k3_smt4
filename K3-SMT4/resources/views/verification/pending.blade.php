<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Menunggu Verifikasi — SMK3 JNE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
</head>
<body class="h-full bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-900 dark:to-gray-800 font-sans antialiased">

    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="max-w-md w-full bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 p-8 text-center">

            <!-- Icon -->
            <div class="mx-auto w-20 h-20 bg-amber-100 dark:bg-amber-900/30 rounded-full flex items-center justify-center mb-6">
                <svg class="w-10 h-10 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>

            <!-- Title -->
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                Akun Anda Menunggu Verifikasi
            </h1>
            <p class="text-gray-500 dark:text-gray-400 mb-6">
                Terima kasih telah mendaftar. Akun Anda saat ini masih dalam status menunggu persetujuan dari Admin. Silakan cek kembali secara berkala.
            </p>

            <!-- User Info -->
            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 mb-6 text-left">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 font-bold text-sm">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white text-sm">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</p>
                    </div>
                </div>
                <div class="border-t border-gray-200 dark:border-gray-600 pt-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 dark:text-gray-400">Status</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">
                            Pending
                        </span>
                    </div>
                    <div class="flex justify-between text-sm mt-2">
                        <span class="text-gray-500 dark:text-gray-400">Tanggal Daftar</span>
                        <span class="text-gray-900 dark:text-white">{{ auth()->user()->created_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Info Alert -->
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4 mb-6 text-sm text-blue-700 dark:text-blue-400 text-left">
                <div class="flex items-start gap-2">
                    <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Setelah disetujui Admin, Anda akan mendapatkan akses penuh ke sistem SMK3 JNE. Silakan <strong>logout</strong> dan login kembali setelah akun Anda divalidasi.</span>
                </div>
            </div>

            <!-- Logout Button -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 text-sm font-medium text-white bg-red-600 rounded-xl hover:bg-red-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Logout
                </button>
            </form>

        </div>
    </div>

</body>
</html>