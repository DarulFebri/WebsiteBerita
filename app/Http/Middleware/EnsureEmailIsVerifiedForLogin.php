<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureEmailIsVerifiedForLogin
{
    /**
     * Tangani permintaan yang masuk.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Jika user mencoba login, cek apakah emailnya sudah diverifikasi
        // Middleware ini diterapkan pada POST /login, sehingga Auth::attempt sudah terjadi
        // atau akan segera terjadi. Kita akan menangani ini secara kustom.

        // Jika user sudah terautentikasi dan emailnya belum diverifikasi,
        // logout user dan arahkan ke halaman verifikasi email.
        if (Auth::check() && !Auth::user()->hasVerifiedEmail()) {
            Auth::logout(); // Logout user yang belum terverifikasi
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Redirect ke halaman pemberitahuan verifikasi email
            return redirect()->route('verification.notice')->with('status', 'email-not-verified');
        }

        return $next($request);
    }
}
