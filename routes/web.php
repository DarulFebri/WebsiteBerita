<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController; // Import HomeController
use App\Http\Controllers\NewsController; // Import NewsController
use App\Http\Controllers\Auth\AuthenticatedSessionController; // Import AuthenticatedSessionController
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; // Tambahkan ini untuk memeriksa status autentikasi

// Rute untuk mengarahkan root '/'
Route::get('/', function () {
    if (Auth::check()) {
        // Jika user terautentikasi, arahkan ke halaman berita
        return redirect()->route('berita.index');
    }
    // Jika user tidak terautentikasi, arahkan ke halaman login
    return redirect()->route('login');
});

// Rute yang membutuhkan otentikasi (semua user yang sudah login, baik user biasa maupun admin)
Route::middleware(['auth'])->group(function () { // Hanya 'auth' karena verified_login sudah menangani di login
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Fitur User: Hanya bisa melihat daftar berita
    // Halaman /berita menampilkan daftar berita terbaru (judul, foto, penulis, tanggal).
    // Halaman /berita/{news} menampilkan detail berita.
    Route::get('/berita', [HomeController::class, 'index'])->name('berita.index');
    Route::get('/berita/{news}', [HomeController::class, 'show'])->name('berita.show');
});

// Rute yang hanya bisa diakses oleh Admin
// Gunakan middleware auth dan admin untuk mengamankan fitur admin.
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('news', NewsController::class); // Rute resource untuk manajemen berita
});

// Kustomisasi rute login untuk menerapkan middleware 'verified_login'
// Ini harus ditempatkan SETELAH rute auth() yang disediakan Breeze
Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('verified_login');

// Termasuk rute otentikasi Breeze lainnya (register, reset password, etc.)
require __DIR__.'/auth.php';
