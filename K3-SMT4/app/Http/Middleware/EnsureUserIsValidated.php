<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsValidated
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Jika user belum divalidasi, redirect ke halaman menunggu verifikasi
        if ($user->is_validated === false) {
            return redirect()->route('verification.pending');
        }

        return $next($request);
    }
}