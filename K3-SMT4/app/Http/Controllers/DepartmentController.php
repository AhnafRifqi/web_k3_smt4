<?php namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Division;
use Illuminate\Http\Request;

class DepartmentController extends Controller {
    public function index() { return view('departments.index', ['departments' => Department::with(['division'])->withCount('employees')->paginate(20)]); }
    public function create() {
        $divisions = Division::orderBy('name')->get();
        return view('departments.create', compact('divisions'));
    }
    public function store(Request $request) {
        $request->validate([
            'name' => 'required|max:100',
            'code' => 'required|unique:departments,code|max:10|uppercase',
            'description' => 'nullable',
            'division_id' => 'nullable|exists:divisions,id',
        ]);
        Department::create($request->only('name', 'code', 'description', 'division_id'));
        return redirect()->route('departments.index')->with('success', 'Departemen berhasil ditambahkan.');
    }
    public function edit(Department $department) {
        $divisions = Division::orderBy('name')->get();
        return view('departments.edit', compact('department', 'divisions'));
    }

    public function show(Department $department)
    {
        $department->load('division');
        return view('departments.show', compact('department'));
    }

    public function update(Request $request, Department $department) {
        $request->validate([
            'name' => 'required|max:100',
            'code' => 'required|max:10|unique:departments,code,' . $department->id,
            'description' => 'nullable',
            'division_id' => 'nullable|exists:divisions,id',
        ]);
        $department->update($request->only('name', 'code', 'description', 'is_active', 'division_id'));
        return redirect()->route('departments.index')->with('success', 'Departemen diperbarui.');
    }
    public function destroy(Department $department) {
        if ($department->employees()->exists()) return back()->with('error', 'Tidak bisa hapus departemen yang masih memiliki karyawan.');
        $department->delete();
        return redirect()->route('departments.index')->with('success', 'Departemen dihapus.');
    }
}
