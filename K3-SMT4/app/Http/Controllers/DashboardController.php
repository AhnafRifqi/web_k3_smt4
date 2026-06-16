<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use App\Models\AuditFinding;
use App\Models\Capa;
use App\Models\Employee;
use App\Models\Incident;
use App\Models\K3Document;
use App\Models\Sop;
use App\Models\SopExecution;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $departmentId = $request->department_id;
        $dateFrom = $request->date_from;
        $dateTo = $request->date_to;

        // For dept_head: auto-scope to their department
        if ($user->isDeptHead() && $user->employee) {
            $departmentId = $user->employee->department_id;
        }

        // Build base query scopes
        $empQuery = Employee::where('status', 'aktif');
        $sopQuery = Sop::where('status', 'aktif');
        $auditQuery = Audit::query();
        $findingQuery = AuditFinding::query();
        $capaQuery = Capa::query();
        $sopExecQuery = SopExecution::query();
        $incidentQuery = Incident::query();
        $docQuery = K3Document::query();

        // Apply department filter
        if ($departmentId) {
            $empQuery->where('department_id', $departmentId);
            $incidentQuery->where('department_id', $departmentId);
        }

        // Apply date range filter
        if ($dateFrom) {
            $auditQuery->whereDate('audit_date', '>=', $dateFrom);
            $findingQuery->whereHas('audit', fn($q) => $q->whereDate('audit_date', '>=', $dateFrom));
            $incidentQuery->whereDate('incident_date', '>=', $dateFrom);
        }
        if ($dateTo) {
            $auditQuery->whereDate('audit_date', '<=', $dateTo);
            $findingQuery->whereHas('audit', fn($q) => $q->whereDate('audit_date', '<=', $dateTo));
            $incidentQuery->whereDate('incident_date', '<=', $dateTo);
        }

        // Stats
        $stats = [
            'total_employees'  => $empQuery->count(),
            'total_sops'       => $sopQuery->count(),
            'total_audits'     => $auditQuery->count(),
            'total_findings'   => $findingQuery->count(),
        ];

        $findingStats = AuditFinding::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $stats['open_findings']   = $findingStats['open'] ?? 0;
        $stats['closed_findings'] = $findingStats['closed'] ?? 0;

        $capaStats = Capa::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $stats['open_capa']   = $capaStats['open'] ?? 0;
        $stats['closed_capa'] = $capaStats['closed'] ?? 0;
        $stats['overdue_capa'] = Capa::where('status', '!=', 'closed')->where('target_date', '<', now())->count();

        // ===== GAP 6: New KPIs =====
        // Safe Days: days since last Lost Time Injury
        $lastLti = Incident::where('incident_type', 'lost_time_injury')
            ->where('status', 'closed')
            ->orderBy('incident_date', 'desc')
            ->first();
        $stats['safe_days'] = $lastLti ? $lastLti->incident_date->diffInDays(now()) : 365;

        // Open incidents
        $stats['open_incidents'] = Incident::where('status', '!=', 'closed')->count();

        // Documents expiring soon (within 30 days)
        $stats['documents_expiring_soon'] = K3Document::where('workflow_status', 'approved')
            ->whereNotNull('review_due_date')
            ->whereDate('review_due_date', '>=', now())
            ->whereDate('review_due_date', '<=', now()->addDays(30))
            ->count();

        // Overdue forms placeholder
        $stats['overdue_forms'] = 0;

        // SOP Compliance this month
        $thisMonth = Carbon::now();
        $execThisMonth = SopExecution::whereYear('execution_date', $thisMonth->year)
            ->whereMonth('execution_date', $thisMonth->month)->get();
        $totalExec = $execThisMonth->count();
        $stats['sop_compliance'] = $totalExec > 0
            ? round(($execThisMonth->where('status', 'sesuai')->count() / $totalExec) * 100, 1)
            : 0;

        // Chart: Monthly SOP Compliance (last 6 months)
        $monthlyCompliance = [];
        for ($i = 5; $i >= 0; $i--) {
            $m    = Carbon::now()->subMonths($i);
            $exec = SopExecution::whereYear('execution_date', $m->year)
                ->whereMonth('execution_date', $m->month)->get();
            $tot  = $exec->count();
            $monthlyCompliance[] = [
                'month'      => $m->translatedFormat('M Y'),
                'compliance' => $tot > 0 ? round(($exec->where('status', 'sesuai')->count() / $tot) * 100, 1) : 0,
                'total'      => $tot,
            ];
        }

        // Chart: Findings by severity
        $findingsBySeverity = [
            'minor'    => AuditFinding::where('severity', 'minor')->count(),
            'major'    => AuditFinding::where('severity', 'major')->count(),
            'critical' => AuditFinding::where('severity', 'critical')->count(),
        ];

        // Chart: CAPA Status
        $capaByStatus = [
            'open'        => Capa::where('status', 'open')->count(),
            'in_progress' => Capa::where('status', 'in_progress')->count(),
            'closed'      => Capa::where('status', 'closed')->count(),
        ];

        // ===== GAP 6: Incident Statistics Chart (by type per month, last 6 months) =====
        $incidentChartData = [];
        $incidentTypes = ['near_miss', 'first_aid', 'medical_treatment', 'lost_time_injury', 'fatality', 'property_damage', 'environmental'];
        $typeLabels = [
            'near_miss' => 'Near Miss',
            'first_aid' => 'First Aid',
            'medical_treatment' => 'Medical',
            'lost_time_injury' => 'LTI',
            'fatality' => 'Fatality',
            'property_damage' => 'Property',
            'environmental' => 'Env',
        ];

        for ($i = 5; $i >= 0; $i--) {
            $m = Carbon::now()->subMonths($i);
            $monthLabel = $m->translatedFormat('M Y');
            $row = ['month' => $monthLabel];
            foreach ($incidentTypes as $type) {
                $count = Incident::where('incident_type', $type)
                    ->whereYear('incident_date', $m->year)
                    ->whereMonth('incident_date', $m->month)
                    ->count();
                $row[$type] = $count;
            }
            $incidentChartData[] = $row;
        }

        // Recent Audits
        $recentAudits = Audit::with('findings')->latest()->take(5)->get();

        // Recent CAPA overdue
        $overdueCapa = Capa::with(['pic', 'audit'])
            ->where('status', '!=', 'closed')
            ->where('target_date', '<', now())
            ->latest()->take(5)->get();

        // ===== GAP 6: Recent Incidents widget =====
        $recentIncidents = Incident::with(['department', 'reporter'])
            ->where('status', '!=', 'closed')
            ->latest('incident_date')
            ->take(5)->get();

        // Departments for filter
        $departments = \App\Models\Department::orderBy('name')->get();

        return view('dashboard', compact(
            'stats', 'monthlyCompliance', 'findingsBySeverity',
            'capaByStatus', 'recentAudits', 'overdueCapa',
            'incidentChartData', 'incidentTypes', 'typeLabels',
            'recentIncidents', 'departments', 'departmentId',
            'dateFrom', 'dateTo'
        ));
    }
}