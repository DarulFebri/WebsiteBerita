<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Tangani permintaan yang masuk.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Pastikan user terautentikasi dan memiliki role 'admin'
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Akses Ditolak. Anda tidak memiliki hak akses administrator.');
        }

        return $next($request);
    }
}
