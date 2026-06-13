<?php

namespace App\Services;

use App\Models\Audit;
use App\Models\AuditFinding;
use App\Models\Capa;
use App\Models\SopExecution;
use Carbon\Carbon;

class NarasiService
{
    public function generateBulanan(int $year, int $month): array
    {
        $period = Carbon::create($year, $month, 1);
        $periodLabel = $period->translatedFormat('F Y');

        $executions = SopExecution::whereYear('execution_date', $year)
            ->whereMonth('execution_date', $month)->get();

        $totalExec   = $executions->count();
        $sesuai      = $executions->where('status', 'sesuai')->count();
        $tidakSesuai = $executions->where('status', 'tidak_sesuai')->count();
        $perbaikan   = $executions->where('status', 'perlu_perbaikan')->count();
        $compliance  = $totalExec > 0 ? round(($sesuai / $totalExec) * 100, 1) : 0;

        $audits = Audit::whereYear('audit_date', $year)
            ->whereMonth('audit_date', $month)->get();
        $totalAudit = $audits->count();

        $auditIds = $audits->pluck('id');
        $findings = AuditFinding::whereIn('audit_id', $auditIds)->get();
        $minor    = $findings->where('severity', 'minor')->count();
        $major    = $findings->where('severity', 'major')->count();
        $critical = $findings->where('severity', 'critical')->count();

        $capas     = Capa::whereYear('target_date', $year)->whereMonth('target_date', $month)->get();
        $capaTotal = $capas->count();
        $capaDone  = $capas->where('status', 'closed')->count();
        $capaRate  = $capaTotal > 0 ? round(($capaDone / $capaTotal) * 100, 1) : 0;

        $narasi = $this->buildNarasi($periodLabel, $compliance, $totalExec, $totalAudit, $minor, $major, $critical, $capaRate, $capaDone, $capaTotal);

        return [
            'period'     => $periodLabel,
            'compliance' => $compliance,
            'total_exec' => $totalExec,
            'sesuai'     => $sesuai,
            'tidak_sesuai' => $tidakSesuai,
            'perbaikan'  => $perbaikan,
            'total_audit' => $totalAudit,
            'minor'      => $minor,
            'major'      => $major,
            'critical'   => $critical,
            'capa_total' => $capaTotal,
            'capa_done'  => $capaDone,
            'capa_rate'  => $capaRate,
            'narasi'     => $narasi,
        ];
    }

    public function generateTriwulan(int $year, int $quarter): array
    {
        $monthStart = ($quarter - 1) * 3 + 1;
        $monthEnd   = $monthStart + 2;
        $periodLabel = "Triwulan {$quarter} {$year} (Bulan {$monthStart}–{$monthEnd})";

        $executions = SopExecution::whereYear('execution_date', $year)
            ->whereMonth('execution_date', '>=', $monthStart)
            ->whereMonth('execution_date', '<=', $monthEnd)->get();

        $totalExec   = $executions->count();
        $sesuai      = $executions->where('status', 'sesuai')->count();
        $tidakSesuai = $executions->where('status', 'tidak_sesuai')->count();
        $perbaikan   = $executions->where('status', 'perlu_perbaikan')->count();
        $compliance  = $totalExec > 0 ? round(($sesuai / $totalExec) * 100, 1) : 0;

        $audits = Audit::whereYear('audit_date', $year)
            ->whereMonth('audit_date', '>=', $monthStart)
            ->whereMonth('audit_date', '<=', $monthEnd)->get();
        $totalAudit = $audits->count();

        $auditIds = $audits->pluck('id');
        $findings = AuditFinding::whereIn('audit_id', $auditIds)->get();
        $minor    = $findings->where('severity', 'minor')->count();
        $major    = $findings->where('severity', 'major')->count();
        $critical = $findings->where('severity', 'critical')->count();

        $capas     = Capa::whereYear('target_date', $year)
            ->whereMonth('target_date', '>=', $monthStart)
            ->whereMonth('target_date', '<=', $monthEnd)->get();
        $capaTotal = $capas->count();
        $capaDone  = $capas->where('status', 'closed')->count();
        $capaRate  = $capaTotal > 0 ? round(($capaDone / $capaTotal) * 100, 1) : 0;

        $narasi = $this->buildNarasi($periodLabel, $compliance, $totalExec, $totalAudit, $minor, $major, $critical, $capaRate, $capaDone, $capaTotal);

        return compact('periodLabel', 'compliance', 'totalExec', 'sesuai', 'tidakSesuai', 'perbaikan', 'totalAudit', 'minor', 'major', 'critical', 'capaTotal', 'capaDone', 'capaRate', 'narasi');
    }

    public function generateTahunan(int $year): array
    {
        $periodLabel = "Tahun {$year}";

        $executions  = SopExecution::whereYear('execution_date', $year)->get();
        $totalExec   = $executions->count();
        $sesuai      = $executions->where('status', 'sesuai')->count();
        $tidakSesuai = $executions->where('status', 'tidak_sesuai')->count();
        $perbaikan   = $executions->where('status', 'perlu_perbaikan')->count();
        $compliance  = $totalExec > 0 ? round(($sesuai / $totalExec) * 100, 1) : 0;

        $audits     = Audit::whereYear('audit_date', $year)->get();
        $totalAudit = $audits->count();
        $auditIds   = $audits->pluck('id');

        $findings = AuditFinding::whereIn('audit_id', $auditIds)->get();
        $minor    = $findings->where('severity', 'minor')->count();
        $major    = $findings->where('severity', 'major')->count();
        $critical = $findings->where('severity', 'critical')->count();

        $capas     = Capa::whereYear('target_date', $year)->get();
        $capaTotal = $capas->count();
        $capaDone  = $capas->where('status', 'closed')->count();
        $capaRate  = $capaTotal > 0 ? round(($capaDone / $capaTotal) * 100, 1) : 0;

        $narasi = $this->buildNarasi($periodLabel, $compliance, $totalExec, $totalAudit, $minor, $major, $critical, $capaRate, $capaDone, $capaTotal, true);

        return compact('periodLabel', 'compliance', 'totalExec', 'sesuai', 'tidakSesuai', 'perbaikan', 'totalAudit', 'minor', 'major', 'critical', 'capaTotal', 'capaDone', 'capaRate', 'narasi');
    }

    private function buildNarasi(
        string $periodLabel, float $compliance, int $totalExec,
        int $totalAudit, int $minor, int $major, int $critical,
        float $capaRate, int $capaDone, int $capaTotal, bool $isTahunan = false
    ): string {
        $complianceStatus = match(true) {
            $compliance >= 90 => 'sangat baik',
            $compliance >= 75 => 'baik',
            $compliance >= 60 => 'cukup',
            default           => 'perlu perhatian serius',
        };

        $findingDesc = '';
        if ($minor > 0 || $major > 0 || $critical > 0) {
            $parts = [];
            if ($minor > 0) $parts[] = "{$minor} temuan minor";
            if ($major > 0) $parts[] = "{$major} temuan major";
            if ($critical > 0) $parts[] = "{$critical} temuan critical";
            $findingDesc = 'Ditemukan ' . implode(', ', $parts) . '. ';
        } else {
            $findingDesc = 'Tidak ditemukan temuan audit pada periode ini. ';
        }

        $capaDesc = $capaTotal > 0
            ? "Sebanyak {$capaRate}% tindakan perbaikan (CAPA) telah diselesaikan ({$capaDone} dari {$capaTotal}). "
            : 'Tidak terdapat CAPA yang perlu ditindaklanjuti. ';

        $overallStatus = match(true) {
            $compliance >= 90 && $critical === 0 => 'secara keseluruhan implementasi SMK3 berjalan dengan sangat baik dan konsisten.',
            $compliance >= 75 && $critical === 0 => 'secara umum implementasi SMK3 berjalan baik namun masih perlu peningkatan pada beberapa area.',
            $compliance >= 60                    => 'implementasi SMK3 masih perlu perhatian dan peningkatan yang signifikan.',
            default                              => 'implementasi SMK3 memerlukan evaluasi menyeluruh dan tindakan korektif segera.',
        };

        $sopDesc = $totalExec > 0
            ? "Tercatat {$totalExec} pelaksanaan SOP dengan tingkat kepatuhan sebesar {$compliance}% (kategori: {$complianceStatus}). "
            : 'Belum ada data pelaksanaan SOP pada periode ini. ';

        $auditDesc = $totalAudit > 0
            ? "Dilakukan {$totalAudit} kegiatan audit K3. "
            : ($isTahunan ? 'Tidak ada kegiatan audit yang tercatat pada tahun ini. ' : '');

        return "Pada periode {$periodLabel}, {$sopDesc}{$auditDesc}{$findingDesc}{$capaDesc}Kesimpulan: {$overallStatus}";
    }
}
