<?php

namespace App\Http\Controllers;

use App\Models\Sop;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
            'status'         => 'required|in:aktif,revisi,tidak_aktif',
            'file'           => 'nullable|file|mimes:pdf|max:10240',
        ]);

        if ($request->hasFile('file')) {
            $result = cloudinary()->upload($request->file('file')->getRealPath(), [
                'folder' => 'smk3-jne/sops', 'resource_type' => 'raw',
            ]);
            $data['file_url'] = $result->getSecurePath();
        }
        unset($data['file']);
        $data['created_by'] = auth()->id();

        Sop::create($data);
        return redirect()->route('sops.index')->with('success', 'SOP berhasil ditambahkan.');
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

    /**
     * Download SOP file proxy.
     * Fetches the file from Cloudinary server-side and serves it with proper headers.
     */
    public function download(Sop $sop)
    {
        if (!$sop->file_url) {
            abort(404, 'File tidak ditemukan.');
        }

        $extension = strtolower(pathinfo(parse_url($sop->file_url, PHP_URL_PATH) ?? '', PATHINFO_EXTENSION));
        $filename = Str::slug($sop->name) . '.' . ($extension ?: 'pdf');

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $sop->file_url,
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
     * Stream SOP file inline in browser (for PDF preview).
     */
    public function stream(Sop $sop)
    {
        if (!$sop->file_url) {
            abort(404, 'File tidak ditemukan.');
        }

        $extension = strtolower(pathinfo(parse_url($sop->file_url, PHP_URL_PATH) ?? '', PATHINFO_EXTENSION));
        $filename = Str::slug($sop->name) . '.' . ($extension ?: 'pdf');

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $sop->file_url,
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
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
            'Content-Length' => strlen($fileContent),
            'Cache-Control' => 'private, max-age=3600',
        ]);
    }
}
