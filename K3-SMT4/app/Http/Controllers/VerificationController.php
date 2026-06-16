<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function pending()
    {
        $user = auth()->user();

        // If already validated, redirect to dashboard
        if ($user && $user->role !== 'pending' && $user->is_validated) {
            return redirect()->route('dashboard');
        }

        return view('verification.pending');
    }
}