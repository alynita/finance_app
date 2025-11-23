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
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\VerifikatorController;
use App\http\Controllers\AnggotaTimkerController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

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
    Route::delete('admin/kro/{id}', [KroController::class, 'destroy'])->name('admin.kro.destroy');
});

//**Pegawai */
Route::middleware('auth')->group(function () {
    Route::get('/pegawai/dashboard', [PegawaiController::class, 'dashboard'])->name('pegawai.dashboard');
    Route::get('/pegawai/pengajuan', [PengajuanController::class, 'create'])->name('pegawai.pengajuan.create');
    Route::post('/pegawai/pengajuan', [PengajuanController::class, 'store'])->name('pegawai.pengajuan.store');
    Route::get('/pegawai/daftar-pengajuan', [PengajuanController::class, 'index'])->name('pegawai.daftar-pengajuan');
    Route::get('/pegawai/pengajuan/{pengajuan}', [PengajuanController::class, 'show'])->name('pegawai.pengajuan.show');
});

//*Anggota Timker*/
Route::prefix('anggota_timker')->middleware('auth')->group(function () {
    Route::get('/dashboard', [AnggotaTimkerController::class, 'dashboard'])->name('anggota_timker.dashboard');
    Route::get('/pengajuan', [AnggotaTimkerController::class, 'daftarPengajuan'])->name('anggota_timker.index');
    Route::get('/pengajuan/create', [AnggotaTimkerController::class, 'create'])->name('anggota_timker.create');
});


// Dropdown form pengajuan
Route::get('/kro/children/{id}', [KroController::class, 'getChildren']);

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
        // Route untuk menampilkan daftar pengajuan berdasarkan kategori
        Route::get('/pengajuan/kategori/{kategori}', [ApproveController::class, 'pengajuanKategori'])
            ->name('pengajuan.kategori');

    });
});

// PPK
Route::prefix('ppk')->name('ppk.')->group(function() {
    Route::get('/dashboard', [PpkController::class, 'dashboard'])->name('dashboard');
    Route::post('/update-kro/{id}', [PpkController::class, 'updateKRO'])->name('ppk.updateKRO');
    Route::get('/approve', [PpkController::class, 'approvedList'])->name('approve');
    Route::get('/{id}', [PpkController::class, 'show'])->name('show');
    Route::post('/{id}/store-group', [PpkController::class, 'storeGroup'])->name('storeGroup');
    Route::post('/group/{id}/approve', [PpkController::class, 'approveGroup'])->name('approveGroup');
    Route::post('/ppk/{id}/approve-semua', [PpkController::class, 'approveAll'])->name('approveAll');
    Route::get('/pengajuan/kategori/{kategori}', [ApproveController::class, 'pengajuanKategori'])
        ->name('pengajuan.kategori');
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
    Route::get('/honor/input', [KeuanganController::class, 'honorForm'])->name('keuangan.honor.form');
    Route::post('/honor/store', [KeuanganController::class, 'storeHonor'])->name('keuangan.honor.store');
    Route::get('/honor/data', [KeuanganController::class, 'honorData'])->name('keuangan.honor.data');
    Route::get('/honor/detail/{id}', [KeuanganController::class, 'honorDetail'])->name('keuangan.honor.detail');
    Route::post('/honor/{id}/simpan-arsip', [HonorController::class, 'simpanArsip'])->name('honor.simpanArsip');
    // Halaman daftar laporan honor
    Route::get('/honor/laporan', [KeuanganController::class, 'indexLaporan'])->name('keuangan.honor.index.laporan');
    // Halaman detail laporan berdasarkan ID
    Route::get('/honor/laporan/{id}', [KeuanganController::class, 'detailLaporan'])->name('keuangan.honor.detail.laporan');

});

//Bendahara
Route::prefix('bendahara')->group(function() {
    Route::get('/dashboard', [BendaharaController::class, 'dashboard'])->name('bendahara.dashboard');
    Route::get('/laporan/{id}', [BendaharaController::class, 'show'])->name('bendahara.laporan.show');
    Route::get('/bendahara/honor/{id}', [BendaharaController::class, 'showHonor'])->name('bendahara.honor.show');

    Route::get('/arsip/pengadaan', [BendaharaController::class, 'arsipPengadaanList'])->name('bendahara.arsip.pengadaan.list');
    Route::get('/arsip/kerusakan', [BendaharaController::class, 'arsipKerusakanList'])->name('bendahara.arsip.kerusakan.list');
    Route::get('/arsip/honor', [BendaharaController::class, 'arsipHonorList'])->name('bendahara.arsip.honor.list');

    Route::post('/arsip/pengadaan/{id}', [BendaharaController::class, 'arsipPengadaan'])->name('bendahara.arsip.pengadaan');
    Route::post('/arsip/honor/{id}', [BendaharaController::class, 'arsipHonor'])->name('bendahara.arsip.honor');
    Route::get('/laporan/{id}/download', [BendaharaController::class, 'downloadPDF'])->name('bendahara.download-pdf');

    // HONOR
    Route::get('/honor/{id}/pdf', [BendaharaController::class, 'downloadHonorPdf'])
        ->name('bendahara.honor.download.pdf');

    // PENGADAAN
    Route::get('/laporan/{id}/pdf', [BendaharaController::class, 'downloadPengadaanPdf'])
        ->name('bendahara.laporan.download.pdf');

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

// Verifikator
Route::prefix('verifikator')->middleware(['auth'])->group(function() {
    Route::get('/dashboard', [VerifikatorController::class, 'dashboard'])->name('verifikator.dashboard');
    Route::get('/proses-keuangan', [VerifikatorController::class, 'prosesKeuangan'])->name('verifikator.proses');
    Route::get('/arsip-honor', [VerifikatorController::class, 'arsipHonor'])->name('verifikator.arsip');
    Route::get('/arsip-honor/{id}', [VerifikatorController::class, 'detailHonor'])->name('verifikator.honor.detail');
    
    Route::post('/proses-keuangan/approve/{id}', [VerifikatorController::class, 'approve'])->name('verifikator.proses.approve');
    Route::post('/proses-keuangan/reject/{id}', [VerifikatorController::class, 'reject'])->name('verifikator.proses.reject');
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
