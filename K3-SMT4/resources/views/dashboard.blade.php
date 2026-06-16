@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard SMK3')
@section('page-subtitle', 'Ringkasan sistem manajemen K3')

@section('content')

@if(auth()->check() && auth()->user()->isKaryawan())
    @if(!auth()->user()->is_validated)
    <div class="mb-6 p-5 bg-gradient-to-r from-amber-500/10 to-orange-500/10 dark:from-amber-500/5 dark:to-orange-500/5 border border-amber-500/20 rounded-2xl flex flex-col md:flex-row md:items-center justify-between gap-4 shadow-sm animate-pulse">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-500/10 flex items-center justify-center text-amber-600 dark:text-amber-400 shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div>
                <h3 class="font-bold text-amber-800 dark:text-amber-300 text-sm md:text-base">Akun Belum Divalidasi</h3>
                <p class="text-xs md:text-sm text-amber-700/80 dark:text-amber-400/80 mt-1">Akun karyawan Anda saat ini sedang menunggu persetujuan dari Admin. Fitur melengkapi data diri dan beberapa fitur lainnya akan aktif setelah akun Anda divalidasi.</p>
            </div>
        </div>
    </div>
    @elseif(!auth()->user()->employee)
    <div class="mb-6 p-5 bg-gradient-to-r from-blue-500/10 to-indigo-500/10 dark:from-blue-500/5 dark:to-indigo-500/5 border border-blue-500/20 rounded-2xl flex flex-col md:flex-row md:items-center justify-between gap-4 shadow-sm">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-600 dark:text-blue-400 shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <h3 class="font-bold text-blue-800 dark:text-blue-300 text-sm md:text-base">Lengkapi Data Karyawan Anda</h3>
                <p class="text-xs md:text-sm text-blue-700/80 dark:text-blue-400/80 mt-1">Akun Anda berhasil divalidasi oleh Admin! Silakan lengkapi profil data karyawan Anda untuk mengaktifkan seluruh fitur sistem.</p>
            </div>
        </div>
        <div class="shrink-0">
            <a href="{{ route('my-employee.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-xs md:text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 shadow-sm transition-all hover:scale-[1.02] active:scale-[0.98]">
                Lengkapi Data Diri
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>
    @endif
@endif

{{-- Stats Cards --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-5 mb-6">

    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-100 dark:border-slate-700/50 shadow-sm transition-all hover:shadow-md">
        <div class="flex items-center justify-between mb-4">
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Karyawan Aktif</p>
            <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-500/10 flex items-center justify-center text-blue-600 dark:text-blue-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
        </div>
        <p class="text-3xl font-extrabold text-slate-900 dark:text-white">{{ number_format($stats['total_employees'] ?? 0) }}</p>
        <p class="text-xs text-slate-400 mt-2">Orang terdaftar aktif</p>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-100 dark:border-slate-700/50 shadow-sm transition-all hover:shadow-md">
        <div class="flex items-center justify-between mb-4">
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">SOP Aktif</p>
            <div class="w-10 h-10 rounded-xl bg-purple-50 dark:bg-purple-500/10 flex items-center justify-center text-purple-600 dark:text-purple-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
        </div>
        <p class="text-3xl font-extrabold text-slate-900 dark:text-white">{{ number_format($stats['total_sops'] ?? 0) }}</p>
        <p class="text-xs text-slate-400 mt-2">Prosedur K3 berlaku</p>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-100 dark:border-slate-700/50 shadow-sm transition-all hover:shadow-md">
        <div class="flex items-center justify-between mb-4">
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Kepatuhan SOP</p>
            @php $compliance = $stats['sop_compliance'] ?? 0; @endphp
            <div class="w-10 h-10 rounded-xl {{ $compliance >= 80 ? 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : 'bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400' }} flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            </div>
        </div>
        <p class="text-3xl font-extrabold {{ $compliance >= 80 ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400' }}">{{ $compliance }}%</p>
        <p class="text-xs text-slate-400 mt-2">Kepatuhan bulan ini</p>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-100 dark:border-slate-700/50 shadow-sm transition-all hover:shadow-md">
        <div class="flex items-center justify-between mb-4">
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">CAPA Open</p>
            <div class="w-10 h-10 rounded-xl bg-rose-50 dark:bg-rose-500/10 flex items-center justify-center text-rose-600 dark:text-rose-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
        </div>
        <p class="text-3xl font-extrabold text-slate-900 dark:text-white">{{ number_format($stats['open_capa'] ?? 0) }}</p>
        <p class="text-xs mt-2">
            @if(($stats['overdue_capa'] ?? 0) > 0)
            <span class="text-rose-500 dark:text-rose-400 font-semibold">{{ $stats['overdue_capa'] }} overdue!</span>
            @else
            <span class="text-slate-400">Semua on-track</span>
            @endif
        </p>
    </div>

</div>

{{-- Charts Row --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">

    {{-- SOP Compliance Chart (6 bulan) --}}
    <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-100 dark:border-slate-700/50 shadow-sm flex flex-col">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="font-bold text-slate-900 dark:text-white text-base">Tren Kepatuhan SOP</h3>
                <p class="text-sm text-slate-500 mt-1">Akumulasi 6 bulan terakhir</p>
            </div>
        </div>
        
        <div class="relative w-full h-64 flex-1">
            <canvas id="complianceChart"></canvas>
        </div>
    </div>

    {{-- CAPA Status Donut --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-100 dark:border-slate-700/50 shadow-sm flex flex-col">
        <div class="mb-4">
            <h3 class="font-bold text-slate-900 dark:text-white text-base">Status CAPA</h3>
            <p class="text-sm text-slate-500 mt-1">Distribusi tindakan perbaikan</p>
        </div>
        
        <div class="relative w-full h-48">
            <canvas id="capaChart"></canvas>
        </div>
        
        <div class="mt-6 space-y-3">
            <div class="flex items-center justify-between text-sm">
                <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-rose-400 inline-block"></span> Open</span>
                <span class="font-bold text-slate-700 dark:text-slate-300">{{ $capaByStatus['open'] ?? 0 }}</span>
            </div>
            <div class="flex items-center justify-between text-sm">
                <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-amber-400 inline-block"></span> In Progress</span>
                <span class="font-bold text-slate-700 dark:text-slate-300">{{ $capaByStatus['in_progress'] ?? 0 }}</span>
            </div>
            <div class="flex items-center justify-between text-sm">
                <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-emerald-400 inline-block"></span> Closed</span>
                <span class="font-bold text-slate-700 dark:text-slate-300">{{ $capaByStatus['closed'] ?? 0 }}</span>
            </div>
        </div>
    </div>

</div>

{{-- Bottom Row --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

    {{-- Temuan by Severity --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-100 dark:border-slate-700/50 shadow-sm flex flex-col">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-bold text-slate-900 dark:text-white text-base">Temuan Audit per Severity</h3>
            <a href="#" class="text-sm font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 transition-colors">Lihat semua &rarr;</a>
        </div>
        
        <div class="relative w-full h-64 flex-1">
            <canvas id="findingsChart"></canvas>
        </div>
    </div>

    {{-- Recent Overdue CAPA --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-100 dark:border-slate-700/50 shadow-sm flex flex-col">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-bold text-slate-900 dark:text-white text-base">CAPA Overdue</h3>
            <a href="#" class="text-sm font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 transition-colors">Lihat semua &rarr;</a>
        </div>
        
        <div class="flex-1 overflow-y-auto pr-2 space-y-4">
            @forelse($overdueCapa ?? [] as $capa)
            <div class="flex items-start gap-4 p-4 rounded-xl bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-800">
                <div class="mt-1 w-2.5 h-2.5 rounded-full bg-rose-500 shadow-[0_0_8px_rgba(244,63,94,0.5)] shrink-0"></div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-slate-800 dark:text-slate-200 truncate">{{ $capa->capa_number }}</p>
                    <p class="text-sm text-slate-500 truncate mt-1">{{ Str::limit($capa->description, 60) }}</p>
                    <div class="flex items-center gap-4 mt-2">
                        <span class="text-xs font-medium text-slate-400 bg-slate-200/50 dark:bg-slate-800 px-2 py-1 rounded-md">PIC: {{ $capa->pic?->name ?? '-' }}</span>
                        <span class="text-xs font-bold text-rose-500 bg-rose-50 dark:bg-rose-500/10 px-2 py-1 rounded-md">Due: {{ $capa->target_date->format('d M Y') }}</span>
                    </div>
                </div>
            </div>
            @empty
            <div class="flex flex-col items-center justify-center h-full py-8 text-center">
                <div class="w-16 h-16 mb-4 rounded-full bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center text-emerald-500">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <p class="text-sm font-medium text-slate-900 dark:text-white">Semua CAPA tertangani</p>
                <p class="text-xs text-slate-500 mt-1">Tidak ada tindakan perbaikan yang melewati batas waktu.</p>
            </div>
            @endforelse
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const isDark = document.documentElement.classList.contains('dark') || document.body.classList.contains('dark');
    const gridColor = isDark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)';
    const textColor = isDark ? '#94a3b8' : '#64748b';

    // Data untuk grafik
    const complianceData = {{ Illuminate\Support\Js::from($monthlyCompliance ?? [['month' => 'Jan', 'compliance' => 0]]) }};
    const capaData = {{ Illuminate\Support\Js::from($capaByStatus ?? ['open' => 0, 'in_progress' => 0, 'closed' => 0]) }};
    const findings = {{ Illuminate\Support\Js::from($findingsBySeverity ?? ['minor' => 0, 'major' => 0, 'critical' => 0]) }};

    // 1. SOP Compliance Line Chart
    const ctxCompliance = document.getElementById('complianceChart');
    if(ctxCompliance) {
        new Chart(ctxCompliance, {
            type: 'line',
            data: {
                labels: complianceData.map(d => d.month),
                datasets: [{
                    label: 'Kepatuhan SOP (%)',
                    data: complianceData.map(d => d.compliance),
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59,130,246,0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#3b82f6',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: { mode: 'index', intersect: false } },
                scales: {
                    y: { min: 0, max: 100, border: { display: false }, grid: { color: gridColor }, ticks: { color: textColor, padding: 10, callback: v => v + '%' } },
                    x: { border: { display: false }, grid: { display: false }, ticks: { color: textColor, padding: 10 } }
                }
            }
        });
    }

    // 2. CAPA Donut Chart
    const ctxCapa = document.getElementById('capaChart');
    if(ctxCapa) {
        new Chart(ctxCapa, {
            type: 'doughnut',
            data: {
                labels: ['Open', 'In Progress', 'Closed'],
                datasets: [{
                    data: [capaData.open, capaData.in_progress, capaData.closed],
                    backgroundColor: ['#fb7185', '#fbbf24', '#34d399'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: { legend: { display: false } }
            }
        });
    }

    // 3. Findings Bar Chart
    const ctxFindings = document.getElementById('findingsChart');
    if(ctxFindings) {
        new Chart(ctxFindings, {
            type: 'bar',
            data: {
                labels: ['Minor', 'Major', 'Critical'],
                datasets: [{
                    data: [findings.minor, findings.major, findings.critical],
                    backgroundColor: ['#fbbf24', '#f97316', '#e11d48'],
                    borderRadius: 6,
                    barPercentage: 0.6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { border: { display: false }, grid: { color: gridColor }, ticks: { color: textColor, stepSize: 1, padding: 10 } },
                    x: { border: { display: false }, grid: { display: false }, ticks: { color: textColor, padding: 10 } }
                }
            }
        });
    }
});
</script>
@endpush