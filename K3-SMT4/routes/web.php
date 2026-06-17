<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AuditChecklistController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\AuditFindingController;
use App\Http\Controllers\CapaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\DocumentApprovalController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\HazardController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\K3DocumentController;
use App\Http\Controllers\MonitoringFormController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RekapController;
use App\Http\Controllers\SopController;
use App\Http\Controllers\SopExecutionController;
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

    // ---- Halaman Verifikasi ----
    Route::get('/menunggu-verifikasi', [VerificationController::class, 'pending'])->name('verification.pending');

    // ---- Middleware Validasi ----
    Route::middleware(['validated'])->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::middleware('role:super_admin,k3_manager,k3_officer')->group(function () {
            Route::get('/dashboard/export-pdf', [DashboardController::class, 'exportPdf'])->name('dashboard.export-pdf');
            Route::get('/dashboard/export-excel', [DashboardController::class, 'exportExcel'])->name('dashboard.export-excel');
        });

        // Profile (accessible by all validated users)
        Route::middleware('role:super_admin,k3_manager,k3_officer,dept_head,employee,auditor,viewer')->group(function () {
            Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
            Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        });

        // ---- SOP (all roles can view) ----
        Route::get('/sop', [SopController::class, 'index'])->name('sops.index');

        // ---- SOP Management (super_admin, k3_manager, k3_officer) ----
        Route::middleware('role:super_admin,k3_manager,k3_officer')->group(function () {
            Route::get('/sop/create', [SopController::class, 'create'])->name('sops.create');
            Route::post('/sop', [SopController::class, 'store'])->name('sops.store');
            Route::get('/sop/{sop}/edit', [SopController::class, 'edit'])->name('sops.edit');
            Route::put('/sop/{sop}', [SopController::class, 'update'])->name('sops.update');
            Route::delete('/sop/{sop}', [SopController::class, 'destroy'])->name('sops.destroy');
        });
        Route::get('/sop/{sop}', [SopController::class, 'show'])->name('sops.show');

        // ---- Employees (super_admin, k3_manager, k3_officer) ----
        Route::middleware('role:super_admin,k3_manager,k3_officer')->group(function () {
            Route::resource('employees', EmployeeController::class);
            Route::get('/employees/export/excel', [EmployeeController::class, 'exportExcel'])->name('employees.export.excel');
            Route::get('/employees/export/pdf', [EmployeeController::class, 'exportPdf'])->name('employees.export.pdf');
            Route::patch('/employees/{user}/approve-pending', [EmployeeController::class, 'approvePending'])->name('employees.approve-pending');
            Route::delete('/employees/{user}/reject-pending', [EmployeeController::class, 'rejectPending'])->name('employees.reject-pending');
        });

        // ---- User Management (super_admin only) ----
        Route::middleware('role:super_admin')->group(function () {
            Route::resource('divisions', DivisionController::class)->except(['show']);
            Route::resource('departments', DepartmentController::class);
            Route::patch('/users/{user}/validate', [UserController::class, 'validateUser'])->name('users.validate');
            Route::patch('/users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle-active');
            Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
            Route::resource('users', UserController::class);
        });

        // ---- Employee Self-Service (employee role) ----
        Route::middleware('role:employee,dept_head')->group(function () {
            Route::get('/my-employee', [EmployeeController::class, 'myEmployee'])->name('my-employee');
            Route::get('/my-employee/create', [EmployeeController::class, 'createMyEmployee'])->name('my-employee.create');
            Route::post('/my-employee', [EmployeeController::class, 'storeMyEmployee'])->name('my-employee.store');
            Route::get('/my-employee/edit', [EmployeeController::class, 'editMyEmployee'])->name('my-employee.edit');
            Route::put('/my-employee', [EmployeeController::class, 'updateMyEmployee'])->name('my-employee.update');
        });

        // ---- Dokumen K3 ----
        Route::middleware('role:super_admin,k3_manager,k3_officer')->group(function () {
            Route::get('/k3-documents/create', [K3DocumentController::class, 'create'])->name('k3-documents.create');
            Route::post('/k3-documents', [K3DocumentController::class, 'store'])->name('k3-documents.store');
            Route::get('/k3-documents/{k3Document}/edit', [K3DocumentController::class, 'edit'])->name('k3-documents.edit');
            Route::put('/k3-documents/{k3Document}', [K3DocumentController::class, 'update'])->name('k3-documents.update');
            Route::delete('/k3-documents/{k3Document}', [K3DocumentController::class, 'destroy'])->name('k3-documents.destroy');

            // Document Approval Workflow
            Route::post('/k3-documents/{k3Document}/submit', [DocumentApprovalController::class, 'submit'])->name('k3-documents.submit');
            Route::post('/k3-documents/{k3Document}/approve', [DocumentApprovalController::class, 'approve'])->name('k3-documents.approve');
            Route::post('/k3-documents/{k3Document}/reject', [DocumentApprovalController::class, 'reject'])->name('k3-documents.reject');
            Route::post('/k3-documents/{k3Document}/create-revision', [DocumentApprovalController::class, 'createRevision'])->name('k3-documents.create-revision');
            Route::post('/k3-documents/{k3Document}/mark-obsolete', [DocumentApprovalController::class, 'markObsolete'])->name('k3-documents.mark-obsolete');
        });
        Route::get('/k3-documents', [K3DocumentController::class, 'index'])->name('k3-documents.index');
        Route::get('/k3-documents/{k3Document}', [K3DocumentController::class, 'show'])->name('k3-documents.show');
        // Document download & stream proxy (accessible by all validated users)
        Route::get('/k3-documents/{k3Document}/download', [K3DocumentController::class, 'download'])->name('k3-documents.download');
        Route::get('/k3-documents/{k3Document}/stream', [K3DocumentController::class, 'stream'])->name('k3-documents.stream');

        // ---- Pelaksanaan SOP ----
        Route::get('/sop-executions', [SopExecutionController::class, 'index'])->name('sop-executions.index');
        Route::middleware('role:super_admin,k3_manager,k3_officer,employee')->group(function () {
            Route::get('/sop-executions/create', [SopExecutionController::class, 'create'])->name('sop-executions.create');
            Route::post('/sop-executions', [SopExecutionController::class, 'store'])->name('sop-executions.store');
            Route::get('/sop-executions/{sopExecution}/edit', [SopExecutionController::class, 'edit'])->name('sop-executions.edit');
            Route::put('/sop-executions/{sopExecution}', [SopExecutionController::class, 'update'])->name('sop-executions.update');
            Route::delete('/sop-executions/{sopExecution}', [SopExecutionController::class, 'destroy'])->name('sop-executions.destroy');
        });
        Route::get('/sop-executions/{sopExecution}', [SopExecutionController::class, 'show'])->name('sop-executions.show');

        // ---- Audit ----
        Route::get('/audits', [AuditController::class, 'index'])->name('audits.index');
        Route::middleware('role:super_admin,auditor,k3_manager')->group(function () {
            Route::get('/audits/create', [AuditController::class, 'create'])->name('audits.create');
            Route::post('/audits', [AuditController::class, 'store'])->name('audits.store');
            Route::get('/audits/{audit}/edit', [AuditController::class, 'edit'])->name('audits.edit');
            Route::put('/audits/{audit}', [AuditController::class, 'update'])->name('audits.update');
            Route::delete('/audits/{audit}', [AuditController::class, 'destroy'])->name('audits.destroy');
        });
        Route::get('/audits/{audit}', [AuditController::class, 'show'])->name('audits.show');
        Route::get('/audits/{audit}/export-pdf', [AuditController::class, 'exportPdf'])->name('audits.export-pdf');

        Route::middleware('role:super_admin,auditor,k3_manager')->group(function () {
            Route::post('/audits/{audit}/checklist-items', [AuditChecklistController::class, 'store'])->name('audit-checklist.store');
            Route::patch('/audit-checklist-items/{auditChecklistItem}', [AuditChecklistController::class, 'update'])->name('audit-checklist.update');
            Route::delete('/audit-checklist-items/{auditChecklistItem}', [AuditChecklistController::class, 'destroy'])->name('audit-checklist.destroy');
        });

        // ---- Evidence Package Export (GAP 7) ----
        Route::middleware('role:super_admin,auditor,k3_manager')->group(function () {
            Route::get('/audits/{audit}/evidence-package', [AuditController::class, 'exportEvidencePackage'])->name('audits.evidence-package');
        });

        // ---- Temuan Audit ----
        Route::middleware('role:super_admin,auditor,k3_manager,k3_officer')->group(function () {
            Route::resource('audit-findings', AuditFindingController::class);
        });

        // ---- CAPA ----
        Route::get('/capa', [CapaController::class, 'index'])->name('capa.index');
        Route::middleware('role:super_admin,k3_manager,k3_officer')->group(function () {
            Route::get('/capa/create', [CapaController::class, 'create'])->name('capa.create');
            Route::post('/capa', [CapaController::class, 'store'])->name('capa.store');
            Route::get('/capa/{capa}/edit', [CapaController::class, 'edit'])->name('capa.edit');
            Route::put('/capa/{capa}', [CapaController::class, 'update'])->name('capa.update');
            Route::delete('/capa/{capa}', [CapaController::class, 'destroy'])->name('capa.destroy');
        });
        Route::get('/capa/{capa}', [CapaController::class, 'show'])->name('capa.show');

        // ---- INCIDENTS (GAP 2) ----
        Route::resource('incidents', IncidentController::class);
        Route::post('/incidents/{incident}/assign-investigation', [IncidentController::class, 'assignInvestigation'])->name('incidents.assign-investigation');

        // ---- HAZARDS / HIRARC (GAP 2) ----
        Route::resource('hazards', HazardController::class);

        // ---- Form Monitoring (GAP 1) ----
        Route::get('/monitoring-forms', [MonitoringFormController::class, 'index'])->name('monitoring-forms.index');

        Route::middleware('role:super_admin,k3_manager,k3_officer')->group(function () {
            Route::get('/monitoring-forms/create', [MonitoringFormController::class, 'create'])->name('monitoring-forms.create');
            Route::post('/monitoring-forms', [MonitoringFormController::class, 'store'])->name('monitoring-forms.store');
        });

        Route::middleware('role:super_admin,k3_manager,k3_officer,dept_head,employee')->group(function () {
            Route::get('/monitoring-forms/{monitoringForm}/fill/{assignment}', [MonitoringFormController::class, 'fill'])->name('monitoring-forms.fill');
            Route::post('/monitoring-forms/{monitoringForm}/fill/{assignment}', [MonitoringFormController::class, 'submit'])->name('monitoring-forms.submit');
        });

        Route::get('/monitoring-forms/{monitoringForm}', [MonitoringFormController::class, 'show'])->name('monitoring-forms.show');

        Route::middleware('role:super_admin,k3_manager,k3_officer')->group(function () {
            Route::get('/monitoring-forms/{monitoringForm}/edit', [MonitoringFormController::class, 'edit'])->name('monitoring-forms.edit');
            Route::put('/monitoring-forms/{monitoringForm}', [MonitoringFormController::class, 'update'])->name('monitoring-forms.update');
            Route::delete('/monitoring-forms/{monitoringForm}', [MonitoringFormController::class, 'destroy'])->name('monitoring-forms.destroy');
            Route::post('/monitoring-forms/{monitoringForm}/assign', [MonitoringFormController::class, 'assign'])->name('monitoring-forms.assign');
        });

        Route::middleware('role:super_admin,k3_manager,k3_officer,dept_head')->group(function () {
            Route::post('/monitoring-forms/{monitoringForm}/submissions/{submission}/approve', [MonitoringFormController::class, 'approveSubmission'])->name('monitoring-forms.submissions.approve');
            Route::post('/monitoring-forms/{monitoringForm}/submissions/{submission}/reject', [MonitoringFormController::class, 'rejectSubmission'])->name('monitoring-forms.submissions.reject');
        });

        // ---- ACTIVITY LOGS (GAP 4) ----
        Route::middleware('role:super_admin,k3_manager,auditor')->group(function () {
            Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
        });

        // ---- NOTIFICATIONS (GAP 5) ----
        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.mark-read');
        Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');
        Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
        Route::get('/notifications/latest', [NotificationController::class, 'latest'])->name('notifications.latest');

        // ---- Rekap & Narasi (super_admin, k3_manager, k3_officer) ----
        Route::middleware('role:super_admin,k3_manager,k3_officer')->prefix('rekap')->name('rekap.')->group(function () {
            Route::get('/', [RekapController::class, 'index'])->name('index');
            Route::get('/bulanan', [RekapController::class, 'bulanan'])->name('bulanan');
            Route::get('/triwulan', [RekapController::class, 'triwulan'])->name('triwulan');
            Route::get('/tahunan', [RekapController::class, 'tahunan'])->name('tahunan');
            Route::get('/export-pdf', [RekapController::class, 'exportPdf'])->name('export-pdf');
        });

    }); // end validated middleware
}); // end auth middleware