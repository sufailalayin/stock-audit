<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\ResultController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Staff\StockCountController;
use App\Http\Controllers\Admin\StockAuditController;
use App\Http\Controllers\Admin\UserController;

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // ===== USERS =====
        Route::get('/users', [UserController::class, 'index'])
            ->name('users.index');

        Route::get('/users/create', [UserController::class, 'create'])
            ->name('users.create');

        Route::post('/users', [UserController::class, 'store'])
            ->name('users.store');

        // ===== AUDIT EXPORTS =====
        Route::get(
            '/audit-files/{auditFile}/export-excel',
            [StockAuditController::class, 'exportExcel']
        )->name('audit.export.excel');

        Route::get(
            '/audit-files/{auditFile}/export-pdf',
            [StockAuditController::class, 'exportPdf']
        )->name('audit.export.pdf');

        // ===== STOCK COUNT EDIT =====
        Route::get(
            '/stocks/{stockCount}/edit',
            [StockAuditController::class, 'edit']
        )->name('stocks.edit-count');

        Route::put(
            '/stocks/{stockCount}',
            [StockAuditController::class, 'update']
        )->name('stocks.update-count');

        Route::delete(
            '/stocks/{stockCount}',
            [StockAuditController::class, 'destroy']
        )->name('stocks.delete-count');
    /* =======================
       DASHBOARD
    ======================= */
    Route::get('/', [DashboardController::class, 'index'])
        ->name('dashboard');


    /* =======================
       STOCK UPLOAD
    ======================= */
  

    // Upload page
Route::get('/stocks/upload', [StockAuditController::class, 'upload'])
    ->name('stocks.upload');

// Upload submit
Route::post('/stocks/upload', [StockAuditController::class, 'uploadStore'])
    ->name('stocks.upload.store');


    /* =======================
       STOCK AUDIT – FILES & STOCK COUNTS
    ======================= */

    // Show stocks under audit file
    Route::get('/stocks/{auditFile}', [StockAuditController::class, 'show'])
        ->name('stocks.show');

    // Delete audit file (with related stocks)
    Route::delete('/audit-files/{auditFile}', [StockAuditController::class, 'destroyAuditFile'])
        ->name('audit-files.destroy');

    // Edit stock count
    Route::get('/stock-counts/{stockCount}/edit', [StockAuditController::class, 'edit'])
        ->name('stock-counts.edit');

    // Update stock count
    Route::put('/stock-counts/{stockCount}', [StockAuditController::class, 'update'])
        ->name('stock-counts.update');

    // Delete stock count
    Route::delete('/stock-counts/{stockCount}', [StockAuditController::class, 'destroy'])
        ->name('stock-counts.destroy');


    /* =======================
       BRANCH & STOCK LIST
    ======================= */

    // Branch list
    Route::get('/branches', [StockController::class, 'branches'])
        ->name('branches');

    // Files under branch
    Route::get('/branches/{branch}/files', [StockController::class, 'files'])
        ->name('branches.files');

    // Stocks under file
    Route::get('/files/{file}/stocks', [StockController::class, 'fileStocks'])
        ->name('files.stocks');

    // All stocks
    Route::get('/stocks', [StockController::class, 'list'])
        ->name('stocks.list');


    /* =======================
       STOCK CRUD
    ======================= */

    Route::get('/stocks/{stock}/edit', [StockController::class, 'edit'])
        ->name('stocks.edit');

    Route::put('/stocks/{stock}', [StockController::class, 'update'])
        ->name('stocks.update');

    Route::delete('/stocks/{stock}', [StockController::class, 'delete'])
        ->name('stocks.delete');


    /* =======================
       AUDIT RESULTS
    ======================= */
    Route::get('/results', [ResultController::class, 'index'])
        ->name('results');

});
/*
|--------------------------------------------------------------------------
| STAFF
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'staff'])
    ->prefix('staff')
    ->name('staff.')
    ->group(function () {

        Route::get('/', [StockCountController::class, 'dashboard'])
            ->name('dashboard');

        Route::get('/count/{auditFile}', [StockCountController::class, 'count'])
            ->name('count');

        Route::post('/count/{auditFile}', [StockCountController::class, 'store'])
            ->name('count.store');

        // ✅ BARCODE FETCH (THIS WAS MISSING)
        Route::get('/barcode/{code}', [StockCountController::class, 'getStockByCode'])
            ->name('barcode.get');
});
/*
|--------------------------------------------------------------------------
| DEFAULT
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect('/login');
});