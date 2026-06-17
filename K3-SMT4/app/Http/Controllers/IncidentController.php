<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use App\Models\Department;
use App\Services\ActivityLogService;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class IncidentController extends Controller
{
    public function index(Request $request)
    {
        $query = Incident::with(['department', 'reporter']);

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('incident_number', 'ilike', "%{$request->search}%")
                  ->orWhere('title', 'ilike', "%{$request->search}%");
            });
        }
        if ($request->incident_type) $query->where('incident_type', $request->incident_type);
        if ($request->severity) $query->where('severity', $request->severity);
        if ($request->status) $query->where('status', $request->status);
        if ($request->department_id) $query->where('department_id', $request->department_id);
        if ($request->date_from) $query->whereDate('incident_date', '>=', $request->date_from);
        if ($request->date_to) $query->whereDate('incident_date', '<=', $request->date_to);

        // For dept_head, scope to their department
        if (auth()->user()->isDeptHead() && auth()->user()->employee) {
            $query->where('department_id', auth()->user()->employee->department_id);
        }

        $incidents = $query->latest('incident_date')->paginate(15)->withQueryString();
        $departments = Department::orderBy('name')->get();

        return view('incidents.index', compact('incidents', 'departments'));
    }

    public function create()
    {
        $departments = Department::orderBy('name')->get();
        return view('incidents.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'                  => 'required|max:200',
            'description'            => 'required',
            'incident_date'          => 'required|date',
            'location'               => 'required|max:200',
            'department_id'          => 'nullable|exists:departments,id',
            'incident_type'          => 'required|in:near_miss,first_aid,medical_treatment,lost_time_injury,fatality,property_damage,environmental',
            'severity'               => 'required|in:low,medium,high,critical',
            'injured_persons'        => 'nullable',
            'witnesses'              => 'nullable',
            'immediate_action_taken' => 'nullable',
            'immediate_cause'        => 'nullable',
            'capa_required'          => 'boolean',
        ]);

        $data['incident_number'] = 'INC-' . date('Ymd') . '-' . str_pad(Incident::count() + 1, 4, '0', STR_PAD_LEFT);
        $data['reported_by'] = auth()->id();
        $data['status'] = 'reported';
        $data['capa_required'] = $request->boolean('capa_required');

        $incident = Incident::create($data);

        ActivityLogService::log('incident.reported', 'incidents', "Incident {$incident->incident_number} reported: {$incident->title}", $incident, [], $incident->toArray());

        // Notify K3 Manager and K3 Officer
        NotificationService::sendToRoles(
            ['k3_manager', 'k3_officer'],
            'incident.reported',
            'New Incident: ' . $incident->incident_number,
            "A new {$incident->severity} {$incident->incident_type} incident has been reported: {$incident->title}",
            route('incidents.show', $incident)
        );

        return redirect()->route('incidents.show', $incident)->with('success', 'Laporan insiden berhasil dibuat.');
    }

    public function show(Incident $incident)
    {
        $incident->load(['department', 'reporter', 'investigator', 'capa']);
        return view('incidents.show', compact('incident'));
    }

    public function edit(Incident $incident)
    {
        $departments = Department::orderBy('name')->get();
        return view('incidents.edit', compact('incident', 'departments'));
    }

    public function update(Request $request, Incident $incident)
    {
        $data = $request->validate([
            'title'                  => 'required|max:200',
            'description'            => 'required',
            'incident_date'          => 'required|date',
            'location'               => 'required|max:200',
            'department_id'          => 'nullable|exists:departments,id',
            'incident_type'          => 'required|in:near_miss,first_aid,medical_treatment,lost_time_injury,fatality,property_damage,environmental',
            'severity'               => 'required|in:low,medium,high,critical',
            'injured_persons'        => 'nullable',
            'witnesses'              => 'nullable',
            'immediate_action_taken' => 'nullable',
            'immediate_cause'        => 'nullable',
            'status'                 => 'required|in:reported,under_investigation,corrective_action,closed',
            'root_cause'             => 'nullable',
            'lesson_learned'         => 'nullable',
            'investigated_by'        => 'nullable|exists:users,id',
            'capa_required'          => 'boolean',
        ]);

        $data['capa_required'] = $request->boolean('capa_required');

        // If status changed to closed, set closed_at
        if ($data['status'] === 'closed' && $incident->status !== 'closed') {
            $data['closed_at'] = now();
        }

        $oldStatus = $incident->status;
        $incident->update($data);

        ActivityLogService::log('incident.updated', 'incidents', "Incident {$incident->incident_number} updated (status: {$oldStatus} -> {$data['status']})", $incident, ['status' => $oldStatus], ['status' => $data['status']]);

        return redirect()->route('incidents.show', $incident)->with('success', 'Insiden berhasil diperbarui.');
    }

    public function destroy(Incident $incident)
    {
        $incident->delete();
        ActivityLogService::log('incident.deleted', 'incidents', "Incident {$incident->incident_number} deleted", $incident);
        return redirect()->route('incidents.index')->with('success', 'Insiden berhasil dihapus.');
    }

    /**
     * Assign investigation
     */
    public function assignInvestigation(Request $request, Incident $incident)
    {
        $data = $request->validate([
            'investigated_by' => 'required|exists:users,id',
        ]);

        $incident->update([
            'investigated_by' => $data['investigated_by'],
            'status' => 'under_investigation',
        ]);

        ActivityLogService::log('incident.assigned', 'incidents', "Incident {$incident->incident_number} assigned for investigation", $incident);

        return redirect()->route('incidents.show', $incident)->with('success', 'Investigasi berhasil ditugaskan.');
    }
}