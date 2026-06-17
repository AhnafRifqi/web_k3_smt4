<?php

use App\Models\K3Document;
use App\Services\NotificationService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    $expiringDocs = K3Document::where('workflow_status', 'approved')
        ->whereNotNull('review_due_date')
        ->whereDate('review_due_date', '>=', now())
        ->whereDate('review_due_date', '<=', now()->addDays(30))
        ->get();

    if ($expiringDocs->isEmpty()) {
        return;
    }

    foreach ($expiringDocs as $doc) {
        $daysLeft = (int) now()->diffInDays($doc->review_due_date);
        $message = "Dokumen \"{$doc->title}\" ({$doc->document_number}) akan kadaluarsa dalam {$daysLeft} hari (tanggal review: {$doc->review_due_date->format('d M Y')}).";

        NotificationService::sendToRoles(
            ['k3_manager', 'k3_officer'],
            'document.expiring',
            'Dokumen Akan Kadaluarsa: ' . $doc->title,
            $message,
            route('k3-documents.show', $doc)
        );
    }
})->daily()->name('document-expiry-alert')->withoutOverlapping();
