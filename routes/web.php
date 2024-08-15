<?php
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StockController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\SuppliersController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\RincianPembelianController;
use App\Http\Controllers\RincianPenjualanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PdfgenerateController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LevelController;

// Route accessible to everyone
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
});

Route::get('/wait', function () {
    return view('wait');
})->name('wait');


// Routes that require authentication and email verification
Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/rincianpenjualan/{penjualan_id}', [RincianPenjualanController::class, 'index'])->name('rincianpenjualan.index');
    Route::get('/penjualan/{penjualan_id}/rincianpenjualan/create', [RincianPenjualanController::class, 'create'])->name('rincianpenjualan.create');
    Route::post('/rincianpenjualan', [RincianPenjualanController::class, 'store'])->name('rincianpenjualan.store');
    Route::get('/rincianpenjualan/{id}/edit', [RincianPenjualanController::class, 'edit'])->name('rincianpenjualan.edit');
    Route::put('/rincianpenjualan/{id}', [RincianPenjualanController::class, 'update'])->name('rincianpenjualan.update');
    Route::delete('/rincianpenjualan/{id}', [RincianPenjualanController::class, 'destroy'])->name('rincianpenjualan.destroy');

    Route::get('/rincianpembelian/{pembelian_id}', [RincianPembelianController::class, 'index'])->name('rincianpembelian.index');
    Route::get('/pembelian/{pembelian_id}/rincianpembelian/create', [RincianPembelianController::class, 'create'])->name('rincianpembelian.create');
    Route::post('/rincianpembelian', [RincianPembelianController::class, 'store'])->name('rincianpembelian.store');
    Route::get('/rincianpembelian/{id}/edit', [RincianPembelianController::class, 'edit'])->name('rincianpembelian.edit');
    Route::put('/rincianpembelian/{id}', [RincianPembelianController::class, 'update'])->name('rincianpembelian.update');
    Route::delete('/rincianpembelian/{id}', [RincianPembelianController::class, 'destroy'])->name('rincianpembelian.destroy');

    Route::resource('customers', CustomersController::class);
    Route::resource('suppliers', SuppliersController::class);
    Route::resource('pembelian', PembelianController::class);
    Route::resource('penjualan', PenjualanController::class);
    Route::resource('stocks', StockController::class);

    // Additional routes that require authentication and verification
    Route::get('/laporan/profit', [LaporanController::class, 'profit'])->name('laporan.profit');
    Route::get('/rincianpenjualan/{penjualan_id}/invoice', [PdfgenerateController::class, 'generatePDF'])->name('rincianpenjualan.invoice');
    

    // Route for users with level 0 or 1
    Route::get('/level', [LevelController::class, 'index'])->name('level');

    // Route for users with level 2
    Route::middleware(['level'])->group(function () {
        Route::get('/level2', [LevelController::class, 'index2'])->name('level2');
    });


    // Route for editing a user
    Route::get('/level/{id}/edit', [LevelController::class, 'edit'])->name('level.edit');

    // Route for updating a user
    Route::put('/level/{id}', [LevelController::class, 'update'])->name('level.update');

    // Route for deleting a user
    Route::delete('/level/{id}', [LevelController::class, 'destroy'])->name('level.destroy');
    
});

require __DIR__.'/auth.php';
