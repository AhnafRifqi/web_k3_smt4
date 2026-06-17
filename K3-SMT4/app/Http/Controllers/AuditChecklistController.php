<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use App\Models\AuditChecklistItem;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class AuditChecklistController extends Controller
{
    public function store(Request $request, Audit $audit)
    {
        $data = $request->validate([
            'item_number' => 'required|max:20',
            'description' => 'required',
            'standard_ref' => 'nullable|max:100',
            'evidence_type' => 'required|in:document,form_submission,manual',
            'evidence_id' => 'nullable|integer',
            'notes' => 'nullable',
        ]);

        $data['audit_id'] = $audit->id;
        $data['conformance_status'] = 'not_assessed';

        if ($data['evidence_type'] === 'manual') {
            $data['evidence_id'] = null;
        }

        $item = AuditChecklistItem::create($data);

        ActivityLogService::log(
            'audit_checklist.created',
            'audits',
            "Checklist item {$item->item_number} ditambahkan ke audit {$audit->audit_number}",
            $item
        );

        return redirect()->route('audits.show', $audit)->with('success', 'Item checklist berhasil ditambahkan.');
    }

    public function update(Request $request, AuditChecklistItem $auditChecklistItem)
    {
        $data = $request->validate([
            'conformance_status' => 'required|in:conforming,minor_nc,major_nc,observation,not_assessed',
            'notes' => 'nullable',
        ]);

        $oldValues = $auditChecklistItem->only(['conformance_status', 'notes']);
        $auditChecklistItem->update($data);

        ActivityLogService::log(
            'audit_checklist.updated',
            'audits',
            "Status checklist item {$auditChecklistItem->item_number} diperbarui",
            $auditChecklistItem,
            $oldValues,
            $data
        );

        return redirect()->route('audits.show', $auditChecklistItem->audit_id)
            ->with('success', 'Status checklist berhasil diperbarui.');
    }

    public function destroy(AuditChecklistItem $auditChecklistItem)
    {
        $auditId = $auditChecklistItem->audit_id;
        $itemNumber = $auditChecklistItem->item_number;
        $auditChecklistItem->delete();

        ActivityLogService::log(
            'audit_checklist.deleted',
            'audits',
            "Checklist item {$itemNumber} dihapus"
        );

        return redirect()->route('audits.show', $auditId)->with('success', 'Item checklist berhasil dihapus.');
    }
}
