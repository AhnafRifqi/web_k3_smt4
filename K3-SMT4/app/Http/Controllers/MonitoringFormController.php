<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\FormAssignment;
use App\Models\FormField;
use App\Models\FormSubmission;
use App\Models\MonitoringForm;
use App\Models\User;
use App\Services\ActivityLogService;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class MonitoringFormController extends Controller
{
    private const FIELD_TYPES = 'text,number,yes_no,checklist,dropdown,date,photo,signature,rating';

    public function index(Request $request)
    {
        $query = MonitoringForm::with(['department', 'creator'])
            ->withCount(['fields', 'assignments', 'submissions']);

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'ilike', "%{$request->search}%")
                  ->orWhere('description', 'ilike', "%{$request->search}%");
            });
        }

        if ($request->department_id) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->has('is_active') && $request->is_active !== '') {
            $query->where('is_active', $request->boolean('is_active'));
        }

        if (! auth()->user()->canManage()) {
            $user = auth()->user();
            $departmentId = $user->employee?->department_id;

            $query->where(function ($q) use ($user, $departmentId) {
                $q->whereHas('assignments', function ($aq) use ($user, $departmentId) {
                    $aq->where(function ($sub) use ($user, $departmentId) {
                        $sub->where(function ($s) use ($user) {
                            $s->where('assigned_to_type', 'user')
                              ->where('assigned_to_id', $user->id);
                        });
                        if ($departmentId) {
                            $sub->orWhere(function ($s) use ($departmentId) {
                                $s->where('assigned_to_type', 'department')
                                  ->where('assigned_to_id', $departmentId);
                            });
                        }
                    });
                });
            });
        }

        $forms = $query->latest()->paginate(15)->withQueryString();
        $departments = Department::orderBy('name')->get();

        return view('monitoring-forms.index', compact('forms', 'departments'));
    }

    public function create()
    {
        $departments = Department::orderBy('name')->get();

        return view('monitoring-forms.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|max:200',
            'description' => 'nullable|string',
            'department_id' => 'nullable|exists:departments,id',
            'is_active' => 'boolean',
            'fields' => 'required|array|min:1',
            'fields.*.field_type' => 'required|in:' . self::FIELD_TYPES,
            'fields.*.label' => 'required|max:200',
            'fields.*.options' => 'nullable|array',
            'fields.*.is_required' => 'boolean',
            'fields.*.order' => 'integer|min:0',
        ]);

        $form = MonitoringForm::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'department_id' => $data['department_id'] ?? null,
            'created_by' => auth()->id(),
            'is_active' => $request->boolean('is_active', true),
        ]);

        foreach ($data['fields'] as $index => $field) {
            FormField::create([
                'form_id' => $form->id,
                'field_type' => $field['field_type'],
                'label' => $field['label'],
                'options' => $field['options'] ?? null,
                'is_required' => ! empty($field['is_required']),
                'order' => $field['order'] ?? $index,
            ]);
        }

        ActivityLogService::log(
            'monitoring_form.created',
            'monitoring_forms',
            "Form monitoring \"{$form->title}\" dibuat",
            $form,
            [],
            $form->toArray()
        );

        return redirect()->route('monitoring-forms.show', $form)
            ->with('success', 'Form monitoring berhasil dibuat.');
    }

    public function show(MonitoringForm $monitoringForm)
    {
        $monitoringForm->load([
            'department',
            'creator',
            'fields',
            'assignments.assignedDepartment',
            'assignments.assignedUser',
            'assignments.creator',
            'submissions.submitter',
            'submissions.department',
        ]);

        $departments = Department::orderBy('name')->get();
        $users = User::where('is_active', true)
            ->whereIn('role', ['employee', 'dept_head', 'k3_officer', 'k3_manager'])
            ->orderBy('name')
            ->get();

        $myAssignments = collect();
        if (! auth()->user()->canManage()) {
            $user = auth()->user();
            $departmentId = $user->employee?->department_id;

            $myAssignments = $monitoringForm->assignments->filter(function ($assignment) use ($user, $departmentId) {
                if ($assignment->assigned_to_type === 'user') {
                    return $assignment->assigned_to_id === $user->id;
                }

                return $departmentId && $assignment->assigned_to_id === $departmentId;
            });
        }

        return view('monitoring-forms.show', compact('monitoringForm', 'departments', 'users', 'myAssignments'));
    }

    public function edit(MonitoringForm $monitoringForm)
    {
        $monitoringForm->load('fields');
        $departments = Department::orderBy('name')->get();

        return view('monitoring-forms.create', [
            'monitoringForm' => $monitoringForm,
            'departments' => $departments,
        ]);
    }

    public function update(Request $request, MonitoringForm $monitoringForm)
    {
        $data = $request->validate([
            'title' => 'required|max:200',
            'description' => 'nullable|string',
            'department_id' => 'nullable|exists:departments,id',
            'is_active' => 'boolean',
            'fields' => 'required|array|min:1',
            'fields.*.field_type' => 'required|in:' . self::FIELD_TYPES,
            'fields.*.label' => 'required|max:200',
            'fields.*.options' => 'nullable|array',
            'fields.*.is_required' => 'boolean',
            'fields.*.order' => 'integer|min:0',
        ]);

        $oldValues = $monitoringForm->toArray();

        $monitoringForm->update([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'department_id' => $data['department_id'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        $monitoringForm->fields()->delete();

        foreach ($data['fields'] as $index => $field) {
            FormField::create([
                'form_id' => $monitoringForm->id,
                'field_type' => $field['field_type'],
                'label' => $field['label'],
                'options' => $field['options'] ?? null,
                'is_required' => ! empty($field['is_required']),
                'order' => $field['order'] ?? $index,
            ]);
        }

        ActivityLogService::log(
            'monitoring_form.updated',
            'monitoring_forms',
            "Form monitoring \"{$monitoringForm->title}\" diperbarui",
            $monitoringForm,
            $oldValues,
            $monitoringForm->fresh()->toArray()
        );

        return redirect()->route('monitoring-forms.show', $monitoringForm)
            ->with('success', 'Form monitoring berhasil diperbarui.');
    }

    public function destroy(MonitoringForm $monitoringForm)
    {
        $title = $monitoringForm->title;
        $monitoringForm->delete();

        ActivityLogService::log(
            'monitoring_form.deleted',
            'monitoring_forms',
            "Form monitoring \"{$title}\" dihapus"
        );

        return redirect()->route('monitoring-forms.index')
            ->with('success', 'Form monitoring berhasil dihapus.');
    }

    public function assign(Request $request, MonitoringForm $monitoringForm)
    {
        $data = $request->validate([
            'assigned_to_type' => 'required|in:department,user',
            'assigned_to_id' => 'required|integer',
            'frequency' => 'required|in:daily,weekly,monthly,once,per_event,ad_hoc',
            'due_date' => 'nullable|date',
        ]);

        if ($data['assigned_to_type'] === 'department') {
            $request->validate(['assigned_to_id' => 'exists:departments,id']);
        } else {
            $request->validate(['assigned_to_id' => 'exists:users,id']);
        }

        $assignment = FormAssignment::create([
            'form_id' => $monitoringForm->id,
            'assigned_to_type' => $data['assigned_to_type'],
            'assigned_to_id' => $data['assigned_to_id'],
            'frequency' => $data['frequency'],
            'due_date' => $data['due_date'] ?? null,
            'created_by' => auth()->id(),
        ]);

        ActivityLogService::log(
            'monitoring_form.assigned',
            'monitoring_forms',
            "Form \"{$monitoringForm->title}\" ditugaskan ({$assignment->frequency_label})",
            $assignment
        );

        if ($data['assigned_to_type'] === 'department') {
            NotificationService::sendToDepartment(
                (int) $data['assigned_to_id'],
                'form.assigned',
                'Form Monitoring Baru: ' . $monitoringForm->title,
                'Anda mendapat tugas mengisi form monitoring: ' . $monitoringForm->title,
                route('monitoring-forms.show', $monitoringForm)
            );
        } else {
            $user = User::find($data['assigned_to_id']);
            if ($user) {
                NotificationService::send(
                    $user,
                    'form.assigned',
                    'Form Monitoring Baru: ' . $monitoringForm->title,
                    'Anda mendapat tugas mengisi form monitoring: ' . $monitoringForm->title,
                    route('monitoring-forms.show', $monitoringForm)
                );
            }
        }

        return redirect()->route('monitoring-forms.show', $monitoringForm)
            ->with('success', 'Form berhasil ditugaskan.');
    }

    public function fill(MonitoringForm $monitoringForm, FormAssignment $assignment)
    {
        $this->authorizeAssignment($monitoringForm, $assignment);

        $monitoringForm->load('fields');

        $existingSubmission = FormSubmission::where('form_id', $monitoringForm->id)
            ->where('assignment_id', $assignment->id)
            ->where('submitted_by', auth()->id())
            ->where('status', 'draft')
            ->first();

        return view('monitoring-forms.fill', compact('monitoringForm', 'assignment', 'existingSubmission'));
    }

    public function submit(Request $request, MonitoringForm $monitoringForm, FormAssignment $assignment)
    {
        $this->authorizeAssignment($monitoringForm, $assignment);

        $monitoringForm->load('fields');

        $rules = ['status' => 'required|in:submitted,draft'];
        foreach ($monitoringForm->fields as $field) {
            $key = 'field_' . $field->id;
            if ($field->field_type === 'photo') {
                $required = $field->is_required && $request->input('status') === 'submitted' ? 'required' : 'nullable';
                $rules[$key] = $required . '|file|image|max:51200';
            } elseif ($field->is_required && $request->input('status') === 'submitted') {
                $rules[$key] = 'required';
            } else {
                $rules[$key] = 'nullable';
            }
        }

        $request->validate($rules);

        $data = [];
        foreach ($monitoringForm->fields as $field) {
            $key = 'field_' . $field->id;
            $value = $request->input($key);

            if ($field->field_type === 'photo' && $request->hasFile($key)) {
                $result = cloudinary()->upload($request->file($key)->getRealPath(), [
                    'folder' => 'smk3-jne/monitoring-forms',
                ]);
                $value = $result->getSecurePath();
            }

            if ($field->field_type === 'checklist' && is_array($value)) {
                $value = array_values($value);
            }

            $data[(string) $field->id] = [
                'label' => $field->label,
                'type' => $field->field_type,
                'value' => $value,
            ];
        }

        $departmentId = auth()->user()->employee?->department_id;
        $status = $request->input('status');

        $submission = FormSubmission::updateOrCreate(
            [
                'form_id' => $monitoringForm->id,
                'assignment_id' => $assignment->id,
                'submitted_by' => auth()->id(),
            ],
            [
                'department_id' => $departmentId,
                'data' => $data,
                'status' => $status,
                'submitted_at' => $status === 'submitted' ? now() : null,
            ]
        );

        if ($status === 'submitted') {
            $submission->update(['approval_status' => 'pending_approval']);
        }

        $action = $status === 'submitted' ? 'monitoring_form.submitted' : 'monitoring_form.draft_saved';
        $desc = $status === 'submitted'
            ? "Form \"{$monitoringForm->title}\" disubmit"
            : "Draft form \"{$monitoringForm->title}\" disimpan";

        ActivityLogService::log($action, 'monitoring_forms', $desc, $submission);

        if ($status === 'submitted') {
            NotificationService::sendToRoles(
                ['k3_manager', 'k3_officer', 'dept_head'],
                'form.submitted',
                'Form Monitoring Perlu Persetujuan: ' . $monitoringForm->title,
                auth()->user()->name . ' telah mengisi form "' . $monitoringForm->title . '" dan menunggu persetujuan.',
                route('monitoring-forms.show', $monitoringForm)
            );
        }

        $message = $status === 'submitted'
            ? 'Form monitoring berhasil disubmit dan menunggu persetujuan.'
            : 'Draft form monitoring berhasil disimpan.';

        return redirect()->route('monitoring-forms.show', $monitoringForm)
            ->with('success', $message);
    }

    public function approveSubmission(Request $request, MonitoringForm $monitoringForm, FormSubmission $submission)
    {
        abort_if($submission->form_id !== $monitoringForm->id, 404);

        $data = $request->validate(['review_notes' => 'nullable|string|max:1000']);

        $submission->update([
            'approval_status' => 'approved',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'review_notes' => $data['review_notes'] ?? null,
        ]);

        ActivityLogService::log('form_submission.approved', 'monitoring_forms', "Submission form \"{$monitoringForm->title}\" disetujui", $submission);

        if ($submission->submitter) {
            NotificationService::send(
                $submission->submitter,
                'form.approved',
                'Form Disetujui: ' . $monitoringForm->title,
                'Pengisian form "' . $monitoringForm->title . '" Anda telah disetujui oleh ' . auth()->user()->name . '.',
                route('monitoring-forms.show', $monitoringForm)
            );
        }

        return redirect()->route('monitoring-forms.show', $monitoringForm)
            ->with('success', 'Submission berhasil disetujui.');
    }

    public function rejectSubmission(Request $request, MonitoringForm $monitoringForm, FormSubmission $submission)
    {
        abort_if($submission->form_id !== $monitoringForm->id, 404);

        $data = $request->validate(['review_notes' => 'nullable|string|max:1000']);

        $submission->update([
            'approval_status' => 'rejected',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'review_notes' => $data['review_notes'] ?? null,
        ]);

        ActivityLogService::log('form_submission.rejected', 'monitoring_forms', "Submission form \"{$monitoringForm->title}\" ditolak", $submission);

        if ($submission->submitter) {
            NotificationService::send(
                $submission->submitter,
                'form.rejected',
                'Form Ditolak: ' . $monitoringForm->title,
                'Pengisian form "' . $monitoringForm->title . '" Anda ditolak oleh ' . auth()->user()->name . ($data['review_notes'] ? '. Catatan: ' . $data['review_notes'] : '.'),
                route('monitoring-forms.show', $monitoringForm)
            );
        }

        return redirect()->route('monitoring-forms.show', $monitoringForm)
            ->with('success', 'Submission ditolak.');
    }

    private function authorizeAssignment(MonitoringForm $monitoringForm, FormAssignment $assignment): void
    {
        if ($assignment->form_id !== $monitoringForm->id) {
            abort(404);
        }

        if (auth()->user()->canManage()) {
            return;
        }

        $user = auth()->user();
        $departmentId = $user->employee?->department_id;

        $allowed = false;
        if ($assignment->assigned_to_type === 'user' && $assignment->assigned_to_id === $user->id) {
            $allowed = true;
        }
        if ($assignment->assigned_to_type === 'department' && $departmentId && $assignment->assigned_to_id === $departmentId) {
            $allowed = true;
        }

        if (! $allowed) {
            abort(403, 'Anda tidak memiliki akses ke penugasan form ini.');
        }
    }
}
