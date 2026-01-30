<?php

use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\RiwayatJabatanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CareerMapController;
use App\Http\Controllers\AngkaKreditController;
use App\Http\Controllers\PakController;
use App\Http\Controllers\PengembanganKompetensiController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\IkuSdmoController;

// Root route - redirect ke dashboard atau login
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');
/*
|--------------------------------------------------------------------------
| Pegawai Routes
|--------------------------------------------------------------------------
*/

Route::get('/pegawai', [PegawaiController::class, 'index'])->name('pegawai.index');
Route::get('/pegawai/suggest', [PegawaiController::class, 'suggest'])->name('pegawai.suggest');
Route::get('/pegawai/create', [PegawaiController::class, 'create'])->name('pegawai.create');
Route::post('/pegawai', [PegawaiController::class, 'store'])->name('pegawai.store');
Route::get('/pegawai/profil', [PegawaiController::class, 'profil'])->name('pegawai.profil');
Route::get('/pegawai/{id}', [PegawaiController::class, 'show'])->whereNumber('id')->name('pegawai.show');
Route::get('/pegawai/{id}/edit', [PegawaiController::class, 'edit'])->name('pegawai.edit');
Route::put('/pegawai/{id}', [PegawaiController::class, 'update'])->name('pegawai.update');
Route::delete('/pegawai/{id}', [PegawaiController::class, 'destroy'])->name('pegawai.destroy');

Route::get('/pegawai/{id}/dosir', [PegawaiController::class, 'dosir'])->name('pegawai.dosir');
Route::post('/pegawai/{id}/dosir/upload', [PegawaiController::class, 'uploadDosir'])->name('pegawai.dosir.upload');
Route::get('/dosir/{id}/download', [PegawaiController::class, 'downloadDosir'])->name('pegawai.dosir.download');
Route::delete('/dosir/{id}', [PegawaiController::class, 'deleteDosir'])->name('pegawai.dosir.delete');

/*
|--------------------------------------------------------------------------
| Riwayat Jabatan Routes
|--------------------------------------------------------------------------
*/

Route::get('/riwayat-jabatan/create/{pegawai}', [RiwayatJabatanController::class, 'create'])->name('riwayat-jabatan.create');
Route::post('/riwayat-jabatan/store', [RiwayatJabatanController::class, 'store'])->name('riwayat-jabatan.store');
Route::get('/riwayat-jabatan/{id}/edit', [RiwayatJabatanController::class, 'edit'])->name('riwayat-jabatan.edit');
Route::put('/riwayat-jabatan/{id}', [RiwayatJabatanController::class, 'update'])->name('riwayat-jabatan.update');
Route::delete('/riwayat-jabatan/{id}', [RiwayatJabatanController::class, 'destroy'])->name('riwayat-jabatan.destroy');

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Admin-only user management
Route::middleware(['auth', \App\Http\Middleware\EnsureUserIsAdmin::class])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
});

/*
|--------------------------------------------------------------------------
| Career Map
|--------------------------------------------------------------------------
*/

// daftar /career-map (index)
Route::prefix('career-map')->group(function () {

    Route::get('/', [CareerMapController::class, 'index'])
        ->name('career-map.index');

    Route::get('/rekap', [CareerMapController::class, 'rekap'])
        ->name('career-map.rekap');

    Route::get('/saya', [CareerMapController::class, 'saya'])
        ->name('career-map.saya');

    // Edit Riwayat AK
    Route::get('/{pegawai}/edit-ak', [CareerMapController::class, 'editAk'])
        ->name('career-map.edit-ak');
    
    Route::post('/{pegawai}/store-ak', [CareerMapController::class, 'storeAk'])
        ->name('career-map.store-ak');
    
    Route::put('/ak/{angkaKredit}', [CareerMapController::class, 'updateAk'])
        ->name('career-map.update-ak');
    
    Route::delete('/ak/{angkaKredit}', [CareerMapController::class, 'deleteAk'])
        ->name('career-map.delete-ak');

});

/*
|--------------------------------------------------------------------------
| Menu Lain
|--------------------------------------------------------------------------
*/

Route::view('/pengembangan-kompetensi', 'menu.pengembangan-kompetensi')->name('pengembangan.kompetensi');
Route::view('/informasi-jf', 'menu.informasi-jf')->name('informasi.jf');
Route::view('/sotk', 'menu.sotk')->name('sotk');

Route::get('/cascading-kinerja', [\App\Http\Controllers\CascadingKinerjaController::class, 'index'])->name('cascading.kinerja');
Route::post('/cascading-kinerja', [\App\Http\Controllers\CascadingKinerjaController::class, 'update'])->name('cascading.kinerja.save')->middleware(['auth', \App\Http\Middleware\EnsureUserIsAdmin::class]);
// IKU SDMO - handled by controller so view can receive dynamic data
Route::get('/pic-jabatan-fungsional', [IkuSdmoController::class, 'index'])->name('pic-jabatan-fungsional');
Route::view('/halo-sdmo', 'menu.halo-sdmo')->name('halo.sdmo');

/*
|--------------------------------------------------------------------------
| PERHITUNGAN ANGKA KREDIT
|--------------------------------------------------------------------------
*/

Route::get('/career-map/hitung-ak', 
    [AngkaKreditController::class, 'index']
)->name('ak.index');

Route::post('/career-map/hitung-ak', 
    [AngkaKreditController::class, 'store']
)->name('ak.store');

Route::delete('/career-map/hitung-ak/{id}',
    [AngkaKreditController::class, 'destroy']
)->name('ak.destroy');

Route::get('/career-map/hitung-ak/{id}/edit',
    [AngkaKreditController::class, 'edit']
)->name('ak.edit');

Route::put(
    '/career-map/hitung-ak/{id}',
    [AngkaKreditController::class, 'update']
)->name('ak.update');

    // VERIFIKASI SDMO
    Route::get('/career-map/verifikasi-ak',
        [AngkaKreditController::class, 'verifikasiIndex']
    )->name('ak.verifikasi.index');

    Route::post('/career-map/verifikasi-ak/{id}',
        [AngkaKreditController::class, 'verifikasi']
    )->name('ak.verifikasi');

Route::put('/angka-kredit/{id}/kirim-usulan', 
    [AngkaKreditController::class, 'kirimUsulan']
)->name('ak.kirim-usulan');

// ================= DOKUMEN PAK =================
Route::get('/pak/{id}', 
    [AngkaKreditController::class, 'show']
)->name('pak.show');

Route::get('/career-map/ak/{id}/pdf',
    [AngkaKreditController::class, 'downloadPdf']
)->name('pak.pdf');

Route::get('/pak/{id}/download', [AngkaKreditController::class, 'downloadPdf'])
    ->name('pak.download');

Route::post('/career-map/ak/{id}/verifikasi', 
    [PakController::class, 'verifikasiSdmo']
)->name('pak.verifikasi.sdmo');

Route::post('/career-map/ak/{id}/ttd', 
    [PakController::class, 'ttdPpk']
)->name('pak.ttd.ppk');

Route::post('/career-map/ak/{id}/tolak-ttd', 
    [PakController::class, 'tolakPpk']
)->name('pak.tolak.ppk');

/*
|--------------------------------------------------------------------------
| PENGEMBANGAN KOMPETENSI
|--------------------------------------------------------------------------
*/
Route::prefix('pengembangan-kompetensi')
    ->name('kompetensi.')
    ->group(function () {

        // INDEX
        Route::get('/', 
            [PengembanganKompetensiController::class, 'index']
        )->name('index');

        // SUB MENU
        Route::get('/standar', 
            [PengembanganKompetensiController::class, 'standar']
        )->name('standar');

        Route::get('/capaian', 
            [PengembanganKompetensiController::class, 'capaian']
        )->name('capaian');

        Route::get('/gap', 
            [PengembanganKompetensiController::class, 'gap']
        )->name('gap');

        Route::get('/rencana', 
            [PengembanganKompetensiController::class, 'rencana']
        )->name('rencana');
});

/*
|--------------------------------------------------------------------------
| Career Map DETAIL (HARUS PALING BAWAH)
|--------------------------------------------------------------------------
*/
Route::get('/career-map/{id}', [CareerMapController::class, 'show'])
    ->whereNumber('id')
    ->name('career-map.show');


Route::get('/pegawai/template-excel',
    [PegawaiController::class, 'downloadTemplateExcel']
)->name('pegawai.template.excel');

Route::get('/pegawai/export-excel',
    [PegawaiController::class, 'exportExcel']
)->name('pegawai.export.excel');

Route::post('/pegawai/import',
    [PegawaiController::class, 'importExcel']
)->name('pegawai.import');

Route::get('/api/pegawai-search', function(\Illuminate\Http\Request $r){
    return \App\Models\Pegawai::where('nama','like','%'.$r->q.'%')
        ->limit(10)
        ->get(['id','nama','nip']);
});

Route::get('/iku-sdmo', [IkuSdmoController::class, 'index'])->name('iku-sdmo');
Route::post('/iku-sdmo/save', [IkuSdmoController::class, 'save'])->middleware(['auth', \App\Http\Middleware\EnsureUserIsAdmin::class]);

