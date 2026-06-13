@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard SMK3')
@section('page-subtitle', 'Ringkasan sistem manajemen K3')

@section('content')

{{-- Stats Cards --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">

    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-100 dark:border-gray-700 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Karyawan Aktif</p>
            <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center">
                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['total_employees']) }}</p>
        <p class="text-xs text-gray-400 mt-1">Orang terdaftar aktif</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-100 dark:border-gray-700 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">SOP Aktif</p>
            <div class="w-8 h-8 rounded-lg bg-purple-50 dark:bg-purple-900/30 flex items-center justify-center">
                <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_sops'] }}</p>
        <p class="text-xs text-gray-400 mt-1">Prosedur K3 berlaku</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-100 dark:border-gray-700 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Kepatuhan SOP</p>
            <div class="w-8 h-8 rounded-lg {{ $stats['sop_compliance'] >= 80 ? 'bg-green-50 dark:bg-green-900/30' : 'bg-yellow-50 dark:bg-yellow-900/30' }} flex items-center justify-center">
                <svg class="w-4 h-4 {{ $stats['sop_compliance'] >= 80 ? 'text-green-600' : 'text-yellow-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            </div>
        </div>
        <p class="text-2xl font-bold {{ $stats['sop_compliance'] >= 80 ? 'text-green-600' : 'text-yellow-600' }}">{{ $stats['sop_compliance'] }}%</p>
        <p class="text-xs text-gray-400 mt-1">Bulan ini</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-100 dark:border-gray-700 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">CAPA Open</p>
            <div class="w-8 h-8 rounded-lg bg-red-50 dark:bg-red-900/30 flex items-center justify-center">
                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['open_capa'] }}</p>
        <p class="text-xs text-gray-400 mt-1">
            @if($stats['overdue_capa'] > 0)
            <span class="text-red-500 font-medium">{{ $stats['overdue_capa'] }} overdue!</span>
            @else
            Semua on-track
            @endif
        </p>
    </div>

</div>

{{-- Charts Row --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">

    {{-- SOP Compliance Chart (6 bulan) --}}
    <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="font-semibold text-gray-900 dark:text-white text-sm">Tren Kepatuhan SOP</h3>
                <p class="text-xs text-gray-500 mt-0.5">6 bulan terakhir</p>
            </div>
        </div>
        <canvas id="complianceChart" height="120"></canvas>
    </div>

    {{-- CAPA Status Donut --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm">
        <div class="mb-4">
            <h3 class="font-semibold text-gray-900 dark:text-white text-sm">Status CAPA</h3>
            <p class="text-xs text-gray-500 mt-0.5">Distribusi tindakan perbaikan</p>
        </div>
        <canvas id="capaChart" height="160"></canvas>
        <div class="mt-3 space-y-1.5">
            <div class="flex items-center justify-between text-xs">
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-red-400 inline-block"></span> Open</span>
                <span class="font-medium text-gray-700 dark:text-gray-300">{{ $capaByStatus['open'] }}</span>
            </div>
            <div class="flex items-center justify-between text-xs">
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-yellow-400 inline-block"></span> In Progress</span>
                <span class="font-medium text-gray-700 dark:text-gray-300">{{ $capaByStatus['in_progress'] }}</span>
            </div>
            <div class="flex items-center justify-between text-xs">
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-green-400 inline-block"></span> Closed</span>
                <span class="font-medium text-gray-700 dark:text-gray-300">{{ $capaByStatus['closed'] }}</span>
            </div>
        </div>
    </div>

</div>

{{-- Bottom Row --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

    {{-- Temuan by Severity --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-900 dark:text-white text-sm">Temuan Audit per Severity</h3>
            <a href="{{ route('audits.index') }}" class="text-xs text-blue-600 hover:underline dark:text-blue-400">Lihat semua →</a>
        </div>
        <canvas id="findingsChart" height="120"></canvas>
    </div>

    {{-- Recent Overdue CAPA --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-900 dark:text-white text-sm">CAPA Overdue</h3>
            <a href="{{ route('capa.index') }}" class="text-xs text-blue-600 hover:underline dark:text-blue-400">Lihat semua →</a>
        </div>
        @forelse($overdueCapa as $capa)
        <div class="flex items-start gap-3 py-2.5 {{ !$loop->last ? 'border-b border-gray-50 dark:border-gray-700' : '' }}">
            <span class="mt-0.5 w-2 h-2 rounded-full bg-red-500 shrink-0"></span>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-800 dark:text-gray-200 truncate">{{ $capa->capa_number }}</p>
                <p class="text-xs text-gray-500 truncate mt-0.5">{{ Str::limit($capa->description, 60) }}</p>
                <div class="flex items-center gap-3 mt-1">
                    <span class="text-xs text-gray-400">PIC: {{ $capa->pic?->name ?? '-' }}</span>
                    <span class="text-xs text-red-500 font-medium">Due: {{ $capa->target_date->format('d M Y') }}</span>
                </div>
            </div>
        </div>
        @empty
        <div class="flex flex-col items-center justify-center py-8 text-center">
            <svg class="w-10 h-10 text-green-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-sm text-gray-500">Tidak ada CAPA overdue</p>
        </div>
        @endforelse
    </div>

</div>

@endsection

@push('scripts')
<script>
const isDark = document.documentElement.classList.contains('dark');
const gridColor = isDark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)';
const textColor = isDark ? '#9ca3af' : '#6b7280';

// SOP Compliance Line Chart
const complianceData = @json($monthlyCompliance);
new Chart(document.getElementById('complianceChart'), {
    type: 'line',
    data: {
        labels: complianceData.map(d => d.month),
        datasets: [{
            label: 'Kepatuhan SOP (%)',
            data: complianceData.map(d => d.compliance),
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59,130,246,0.08)',
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#3b82f6',
            pointRadius: 4,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { min: 0, max: 100, grid: { color: gridColor }, ticks: { color: textColor, callback: v => v + '%' } },
            x: { grid: { display: false }, ticks: { color: textColor } }
        }
    }
});

// CAPA Donut
const capaData = @json($capaByStatus);
new Chart(document.getElementById('capaChart'), {
    type: 'doughnut',
    data: {
        labels: ['Open', 'In Progress', 'Closed'],
        datasets: [{
            data: [capaData.open, capaData.in_progress, capaData.closed],
            backgroundColor: ['#f87171', '#fbbf24', '#34d399'],
            borderWidth: 0,
        }]
    },
    options: {
        responsive: true,
        cutout: '70%',
        plugins: { legend: { display: false } }
    }
});

// Findings Bar Chart
const findings = @json($findingsBySeverity);
new Chart(document.getElementById('findingsChart'), {
    type: 'bar',
    data: {
        labels: ['Minor', 'Major', 'Critical'],
        datasets: [{
            data: [findings.minor, findings.major, findings.critical],
            backgroundColor: ['#fbbf24', '#f97316', '#ef4444'],
            borderRadius: 6,
            barPercentage: 0.5,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { grid: { color: gridColor }, ticks: { color: textColor } },
            x: { grid: { display: false }, ticks: { color: textColor } }
        }
    }
});
</script>
@endpush
