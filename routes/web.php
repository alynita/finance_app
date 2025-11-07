<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\ApproveController;
use App\Http\Controllers\KeuanganController;
use App\Http\Controllers\ProsesKeuanganController;
use App\Http\Controllers\PengadaanController;
use App\Http\Controllers\BendaharaController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PpkController;
use App\Http\Controllers\HonorController;
use App\Http\Controllers\KroController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

//**Admin */
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::put('/users/{id}/update-role', [AdminController::class, 'updateRole'])->name('admin.users.updateRole');
    Route::post('/users/{id}/reset-password', [AdminController::class, 'resetPassword'])->name('admin.users.resetPassword');

    // KRO Management
    Route::get('/kro/index', [KroController::class, 'index'])->name('admin.kro.index');
    Route::get('/kro/{id}/edit', [KroController::class, 'edit'])->name('admin.kro.edit');
    Route::post('/kro/store', [KroController::class, 'store'])->name('admin.kro.store');
    Route::put('/kro/{id}/update', [KroController::class, 'update'])->name('admin.kro.update');
    Route::delete('/kro/{id}/delete', [KroController::class, 'destroy'])->name('admin.kro.delete');
});

//**Pegawai */
Route::middleware('auth')->group(function () {
    Route::get('/pegawai/dashboard', [PegawaiController::class, 'dashboard'])->name('pegawai.dashboard');
    Route::get('/pegawai/pengajuan', [PengajuanController::class, 'create'])->name('pegawai.pengajuan.create');
    Route::post('/pegawai/pengajuan', [PengajuanController::class, 'store'])->name('pegawai.pengajuan.store');
    Route::get('/pegawai/daftar-pengajuan', [PengajuanController::class, 'index'])->name('pegawai.daftar-pengajuan');
    Route::get('/pegawai/pengajuan/{pengajuan}', [PengajuanController::class, 'show'])->name('pegawai.pengajuan.show');
});

// Dropdown form pengajuan
Route::get('/get-kode-akun', [DropdownController::class, 'getKodeAkun'])->name('get.kode.akun');

// CRUD admin KRO & Kode Akun
Route::prefix('admin')->group(function() {
    Route::resource('kro', KroController::class);
    Route::resource('kode-akun', KroController::class); // atau controller terpisah
});


// Route buat sarpras & bmn
Route::middleware('auth')->group(function () {
    Route::get('/sarpras/dashboard', [PengajuanController::class, 'dashboard'])->name('sarpras.dashboard');
    Route::get('/sarpras/pengajuan/create', [PengajuanController::class, 'createSarpras'])->name('pegawai.pengajuan.create');
    Route::post('/sarpras/pengajuan/store', [PengajuanController::class, 'store'])->name('sarpras.pengajuan.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/bmn/dashboard', [PengajuanController::class, 'dashboard'])->name('bmn.dashboard');
    Route::get('/bmn/pengajuan/create', [PengajuanController::class, 'createbmn'])->name('pegawai.pengajuan.create');
    Route::post('/bmn/pengajuan/store', [PengajuanController::class, 'store'])->name('bmn.pengajuan.store');
});

//**Pengadaan */
Route::prefix('pengadaan')->middleware('auth')->group(function(){
    Route::get('/dashboard', [PengadaanController::class, 'dashboard'])->name('pengadaan.dashboard');
    Route::get('/pengadaan/grup/{group}', [PengadaanController::class, 'showGroup'])->name('pengadaan.showGroup');
    Route::post('/{id}/update-items', [PengadaanController::class,'updateItems'])->name('pengadaan.updateItems');
    Route::post('/{id}/submit', [PengadaanController::class,'submitToKeuangan'])->name('pengadaan.submit');
    Route::get('/pengadaan/arsip', [PengadaanController::class, 'arsip'])->name('pengadaan.arsip');
    Route::get('/pengadaan/arsip/{id}', [PengadaanController::class, 'showArsip'])->name('pengadaan.showArsip');
});


//**ADUM/PPK */
Route::middleware(['auth'])->group(function() {

    // Timker1–timker6
    foreach (range(1,6) as $i) {
        $role = 'timker_'.$i;

        Route::prefix($role)->name($role.'.')->group(function() use ($role) {
            Route::get('/dashboard', [ApproveController::class, 'dashboard'])->name('dashboard');
            Route::get('/pengajuan', [ApproveController::class, 'pengajuan'])->name('pengajuan');
            Route::post('/approve/{id}', [ApproveController::class, 'approve'])->name('approve');
            Route::post('/reject/{id}', [ApproveController::class, 'reject'])->name('reject');
        });
    }

    // ADUM
    Route::prefix('adum')->name('adum.')->group(function() {
        Route::get('/dashboard', [ApproveController::class, 'dashboard'])->name('dashboard');
        Route::get('/pengajuan', [ApproveController::class, 'pengajuan'])->name('pengajuan');
        Route::post('/approve/{id}', [ApproveController::class, 'approve'])->name('approve');
        Route::post('/reject/{id}', [ApproveController::class, 'reject'])->name('reject');
    });
});

// PPK
Route::prefix('ppk')->name('ppk.')->group(function() {
    Route::get('/dashboard', [PpkController::class, 'dashboard'])->name('dashboard');
    // Route draf / approve PPK
    Route::get('/approve', [PpkController::class, 'approvedList'])->name('approve');
    // Route untuk lihat detail PPK per id
    Route::get('/{id}', [PpkController::class, 'show'])->name('show');
    // Simpan grup / approve langsung
    Route::post('/{id}/store-group', [PpkController::class, 'storeGroup'])->name('storeGroup');
    Route::post('/group/{id}/approve', [PpkController::class, 'approveGroup'])->name('approveGroup');
    Route::post('/ppk/{id}/approve-semua', [PpkController::class, 'approveAll'])->name('approveAll');
});

// Laporan untuk Adum
Route::middleware(['auth'])->group(function () {
    Route::get('/adum/laporan', [ApproveController::class, 'laporan'])->name('adum.laporan');
    Route::get('/adum/laporan/pdf', [ApproveController::class, 'laporanPDF'])->name('adum.laporan.pdf');
    Route::get('/adum/laporan/excel', [ApproveController::class, 'laporanExcel'])->name('adum.laporan.excel');
});

//Keuangan
Route::prefix('keuangan')->group(function() {
    Route::get('/dashboard', [KeuanganController::class, 'dashboard'])->name('keuangan.dashboard');
    Route::get('/group/{id}', [KeuanganController::class, 'showGroup'])->name('keuangan.showGroup');
    Route::post('/group/{id}/store', [KeuanganController::class, 'storeProses'])->name('keuangan.storeProses');
    Route::get('/keuangan/laporan', [KeuanganController::class, 'laporan'])->name('keuangan.laporan');
    Route::get('/keuangan/laporan/{id}', [KeuanganController::class, 'laporan_detail'])->name('keuangan.laporan_detail');
    // KEUANGAN - Pengajuan Honor
    // Form input honor
    Route::get('/honor/input', [KeuanganController::class, 'honorForm'])->name('keuangan.honor.form');
    Route::post('/honor/store', [KeuanganController::class, 'storeHonor'])->name('keuangan.honor.store');

    // List semua honor
    Route::get('/honor/data', [KeuanganController::class, 'honorData'])->name('keuangan.honor.data');

    // Detail honor per ID
    Route::get('/honor/detail/{id}', [KeuanganController::class, 'honorDetail'])->name('keuangan.honor.detail');
});

//Bendahara
Route::prefix('bendahara')->group(function() {
    Route::get('/dashboard', [BendaharaController::class, 'dashboard'])->name('bendahara.dashboard');
    Route::get('/laporan/{id}', [BendaharaController::class, 'show'])->name('bendahara.laporan.show');
    Route::post('/laporan/{id}/arsip', [BendaharaController::class, 'simpanArsip'])->name('bendahara.simpan-arsip');
    Route::get('/laporan/{id}/download', [BendaharaController::class, 'downloadPDF'])->name('bendahara.download-pdf');
    Route::get('/arsip', [BendaharaController::class, 'arsip'])->name('bendahara.arsip');
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
    Route::get('/proses-keuangan/reject/{id}', [ProsesKeuanganController::class, 'reject'])->name('proses.reject');
}); 

//Verifikator
Route::prefix('verifikator')->middleware(['auth'])->group(function(){
    Route::get('/dashboard', [ProsesKeuanganController::class, 'dashboard'])->name('verifikator.dashboard');
    Route::get('/proses-keuangan/approve/{id}', [ProsesKeuanganController::class, 'approve'])->name('proses.approve');
});

//Honor
Route::prefix('honor')->middleware('auth')->group(function () {
    Route::get('dashboard', [HonorController::class, 'dashboard'])->name('honor.dashboard');
    Route::post('approve/{id}', [HonorController::class, 'approve'])->name('honor.approve');
    Route::post('reject/{id}', [HonorController::class, 'reject'])->name('honor.reject');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
