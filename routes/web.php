<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\ApproveController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

//**Pegawai */
Route::middleware('auth')->group(function () {
    Route::get('/pegawai/dashboard', [PegawaiController::class, 'dashboard'])
        ->name('pegawai.dashboard');

    Route::get('/pegawai/pengajuan', [PengajuanController::class, 'create'])
        ->name('pegawai.pengajuan.create');
        
    Route::post('/pegawai/pengajuan', [PengajuanController::class, 'store'])
        ->name('pegawai.pengajuan.store');

    Route::get('/pegawai/daftar-pengajuan', [PengajuanController::class, 'index'])
        ->name('pegawai.daftar-pengajuan');

    Route::get('/pegawai/pengajuan/{pengajuan}', [PengajuanController::class, 'show'])
        ->name('pegawai.pengajuan.show');

});

//**ADUM/PPK */
Route::prefix('adum')->middleware(['auth'])->group(function() {
    Route::get('/dashboard', [ApproveController::class, 'dashboard'])->name('adum.dashboard');
    Route::get('/pengajuan', [ApproveController::class, 'pengajuan'])->name('adum.pengajuan');
    Route::get('/laporan', [ApproveController::class, 'laporan'])->name('adum.laporan');
    Route::post('/approve/{id}', [ApproveController::class, 'approve'])->name('adum.approve');
    Route::post('/reject/{id}', [ApproveController::class, 'reject'])->name('adum.reject');
});

Route::prefix('ppk')->middleware(['auth'])->group(function() {
    Route::get('/dashboard', [ApproveController::class, 'dashboard'])->name('ppk.dashboard');
    Route::get('/pengajuan', [ApproveController::class, 'pengajuan'])->name('ppk.pengajuan');
    Route::get('/laporan', [ApproveController::class, 'laporan'])->name('ppk.laporan');
    Route::get('/approve/{id}', [ApproveController::class, 'approve'])->name('approve.approve');
    Route::get('/reject/{id}', [ApproveController::class, 'reject'])->name('approve.reject');
});

// Laporan untuk Adum
Route::middleware(['auth'])->group(function () {
    Route::get('/adum/laporan', [App\Http\Controllers\ApproveController::class, 'laporan'])->name('adum.laporan');
    Route::get('/adum/laporan/pdf', [App\Http\Controllers\ApproveController::class, 'laporanPDF'])->name('adum.laporan.pdf');
    Route::get('/adum/laporan/excel', [App\Http\Controllers\ApproveController::class, 'laporanExcel'])->name('adum.laporan.excel');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
