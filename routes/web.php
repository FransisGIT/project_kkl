<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BerandaController;
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
Route::post('/switch-role', [AuthController::class, 'switchRole'])->name('switch-role')->middleware('auth');

// Dashboard Route
Route::middleware(['auth'])->group(function () {
    Route::get('/beranda', [BerandaController::class, 'index'])->name('beranda.index')->middleware('auth');

    // KRS untuk Mahasiswa
    Route::get('/krs', [RencanaStudiController::class, 'index'])->name('krs.index');
    Route::post('/rencana-studi/simpan', [RencanaStudiController::class, 'store'])->name('rencana-studi.store');
<<<<<<< HEAD

    // Jadwal Kuliah untuk Mahasiswa
    Route::get('/jadwal-kuliah', [\App\Http\Controllers\JadwalKuliahController::class, 'index'])->name('jadwal-kuliah.index');

    // Persetujuan KRS untuk Admin/Dosen
    Route::get('/persetujuan-krs', [\App\Http\Controllers\PersetujuanKrsController::class, 'index'])->name('persetujuan-krs.index');
    Route::post('/persetujuan-krs/{id}/approve', [\App\Http\Controllers\PersetujuanKrsController::class, 'approve'])->name('persetujuan-krs.approve');
    Route::post('/persetujuan-krs/{id}/reject', [\App\Http\Controllers\PersetujuanKrsController::class, 'reject'])->name('persetujuan-krs.reject');
=======
    // (opsional) jika ingin akses halaman KRS sebagai /rencana-studi
    // Route::get('/dashboard', [\App\Http\Controllers\RencanaStudiController::class, 'index'])->name('dashboard.index');

    // Dispensasi Routes
    Route::get('/dispensasi', [\App\Http\Controllers\DispensasiController::class, 'index'])
        ->name('dispensasi.index');

    Route::get('/dispensasi/create', [\App\Http\Controllers\DispensasiController::class, 'create'])
        ->name('dispensasi.create');

    Route::post('/dispensasi', [\App\Http\Controllers\DispensasiController::class, 'store'])
        ->name('dispensasi.store');
>>>>>>> 7cf422054cccd11441af387f6573a40d89ae7f21
});
