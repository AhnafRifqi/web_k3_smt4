<?php namespace App\Http\Controllers;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller {
    public function index() { return view('departments.index', ['departments' => Department::withCount('employees')->paginate(20)]); }
    public function create() { return view('departments.create'); }
    public function store(Request $request) {
        $request->validate(['name' => 'required|max:100', 'code' => 'required|unique:departments,code|max:10|uppercase', 'description' => 'nullable']);
        Department::create($request->only('name', 'code', 'description'));
        return redirect()->route('departments.index')->with('success', 'Departemen berhasil ditambahkan.');
    }
    public function edit(Department $department) { return view('departments.edit', compact('department')); }
    public function update(Request $request, Department $department) {
        $request->validate(['name' => 'required|max:100', 'code' => 'required|max:10|unique:departments,code,' . $department->id, 'description' => 'nullable']);
        $department->update($request->only('name', 'code', 'description', 'is_active'));
        return redirect()->route('departments.index')->with('success', 'Departemen diperbarui.');
    }
    public function destroy(Department $department) {
        if ($department->employees()->exists()) return back()->with('error', 'Tidak bisa hapus departemen yang masih memiliki karyawan.');
        $department->delete();
        return redirect()->route('departments.index')->with('success', 'Departemen dihapus.');
    }
}
