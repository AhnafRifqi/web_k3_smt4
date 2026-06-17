<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use App\Models\AuditFinding;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    public function index(Request $request)
    {
        $query = Audit::with(['findings', 'creator']);

        if ($request->search) {
            $query->where('audit_number', 'ilike', "%{$request->search}%")
                  ->orWhere('area', 'ilike', "%{$request->search}%");
        }
        if ($request->type) $query->where('type', $request->type);
        if ($request->status) $query->where('status', $request->status);
        if ($request->year) $query->whereYear('audit_date', $request->year);

        $audits = $query->latest('audit_date')->paginate(10)->withQueryString();
        return view('audits.index', compact('audits'));
    }

    public function create()
    {
        return view('audits.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'audit_number'  => 'required|unique:audits,audit_number|max:30',
            'type'          => 'required|in:internal,eksternal',
            'audit_date'    => 'required|date',
            'audit_date_end'=> 'nullable|date|after_or_equal:audit_date',
            'auditor_name'  => 'required|max:100',
            'audit_agency'  => 'nullable|max:100',
            'area'          => 'required|max:200',
            'scope'         => 'nullable',
            'standard'      => 'required|in:iso_45001,pp_50_2012,keduanya',
            'status'        => 'required|in:planned,ongoing,completed,cancelled',
        ]);

        $data['created_by'] = auth()->id();
        $audit = Audit::create($data);

        return redirect()->route('audits.show', $audit)->with('success', 'Audit berhasil dibuat.');
    }

    public function show(Audit $audit)
    {
        $audit->load(['findings.capa', 'capas.pic', 'creator', 'checklistItems']);
        return view('audits.show', compact('audit'));
    }

    public function edit(Audit $audit)
    {
        return view('audits.edit', compact('audit'));
    }

    public function update(Request $request, Audit $audit)
    {
        $data = $request->validate([
            'audit_number'  => 'required|max:30|unique:audits,audit_number,' . $audit->id,
            'type'          => 'required|in:internal,eksternal',
            'audit_date'    => 'required|date',
            'audit_date_end'=> 'nullable|date|after_or_equal:audit_date',
            'auditor_name'  => 'required|max:100',
            'audit_agency'  => 'nullable|max:100',
            'area'          => 'required|max:200',
            'scope'         => 'nullable',
            'standard'      => 'required|in:iso_45001,pp_50_2012,keduanya',
            'status'        => 'required|in:planned,ongoing,completed,cancelled',
            'summary'       => 'nullable',
        ]);

        $audit->update($data);
        return redirect()->route('audits.show', $audit)->with('success', 'Audit berhasil diperbarui.');
    }

    public function destroy(Audit $audit)
    {
        $audit->delete();
        return redirect()->route('audits.index')->with('success', 'Audit berhasil dihapus.');
    }

    public function exportPdf(Audit $audit)
    {
        $audit->load(['findings.capa', 'creator']);
        $pdf = Pdf::loadView('audits.pdf', compact('audit'))->setPaper('a4');
        return $pdf->download("laporan-audit-{$audit->audit_number}.pdf");
    }

    public function exportEvidencePackage(Audit $audit)
    {
        $audit->load(['findings', 'capas.pic', 'creator', 'documents']);
        $pdf = Pdf::loadView('audits.evidence-package', compact('audit'))->setPaper('a4');
        return $pdf->download("evidence-package-{$audit->audit_number}.pdf");
    }
}
