<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\AdminMiddleware; // Import AdminMiddleware
use App\Http\Middleware\EnsureEmailIsVerifiedForLogin; // Import EnsureEmailIsVerifiedForLogin

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            // Tambahkan middleware web yang diperlukan di sini jika ada
        ]);

        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);

        // Daftar alias middleware di sini
        $middleware->alias([
            'admin' => AdminMiddleware::class, // Middleware untuk role admin
            'verified_login' => EnsureEmailIsVerifiedForLogin::class, // Middleware untuk verifikasi email saat login
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
