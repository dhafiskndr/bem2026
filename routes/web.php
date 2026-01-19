<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\AnggaranController;
use App\Http\Controllers\RktController;
use App\Http\Controllers\UserController;

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

// Redirect ke login
Route::get('/', function () {
    return redirect('/login');
});

// Authentication routes (Laravel Breeze / Fortify)
require __DIR__ . '/auth.php';

// Protected routes - hanya untuk user yang sudah login
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Surat Masuk/Keluar
    Route::resource('surat', SuratController::class, ['names' => [
        'index' => 'surat',
        'show' => 'surat.show',
        'create' => 'surat.create',
        'store' => 'surat.store',
        'edit' => 'surat.edit',
        'update' => 'surat.update',
        'destroy' => 'surat.destroy'
    ]]);
    Route::get('/surat/{surat}/data', [SuratController::class, 'getSurat'])->name('surat.data');
    
    // Jadwal
    Route::resource('jadwal', JadwalController::class, ['names' => [
        'index' => 'jadwal',
        'show' => 'jadwal.show',
        'create' => 'jadwal.create',
        'store' => 'jadwal.store',
        'edit' => 'jadwal.edit',
        'update' => 'jadwal.update',
        'destroy' => 'jadwal.destroy'
    ]]);
    Route::get('/jadwal/{jadwal}/data', [JadwalController::class, 'getJadwal'])->name('jadwal.data');
    
    // Anggaran
    Route::resource('anggaran', AnggaranController::class, ['names' => [
        'index' => 'anggaran',
        'show' => 'anggaran.show',
        'create' => 'anggaran.create',
        'store' => 'anggaran.store',
        'edit' => 'anggaran.edit',
        'update' => 'anggaran.update',
        'destroy' => 'anggaran.destroy'
    ]]);
    Route::get('/anggaran/{anggaran}/data', [AnggaranController::class, 'getAnggaran'])->name('anggaran.data');
    
    // RKT BEM
    Route::resource('rkt', RktController::class, ['names' => [
        'index' => 'rkt',
        'show' => 'rkt.show',
        'create' => 'rkt.create',
        'store' => 'rkt.store',
        'edit' => 'rkt.edit',
        'update' => 'rkt.update',
        'destroy' => 'rkt.destroy'
    ]]);
    Route::get('/rkt/{rkt}/data', [RktController::class, 'getRkt'])->name('rkt.data');
    Route::get('/rkt/export/pdf', [RktController::class, 'exportPdf'])->name('rkt.export.pdf');

    // User Management (hanya admin)
    Route::middleware('admin')->group(function () {
        Route::resource('users', UserController::class, ['names' => [
            'index' => 'users.index',
            'show' => 'users.show',
            'store' => 'users.store',
            'update' => 'users.update',
            'destroy' => 'users.destroy'
        ]]);
        Route::get('/users/{user}/data', [UserController::class, 'getUser'])->name('users.data');
    });
});
