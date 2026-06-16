<?php

namespace App\Http\Controllers;

use App\Models\Hazard;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Sop;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class HazardController extends Controller
{
    public function index(Request $request)
    {
        $query = Hazard::with(['department', 'responsiblePerson', 'sop']);

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('hazard_number', 'ilike', "%{$request->search}%")
                  ->orWhere('task_description', 'ilike', "%{$request->search}%")
                  ->orWhere('hazard_description', 'ilike', "%{$request->search}%");
            });
        }
        if ($request->hazard_type) $query->where('hazard_type', $request->hazard_type);
        if ($request->risk_level) $query->where('risk_level', $request->risk_level);
        if ($request->status) $query->where('status', $request->status);
        if ($request->department_id) $query->where('department_id', $request->department_id);

        if (auth()->user()->isDeptHead() && auth()->user()->employee) {
            $query->where('department_id', auth()->user()->employee->department_id);
        }

        $hazards = $query->latest()->paginate(15)->withQueryString();
        $departments = Department::orderBy('name')->get();

        return view('hazards.index', compact('hazards', 'departments'));
    }

    public function create()
    {
        $departments = Department::orderBy('name')->get();
        $employees = Employee::where('status', 'aktif')->orderBy('name')->get();
        $sops = Sop::where('status', 'aktif')->orderBy('title')->get();
        return view('hazards.create', compact('departments', 'employees', 'sops'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'location'                => 'required|max:200',
            'department_id'           => 'nullable|exists:departments,id',
            'task_description'        => 'required',
            'hazard_description'      => 'required',
            'hazard_type'             => 'required|in:physical,chemical,biological,ergonomic,psychosocial,electrical,mechanical',
            'likelihood'              => 'required|integer|min:1|max:5',
            'severity'                => 'required|integer|min:1|max:5',
            'existing_controls'       => 'nullable',
            'additional_controls'     => 'nullable',
            'responsible_person_id'   => 'nullable|exists:employees,id',
            'target_completion_date'  => 'nullable|date',
            'status'                  => 'required|in:identified,controlled,closed',
            'sop_id'                  => 'nullable|exists:sops,id',
        ]);

        $data['hazard_number'] = 'HAZ-' . date('Ymd') . '-' . str_pad(Hazard::withTrashed()->count() + 1, 4, '0', STR_PAD_LEFT);

        // Calculate risk score
        $risk = Hazard::calculateRiskScore($data['likelihood'], $data['severity']);
        $data['risk_score'] = $risk['risk_score'];
        $data['risk_level'] = $risk['risk_level'];

        $data['identified_by'] = auth()->id();

        $hazard = Hazard::create($data);

        ActivityLogService::log('hazard.identified', 'hazards', "Hazard {$hazard->hazard_number} identified: {$hazard->hazard_type}", $hazard, [], $hazard->toArray());

        return redirect()->route('hazards.show', $hazard)->with('success', 'Identifikasi bahaya berhasil dibuat.');
    }

    public function show(Hazard $hazard)
    {
        $hazard->load(['department', 'responsiblePerson', 'sop', 'identifier']);
        return view('hazards.show', compact('hazard'));
    }

    public function edit(Hazard $hazard)
    {
        $departments = Department::orderBy('name')->get();
        $employees = Employee::where('status', 'aktif')->orderBy('name')->get();
        $sops = Sop::where('status', 'aktif')->orderBy('title')->get();
        return view('hazards.edit', compact('hazard', 'departments', 'employees', 'sops'));
    }

    public function update(Request $request, Hazard $hazard)
    {
        $data = $request->validate([
            'location'                => 'required|max:200',
            'department_id'           => 'nullable|exists:departments,id',
            'task_description'        => 'required',
            'hazard_description'      => 'required',
            'hazard_type'             => 'required|in:physical,chemical,biological,ergonomic,psychosocial,electrical,mechanical',
            'likelihood'              => 'required|integer|min:1|max:5',
            'severity'                => 'required|integer|min:1|max:5',
            'existing_controls'       => 'nullable',
            'additional_controls'     => 'nullable',
            'responsible_person_id'   => 'nullable|exists:employees,id',
            'target_completion_date'  => 'nullable|date',
            'status'                  => 'required|in:identified,controlled,closed',
            'sop_id'                  => 'nullable|exists:sops,id',
        ]);

        // Recalculate risk score
        $risk = Hazard::calculateRiskScore($data['likelihood'], $data['severity']);
        $data['risk_score'] = $risk['risk_score'];
        $data['risk_level'] = $risk['risk_level'];

        $hazard->update($data);

        ActivityLogService::log('hazard.updated', 'hazards', "Hazard {$hazard->hazard_number} updated", $hazard);

        return redirect()->route('hazards.show', $hazard)->with('success', 'Identifikasi bahaya berhasil diperbarui.');
    }

    public function destroy(Hazard $hazard)
    {
        $hazard->delete();
        ActivityLogService::log('hazard.deleted', 'hazards', "Hazard {$hazard->hazard_number} deleted", $hazard);
        return redirect()->route('hazards.index')->with('success', 'Identifikasi bahaya berhasil dihapus.');
    }
}