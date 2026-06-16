<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class DivisionController extends Controller
{
    public function index()
    {
        $divisions = Division::withCount('departments')->orderBy('name')->paginate(20);

        return view('divisions.index', compact('divisions'));
    }

    public function create()
    {
        return view('divisions.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|max:100',
            'code' => 'required|unique:divisions,code|max:20|uppercase',
            'description' => 'nullable',
            'is_active' => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        $division = Division::create($data);

        ActivityLogService::log(
            'division.created',
            'divisions',
            "Divisi \"{$division->name}\" dibuat",
            $division
        );

        return redirect()->route('divisions.index')->with('success', 'Divisi berhasil ditambahkan.');
    }

    public function edit(Division $division)
    {
        return view('divisions.edit', compact('division'));
    }

    public function update(Request $request, Division $division)
    {
        $data = $request->validate([
            'name' => 'required|max:100',
            'code' => 'required|max:20|unique:divisions,code,' . $division->id,
            'description' => 'nullable',
            'is_active' => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        $oldValues = $division->toArray();
        $division->update($data);

        ActivityLogService::log(
            'division.updated',
            'divisions',
            "Divisi \"{$division->name}\" diperbarui",
            $division,
            $oldValues,
            $division->fresh()->toArray()
        );

        return redirect()->route('divisions.index')->with('success', 'Divisi berhasil diperbarui.');
    }

    public function destroy(Division $division)
    {
        if ($division->departments()->exists()) {
            return back()->with('error', 'Tidak bisa hapus divisi yang masih memiliki departemen.');
        }

        $name = $division->name;
        $division->delete();

        ActivityLogService::log('division.deleted', 'divisions', "Divisi \"{$name}\" dihapus");

        return redirect()->route('divisions.index')->with('success', 'Divisi berhasil dihapus.');
    }
}
