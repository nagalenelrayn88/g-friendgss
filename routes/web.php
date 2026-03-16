<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Super\LaporanController;
use App\Http\Controllers\Super\BarangController;
use App\Http\Controllers\Super\DiskonController;
use App\Http\Controllers\Operator\TransaksiController;

use App\Http\Controllers\Super\DashboardController as SuperDashboard;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Operator\DashboardController as OperatorDashboard;

/*
|--------------------------------------------------------------------------
| DEFAULT
|--------------------------------------------------------------------------
*/

Route::get('/', function () {

    if (Auth::check()) {

        if (Auth::user()->role == 'superadmin') {
            return redirect()->route('super.dashboard');
        }

        if (Auth::user()->role == 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if (Auth::user()->role == 'operator') {
            return redirect()->route('operator.dashboard');
        }

    }

    return redirect()->route('login');

});

/*
|--------------------------------------------------------------------------
| AUTH (ONLY GUEST CAN ACCESS)
|--------------------------------------------------------------------------
*/

Route::middleware(['guest'])->group(function () {

    Route::get('/login', [AuthController::class, 'loginForm'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.process');
});


/*
|--------------------------------------------------------------------------
| PROTECTED AREA (HARUS LOGIN)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    Route::post('/logout', function () {

        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login');

    })->name('logout');


    /*
    |--------------------------------------------------------------------------
    | SUPER ADMIN
    |--------------------------------------------------------------------------
    */

    Route::middleware(['role:superadmin'])
    ->prefix('super')   // FIX DI SINI
    ->name('super.')
    ->group(function () {

        Route::get('/dashboard', [SuperDashboard::class, 'index'])
            ->name('dashboard');

        Route::get('/users', [UserController::class, 'index'])
            ->name('index');

        Route::get('/users/create', [UserController::class, 'create'])
            ->name('create');

        Route::post('/users/store', [UserController::class, 'store'])
            ->name('store');

        Route::get('/users/edit/{id}', [UserController::class, 'edit'])
            ->name('edit');

        Route::put('/users/update/{id}', [UserController::class, 'update'])
            ->name('update');

        Route::delete('/users/{id}', [UserController::class, 'destroy'])
            ->name('destroy');

        Route::get('/laporan/transaksi', [LaporanController::class, 'transaksi'])
            ->name('laporan.transaksi');

        Route::get('/laporan/pendapatan', [LaporanController::class, 'pendapatan'])
            ->name('laporan.pendapatan');

        Route::get('/laporan/stok', [LaporanController::class, 'stok'])
            ->name('laporan.stok');
    });


    /*
    |--------------------------------------------------------------------------
    | BARANG (SUPERADMIN + ADMIN)
    |--------------------------------------------------------------------------
    */

    Route::middleware(['role:superadmin,admin'])
    ->prefix('super')
    ->name('super.')
    ->group(function () {

        Route::get('/barang', [BarangController::class,'index'])
            ->name('barang.index');

        Route::get('/barang/create', [BarangController::class,'create'])
            ->name('barang.create');

        Route::post('/barang/store', [BarangController::class,'store'])
            ->name('barang.store');

        Route::get('/barang/{id}/edit', [BarangController::class,'edit'])
            ->name('barang.edit');

        Route::put('/barang/{id}', [BarangController::class,'update'])
            ->name('barang.update');

        Route::delete('/barang/{id}', [BarangController::class,'destroy'])
            ->name('barang.destroy');
    });


    /*
    |--------------------------------------------------------------------------
    | DISKON (SUPERADMIN + ADMIN)
    |--------------------------------------------------------------------------
    */

    Route::middleware(['role:superadmin,admin'])
    ->prefix('super')
    ->name('super.')
    ->group(function(){

        Route::get('/diskon', [DiskonController::class,'index'])
            ->name('diskon.index');

        Route::get('/diskon/create', [DiskonController::class,'create'])
            ->name('diskon.create');

        Route::post('/diskon/store', [DiskonController::class,'store'])
            ->name('diskon.store');

        Route::get('/diskon/{id}/edit', [DiskonController::class,'edit'])
            ->name('diskon.edit');

        Route::put('/diskon/{id}', [DiskonController::class,'update'])
            ->name('diskon.update');

        Route::delete('/diskon/{id}', [DiskonController::class,'destroy'])
            ->name('diskon.destroy');
    });


    /*
    |--------------------------------------------------------------------------
    | ADMIN
    |--------------------------------------------------------------------------
    */

    Route::middleware(['role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminDashboard::class, 'index'])
            ->name('dashboard');

        Route::get('/barang', [BarangController::class,'index'])
            ->name('barang.index');

        Route::get('/barang/create', [BarangController::class,'create'])
            ->name('barang.create');

        Route::post('/barang/store', [BarangController::class,'store'])
            ->name('barang.store');

        Route::get('/barang/{id}/edit', [BarangController::class,'edit'])
            ->name('barang.edit');

        Route::put('/barang/{id}', [BarangController::class,'update'])
            ->name('barang.update');

        Route::delete('/barang/{id}', [BarangController::class,'destroy'])
            ->name('barang.destroy');

        Route::get('/diskon', [DiskonController::class,'index'])
            ->name('diskon.index');

        Route::get('/diskon/create', [DiskonController::class,'create'])
            ->name('diskon.create');

        Route::post('/diskon/store', [DiskonController::class,'store'])
            ->name('diskon.store');

        Route::get('/diskon/{id}/edit', [DiskonController::class,'edit'])
            ->name('diskon.edit');

        Route::put('/diskon/{id}', [DiskonController::class,'update'])
            ->name('diskon.update');

        Route::delete('/diskon/{id}', [DiskonController::class,'destroy'])
            ->name('diskon.destroy');

        Route::get('/laporan/stok', [LaporanController::class,'stok'])
            ->name('laporan.stok');
    });


   /*
|--------------------------------------------------------------------------
| KASIR / OPERATOR
|--------------------------------------------------------------------------
*/

Route::middleware(['role:operator'])
->prefix('operator')
->name('operator.')
->group(function () {

    Route::get('/dashboard', [OperatorDashboard::class,'index'])
        ->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | TRANSAKSI
    |--------------------------------------------------------------------------
    */

    Route::get('/transaksi', [TransaksiController::class,'index'])
        ->name('transaksi.index');

    Route::get('/transaksi/create', [TransaksiController::class,'create'])
        ->name('transaksi.create');

    Route::post('/transaksi/store', [TransaksiController::class,'store'])
        ->name('transaksi.store');

    Route::get('/transaksi/{id}', [TransaksiController::class,'show'])
        ->name('transaksi.show');


    /*
    |--------------------------------------------------------------------------
    | RIWAYAT
    |--------------------------------------------------------------------------
    */

    Route::get('/riwayat-transaksi', [TransaksiController::class,'riwayat'])
        ->name('riwayat.index');


    /*
    |--------------------------------------------------------------------------
    | CETAK STRUK
    |--------------------------------------------------------------------------
    */

    Route::get('/struk/{id}', [TransaksiController::class,'cetakShow'])
        ->name('struk.show');


    /*
    |--------------------------------------------------------------------------
    | LAPORAN
    |--------------------------------------------------------------------------
    */

    Route::get('/laporan/transaksi', [LaporanController::class,'transaksi'])
        ->name('laporan.transaksi');

    Route::get('/laporan/pendapatan', [LaporanController::class,'pendapatan'])
        ->name('laporan.pendapatan');

});

});