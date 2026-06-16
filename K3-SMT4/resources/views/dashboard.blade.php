@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard SMK3')
@section('page-subtitle', 'Ringkasan sistem manajemen K3')

@section('content')

@if(auth()->check() && in_array(auth()->user()->role, ['employee', 'dept_head']))
    @if(!auth()->user()->is_validated)
    <div class="mb-6 p-5 bg-gradient-to-r from-amber-500/10 to-orange-500/10 border border-amber-500/20 rounded-2xl flex flex-col md:flex-row md:items-center justify-between gap-4 shadow-sm">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-500/10 flex items-center justify-center text-amber-600 shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div>
                <h3 class="font-bold text-amber-800 dark:text-amber-300 text-sm md:text-base">Akun Belum Divalidasi</h3>
                <p class="text-xs md:text-sm text-amber-700/80 mt-1">Akun Anda saat ini sedang menunggu persetujuan dari Admin.</p>
            </div>
        </div>
    </div>
    @elseif(!auth()->user()->employee)
    <div class="mb-6 p-5 bg-gradient-to-r from-blue-500/10 to-indigo-500/10 border border-blue-500/20 rounded-2xl flex flex-col md:flex-row md:items-center justify-between gap-4 shadow-sm">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-600 shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <h3 class="font-bold text-blue-800 dark:text-blue-300 text-sm md:text-base">Lengkapi Data Karyawan Anda</h3>
                <p class="text-xs md:text-sm text-blue-700/80 mt-1">Silakan lengkapi profil data karyawan Anda.</p>
            </div>
        </div>
        <div class="shrink-0">
            <a href="{{ route('my-employee.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-xs md:text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 shadow-sm transition-all">Lengkapi Data Diri &rarr;</a>
        </div>
    </div>
    @endif
@endif

{{-- Dashboard Filters (GAP 8) --}}
@if(auth()->check() && in_array(auth()->user()->role, ['super_admin', 'k3_manager', 'k3_officer']))
<div x-data="{ showFilters: false }" class="mb-6">
    <button @click="showFilters = !showFilters" class="text-sm text-blue-600 hover:underline flex items-center gap-1 mb-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
        <span x-text="showFilters ? 'Hide Filters' : 'Show Filters'"></span>
    </button>
    <div x-show="showFilters" x-transition class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm p-4">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <select name="department_id" class="form-input">
                <option value="">All Departments</option>
                @foreach($departments as $dept)
                <option value="{{ $dept->id }}" @selected($departmentId == $dept->id)>{{ $dept->name }}</option>
                @endforeach
            </select>
            <input type="date" name="date_from" value="{{ $dateFrom ?? '' }}" class="form-input" placeholder="Date From">
            <input type="date" name="date_to" value="{{ $dateTo ?? '' }}" class="form-input" placeholder="Date To">
            <div class="flex gap-2">
                <button type="submit" class="btn-primary bg-blue-600 text-white px-4 py-2 rounded-lg text-sm flex-1">Apply</button>
                <a href="{{ route('dashboard') }}" class="btn-secondary bg-slate-100 text-slate-600 px-4 py-2 rounded-lg text-sm">Reset</a>
            </div>
        </form>
    </div>
</div>
@endif

{{-- Stats Cards --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-5 mb-6">

    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-100 dark:border-slate-700/50 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Safe Days</p>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
        <p class="text-3xl font-extrabold text-emerald-600 dark:text-emerald-400">{{ number_format($stats['safe_days'] ?? 0) }}</p>
        <p class="text-xs text-slate-400 mt-2">Days without LTI</p>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-100 dark:border-slate-700/50 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Karyawan Aktif</p>
            <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-500/10 flex items-center justify-center text-blue-600 dark:text-blue-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
        </div>
        <p class="text-3xl font-extrabold text-slate-900 dark:text-white">{{ number_format($stats['total_employees'] ?? 0) }}</p>
        <p class="text-xs text-slate-400 mt-2">Orang terdaftar aktif</p>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-100 dark:border-slate-700/50 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">SOP Aktif</p>
            <div class="w-10 h-10 rounded-xl bg-purple-50 dark:bg-purple-500/10 flex items-center justify-center text-purple-600 dark:text-purple-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
        </div>
        <p class="text-3xl font-extrabold text-slate-900 dark:text-white">{{ number_format($stats['total_sops'] ?? 0) }}</p>
        <p class="text-xs text-slate-400 mt-2">Prosedur K3 berlaku</p>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-100 dark:border-slate-700/50 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Open Incidents</p>
            <div class="w-10 h-10 rounded-xl bg-rose-50 dark:bg-rose-500/10 flex items-center justify-center text-rose-600 dark:text-rose-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
        <p class="text-3xl font-extrabold text-slate-900 dark:text-white">{{ number_format($stats['open_incidents'] ?? 0) }}</p>
        <p class="text-xs mt-2">
            @if(($stats['documents_expiring_soon'] ?? 0) > 0)
            <span class="text-amber-500 font-semibold">{{ $stats['documents_expiring_soon'] }} docs expiring</span>
            @else
            <span class="text-slate-400">Waiting reports</span>
            @endif
        </p>
    </div>

</div>

{{-- Second Row Stats --}}
<div class="grid grid-cols-2 md:grid-cols-3 gap-5 mb-6">
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-100 dark:border-slate-700/50 shadow-sm">
        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Kepatuhan SOP</p>
        @php $compliance = $stats['sop_compliance'] ?? 0; @endphp
        <p class="text-3xl font-extrabold {{ $compliance >= 80 ? 'text-emerald-600' : 'text-amber-600' }}">{{ $compliance }}%</p>
        <p class="text-xs text-slate-400 mt-2">Bulan ini</p>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-100 dark:border-slate-700/50 shadow-sm">
        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">CAPA Open / Overdue</p>
        <p class="text-3xl font-extrabold text-slate-900 dark:text-white">{{ number_format($stats['open_capa'] ?? 0) }}</p>
        <p class="text-xs mt-2">
            @if(($stats['overdue_capa'] ?? 0) > 0)
            <span class="text-rose-500 font-semibold">{{ $stats['overdue_capa'] }} overdue!</span>
            @else
            <span class="text-slate-400">All on-track</span>
            @endif
        </p>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-100 dark:border-slate-700/50 shadow-sm">
        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Docs Expiring</p>
        <p class="text-3xl font-extrabold text-slate-900 dark:text-white">{{ number_format($stats['documents_expiring_soon'] ?? 0) }}</p>
        <p class="text-xs mt-2">
            @if(($stats['documents_expiring_soon'] ?? 0) > 0)
            <a href="{{ route('k3-documents.index') }}" class="text-amber-500 hover:underline font-semibold">Review now</a>
            @else
            <span class="text-slate-400">No docs expiring</span>
            @endif
        </p>
    </div>
</div>

{{-- Charts Row --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">
    <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-100 dark:border-slate-700/50 shadow-sm flex flex-col">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="font-bold text-slate-900 dark:text-white text-base">Tren Kepatuhan SOP</h3>
                <p class="text-sm text-slate-500 mt-1">6 bulan terakhir</p>
            </div>
        </div>
        <div class="relative w-full h-64 flex-1">
            <canvas id="complianceChart"></canvas>
        </div>
    </div>

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

{{-- Incident Statistics Chart --}}
<div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-100 dark:border-slate-700/50 shadow-sm mb-6">
    <div class="mb-6">
        <h3 class="font-bold text-slate-900 dark:text-white text-base">Incident Statistics by Type</h3>
        <p class="text-sm text-slate-500 mt-1">Monthly breakdown (last 6 months)</p>
    </div>
    <div class="relative w-full h-72">
        <canvas id="incidentChart"></canvas>
    </div>
</div>

{{-- Bottom Row --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-6">
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-100 dark:border-slate-700/50 shadow-sm flex flex-col">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-bold text-slate-900 dark:text-white text-base">Temuan Audit per Severity</h3>
            <a href="{{ route('audits.index') }}" class="text-sm font-medium text-blue-600 hover:underline">Lihat semua &rarr;</a>
        </div>
        <div class="relative w-full h-64 flex-1">
            <canvas id="findingsChart"></canvas>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-100 dark:border-slate-700/50 shadow-sm flex flex-col">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-bold text-slate-900 dark:text-white text-base">Recent Incidents</h3>
            <a href="{{ route('incidents.index') }}" class="text-sm font-medium text-blue-600 hover:underline">Lihat semua &rarr;</a>
        </div>
        <div class="flex-1 overflow-y-auto pr-2 space-y-4">
            @forelse($recentIncidents ?? [] as $inc)
            <div class="flex items-start gap-4 p-4 rounded-xl bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-800">
                <div class="mt-1 w-2.5 h-2.5 rounded-full bg-{{ $inc->severity_color }}-500 shrink-0"></div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-slate-800 dark:text-slate-200 truncate">{{ $inc->incident_number }}</p>
                    <p class="text-sm text-slate-500 truncate mt-1">{{ Str::limit($inc->title, 50) }}</p>
                    <div class="flex items-center gap-3 mt-2">
                        <span class="text-xs font-medium text-slate-400 bg-slate-200/50 dark:bg-slate-800 px-2 py-1 rounded-md">{{ $inc->incident_type_label }}</span>
                        <span class="text-xs font-medium badge-{{ $inc->status_color }}">{{ $inc->status_label }}</span>
                    </div>
                </div>
                <a href="{{ route('incidents.show', $inc) }}" class="text-blue-600 hover:underline text-xs shrink-0">View</a>
            </div>
            @empty
            <div class="flex flex-col items-center justify-center h-full py-8 text-center">
                <div class="w-16 h-16 mb-4 rounded-full bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center text-emerald-500">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <p class="text-sm font-medium text-slate-900 dark:text-white">No open incidents</p>
                <p class="text-xs text-slate-500 mt-1">All incidents have been resolved.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

{{-- CAPA Overdue Widget --}}
<div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-100 dark:border-slate-700/50 shadow-sm">
    <div class="flex items-center justify-between mb-6">
        <h3 class="font-bold text-slate-900 dark:text-white text-base">CAPA Overdue</h3>
        <a href="{{ route('capa.index') }}" class="text-sm font-medium text-blue-600 hover:underline">Lihat semua &rarr;</a>
    </div>
    <div class="space-y-4">
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
        <div class="flex flex-col items-center justify-center py-8 text-center">
            <div class="w-16 h-16 mb-4 rounded-full bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center text-emerald-500">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="text-sm font-medium text-slate-900 dark:text-white">Semua CAPA tertangani</p>
            <p class="text-xs text-slate-500 mt-1">Tidak ada tindakan perbaikan yang melewati batas waktu.</p>
        </div>
        @endforelse
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const isDark = document.documentElement.classList.contains('dark');
    const gridColor = isDark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)';
    const textColor = isDark ? '#94a3b8' : '#64748b';

    const complianceData = {{ Illuminate\Support\Js::from($monthlyCompliance ?? [['month' => 'Jan', 'compliance' => 0]]) }};
    const capaData = {{ Illuminate\Support\Js::from($capaByStatus ?? ['open' => 0, 'in_progress' => 0, 'closed' => 0]) }};
    const findings = {{ Illuminate\Support\Js::from($findingsBySeverity ?? ['minor' => 0, 'major' => 0, 'critical' => 0]) }};
    const incidentData = {{ Illuminate\Support\Js::from($incidentChartData ?? []) }};
    const typeLabels = {{ Illuminate\Support\Js::from($typeLabels ?? []) }};

    const colors = {
        near_miss: '#94a3b8',
        first_aid: '#22c55e',
        medical_treatment: '#3b82f6',
        lost_time_injury: '#ef4444',
        fatality: '#000000',
        property_damage: '#f97316',
        environmental: '#84cc16',
    };

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

    // 4. Incident Statistics Bar Chart
    const ctxIncident = document.getElementById('incidentChart');
    if(ctxIncident && incidentData.length > 0) {
        const incidentTypes = Object.keys(colors);
        const datasets = incidentTypes.map(type => ({
            label: typeLabels[type] || type,
            data: incidentData.map(d => d[type] || 0),
            backgroundColor: colors[type] || '#94a3b8',
            borderRadius: 3,
            barPercentage: 0.9,
        }));

        new Chart(ctxIncident, {
            type: 'bar',
            data: {
                labels: incidentData.map(d => d.month),
                datasets: datasets,
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 12, padding: 10, color: textColor, font: { size: 10 } } },
                    tooltip: { mode: 'index', intersect: false }
                },
                scales: {
                    x: { stacked: true, border: { display: false }, grid: { display: false }, ticks: { color: textColor, font: { size: 10 } } },
                    y: { stacked: true, border: { display: false }, grid: { color: gridColor }, ticks: { color: textColor, stepSize: 1, padding: 10 } }
                }
            }
        });
    }
});
</script>
@endpush