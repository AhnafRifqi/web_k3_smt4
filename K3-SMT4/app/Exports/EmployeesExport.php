<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EmployeesExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    public function __construct(private array $filters = []) {}

    public function query()
    {
        $query = Employee::with('department');
        if (!empty($this->filters['department_id'])) {
            $query->where('department_id', $this->filters['department_id']);
        }
        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }
        return $query;
    }

    public function headings(): array
    {
        return ['No', 'NIK', 'Nama', 'Jabatan', 'Departemen', 'Email', 'No. HP', 'Tgl Masuk', 'Status'];
    }

    public function map($employee): array
    {
        static $no = 0;
        $no++;
        return [
            $no,
            $employee->nik,
            $employee->name,
            $employee->position,
            $employee->department?->name ?? '-',
            $employee->email ?? '-',
            $employee->phone ?? '-',
            $employee->join_date?->format('d/m/Y') ?? '-',
            $employee->status_label,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'color' => ['rgb' => '3B82F6']], 'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true]],
        ];
    }
}
