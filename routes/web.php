<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\ApproveController;
use App\Http\Controllers\KeuanganController;
use App\Http\Controllers\ProsesKeuanganController;
use App\Http\Controllers\PengadaanController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

//**Pegawai */
Route::middleware('auth')->group(function () {
    Route::get('/pegawai/dashboard', [PegawaiController::class, 'dashboard'])->name('pegawai.dashboard');
    Route::get('/pegawai/pengajuan', [PengajuanController::class, 'create'])->name('pegawai.pengajuan.create');
    Route::post('/pegawai/pengajuan', [PengajuanController::class, 'store'])->name('pegawai.pengajuan.store');
    Route::get('/pegawai/daftar-pengajuan', [PengajuanController::class, 'index'])->name('pegawai.daftar-pengajuan');
    Route::get('/pegawai/pengajuan/{pengajuan}', [PengajuanController::class, 'show'])->name('pegawai.pengajuan.show');
});

//**Penyelenggara */
Route::middleware(['auth'])->prefix('pengadaan')->group(function() {
    Route::get('/dashboard', [PengadaanController::class, 'dashboard'])->name('pengadaan.dashboard');
    Route::post('/pengadaan/arsip/{id}', [PengadaanController::class, 'simpanArsip'])->name('pengadaan.arsip');
    Route::get('/pengadaan/arsip', [PengadaanController::class, 'viewArsip'])->name('pengadaan.view-arsip');

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
    Route::post('/approve/{id}', [ApproveController::class, 'approve'])->name('ppk.approve');
    Route::post('/reject/{id}', [ApproveController::class, 'reject'])->name('ppk.reject');
});

// Laporan untuk Adum
Route::middleware(['auth'])->group(function () {
    Route::get('/adum/laporan', [App\Http\Controllers\ApproveController::class, 'laporan'])->name('adum.laporan');
    Route::get('/adum/laporan/pdf', [App\Http\Controllers\ApproveController::class, 'laporanPDF'])->name('adum.laporan.pdf');
    Route::get('/adum/laporan/excel', [App\Http\Controllers\ApproveController::class, 'laporanExcel'])->name('adum.laporan.excel');
});

//Keuangan
Route::prefix('keuangan')->middleware(['auth'])->group(function() {
    Route::get('/dashboard', [KeuanganController::class, 'dashboard'])->name('keuangan.dashboard');
    Route::get('/proses/{id}', [KeuanganController::class, 'proses'])->name('keuangan.proses');
    Route::post('/proses/{id}', [KeuanganController::class, 'storeProses'])->name('keuangan.storeProses');
    Route::post('/simpan-honorarium/{id}', [KeuanganController::class, 'simpanHonorarium'])->name('keuangan.simpanHonorarium');
    Route::post('/keuangan/approve/{id}', [KeuanganController::class, 'approveProcess'])->name('keuangan.approve.process');

    Route::get('/laporan', [KeuanganController::class, 'laporan'])->name('keuangan.laporan');
});

// Laporan Keuangan
Route::middleware(['auth'])->group(function() {
    Route::get('/laporan', [KeuanganController::class, 'laporan'])->name('keuangan.laporan');
    Route::get('/laporan/{id}', [KeuanganController::class, 'lihatDetail'])->name('keuangan.laporan_detail');
});

//Approve Proses ADUM & PPK
Route::middleware(['auth'])->group(function() {
    Route::get('/proses-keuangan', [ProsesKeuanganController::class, 'dashboard'])->name('proses.dashboard');
    Route::get('/proses-keuangan/approve/{id}', [ProsesKeuanganController::class, 'approve'])->name('proses.approve');
});

//Verifikator
Route::prefix('verifikator')->middleware(['auth'])->group(function(){
    Route::get('/dashboard', [ProsesKeuanganController::class, 'dashboard'])->name('verifikator.dashboard');
    Route::get('/proses-keuangan/approve/{id}', [ProsesKeuanganController::class, 'approve'])->name('proses.approve');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
