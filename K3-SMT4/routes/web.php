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
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerificationController;
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

    // ---- Halaman Verifikasi (bisa diakses oleh semua yang login, termasuk pending) ----
    Route::get('/menunggu-verifikasi', [VerificationController::class, 'pending'])->name('verification.pending');

    // ---- Middleware Validasi: hanya user yang sudah divalidasi bisa akses modul ----
    Route::middleware(['validated'])->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Profile
        Route::middleware('role:admin,supervisor_k3,auditor,karyawan')->group(function () {
            Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
            Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        });

        // ---- SOP (semua role bisa lihat) ----
        Route::get('/sop', [SopController::class, 'index'])->name('sops.index');

        // ---- SOP Management (admin, supervisor_k3) ----
        Route::middleware('role:admin,supervisor_k3')->group(function () {
            Route::get('/sop/create', [SopController::class, 'create'])->name('sops.create');
            Route::post('/sop', [SopController::class, 'store'])->name('sops.store');
            Route::get('/sop/{sop}/edit', [SopController::class, 'edit'])->name('sops.edit');
            Route::put('/sop/{sop}', [SopController::class, 'update'])->name('sops.update');
            Route::delete('/sop/{sop}', [SopController::class, 'destroy'])->name('sops.destroy');
        });
        Route::get('/sop/{sop}', [SopController::class, 'show'])->name('sops.show');

        // ---- Karyawan (admin, supervisor_k3) ----
        Route::middleware('role:admin,supervisor_k3')->group(function () {
            Route::resource('employees', EmployeeController::class);
            Route::get('/employees/export/excel', [EmployeeController::class, 'exportExcel'])->name('employees.export.excel');
            Route::get('/employees/export/pdf', [EmployeeController::class, 'exportPdf'])->name('employees.export.pdf');
            // Approve/Reject pending users
            Route::patch('/employees/{user}/approve-pending', [EmployeeController::class, 'approvePending'])->name('employees.approve-pending');
            Route::delete('/employees/{user}/reject-pending', [EmployeeController::class, 'rejectPending'])->name('employees.reject-pending');
        });

        // ---- User Management (admin) ----
        Route::middleware('role:admin')->group(function () {
            Route::resource('departments', DepartmentController::class);
            Route::patch('/users/{user}/validate', [UserController::class, 'validateUser'])->name('users.validate');
            Route::patch('/users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle-active');
            Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
            Route::resource('users', UserController::class);
        });

        // ---- Data Karyawan Mandiri (karyawan) ----
        Route::middleware('role:karyawan')->group(function () {
            Route::get('/my-employee', [EmployeeController::class, 'myEmployee'])->name('my-employee');
            Route::get('/my-employee/create', [EmployeeController::class, 'createMyEmployee'])->name('my-employee.create');
            Route::post('/my-employee', [EmployeeController::class, 'storeMyEmployee'])->name('my-employee.store');
            Route::get('/my-employee/edit', [EmployeeController::class, 'editMyEmployee'])->name('my-employee.edit');
            Route::put('/my-employee', [EmployeeController::class, 'updateMyEmployee'])->name('my-employee.update');
        });

        // ---- Dokumen K3 ----
        Route::middleware('role:admin,supervisor_k3')->group(function () {
            Route::get('/k3-documents/create', [K3DocumentController::class, 'create'])->name('k3-documents.create');
            Route::post('/k3-documents', [K3DocumentController::class, 'store'])->name('k3-documents.store');
            Route::get('/k3-documents/{k3Document}/edit', [K3DocumentController::class, 'edit'])->name('k3-documents.edit');
            Route::put('/k3-documents/{k3Document}', [K3DocumentController::class, 'update'])->name('k3-documents.update');
            Route::delete('/k3-documents/{k3Document}', [K3DocumentController::class, 'destroy'])->name('k3-documents.destroy');
        });
        Route::get('/k3-documents', [K3DocumentController::class, 'index'])->name('k3-documents.index');
        Route::get('/k3-documents/{k3Document}', [K3DocumentController::class, 'show'])->name('k3-documents.show');

        // ---- Pelaksanaan SOP ----
        Route::get('/sop-executions', [SopExecutionController::class, 'index'])->name('sop-executions.index');
        Route::middleware('role:admin,supervisor_k3,karyawan')->group(function () {
            Route::get('/sop-executions/create', [SopExecutionController::class, 'create'])->name('sop-executions.create');
            Route::post('/sop-executions', [SopExecutionController::class, 'store'])->name('sop-executions.store');
            Route::get('/sop-executions/{sopExecution}/edit', [SopExecutionController::class, 'edit'])->name('sop-executions.edit');
            Route::put('/sop-executions/{sopExecution}', [SopExecutionController::class, 'update'])->name('sop-executions.update');
            Route::delete('/sop-executions/{sopExecution}', [SopExecutionController::class, 'destroy'])->name('sop-executions.destroy');
        });
        Route::get('/sop-executions/{sopExecution}', [SopExecutionController::class, 'show'])->name('sop-executions.show');

        // ---- Audit ----
        Route::get('/audits', [AuditController::class, 'index'])->name('audits.index');
        Route::middleware('role:admin,auditor')->group(function () {
            Route::get('/audits/create', [AuditController::class, 'create'])->name('audits.create');
            Route::post('/audits', [AuditController::class, 'store'])->name('audits.store');
            Route::get('/audits/{audit}/edit', [AuditController::class, 'edit'])->name('audits.edit');
            Route::put('/audits/{audit}', [AuditController::class, 'update'])->name('audits.update');
            Route::delete('/audits/{audit}', [AuditController::class, 'destroy'])->name('audits.destroy');
        });
        Route::get('/audits/{audit}', [AuditController::class, 'show'])->name('audits.show');
        Route::get('/audits/{audit}/export-pdf', [AuditController::class, 'exportPdf'])->name('audits.export-pdf');

        // ---- Temuan Audit ----
        Route::middleware('role:admin,auditor,supervisor_k3')->group(function () {
            Route::resource('audit-findings', AuditFindingController::class);
        });

        // ---- CAPA ----
        Route::get('/capa', [CapaController::class, 'index'])->name('capa.index');
        Route::middleware('role:admin,supervisor_k3')->group(function () {
            Route::get('/capa/create', [CapaController::class, 'create'])->name('capa.create');
            Route::post('/capa', [CapaController::class, 'store'])->name('capa.store');
            Route::get('/capa/{capa}/edit', [CapaController::class, 'edit'])->name('capa.edit');
            Route::put('/capa/{capa}', [CapaController::class, 'update'])->name('capa.update');
            Route::delete('/capa/{capa}', [CapaController::class, 'destroy'])->name('capa.destroy');
        });
        Route::get('/capa/{capa}', [CapaController::class, 'show'])->name('capa.show');

        // ---- Rekap & Narasi (admin, supervisor_k3) ----
        Route::middleware('role:admin,supervisor_k3')->prefix('rekap')->name('rekap.')->group(function () {
            Route::get('/', [RekapController::class, 'index'])->name('index');
            Route::get('/bulanan', [RekapController::class, 'bulanan'])->name('bulanan');
            Route::get('/triwulan', [RekapController::class, 'triwulan'])->name('triwulan');
            Route::get('/tahunan', [RekapController::class, 'tahunan'])->name('tahunan');
            Route::get('/export-pdf', [RekapController::class, 'exportPdf'])->name('export-pdf');
        });
    });
});