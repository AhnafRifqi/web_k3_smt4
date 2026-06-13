<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Sop;
use App\Models\SopExecution;
use Illuminate\Http\Request;

class SopExecutionController extends Controller
{
    public function index(Request $request)
    {
        $query = SopExecution::with(['employee', 'sop', 'recorder']);
        if ($request->search) $query->whereHas('employee', fn($q) => $q->where('name', 'ilike', "%{$request->search}%"));
        if ($request->status) $query->where('status', $request->status);
        if ($request->sop_id) $query->where('sop_id', $request->sop_id);
        if ($request->date_from) $query->where('execution_date', '>=', $request->date_from);
        if ($request->date_to) $query->where('execution_date', '<=', $request->date_to);

        $executions = $query->latest('execution_date')->paginate(15)->withQueryString();
        $sops       = Sop::where('status', 'aktif')->get();
        return view('sop-executions.index', compact('executions', 'sops'));
    }

    public function create()
    {
        $employees = Employee::where('status', 'aktif')->get();
        $sops      = Sop::where('status', 'aktif')->get();
        return view('sop-executions.create', compact('employees', 'sops'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'execution_date' => 'required|date',
            'employee_id'    => 'required|exists:employees,id',
            'sop_id'         => 'required|exists:sops,id',
            'status'         => 'required|in:sesuai,tidak_sesuai,perlu_perbaikan',
            'notes'          => 'nullable',
            'photo'          => 'nullable|image|max:5120',
        ]);
        if ($request->hasFile('photo')) {
            $result = cloudinary()->upload($request->file('photo')->getRealPath(), ['folder' => 'smk3-jne/executions']);
            $data['photo_url'] = $result->getSecurePath();
        }
        unset($data['photo']);
        $data['recorded_by'] = auth()->id();
        SopExecution::create($data);
        return redirect()->route('sop-executions.index')->with('success', 'Pelaksanaan SOP berhasil dicatat.');
    }

    public function edit(SopExecution $sopExecution)
    {
        $employees = Employee::where('status', 'aktif')->get();
        $sops      = Sop::where('status', 'aktif')->get();
        return view('sop-executions.edit', compact('sopExecution', 'employees', 'sops'));
    }

    public function update(Request $request, SopExecution $sopExecution)
    {
        $data = $request->validate([
            'execution_date' => 'required|date',
            'employee_id'    => 'required|exists:employees,id',
            'sop_id'         => 'required|exists:sops,id',
            'status'         => 'required|in:sesuai,tidak_sesuai,perlu_perbaikan',
            'notes'          => 'nullable',
        ]);
        $sopExecution->update($data);
        return redirect()->route('sop-executions.index')->with('success', 'Data pelaksanaan SOP diperbarui.');
    }

    public function destroy(SopExecution $sopExecution)
    {
        $sopExecution->delete();
        return redirect()->route('sop-executions.index')->with('success', 'Data berhasil dihapus.');
    }
}
