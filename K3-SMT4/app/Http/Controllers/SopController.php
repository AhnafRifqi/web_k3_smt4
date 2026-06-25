<?php

namespace App\Http\Controllers;

use App\Models\Sop;
use Illuminate\Http\Request;

class SopController extends Controller
{
    public function index(Request $request)
    {
        $query = Sop::with('creator');
        if ($request->search) {
            $query->where('name', 'ilike', "%{$request->search}%")
                  ->orWhere('code', 'ilike', "%{$request->search}%");
        }
        if ($request->status) $query->where('status', $request->status);
        if ($request->category) $query->where('category', $request->category);

        $sops       = $query->latest()->paginate(12)->withQueryString();
        $categories = Sop::select('category')->distinct()->whereNotNull('category')->pluck('category');
        return view('sops.index', compact('sops', 'categories'));
    }

    public function create() { return view('sops.create'); }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code'           => 'required|unique:sops,code|max:20',
            'name'           => 'required|max:200',
            'description'    => 'nullable',
            'steps'          => 'nullable|array',
            'risks'          => 'nullable|array',
            'controls'       => 'nullable|array',
            'apd_required'   => 'nullable|array',
            'effective_date' => 'required|date',
            'category'       => 'nullable|max:100',
            // VALIDASI STATUS KITA HAPUS DARI SINI
            'file'           => 'nullable|file|mimes:pdf|max:10240',
        ]);

        if ($request->hasFile('file')) {
            $result = cloudinary()->upload($request->file('file')->getRealPath(), [
                'folder' => 'smk3-jne/sops', 'resource_type' => 'raw',
            ]);
            $data['file_url'] = $result->getSecurePath();
        }
        
        // --- SISTEM OTOMATIS MEMAKSA STATUS DRAFT DI SINI ---
        $data['status'] = 'tidak_aktif';
        $data['created_by'] = auth()->id();

        Sop::create($data);
        return redirect()->route('sops.index')->with('success', 'SOP berhasil ditambahkan dan menunggu persetujuan (Draft).');
    }

    public function show(Sop $sop)
    {
        $sop->load(['executions.employee', 'creator']);
        return view('sops.show', compact('sop'));
    }

    public function edit(Sop $sop) { return view('sops.edit', compact('sop')); }

    public function update(Request $request, Sop $sop)
    {
        $data = $request->validate([
            'code'           => 'required|max:20|unique:sops,code,' . $sop->id,
            'name'           => 'required|max:200',
            'description'    => 'nullable',
            'steps'          => 'nullable|array',
            'risks'          => 'nullable|array',
            'controls'       => 'nullable|array',
            'apd_required'   => 'nullable|array',
            'effective_date' => 'required|date',
            'category'       => 'nullable|max:100',
            'status'         => 'required|in:aktif,revisi,tidak_aktif',
            'file'           => 'nullable|file|mimes:pdf|max:10240',
        ]);

        if ($request->hasFile('file')) {
            $result = cloudinary()->upload($request->file('file')->getRealPath(), ['folder' => 'smk3-jne/sops', 'resource_type' => 'raw']);
            $data['file_url'] = $result->getSecurePath();
        }
        unset($data['file']);

        $sop->update($data);
        return redirect()->route('sops.show', $sop)->with('success', 'SOP berhasil diperbarui.');
    }

    public function destroy(Sop $sop)
    {
        $sop->delete();
        return redirect()->route('sops.index')->with('success', 'SOP berhasil dihapus.');
    }

    public function approve(Sop $sop)
    {
        $sop->update(['status' => 'aktif']);
        return redirect()->back()->with('success', 'SOP berhasil disetujui.');
    }

    public function reject(Sop $sop)
    {
        $sop->update(['status' => 'revisi']);
        return redirect()->back()->with('error', 'SOP dikembalikan untuk direvisi.');
    }
}
