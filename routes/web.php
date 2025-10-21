<?php

use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'login'])->name('login');
Route::post('/login', [PageController::class, 'doLogin'])->name('doLogin');
Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard');
Route::get('/pengelolaan', [PageController::class, 'pengelolaan'])->name('pengelolaan');
Route::get('/profile', [PageController::class, 'profile'])->name('profile');
Route::post('/profile/update', [PageController::class, 'updateProfile'])->name('updateProfile');
Route::post('/pengelolaan/update-status', [PageController::class, 'updateStatus'])->name('updateStatus');
Route::post('/pengelolaan/update-jadwal', [PageController::class, 'updateJadwal'])->name('updateJadwal');
Route::post('/pengelolaan/hapus-jadwal', [PageController::class, 'hapusJadwal'])->name('hapusJadwal');
Route::post('/pengelolaan/tambah-anggota', [PageController::class, 'tambahAnggota'])->name('tambahAnggota');
Route::post('/pengelolaan/update-anggota', [PageController::class, 'updateAnggota'])->name('updateAnggota');
Route::post('/pengelolaan/hapus-anggota', [PageController::class, 'hapusAnggota'])->name('hapusAnggota');
Route::get('/reset-data', [PageController::class, 'resetData'])->name('resetData'); // Route untuk reset data
Route::post('/logout', [PageController::class, 'logout'])->name('logout');
