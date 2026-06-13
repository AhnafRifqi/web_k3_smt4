<?php

use App\Http\Controllers\AuditController;
use App\Http\Controllers\AuditFindingController;
use App\Http\Controllers\CapaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\K3DocumentController;
use App\Http\Controllers\RekapController;
use App\Http\Controllers\SopController;
use App\Http\Controllers\SopExecutionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('dashboard'));

// ============================================================
// AUTH ROUTES (dari Laravel Breeze)
// ============================================================
require __DIR__ . '/auth.php';

// ============================================================
// AUTHENTICATED ROUTES
// ============================================================
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ---- SOP (semua role bisa lihat) ----
    Route::get('/sop', [SopController::class, 'index'])->name('sops.index');
    Route::get('/sop/{sop}', [SopController::class, 'show'])->name('sops.show');

    // ---- SOP Management (admin, supervisor_k3) ----
    Route::middleware('role:admin,supervisor_k3')->group(function () {
        Route::get('/sop/create', [SopController::class, 'create'])->name('sops.create');
        Route::post('/sop', [SopController::class, 'store'])->name('sops.store');
        Route::get('/sop/{sop}/edit', [SopController::class, 'edit'])->name('sops.edit');
        Route::put('/sop/{sop}', [SopController::class, 'update'])->name('sops.update');
        Route::delete('/sop/{sop}', [SopController::class, 'destroy'])->name('sops.destroy');
    });

    // ---- Karyawan (admin, supervisor_k3) ----
    Route::middleware('role:admin,supervisor_k3')->group(function () {
        Route::resource('employees', EmployeeController::class);
        Route::get('/employees/export/excel', [EmployeeController::class, 'exportExcel'])->name('employees.export.excel');
        Route::get('/employees/export/pdf', [EmployeeController::class, 'exportPdf'])->name('employees.export.pdf');
    });

    // ---- Departemen (admin) ----
    Route::middleware('role:admin')->group(function () {
        Route::resource('departments', DepartmentController::class);
        Route::resource('users', UserController::class);
    });

    // ---- Dokumen K3 ----
    Route::get('/k3-documents', [K3DocumentController::class, 'index'])->name('k3-documents.index');
    Route::get('/k3-documents/{k3Document}', [K3DocumentController::class, 'show'])->name('k3-documents.show');
    Route::middleware('role:admin,supervisor_k3')->group(function () {
        Route::get('/k3-documents/create', [K3DocumentController::class, 'create'])->name('k3-documents.create');
        Route::post('/k3-documents', [K3DocumentController::class, 'store'])->name('k3-documents.store');
        Route::get('/k3-documents/{k3Document}/edit', [K3DocumentController::class, 'edit'])->name('k3-documents.edit');
        Route::put('/k3-documents/{k3Document}', [K3DocumentController::class, 'update'])->name('k3-documents.update');
        Route::delete('/k3-documents/{k3Document}', [K3DocumentController::class, 'destroy'])->name('k3-documents.destroy');
    });

    // ---- Pelaksanaan SOP ----
    Route::get('/sop-executions', [SopExecutionController::class, 'index'])->name('sop-executions.index');
    Route::middleware('role:admin,supervisor_k3,karyawan')->group(function () {
        Route::get('/sop-executions/create', [SopExecutionController::class, 'create'])->name('sop-executions.create');
        Route::post('/sop-executions', [SopExecutionController::class, 'store'])->name('sop-executions.store');
        Route::get('/sop-executions/{sopExecution}/edit', [SopExecutionController::class, 'edit'])->name('sop-executions.edit');
        Route::put('/sop-executions/{sopExecution}', [SopExecutionController::class, 'update'])->name('sop-executions.update');
        Route::delete('/sop-executions/{sopExecution}', [SopExecutionController::class, 'destroy'])->name('sop-executions.destroy');
    });

    // ---- Audit ----
    Route::get('/audits', [AuditController::class, 'index'])->name('audits.index');
    Route::get('/audits/{audit}', [AuditController::class, 'show'])->name('audits.show');
    Route::get('/audits/{audit}/export-pdf', [AuditController::class, 'exportPdf'])->name('audits.export-pdf');
    Route::middleware('role:admin,auditor')->group(function () {
        Route::get('/audits/create', [AuditController::class, 'create'])->name('audits.create');
        Route::post('/audits', [AuditController::class, 'store'])->name('audits.store');
        Route::get('/audits/{audit}/edit', [AuditController::class, 'edit'])->name('audits.edit');
        Route::put('/audits/{audit}', [AuditController::class, 'update'])->name('audits.update');
        Route::delete('/audits/{audit}', [AuditController::class, 'destroy'])->name('audits.destroy');
    });

    // ---- Temuan Audit ----
    Route::middleware('role:admin,auditor,supervisor_k3')->group(function () {
        Route::resource('audit-findings', AuditFindingController::class);
    });

    // ---- CAPA ----
    Route::get('/capa', [CapaController::class, 'index'])->name('capa.index');
    Route::get('/capa/{capa}', [CapaController::class, 'show'])->name('capa.show');
    Route::middleware('role:admin,supervisor_k3')->group(function () {
        Route::get('/capa/create', [CapaController::class, 'create'])->name('capa.create');
        Route::post('/capa', [CapaController::class, 'store'])->name('capa.store');
        Route::get('/capa/{capa}/edit', [CapaController::class, 'edit'])->name('capa.edit');
        Route::put('/capa/{capa}', [CapaController::class, 'update'])->name('capa.update');
        Route::delete('/capa/{capa}', [CapaController::class, 'destroy'])->name('capa.destroy');
    });

    // ---- Rekap & Narasi (admin, supervisor_k3) ----
    Route::middleware('role:admin,supervisor_k3')->prefix('rekap')->name('rekap.')->group(function () {
        Route::get('/', [RekapController::class, 'index'])->name('index');
        Route::get('/bulanan', [RekapController::class, 'bulanan'])->name('bulanan');
        Route::get('/triwulan', [RekapController::class, 'triwulan'])->name('triwulan');
        Route::get('/tahunan', [RekapController::class, 'tahunan'])->name('tahunan');
        Route::get('/export-pdf', [RekapController::class, 'exportPdf'])->name('export-pdf');
    });
});
