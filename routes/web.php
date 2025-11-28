<?php

use App\Http\Controllers\absensiMahasiswaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KrsController;
use App\Http\Controllers\RencanaStudiController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Auth Routes
Route::get('/', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('fungsi-login')->middleware('guest');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Dashboard Route
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [absensiMahasiswaController::class, 'index'])->name('dashboard.index')->middleware('auth');

    Route::get('/krs', [RencanaStudiController::class, 'index'])->name('krs.index');

    Route::post('/rencana-studi/simpan', [RencanaStudiController::class, 'store'])->name('rencana-studi.store');
    // (opsional) jika ingin akses halaman KRS sebagai /rencana-studi
    // Route::get('/dashboard', [\App\Http\Controllers\RencanaStudiController::class, 'index'])->name('dashboard.index');
});
