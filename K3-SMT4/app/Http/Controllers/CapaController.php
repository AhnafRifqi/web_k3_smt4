<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use App\Models\AuditFinding;
use App\Models\Capa;
use App\Models\Department;
use App\Models\Employee;
use App\Models\K3Document;
use App\Models\Sop;
use App\Models\SopExecution;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

// ===================== CAPA CONTROLLER =====================
class CapaController extends Controller
{
    public function index(Request $request)
    {
        $query = Capa::with(['pic', 'audit', 'finding']);
        if ($request->status) $query->where('status', $request->status);
        $capas = $query->latest()->paginate(15)->withQueryString();
        return view('capa.index', compact('capas'));
    }

    public function create()
    {
        $audits    = Audit::where('status', 'completed')->latest()->get();
        $findings  = AuditFinding::where('status', '!=', 'closed')->with('audit')->get();
        $employees = Employee::where('status', 'aktif')->get();
        return view('capa.create', compact('audits', 'findings', 'employees'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'capa_number'       => 'required|unique:capas,capa_number|max:30',
            'finding_id'        => 'nullable|exists:audit_findings,id',
            'audit_id'          => 'nullable|exists:audits,id',
            'description'       => 'required',
            'root_cause'        => 'nullable',
            'corrective_action' => 'nullable',
            'preventive_action' => 'nullable',
            'pic_id'            => 'nullable|exists:employees,id',
            'target_date'       => 'required|date',
            'status'            => 'required|in:open,in_progress,closed',
        ]);
        Capa::create($data);
        return redirect()->route('capa.index')->with('success', 'CAPA berhasil ditambahkan.');
    }

    public function show(Capa $capa)
    {
        $capa->load(['pic', 'audit', 'finding', 'verifier']);
        return view('capa.show', compact('capa'));
    }

    public function edit(Capa $capa)
    {
        $audits    = Audit::where('status', 'completed')->latest()->get();
        $findings  = AuditFinding::with('audit')->get();
        $employees = Employee::where('status', 'aktif')->get();
        return view('capa.edit', compact('capa', 'audits', 'findings', 'employees'));
    }

    public function update(Request $request, Capa $capa)
    {
        $data = $request->validate([
            'capa_number'        => 'required|max:30|unique:capas,capa_number,' . $capa->id,
            'description'        => 'required',
            'root_cause'         => 'nullable',
            'corrective_action'  => 'nullable',
            'preventive_action'  => 'nullable',
            'pic_id'             => 'nullable|exists:employees,id',
            'target_date'        => 'required|date',
            'completed_date'     => 'nullable|date',
            'status'             => 'required|in:open,in_progress,closed',
            'verification_notes' => 'nullable',
            'evidence'           => 'nullable|file|max:10240',
        ]);

        if ($request->hasFile('evidence')) {
            $result = cloudinary()->upload($request->file('evidence')->getRealPath(), ['folder' => 'smk3-jne/capa']);
            $data['evidence_url'] = $result->getSecurePath();
        }
        unset($data['evidence']);
        if ($data['status'] === 'closed') $data['verified_by'] = auth()->id();

        $capa->update($data);
        return redirect()->route('capa.show', $capa)->with('success', 'CAPA berhasil diperbarui.');
    }

    public function destroy(Capa $capa)
    {
        $capa->delete();
        return redirect()->route('capa.index')->with('success', 'CAPA berhasil dihapus.');
    }
}
