<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use App\Exports\EmployeesExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->tab ?? 'active';

        if ($tab === 'pending') {
            // Ambil user yang masih pending
            $pendingUsers = User::where('role', 'pending')
                ->where('is_validated', \Illuminate\Support\Facades\DB::raw('false'))
                ->latest()
                ->paginate(15, ['*'], 'page')
                ->withQueryString();

            $employees = collect(); // empty collection for active tab data
            $departments = Department::active()->get();

            return view('employees.index', compact('employees', 'departments', 'pendingUsers', 'tab'));
        }

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

        // Ambil pending users untuk tab
        $pendingUsers = User::where('role', 'pending')
            ->where('is_validated', \Illuminate\Support\Facades\DB::raw('false'))
            ->latest()
            ->paginate(15, ['*'], 'page_pending')
            ->withQueryString();

        return view('employees.index', compact('employees', 'departments', 'pendingUsers', 'tab'));
    }

    /**
     * Approve pending user -> menjadi karyawan
     */
    public function approvePending(Request $request, User $user)
    {
        if ($user->role !== 'pending') {
            return back()->with('error', 'User ini bukan dalam status pending.');
        }

        // Update user menjadi karyawan
        $user->update([
            'role' => 'karyawan',
            'is_validated' => true,
        ]);

        return redirect()->route('employees.create', ['user_id' => $user->id])
            ->with('success', 'User ' . $user->name . ' berhasil disetujui. Silakan lengkapi data karyawan.');
    }

    /**
     * Reject pending user
     */
    public function rejectPending(Request $request, User $user)
    {
        if ($user->role !== 'pending') {
            return back()->with('error', 'User ini bukan dalam status pending.');
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('employees.index', ['tab' => 'pending'])
            ->with('success', 'User ' . $name . ' ditolak dan dihapus dari sistem.');
    }

    public function create()
    {
        $departments = Department::active()->get();
        $pendingUsers = User::where('role', 'pending')->get();
        return view('employees.create', compact('departments', 'pendingUsers'));
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
            'user_id'       => 'nullable|exists:users,id',
        ]);

        if ($request->hasFile('photo')) {
            $result = cloudinary()->upload($request->file('photo')->getRealPath(), [
                'folder' => 'smk3-jne/employees',
            ]);
            $data['photo_url'] = $result->getSecurePath();
        }
        unset($data['photo']);

        // Ensure email is set if user_id is provided
        if ($request->user_id && empty($data['email'])) {
            $user = User::find($request->user_id);
            if ($user) {
                $data['email'] = $user->email;
            }
        }

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

    // ==========================================
    // DATA KARYAWAN MANDIRI (Untuk Role Karyawan)
    // ==========================================

    public function myEmployee()
    {
        if (!auth()->user()->is_validated) {
            return redirect()->route('dashboard')->with('error', 'Akun Anda belum divalidasi oleh Admin.');
        }

        $employee = auth()->user()->employee;
        if (!$employee) {
            return redirect()->route('my-employee.create')->with('info', 'Silakan lengkapi data karyawan Anda terlebih dahulu.');
        }

        $employee->load(['department', 'sopExecutions.sop', 'capas']);
        return view('employees.my', compact('employee'));
    }

    public function createMyEmployee()
    {
        if (!auth()->user()->is_validated) {
            return redirect()->route('dashboard')->with('error', 'Akun Anda belum divalidasi oleh Admin.');
        }

        if (auth()->user()->employee) {
            return redirect()->route('my-employee')->with('error', 'Anda sudah melengkapi data diri.');
        }

        $departments = Department::active()->get();
        return view('employees.edit_my', [
            'employee' => null,
            'departments' => $departments
        ]);
    }

    public function storeMyEmployee(Request $request)
    {
        if (!auth()->user()->is_validated) {
            return redirect()->route('dashboard')->with('error', 'Akun Anda belum divalidasi oleh Admin.');
        }

        if (auth()->user()->employee) {
            return redirect()->route('my-employee')->with('error', 'Anda sudah melengkapi data diri.');
        }

        $data = $request->validate([
            'nik'           => 'required|unique:employees,nik|max:20',
            'name'          => 'required|max:100',
            'position'      => 'required|max:100',
            'department_id' => 'required|exists:departments,id',
            'phone'         => 'nullable|max:20',
            'join_date'     => 'required|date',
            'photo'         => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $result = cloudinary()->upload($request->file('photo')->getRealPath(), [
                'folder' => 'smk3-jne/employees',
            ]);
            $data['photo_url'] = $result->getSecurePath();
        }
        unset($data['photo']);

        $data['email'] = auth()->user()->email;
        $data['status'] = 'aktif';
        $data['user_id'] = auth()->id();

        Employee::create($data);

        return redirect()->route('my-employee')->with('success', 'Data karyawan Anda berhasil disimpan.');
    }

    public function editMyEmployee()
    {
        if (!auth()->user()->is_validated) {
            return redirect()->route('dashboard')->with('error', 'Akun Anda belum divalidasi oleh Admin.');
        }

        $employee = auth()->user()->employee;
        if (!$employee) {
            return redirect()->route('my-employee.create')->with('info', 'Silakan lengkapi data karyawan Anda terlebih dahulu.');
        }

        $departments = Department::active()->get();
        return view('employees.edit_my', compact('employee', 'departments'));
    }

    public function updateMyEmployee(Request $request)
    {
        if (!auth()->user()->is_validated) {
            return redirect()->route('dashboard')->with('error', 'Akun Anda belum divalidasi oleh Admin.');
        }

        $employee = auth()->user()->employee;
        if (!$employee) {
            return redirect()->route('my-employee.create')->with('info', 'Silakan lengkapi data karyawan Anda terlebih dahulu.');
        }

        $data = $request->validate([
            'nik'           => 'required|max:20|unique:employees,nik,' . $employee->id,
            'name'          => 'required|max:100',
            'position'      => 'required|max:100',
            'department_id' => 'required|exists:departments,id',
            'phone'         => 'nullable|max:20',
            'join_date'     => 'required|date',
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

        return redirect()->route('my-employee')->with('success', 'Data karyawan Anda berhasil diperbarui.');
    }
}