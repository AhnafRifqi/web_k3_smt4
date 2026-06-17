<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use App\Models\AuditFinding;
use App\Models\Capa;
use App\Models\Department;
use App\Models\Employee;
use App\Models\FormAssignment;
use App\Models\FormSubmission;
use App\Models\Incident;
use App\Models\K3Document;
use App\Models\Sop;
use App\Models\SopExecution;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    public function dashboardData(Request $request)
    {
        $user = auth()->user();

        $departmentId = $request->department_id;
        $dateFrom = $request->date_from;
        $dateTo = $request->date_to;
        $period = (int) ($request->period ?? 6);

        // For dept_head: auto-scope to their department
        if ($user->isDeptHead() && $user->employee) {
            $departmentId = $user->employee->department_id;
        }

        // Build base query scopes
        $empQuery = Employee::where('status', 'aktif');
        $sopQuery = Sop::where('status', 'aktif');
        $incidentQuery = Incident::query();
        $docQuery = K3Document::query();

        // Apply department filter
        if ($departmentId) {
            $empQuery->where('department_id', $departmentId);
            $incidentQuery->where('department_id', $departmentId);
        }

        // Apply date range filter
        if ($dateFrom) {
            $incidentQuery->whereDate('incident_date', '>=', $dateFrom);
        }
        if ($dateTo) {
            $incidentQuery->whereDate('incident_date', '<=', $dateTo);
        }

        // Stats
        $lastLti = Incident::where('incident_type', 'lost_time_injury')
            ->where('status', 'closed')
            ->orderBy('incident_date', 'desc')
            ->first();
        $safeDays = $lastLti ? $lastLti->incident_date->diffInDays(now()) : 365;

        $capaStats = Capa::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $capaByStatus = [
            'open'        => $capaStats['open'] ?? 0,
            'in_progress' => $capaStats['in_progress'] ?? 0,
            'closed'      => $capaStats['closed'] ?? 0,
        ];

        $findingsBySeverity = [
            'minor'    => AuditFinding::where('severity', 'minor')->count(),
            'major'    => AuditFinding::where('severity', 'major')->count(),
            'critical' => AuditFinding::where('severity', 'critical')->count(),
        ];

        // Monthly Compliance
        $monthlyCompliance = [];
        for ($i = $period - 1; $i >= 0; $i--) {
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

        // Incident Chart Data
        $incidentChartData = [];
        $incidentTypes = ['near_miss', 'first_aid', 'medical_treatment', 'lost_time_injury', 'fatality', 'property_damage', 'environmental'];
        $typeLabels = [
            'near_miss' => 'Near Miss', 'first_aid' => 'First Aid', 'medical_treatment' => 'Medical',
            'lost_time_injury' => 'LTI', 'fatality' => 'Fatality', 'property_damage' => 'Property', 'environmental' => 'Env',
        ];

        for ($i = $period - 1; $i >= 0; $i--) {
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

        // Recent incidents
        $recentIncidents = Incident::with(['department', 'reporter'])
            ->where('status', '!=', 'closed')
            ->latest('incident_date')
            ->take(5)->get()->map(function($inc) {
                return [
                    'id' => $inc->id,
                    'incident_number' => $inc->incident_number,
                    'title' => $inc->title,
                    'incident_type_label' => $inc->incident_type_label,
                    'status_label' => $inc->status_label,
                    'status_color' => $inc->status_color,
                    'severity_color' => $inc->severity_color,
                    'url' => route('incidents.show', $inc),
                ];
            });

        // Overdue CAPA
        $overdueCapa = Capa::with(['pic', 'audit'])
            ->where('status', '!=', 'closed')
            ->where('target_date', '<', now())
            ->latest()->take(5)->get()->map(function($capa) {
                return [
                    'id' => $capa->id,
                    'capa_number' => $capa->capa_number,
                    'description' => $capa->description,
                    'pic_name' => $capa->pic?->name ?? '-',
                    'target_date' => $capa->target_date->format('d M Y'),
                ];
            });

        // SOP Compliance
        $thisMonth = Carbon::now();
        $execThisMonth = SopExecution::whereYear('execution_date', $thisMonth->year)
            ->whereMonth('execution_date', $thisMonth->month)->get();
        $totalExec = $execThisMonth->count();
        $sopCompliance = $totalExec > 0
            ? round(($execThisMonth->where('status', 'sesuai')->count() / $totalExec) * 100, 1)
            : 0;

        return response()->json([
            'stats' => [
                'safe_days' => $safeDays,
                'total_employees' => $empQuery->count(),
                'total_sops' => $sopQuery->count(),
                'open_incidents' => Incident::where('status', '!=', 'closed')->count(),
                'sop_compliance' => $sopCompliance,
                'open_capa' => $capaStats['open'] ?? 0,
                'overdue_capa' => Capa::where('status', '!=', 'closed')->where('target_date', '<', now())->count(),
                'documents_expiring_soon' => K3Document::where('workflow_status', 'approved')
                    ->whereNotNull('review_due_date')
                    ->whereDate('review_due_date', '>=', now())
                    ->whereDate('review_due_date', '<=', now()->addDays(30))
                    ->count(),
            ],
            'complianceChart' => $monthlyCompliance,
            'capaByStatus' => $capaByStatus,
            'findingsBySeverity' => $findingsBySeverity,
            'incidentChartData' => $incidentChartData,
            'typeLabels' => $typeLabels,
            'recentIncidents' => $recentIncidents,
            'overdueCapa' => $overdueCapa,
        ]);
    }

    public function index(Request $request)
    {
        $user = auth()->user();

        if ($user->isEmployee()) {
            return redirect()->route('my-employee')
                ->with('info', 'Sebagai karyawan, silakan akses profil Anda.');
        }

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

        // Overdue form assignments
        $stats['overdue_forms'] = FormAssignment::whereNotNull('due_date')
            ->where('due_date', '<', now())
            ->whereDoesntHave('submissions', fn ($q) => $q->where('status', 'submitted'))
            ->count();

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
        $departments = Department::orderBy('name')->get();

        // Form completion heatmap (last 6 months)
        $heatmapData = $this->buildHeatmapData($departmentId);

        return view('dashboard', compact(
            'stats', 'monthlyCompliance', 'findingsBySeverity',
            'capaByStatus', 'recentAudits', 'overdueCapa',
            'incidentChartData', 'incidentTypes', 'typeLabels',
            'recentIncidents', 'departments', 'departmentId',
            'dateFrom', 'dateTo', 'heatmapData'
        ));
    }

    public function exportPdf(Request $request)
    {
        $stats = $this->buildStatsForExport();
        $pdf = Pdf::loadView('dashboard-export-pdf', compact('stats'))->setPaper('a4');
        return $pdf->download('dashboard-k3-' . now()->format('Ymd') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $stats = $this->buildStatsForExport();
        $filename = 'dashboard-k3-' . now()->format('Ymd') . '.xlsx';

        return Excel::download(new class($stats) implements \Maatwebsite\Excel\Concerns\FromArray, \Maatwebsite\Excel\Concerns\WithHeadings, \Maatwebsite\Excel\Concerns\WithTitle {
            public function __construct(private array $stats) {}

            public function title(): string { return 'Dashboard K3'; }

            public function headings(): array
            {
                return ['Indikator', 'Nilai'];
            }

            public function array(): array
            {
                return [
                    ['Safe Days (tanpa LTI)', $this->stats['safe_days']],
                    ['Karyawan Aktif', $this->stats['total_employees']],
                    ['SOP Aktif', $this->stats['total_sops']],
                    ['Open Incidents', $this->stats['open_incidents']],
                    ['Total Audit', $this->stats['total_audits']],
                    ['Total Temuan', $this->stats['total_findings']],
                    ['Temuan Open', $this->stats['open_findings']],
                    ['Temuan Closed', $this->stats['closed_findings']],
                    ['CAPA Open', $this->stats['open_capa']],
                    ['CAPA Overdue', $this->stats['overdue_capa']],
                    ['Kepatuhan SOP (%)', $this->stats['sop_compliance']],
                    ['Dokumen Akan Kadaluarsa (30 hari)', $this->stats['documents_expiring_soon']],
                    ['Form Overdue', $this->stats['overdue_forms']],
                ];
            }
        }, $filename);
    }

    private function buildStatsForExport(): array
    {
        $lastLti = Incident::where('incident_type', 'lost_time_injury')
            ->where('status', 'closed')->orderBy('incident_date', 'desc')->first();

        $findingStats = AuditFinding::selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status');
        $capaStats = Capa::selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status');
        $thisMonth = Carbon::now();
        $execThisMonth = SopExecution::whereYear('execution_date', $thisMonth->year)->whereMonth('execution_date', $thisMonth->month)->get();
        $totalExec = $execThisMonth->count();

        return [
            'safe_days'               => $lastLti ? $lastLti->incident_date->diffInDays(now()) : 365,
            'total_employees'         => Employee::where('status', 'aktif')->count(),
            'total_sops'              => Sop::where('status', 'aktif')->count(),
            'open_incidents'          => Incident::where('status', '!=', 'closed')->count(),
            'total_audits'            => Audit::count(),
            'total_findings'          => AuditFinding::count(),
            'open_findings'           => $findingStats['open'] ?? 0,
            'closed_findings'         => $findingStats['closed'] ?? 0,
            'open_capa'               => $capaStats['open'] ?? 0,
            'overdue_capa'            => Capa::where('status', '!=', 'closed')->where('target_date', '<', now())->count(),
            'sop_compliance'          => $totalExec > 0 ? round(($execThisMonth->where('status', 'sesuai')->count() / $totalExec) * 100, 1) : 0,
            'documents_expiring_soon' => K3Document::where('workflow_status', 'approved')->whereNotNull('review_due_date')->whereDate('review_due_date', '>=', now())->whereDate('review_due_date', '<=', now()->addDays(30))->count(),
            'overdue_forms'           => FormAssignment::whereNotNull('due_date')->where('due_date', '<', now())->whereDoesntHave('submissions', fn($q) => $q->where('status', 'submitted'))->count(),
        ];
    }

    private function buildHeatmapData(?int $departmentFilter = null): array
    {
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $months[] = Carbon::now()->subMonths($i);
        }

        $deptQuery = Department::orderBy('name');
        if ($departmentFilter) {
            $deptQuery->where('id', $departmentFilter);
        }

        $heatmapData = [
            'months' => array_map(fn ($m) => $m->translatedFormat('M Y'), $months),
            'rows' => [],
        ];

        foreach ($deptQuery->get() as $dept) {
            $row = ['department' => $dept->name, 'cells' => []];

            foreach ($months as $month) {
                $assigned = FormAssignment::where('assigned_to_type', 'department')
                    ->where('assigned_to_id', $dept->id)
                    ->where('created_at', '<=', $month->copy()->endOfMonth())
                    ->count();

                $submitted = FormSubmission::where('department_id', $dept->id)
                    ->where('status', 'submitted')
                    ->whereYear('submitted_at', $month->year)
                    ->whereMonth('submitted_at', $month->month)
                    ->count();

                if ($assigned === 0) {
                    $row['cells'][] = [
                        'color' => 'gray',
                        'rate' => null,
                        'submitted' => $submitted,
                        'assigned' => 0,
                    ];
                } else {
                    $rate = min(100, (int) round(($submitted / $assigned) * 100));
                    $color = $rate >= 100 ? 'green' : ($rate >= 50 ? 'yellow' : 'red');
                    $row['cells'][] = [
                        'color' => $color,
                        'rate' => $rate,
                        'submitted' => $submitted,
                        'assigned' => $assigned,
                    ];
                }
            }

            $heatmapData['rows'][] = $row;
        }

        return $heatmapData;
    }
}