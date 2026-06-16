<?php

namespace App\Http\Controllers;

use App\Models\K3Document;
use App\Services\ActivityLogService;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class DocumentApprovalController extends Controller
{
    public function submit(K3Document $k3Document)
    {
        if ($k3Document->workflow_status !== 'draft') {
            return back()->with('error', 'Hanya dokumen dengan status Draft yang dapat disubmit.');
        }

        $k3Document->update([
            'workflow_status' => 'under_review',
            'submitted_by'    => auth()->id(),
            'submitted_at'    => now(),
        ]);

        ActivityLogService::log('document.submitted', 'documents', "Document {$k3Document->document_number} submitted for review", $k3Document);

        // Notify K3 Manager
        NotificationService::sendToRoles(
            ['k3_manager'],
            'document.submitted',
            'Document Submitted for Review: ' . $k3Document->document_number,
            "Document {$k3Document->title} has been submitted for review.",
            route('k3-documents.show', $k3Document)
        );

        return redirect()->route('k3-documents.show', $k3Document)->with('success', 'Dokumen berhasil disubmit untuk review.');
    }

    public function approve(K3Document $k3Document)
    {
        if ($k3Document->workflow_status !== 'under_review') {
            return back()->with('error', 'Hanya dokumen dengan status Under Review yang dapat disetujui.');
        }

        if (!auth()->user()->canApprove()) {
            return back()->with('error', 'Anda tidak memiliki wewenang untuk menyetujui dokumen.');
        }

        $k3Document->update([
            'workflow_status' => 'approved',
            'approved_by'     => auth()->id(),
            'approved_at'     => now(),
            'status'          => 'aktif',
        ]);

        ActivityLogService::log('document.approved', 'documents', "Document {$k3Document->document_number} approved", $k3Document);

        return redirect()->route('k3-documents.show', $k3Document)->with('success', 'Dokumen berhasil disetujui.');
    }

    public function reject(Request $request, K3Document $k3Document)
    {
        if ($k3Document->workflow_status !== 'under_review') {
            return back()->with('error', 'Hanya dokumen dengan status Under Review yang dapat ditolak.');
        }

        $data = $request->validate([
            'rejection_notes' => 'required|string',
        ]);

        $k3Document->update([
            'workflow_status' => 'draft',
            'reviewed_by'     => auth()->id(),
            'reviewed_at'     => now(),
            'description'     => $k3Document->description . "\n\n--- REJECTION NOTES ---\n" . $data['rejection_notes'],
        ]);

        ActivityLogService::log('document.rejected', 'documents', "Document {$k3Document->document_number} rejected", $k3Document, [], ['rejection_notes' => $data['rejection_notes']]);

        return redirect()->route('k3-documents.show', $k3Document)->with('success', 'Dokumen ditolak dan dikembalikan ke Draft.');
    }

    public function createRevision(K3Document $k3Document)
    {
        if ($k3Document->workflow_status !== 'approved') {
            return back()->with('error', 'Revisi hanya dapat dibuat untuk dokumen yang sudah disetujui.');
        }

        // Parse version
        $versionParts = explode('.', $k3Document->version);
        $major = (int) $versionParts[0];
        $minor = isset($versionParts[1]) ? (int) $versionParts[1] + 1 : 1;
        if ($minor > 9) {
            $major++;
            $minor = 0;
        }
        $newVersion = $major . '.' . $minor;

        $revision = K3Document::create([
            'parent_document_id' => $k3Document->id,
            'title'              => $k3Document->title,
            'category'           => $k3Document->category,
            'document_number'    => $k3Document->document_number,
            'revision'           => (intval($k3Document->revision) + 1),
            'version'            => $newVersion,
            'description'        => $k3Document->description,
            'status'             => 'draft',
            'workflow_status'    => 'draft',
            'uploaded_by'        => auth()->id(),
        ]);

        ActivityLogService::log('document.revision_created', 'documents', "New revision {$newVersion} created for document {$k3Document->document_number}", $revision);

        return redirect()->route('k3-documents.show', $revision)->with('success', "Revisi {$newVersion} berhasil dibuat. Silakan upload file terbaru.");
    }

    public function markObsolete(K3Document $k3Document)
    {
        if ($k3Document->workflow_status !== 'approved') {
            return back()->with('error', 'Hanya dokumen yang disetujui yang dapat dijadikan obsolete.');
        }

        $k3Document->update([
            'workflow_status' => 'obsolete',
            'status'          => 'obsolete',
        ]);

        ActivityLogService::log('document.obsoleted', 'documents', "Document {$k3Document->document_number} marked as obsolete", $k3Document);

        return redirect()->route('k3-documents.show', $k3Document)->with('success', 'Dokumen ditandai sebagai obsolete.');
    }
}