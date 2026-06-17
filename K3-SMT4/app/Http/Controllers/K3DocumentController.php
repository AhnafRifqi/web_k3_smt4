<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\K3Document;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class K3DocumentController extends Controller
{
    public function index(Request $request)
    {
        $query = K3Document::with('uploader');
        $user = auth()->user();

        if (in_array($user->role, ['employee', 'dept_head'])) {
            $departmentId = $user->employee?->department_id;

            $query->where(function ($q) use ($departmentId) {
                $q->where('visibility', 'public');
                if ($departmentId) {
                    $q->orWhere(function ($sub) use ($departmentId) {
                        $sub->where('visibility', 'restricted')
                            ->whereJsonContains('allowed_departments', $departmentId);
                    });
                }
            });
        }

        if ($request->search) {
            $query->where('title', 'ilike', "%{$request->search}%");
        }
        if ($request->category) {
            $query->where('category', $request->category);
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $documents = $query->latest()->paginate(12)->withQueryString();

        return view('k3-documents.index', compact('documents'));
    }

    public function create()
    {
        $departments = Department::orderBy('name')->get();

        return view('k3-documents.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'           => 'required|max:200',
            'category'        => 'required',
            'document_number' => 'required|unique:k3_documents,document_number|max:50',
            'revision'        => 'required|max:10',
            'version'         => 'nullable|max:10',
            'effective_date'  => 'required|date',
            'description'     => 'nullable',
            'status'          => 'required|in:aktif,obsolete,draft',
            'workflow_status' => 'nullable|in:draft,under_review,approved,obsolete',
            'review_due_date' => 'nullable|date',
            'visibility'      => 'required|in:public,restricted',
            'allowed_departments' => 'nullable|array',
            'allowed_departments.*' => 'exists:departments,id',
            'file'            => 'nullable|file|mimes:pdf,doc,docx,xlsx,jpg,jpeg,png|max:51200',
        ]);

        if ($request->hasFile('file')) {
            $data['file_url'] = $this->uploadDocument($request->file('file'));
        }

        unset($data['file']);
        $data['uploaded_by'] = auth()->id();
        $data['version'] = $data['version'] ?? '1.0';
        $data['workflow_status'] = $data['workflow_status'] ?? 'draft';

        if ($data['visibility'] === 'public') {
            $data['allowed_departments'] = null;
        }

        $doc = K3Document::create($data);

        ActivityLogService::log('document.uploaded', 'documents', "Document {$doc->document_number} uploaded: {$doc->title}", $doc, [], $doc->toArray());

        return redirect()->route('k3-documents.index')->with('success', 'Dokumen K3 berhasil ditambahkan.');
    }

    public function show(K3Document $k3Document)
    {
        $k3Document->load(['uploader', 'submitter', 'reviewer', 'approver', 'versions', 'parent']);

        return view('k3-documents.show', compact('k3Document'));
    }

    public function edit(K3Document $k3Document)
    {
        $departments = Department::orderBy('name')->get();

        return view('k3-documents.edit', compact('k3Document', 'departments'));
    }

    public function update(Request $request, K3Document $k3Document)
    {
        $data = $request->validate([
            'title'           => 'required|max:200',
            'category'        => 'required',
            'document_number' => 'required|max:50|unique:k3_documents,document_number,' . $k3Document->id,
            'revision'        => 'required|max:10',
            'version'         => 'nullable|max:10',
            'effective_date'  => 'required|date',
            'description'     => 'nullable',
            'status'          => 'required|in:aktif,obsolete,draft',
            'workflow_status' => 'nullable|in:draft,under_review,approved,obsolete',
            'review_due_date' => 'nullable|date',
            'visibility'      => 'required|in:public,restricted',
            'allowed_departments' => 'nullable|array',
            'allowed_departments.*' => 'exists:departments,id',
            'file'            => 'nullable|file|mimes:pdf,doc,docx,xlsx,jpg,jpeg,png|max:51200',
        ]);

        if ($request->hasFile('file')) {
            $data['file_url'] = $this->uploadDocument($request->file('file'));
        }

        unset($data['file']);

        if ($data['visibility'] === 'public') {
            $data['allowed_departments'] = null;
        }

        $k3Document->update($data);

        ActivityLogService::log('document.updated', 'documents', "Document {$k3Document->document_number} updated", $k3Document);

        return redirect()->route('k3-documents.show', $k3Document)->with('success', 'Dokumen berhasil diperbarui.');
    }

    public function destroy(K3Document $k3Document)
    {
        $k3Document->delete();
        ActivityLogService::log('document.deleted', 'documents', "Document {$k3Document->document_number} deleted", $k3Document);

        return redirect()->route('k3-documents.index')->with('success', 'Dokumen berhasil dihapus.');
    }

    private function uploadDocument(UploadedFile $file): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $isImage = in_array($extension, ['jpg', 'jpeg', 'png'], true);
        $resourceType = $isImage ? 'image' : 'raw';

        $result = cloudinary()->upload($file->getRealPath(), [
            'folder' => 'smk3-jne/k3-docs',
            'resource_type' => $resourceType,
        ]);

        return $result->getSecurePath();
    }

    /**
     * Download document file proxy.
     * Fetches the file from Cloudinary server-side and serves it with proper headers,
     * avoiding CORS / Content-Disposition issues with Cloudinary raw URLs.
     */
    public function download(K3Document $k3Document, Request $request)
    {
        if (!$k3Document->file_url) {
            abort(404, 'File tidak ditemukan.');
        }

        $extension = strtolower(pathinfo(parse_url($k3Document->file_url, PHP_URL_PATH) ?? '', PATHINFO_EXTENSION));
        $filename = Str::slug($k3Document->title) . '.' . ($extension ?: 'pdf');

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $k3Document->file_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_TIMEOUT => 120,
        ]);
        $fileContent = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        curl_close($ch);

        if ($httpCode !== 200 || $fileContent === false) {
            abort(502, 'Gagal mengambil file dari penyimpanan.');
        }

        return response($fileContent, 200, [
            'Content-Type' => $contentType ?: 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Content-Length' => strlen($fileContent),
            'Cache-Control' => 'private, max-age=3600',
        ]);
    }

    /**
     * Stream document inline in browser (for PDF preview).
     */
    public function stream(K3Document $k3Document)
    {
        if (!$k3Document->file_url) {
            abort(404, 'File tidak ditemukan.');
        }

        $extension = strtolower(pathinfo(parse_url($k3Document->file_url, PHP_URL_PATH) ?? '', PATHINFO_EXTENSION));
        $filename = Str::slug($k3Document->title) . '.' . ($extension ?: 'pdf');

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $k3Document->file_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_TIMEOUT => 120,
        ]);
        $fileContent = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        curl_close($ch);

        if ($httpCode !== 200 || $fileContent === false) {
            abort(502, 'Gagal mengambil file dari penyimpanan.');
        }

        // For PDF: serve inline so browser renders it
        $disposition = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])
            ? 'inline'
            : 'inline';

        return response($fileContent, 200, [
            'Content-Type' => $contentType ?: 'application/pdf',
            'Content-Disposition' => $disposition . '; filename="' . $filename . '"',
            'Content-Length' => strlen($fileContent),
            'Cache-Control' => 'private, max-age=3600',
        ]);
    }
}
