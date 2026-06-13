<?php

namespace App\Http\Controllers;

use App\Services\NarasiService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class RekapController extends Controller
{
    public function __construct(private NarasiService $narasiService) {}

    public function index()
    {
        return view('rekap.index');
    }

    public function bulanan(Request $request)
    {
        $year  = $request->input('year', now()->year);
        $month = $request->input('month', now()->month);

        $data = $this->narasiService->generateBulanan($year, $month);
        return view('rekap.bulanan', compact('data', 'year', 'month'));
    }

    public function triwulan(Request $request)
    {
        $year    = $request->input('year', now()->year);
        $quarter = $request->input('quarter', ceil(now()->month / 3));

        $data = $this->narasiService->generateTriwulan($year, $quarter);
        return view('rekap.triwulan', compact('data', 'year', 'quarter'));
    }

    public function tahunan(Request $request)
    {
        $year = $request->input('year', now()->year);
        $data = $this->narasiService->generateTahunan($year);
        return view('rekap.tahunan', compact('data', 'year'));
    }

    public function exportPdf(Request $request)
    {
        $type = $request->input('type', 'bulanan');

        if ($type === 'bulanan') {
            $data = $this->narasiService->generateBulanan(
                $request->input('year', now()->year),
                $request->input('month', now()->month)
            );
            $view = 'rekap.pdf-bulanan';
        } elseif ($type === 'triwulan') {
            $data = $this->narasiService->generateTriwulan(
                $request->input('year', now()->year),
                $request->input('quarter', 1)
            );
            $view = 'rekap.pdf-bulanan';
        } else {
            $data = $this->narasiService->generateTahunan($request->input('year', now()->year));
            $view = 'rekap.pdf-bulanan';
        }

        $pdf = Pdf::loadView($view, compact('data'))->setPaper('a4');
        return $pdf->download("rekap-k3-{$type}-" . now()->format('Ymd') . '.pdf');
    }
}
