<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PoController;
use App\Http\Controllers\InspectionController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ExportController;

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [UserController::class, 'login']);

Route::middleware(['auth.check'])
    ->group(function () {
            
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/logout', [UserController::class, 'logout'])->name('logout');

        // PO Management Routes
        Route::prefix('po')->name('po.')->group(function () {
            Route::get('/', [PoController::class, 'index'])->name('index');
            Route::get('/create', [PoController::class, 'create'])->name('create');
            Route::post('/store', [PoController::class, 'store'])->name('store');
            Route::get('/{menu}', [PoController::class, 'show'])->name('show');
            Route::get('/{menu}/edit', [PoController::class, 'edit'])->name('edit');
            Route::put('/{menu}', [PoController::class, 'update'])->name('update');
            Route::delete('/{menu}', [PoController::class, 'destroy'])->name('destroy');
        });

        // Inspection (Pengecekan Quality Control) Routes
        Route::prefix('inspection')->name('inspection.')->group(function () {
            Route::get('/', [InspectionController::class, 'index'])->name('index');
            Route::get('/check/{menu}', [InspectionController::class, 'check'])->name('check');
            Route::post('/store/{menu}', [InspectionController::class, 'store'])->name('store');
            Route::get('/{inspection}', [InspectionController::class, 'show'])->name('show');
        });

        // Invoice Routes
        Route::prefix('invoice')->name('invoice.')->group(function () {
            Route::get('/', [InvoiceController::class, 'index'])->name('index');
            Route::get('/{invoice}', [InvoiceController::class, 'show'])->name('show');
            Route::patch('/{invoice}/status', [InvoiceController::class, 'updateStatus'])->name('status');
        });

        // Export PDF & Excel Routes
        Route::prefix('export')->name('export.')->group(function () {
            Route::get('/po/{menu}/pdf', [ExportController::class, 'exportPoPdf'])->name('po.pdf');
            Route::get('/po/{menu}/qc-form', [ExportController::class, 'exportQcFormPdf'])->name('po.qc-form');
            Route::get('/po/{menu}/invoice-pdf', [ExportController::class, 'exportPoInvoicePdf'])->name('po.invoice-pdf');
            Route::get('/invoice/{invoice}/pdf', [ExportController::class, 'exportInvoicePdf'])->name('invoice.pdf');
            Route::get('/inspection/{inspection}/pdf', [ExportController::class, 'exportInspectionPdf'])->name('inspection.pdf');
            Route::get('/excel/{type}', [ExportController::class, 'exportExcel'])->name('excel');
        });

    });