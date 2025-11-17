<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\SalesPaymentController;
use App\Http\Controllers\Select2Controller;
use App\Http\Controllers\StokOpnameController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\StokTransactionController;
use App\Http\Controllers\SalesReportController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UnitController;

// Redirect root URL to /home if logged in, or to login otherwise
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('home');
    }
    return redirect()->route('login'); // or return view('welcome');
});

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register/process', [RegisterController::class, 'process'])
    ->name('register.process');

// Auth routes (login, register, forgot password, etc.)
Auth::routes();

// Home page after login
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Protected routes (only accessible when logged in)
Route::middleware(['auth'])->group(function () {
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);

    Route::prefix('suppliers')->group(function () {
        Route::get('/', [SupplierController::class, 'index'])->name('suppliers.index');
        Route::get('/data', [SupplierController::class, 'getData'])->name('suppliers.data');
        Route::post('/', [SupplierController::class, 'store'])->name('suppliers.store');
        Route::put('/{id}', [SupplierController::class, 'update'])->name('suppliers.update');
        Route::delete('/{id}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');
        Route::get('/{id}', [SupplierController::class, 'show'])->name('suppliers.show');
    });

Route::prefix('units')->group(function () {
    Route::get('/', [UnitController::class, 'index'])->name('units.index');
    Route::get('/data', [UnitController::class, 'getData'])->name('units.data');
    Route::post('/', [UnitController::class, 'store'])->name('units.store');
    Route::get('/{id}', [UnitController::class, 'show'])->name('units.show');
    Route::put('/{id}', [UnitController::class, 'update'])->name('units.update');
    Route::delete('/{id}', [UnitController::class, 'destroy'])->name('units.delete');
});

Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/data', [CategoryController::class, 'getData'])->name('categories.data');
    Route::post('/', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::get('/{id}', [CategoryController::class, 'show'])->name('categories.show');
    Route::get('/generate/code', [CategoryController::class, 'generateCode'])->name('categories.generate.code');
});





    Route::get('/select2/products', [Select2Controller::class, 'products'])->name('select2.products');
    Route::get('/select2/barang', [Select2Controller::class, 'barang'])->name('select2.barang');
    Route::get('/select2/vehicles', [Select2Controller::class, 'vehicles'])->name('select2.vehicles');
    Route::get('/select2/mechanics', [Select2Controller::class, 'mechanics'])->name('select2.mechanics');
    Route::get('/select2/clients', [Select2Controller::class, 'clients'])->name('select2.clients');
    Route::get('/select2/jasa', [Select2Controller::class, 'jasa'])->name('select2.jasa');


    Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
    Route::get('/barang/data', [BarangController::class, 'getData'])->name('barang.data');
    Route::get('/barang/{id}', [BarangController::class, 'show']);
    Route::post('/barang', [BarangController::class, 'store'])->name('barang.store');
    Route::put('/barang/{id}', [BarangController::class, 'update'])->name('barang.update');
    Route::delete('/barang/{id}', [BarangController::class, 'destroy'])->name('barang.destroy');

    Route::get('/sales', [SalesController::class, 'index'])->name('sales.index');
    Route::get('/sales/create', [SalesController::class, 'create'])->name('sales.create');
    Route::post('/sales/store', [SalesController::class, 'store'])->name('sales.store');
    Route::get('/sales/data', [SalesController::class, 'data'])->name('sales.data');
    Route::get('/sales/{id}/edit', [SalesController::class, 'edit'])->name('sales.edit');
    Route::post('sales/{id}/update', [SalesController::class, 'update'])->name('sales.update');
    Route::delete('/sales/{id}/destroy', [SalesController::class, 'destroy'])->name('sales.destroy');
    Route::get('sales/{id}', [SalesController::class, 'show']);

    Route::post('/sales-payments/{sales}', [SalesPaymentController::class, 'store'])->name('sales-payments.store');
    Route::put('/sales-payments/{id}', [SalesPaymentController::class, 'update'])->name('sales-payments.update');
    Route::delete('/sales-payments/{id}', [SalesPaymentController::class, 'destroy'])->name('sales-payments.destroy');
    Route::get('/sales-payments/data', [SalesPaymentController::class, 'getData'])->name('sales-payments.data');
    Route::get('/sales/{id}/payment-detail', [SalesPaymentController::class, 'paymentDetail']);
    Route::get('/sales/{id}/print', [SalesController::class, 'print'])->name('sales.print');


    Route::get('/api/barang/by-code/{code}', [BarangController::class, 'getByCode']);
    Route::get('/api/barang/by-qr/{code}', [BarangController::class, 'getByQR']);


    Route::get('printQr', [BarangController::class, 'printQr'])->name('printQr');
    Route::get('/generateQr/{id}', [BarangController::class, 'generateQr'])->name('generateQr');

    Route::get('/client/data', [ClientController::class, 'data'])->name('client.data');
    Route::resource('client', ClientController::class);





Route::get('/stok-opname', [StokOpnameController::class, 'index'])->name('stok-opname.index');
Route::get('/stok-opname/data', [StokOpnameController::class, 'data'])->name('stok-opname.data');
Route::post('/stok-opname/update', [StokOpnameController::class, 'update'])->name('stok-opname.update');
// halaman riwayat stok opname
Route::get('/stok-opname/logs', [StokOpnameController::class, 'logs'])->name('stok-opname.logs');
Route::get('/stok-opname/logs/data', [StokOpnameController::class, 'logsData'])->name('stok-opname.logs.data');

Route::get('/pembelian', [PembelianController::class, 'index'])->name('pembelian.index');
Route::get('/pembelian/data', [PembelianController::class, 'data'])->name('pembelian.data');
Route::post('/pembelian', [PembelianController::class, 'store'])->name('pembelian.store');
Route::get('/pembelian/barang-info/{id}', [PembelianController::class, 'barangInfo']);
Route::delete('/pembelian/{id}', [PembelianController::class, 'destroy']);
Route::get('/pembelian/{id}/edit', [PembelianController::class, 'edit']);
Route::put('/pembelian/{id}', [PembelianController::class, 'update']);
Route::get('/select/barang', [PembelianController::class, 'barang'])->name('select.barang');


Route::prefix('stok-transaksi')->group(function () {
    Route::get('/', [StokTransactionController::class, 'index'])->name('stok-transaksi.index');
    Route::get('/data', [StokTransactionController::class, 'data'])->name('stok-transaksi.data');
    Route::post('/store', [StokTransactionController::class, 'store'])->name('stok-transaksi.store');
    Route::delete('/{id}', [StokTransactionController::class, 'destroy'])->name('stok-transaksi.destroy');
});

        // laporan


        Route::prefix('report')->group(function () {
            Route::get('/', [LaporanController::class, 'index'])->name('laporan.index');
        });




    Route::get('/reports/service', [ReportController::class, 'serviceReport'])->name('reports.service');
    Route::get('/reports/sale', [ReportController::class, 'laporanPenjualanBarang'])->name('reports.sale');
    Route::get('/reports/laporanGabungan', [ReportController::class, 'laporanGabungan'])->name('reports.Gabungan');
    Route::get('/reports/mekanik', [ReportController::class, 'mekanik'])->name('reports.mekanik');
    Route::get('/reports/sold-items', [ReportController::class, 'soldItemsReport'])->name('reports.sold-items');

    // routes/web.php
Route::get('/sales-payments/data', [SalesReportController::class, 'getData'])->name('sales-payments.data');
Route::get('/sales-payments', [SalesReportController::class, 'index'])->name('sales-payments.index');

    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/data', [CustomerController::class, 'getData'])->name('customers.data');
    Route::put('/customers/{id}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/customers/{id}', [CustomerController::class, 'destroy'])->name('customers.destroy');
    Route::post('/customers/store', [CustomerController::class, 'store'])->name('customers.store');
    Route::get('/customers/search', [CustomerController::class, 'search'])->name('customers.search');
    Route::get('/customers/{id}/promo-check', [CustomerController::class, 'promoCheck']);



    //report
    Route::prefix('reports')->group(function () {
        Route::get('/omzet', [ReportController::class, 'omzet'])->name('reports.omzet');
    });



    // routes/web.php
    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::get('/permissions/list', [PermissionController::class, 'list'])->name('permissions.list');
    Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions.store');
    Route::put('/permissions/{permission}', [PermissionController::class, 'update'])->name('permissions.update');
    Route::delete('/permissions/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy');


});
