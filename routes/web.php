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
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StokTransactionController;
use App\Http\Controllers\SalesReportController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\WarehouseController;

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
        Route::get('/generate/code', [SupplierController::class, 'generateCode'])->name('suppliers.generate.code');
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

Route::prefix('warehouses')->group(function () {
    Route::get('/', [WarehouseController::class, 'index'])->name('warehouses.index');
    Route::get('/data', [WarehouseController::class, 'getData'])->name('warehouses.data');
    Route::post('/', [WarehouseController::class, 'store'])->name('warehouses.store');
    Route::put('/{id}', [WarehouseController::class, 'update'])->name('warehouses.update');
    Route::delete('/{id}', [WarehouseController::class, 'destroy'])->name('warehouses.destroy');
    Route::get('/{id}', [WarehouseController::class, 'show'])->name('warehouses.show');
    Route::get('/generate/code', [WarehouseController::class, 'generateCode'])->name('warehouses.generate.code');
});

Route::prefix('customers')->group(function () {
    Route::get('/', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/data', [CustomerController::class, 'getData'])->name('customers.data');
    Route::post('/', [CustomerController::class, 'store'])->name('customers.store');
    Route::put('/{id}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/{id}', [CustomerController::class, 'destroy'])->name('customers.destroy');
    Route::get('/{id}', [CustomerController::class, 'show'])->name('customers.show');
    Route::get('/generate/code', [CustomerController::class, 'generateCode'])->name('customers.generate.code');
});


Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('products.index');
    Route::get('/data', [ProductController::class, 'getData'])->name('products.data');
    Route::post('/', [ProductController::class, 'store'])->name('products.store');
    Route::put('/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::get('/{id}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/generate/code', [ProductController::class, 'generateCode'])->name('products.generate.code');
});



    // routes/web.php
    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::get('/permissions/list', [PermissionController::class, 'list'])->name('permissions.list');
    Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions.store');
    Route::put('/permissions/{permission}', [PermissionController::class, 'update'])->name('permissions.update');
    Route::delete('/permissions/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy');

    Route::resource('pos', TransactionController::class);
    Route::get('/transactions', [TransactionController::class, 'getData'])->name('transactions.data');
    Route::get('/transactions/{id}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::get('/list/transactions', [TransactionController::class, 'list'])->name('list.transactions');
});
