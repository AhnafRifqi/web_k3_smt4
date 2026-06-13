@extends('layouts.app')
@section('title', 'Rekap & Narasi K3')
@section('page-title', 'Rekap & Narasi K3')
@section('page-subtitle', 'Generate laporan otomatis dengan narasi AI')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-5">

    <a href="{{ route('rekap.bulanan') }}" class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-md hover:border-blue-200 dark:hover:border-blue-800 transition-all p-6 group">
        <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center mb-4 group-hover:bg-blue-100 transition-colors">
            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </div>
        <h3 class="font-bold text-gray-900 dark:text-white mb-1">Rekap Bulanan</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400">Laporan dan narasi otomatis per bulan termasuk kepatuhan SOP, audit, temuan, dan CAPA.</p>
        <div class="mt-4 text-xs font-medium text-blue-600 dark:text-blue-400 flex items-center gap-1">
            Buka Rekap Bulanan
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </div>
    </a>

    <a href="{{ route('rekap.triwulan') }}" class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-md hover:border-purple-200 dark:hover:border-purple-800 transition-all p-6 group">
        <div class="w-12 h-12 rounded-xl bg-purple-50 dark:bg-purple-900/30 flex items-center justify-center mb-4 group-hover:bg-purple-100 transition-colors">
            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
        </div>
        <h3 class="font-bold text-gray-900 dark:text-white mb-1">Rekap Triwulan</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400">Laporan per kuartal (Q1–Q4) dengan analisis tren 3 bulan dan narasi kesimpulan otomatis.</p>
        <div class="mt-4 text-xs font-medium text-purple-600 dark:text-purple-400 flex items-center gap-1">
            Buka Rekap Triwulan
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </div>
    </a>

    <a href="{{ route('rekap.tahunan') }}" class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-md hover:border-green-200 dark:hover:border-green-800 transition-all p-6 group">
        <div class="w-12 h-12 rounded-xl bg-green-50 dark:bg-green-900/30 flex items-center justify-center mb-4 group-hover:bg-green-100 transition-colors">
            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        </div>
        <h3 class="font-bold text-gray-900 dark:text-white mb-1">Rekap Tahunan</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400">Laporan komprehensif per tahun untuk kebutuhan evaluasi manajemen dan audit eksternal.</p>
        <div class="mt-4 text-xs font-medium text-green-600 dark:text-green-400 flex items-center gap-1">
            Buka Rekap Tahunan
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </div>
    </a>

</div>
@endsection
