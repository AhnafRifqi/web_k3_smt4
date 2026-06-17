<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use App\Models\AuditFinding;
use Illuminate\Http\Request;

class AuditFindingController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditFinding::with('audit');
        if ($request->severity) $query->where('severity', $request->severity);
        if ($request->status) $query->where('status', $request->status);
        if ($request->audit_id) $query->where('audit_id', $request->audit_id);
        $findings = $query->latest()->paginate(15)->withQueryString();
        $audits   = Audit::latest()->get();
        return view('audit-findings.index', compact('findings', 'audits'));
    }

    public function create()
    {
        $audits = Audit::where('status', '!=', 'cancelled')->latest()->get();
        $selectedAuditId = request('audit_id');
        return view('audit-findings.create', compact('audits', 'selectedAuditId'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'audit_id'       => 'required|exists:audits,id',
            'finding_number' => 'required|max:20',
            'description'    => 'required',
            'severity'       => 'required|in:minor,major,critical',
            'finding_type'   => 'required|in:non_conformance,conformance,observation',
            'area'           => 'nullable|max:200',
            'standard_ref'   => 'nullable|max:200',
            'recommendation' => 'nullable',
            'status'         => 'required|in:open,in_progress,closed',
        ]);
        AuditFinding::create($data);
        return redirect()->route('audits.show', $data['audit_id'])->with('success', 'Temuan berhasil ditambahkan.');
    }

    public function show(AuditFinding $auditFinding)
    {
        $auditFinding->load('audit', 'capa');
        return view('audit-findings.show', compact('auditFinding'));
    }

    public function edit(AuditFinding $auditFinding)
    {
        $audits = Audit::latest()->get();
        return view('audit-findings.edit', compact('auditFinding', 'audits'));
    }

    public function update(Request $request, AuditFinding $auditFinding)
    {
        $data = $request->validate([
            'description'    => 'required',
            'severity'       => 'required|in:minor,major,critical',
            'finding_type'   => 'required|in:non_conformance,conformance,observation',
            'area'           => 'nullable|max:200',
            'standard_ref'   => 'nullable|max:200',
            'recommendation' => 'nullable',
            'status'         => 'required|in:open,in_progress,closed',
        ]);

        $auditFinding->update($data);
        return redirect()->route('audits.show', $auditFinding->audit_id)->with('success', 'Temuan diperbarui.');
    }

    public function destroy(AuditFinding $auditFinding)
    {
        $auditId = $auditFinding->audit_id;
        $auditFinding->delete();
        return redirect()->route('audits.show', $auditId)->with('success', 'Temuan dihapus.');
    }
}
