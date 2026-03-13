<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

// Import Controllers
use App\Http\Controllers\BarangController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\BarangPeminjamController;
use App\Http\Controllers\BarangPengembalianController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\HomeController;

// --- GUEST ROUTES (Bisa diakses tanpa login) ---
Route::get('/', function () {
    return view('welcome');
});

// Autentikasi: Login
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended('home');
    }

    return back()->withErrors(['email' => 'Email atau password salah.']);
});

// Autentikasi: Register
Route::get('/register', function () {
    return view('auth.register');
})->name('register');


// --- AUTH ROUTES (Harus Login) ---
Route::middleware(['auth'])->group(function () {

    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Export & Resources Barang
    Route::get('/barang/export', [BarangController::class, 'export'])->name('barang.export');
    Route::get('/barang/export-pdf', [BarangController::class, 'exportPdf'])->name('barang.export.pdf');
    Route::resource('barang', BarangController::class);
    Route::resource('barangmasuk', BarangMasukController::class);
    Route::resource('barangkeluar', BarangKeluarController::class);
    Route::resource('barangpeminjam', BarangPeminjamController::class);
    Route::resource('barangpengembalian', BarangPengembalianController::class);
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/export-excel', [LaporanController::class, 'exportExcel'])->name('barang.export');
Route::get('/laporan/export-pdf', [LaporanController::class, 'exportPdf'])->name('barang.export.pdf');
Route::get('barangkeluar/export/excel', [BarangKeluarController::class, 'exportExcel'])->name('barangkeluar.exportExcel');
Route::get('barangkeluar/export/pdf', [BarangKeluarController::class, 'exportPdf'])->name('barangkeluar.exportPdf');
Route::get('barangmasuk/export/excel', [BarangMasukController::class, 'exportExcel'])->name('barangmasuk.exportExcel');
Route::get('barangmasuk/export/pdf', [BarangMasukController::class, 'exportPdf'])->name('barangmasuk.exportPdf');
Route::get('barangpeminjam/export/excel', [BarangPeminjamController::class, 'exportExcel'])->name('barangpeminjam.exportExcel');
Route::get('barangpeminjam/export/pdf', [BarangPeminjamController::class, 'exportPdf'])->name('barangpeminjam.exportPdf');
    // Logout
    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    })->name('logout');

    // --- ADMIN ONLY ROUTES ---
    Route::middleware(['admin'])->group(function () {
        Route::get('/petugas', [PetugasController::class, 'index'])->name('petugas.index');
        Route::post('/petugas/store', [PetugasController::class, 'store'])->name('petugas.store');
        Route::delete('/petugas/{id}', [PetugasController::class, 'destroy'])->name('petugas.destroy');
        Route::post('/barang/store', [PetugasController::class, 'storeBarang'])->name('barang.store');
    });
});