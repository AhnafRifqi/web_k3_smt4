<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use App\Models\AuditFinding;
use App\Models\Capa;
use App\Models\Employee;
use App\Models\Sop;
use App\Models\SopExecution;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Stats
        $stats = [
            'total_employees'  => Employee::where('status', 'aktif')->count(),
            'total_sops'       => Sop::where('status', 'aktif')->count(),
            'total_audits'     => Audit::count(),
            'total_findings'   => AuditFinding::count(),
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

        // Recent Audits
        $recentAudits = Audit::with('findings')->latest()->take(5)->get();

        // Recent CAPA overdue
        $overdueCapa = Capa::with(['pic', 'audit'])
            ->where('status', '!=', 'closed')
            ->where('target_date', '<', now())
            ->latest()->take(5)->get();

        return view('dashboard', compact(
            'stats', 'monthlyCompliance', 'findingsBySeverity',
            'capaByStatus', 'recentAudits', 'overdueCapa'
        ));
    }
}
