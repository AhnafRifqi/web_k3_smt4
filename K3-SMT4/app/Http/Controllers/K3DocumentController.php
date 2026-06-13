<?php

namespace App\Http\Controllers;

use App\Models\K3Document;
use Illuminate\Http\Request;

class K3DocumentController extends Controller
{
    public function index(Request $request)
    {
        $query = K3Document::with('uploader');
        if ($request->search) $query->where('title', 'ilike', "%{$request->search}%");
        if ($request->category) $query->where('category', $request->category);
        if ($request->status) $query->where('status', $request->status);
        $documents = $query->latest()->paginate(12)->withQueryString();
        return view('k3-documents.index', compact('documents'));
    }

    public function create() { return view('k3-documents.create'); }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'           => 'required|max:200',
            'category'        => 'required',
            'document_number' => 'required|unique:k3_documents,document_number|max:50',
            'revision'        => 'required|max:10',
            'effective_date'  => 'required|date',
            'description'     => 'nullable',
            'status'          => 'required|in:aktif,obsolete,draft',
            'file'            => 'nullable|file|mimes:pdf|max:20480',
        ]);

        if ($request->hasFile('file')) {
            $result = cloudinary()->upload($request->file('file')->getRealPath(), ['folder' => 'smk3-jne/k3-docs', 'resource_type' => 'raw']);
            $data['file_url'] = $result->getSecurePath();
        }
        unset($data['file']);
        $data['uploaded_by'] = auth()->id();

        K3Document::create($data);
        return redirect()->route('k3-documents.index')->with('success', 'Dokumen K3 berhasil ditambahkan.');
    }

    public function show(K3Document $k3Document) { return view('k3-documents.show', compact('k3Document')); }
    public function edit(K3Document $k3Document) { return view('k3-documents.edit', compact('k3Document')); }

    public function update(Request $request, K3Document $k3Document)
    {
        $data = $request->validate([
            'title'           => 'required|max:200',
            'category'        => 'required',
            'document_number' => 'required|max:50|unique:k3_documents,document_number,' . $k3Document->id,
            'revision'        => 'required|max:10',
            'effective_date'  => 'required|date',
            'description'     => 'nullable',
            'status'          => 'required|in:aktif,obsolete,draft',
            'file'            => 'nullable|file|mimes:pdf|max:20480',
        ]);
        if ($request->hasFile('file')) {
            $result = cloudinary()->upload($request->file('file')->getRealPath(), ['folder' => 'smk3-jne/k3-docs', 'resource_type' => 'raw']);
            $data['file_url'] = $result->getSecurePath();
        }
        unset($data['file']);
        $k3Document->update($data);
        return redirect()->route('k3-documents.show', $k3Document)->with('success', 'Dokumen berhasil diperbarui.');
    }

    public function destroy(K3Document $k3Document)
    {
        $k3Document->delete();
        return redirect()->route('k3-documents.index')->with('success', 'Dokumen berhasil dihapus.');
    }
}
