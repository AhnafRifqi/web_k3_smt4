<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Exports\EmployeesExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::with('department');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'ilike', "%{$request->search}%")
                  ->orWhere('nik', 'ilike', "%{$request->search}%")
                  ->orWhere('position', 'ilike', "%{$request->search}%");
            });
        }
        if ($request->department_id) {
            $query->where('department_id', $request->department_id);
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $employees   = $query->latest()->paginate(15)->withQueryString();
        $departments = Department::active()->get();

        return view('employees.index', compact('employees', 'departments'));
    }

    public function create()
    {
        $departments = Department::active()->get();
        return view('employees.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nik'           => 'required|unique:employees,nik|max:20',
            'name'          => 'required|max:100',
            'position'      => 'required|max:100',
            'department_id' => 'required|exists:departments,id',
            'email'         => 'nullable|email',
            'phone'         => 'nullable|max:20',
            'join_date'     => 'required|date',
            'status'        => 'required|in:aktif,tidak_aktif,cuti,resign',
            'photo'         => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $result = cloudinary()->upload($request->file('photo')->getRealPath(), [
                'folder' => 'smk3-jne/employees',
            ]);
            $data['photo_url'] = $result->getSecurePath();
        }
        unset($data['photo']);

        Employee::create($data);
        return redirect()->route('employees.index')->with('success', 'Karyawan berhasil ditambahkan.');
    }

    public function show(Employee $employee)
    {
        $employee->load(['department', 'sopExecutions.sop', 'capas']);
        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        $departments = Department::active()->get();
        return view('employees.edit', compact('employee', 'departments'));
    }

    public function update(Request $request, Employee $employee)
    {
        $data = $request->validate([
            'nik'           => 'required|max:20|unique:employees,nik,' . $employee->id,
            'name'          => 'required|max:100',
            'position'      => 'required|max:100',
            'department_id' => 'required|exists:departments,id',
            'email'         => 'nullable|email',
            'phone'         => 'nullable|max:20',
            'join_date'     => 'required|date',
            'status'        => 'required|in:aktif,tidak_aktif,cuti,resign',
            'photo'         => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $result = cloudinary()->upload($request->file('photo')->getRealPath(), [
                'folder' => 'smk3-jne/employees',
            ]);
            $data['photo_url'] = $result->getSecurePath();
        }
        unset($data['photo']);

        $employee->update($data);
        return redirect()->route('employees.index')->with('success', 'Data karyawan berhasil diperbarui.');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Karyawan berhasil dihapus.');
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new EmployeesExport($request->all()), 'karyawan-' . now()->format('Ymd') . '.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $query = Employee::with('department');
        if ($request->department_id) $query->where('department_id', $request->department_id);
        if ($request->status) $query->where('status', $request->status);
        $employees = $query->get();

        $pdf = Pdf::loadView('employees.pdf', compact('employees'))->setPaper('a4', 'landscape');
        return $pdf->download('karyawan-' . now()->format('Ymd') . '.pdf');
    }
}
