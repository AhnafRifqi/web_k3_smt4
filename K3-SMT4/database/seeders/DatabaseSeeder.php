<?php

namespace Database\Seeders;

use App\Models\Audit;
use App\Models\AuditFinding;
use App\Models\Capa;
use App\Models\Department;
use App\Models\Employee;
use App\Models\K3Document;
use App\Models\Sop;
use App\Models\SopExecution;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ===== DEPARTMENTS =====
        $departments = [
            ['name' => 'Gudang & Sortir', 'code' => 'GDG'],
            ['name' => 'Kurir Lapangan', 'code' => 'KRL'],
            ['name' => 'Keselamatan & Kesehatan Kerja', 'code' => 'K3'],
            ['name' => 'Human Resources', 'code' => 'HRD'],
            ['name' => 'Operasional', 'code' => 'OPS'],
            ['name' => 'IT & Teknologi', 'code' => 'IT'],
        ];

        foreach ($departments as $dept) {
            Department::firstOrCreate(['code' => $dept['code']], $dept);
        }

        // ===== USERS =====
        $admin = User::firstOrCreate(['email' => 'admin@smk3jne.com'], [
            'name'     => 'Administrator SMK3',
            'password' => Hash::make('password123'),
            'role'     => 'admin',
            'is_active' => true,
        ]);

        $supervisor = User::firstOrCreate(['email' => 'supervisor@smk3jne.com'], [
            'name'     => 'Budi Supervisor K3',
            'password' => Hash::make('password123'),
            'role'     => 'supervisor_k3',
            'is_active' => true,
        ]);

        $auditor = User::firstOrCreate(['email' => 'auditor@smk3jne.com'], [
            'name'     => 'Siti Auditor',
            'password' => Hash::make('password123'),
            'role'     => 'auditor',
            'is_active' => true,
        ]);

        User::firstOrCreate(['email' => 'karyawan@smk3jne.com'], [
            'name'     => 'Ahmad Karyawan',
            'password' => Hash::make('password123'),
            'role'     => 'karyawan',
            'is_active' => true,
        ]);

        // ===== EMPLOYEES =====
        $deptGdg = Department::where('code', 'GDG')->first();
        $deptKrl = Department::where('code', 'KRL')->first();
        $deptK3  = Department::where('code', 'K3')->first();

        $employees = [
            ['nik' => 'JNE-001', 'name' => 'Ahmad Santoso', 'position' => 'Staf Gudang', 'department_id' => $deptGdg->id, 'join_date' => '2021-03-15', 'status' => 'aktif'],
            ['nik' => 'JNE-002', 'name' => 'Siti Nurhaliza', 'position' => 'Supervisor Gudang', 'department_id' => $deptGdg->id, 'join_date' => '2019-07-01', 'status' => 'aktif'],
            ['nik' => 'JNE-003', 'name' => 'Budi Setiawan', 'position' => 'Kurir', 'department_id' => $deptKrl->id, 'join_date' => '2022-01-10', 'status' => 'aktif'],
            ['nik' => 'JNE-004', 'name' => 'Dewi Rahayu', 'position' => 'Koordinator K3', 'department_id' => $deptK3->id, 'join_date' => '2020-05-20', 'status' => 'aktif'],
            ['nik' => 'JNE-005', 'name' => 'Eko Purnomo', 'position' => 'Operator Forklift', 'department_id' => $deptGdg->id, 'join_date' => '2021-09-01', 'status' => 'aktif'],
        ];

        foreach ($employees as $emp) {
            Employee::firstOrCreate(['nik' => $emp['nik']], $emp);
        }

        // ===== SOPS =====
        $sops = [
            [
                'code' => 'SOP-K3-001',
                'name' => 'Manual Handling - Pengangkatan Barang',
                'description' => 'Prosedur keselamatan dalam pengangkatan dan pemindahan barang secara manual di area gudang.',
                'steps' => ['Pastikan posisi kaki selebar bahu', 'Tekuk lutut, jaga punggung tetap lurus', 'Genggam barang dengan erat', 'Angkat perlahan menggunakan kekuatan kaki', 'Hindari memutar badan saat membawa barang'],
                'risks' => ['Cedera punggung', 'Cedera otot', 'Terjatuh'],
                'controls' => ['Gunakan hand pallet untuk barang > 23 kg', 'Minta bantuan rekan kerja', 'Gunakan APD lengkap'],
                'apd_required' => ['Helm safety', 'Sepatu safety', 'Sarung tangan', 'Rompi Hi-Vis'],
                'effective_date' => '2024-01-01',
                'category' => 'Manual Handling',
                'status' => 'aktif',
                'created_by' => $admin->id,
            ],
            [
                'code' => 'SOP-K3-002',
                'name' => 'Operasi Conveyor Belt Sortir',
                'description' => 'Prosedur operasional dan keselamatan saat mengoperasikan atau bekerja di sekitar conveyor belt.',
                'steps' => ['Periksa kondisi conveyor sebelum operasi', 'Pastikan safety fence terpasang', 'Jangan letakkan tangan di belt yang bergerak', 'Hentikan mesin sebelum perbaikan', 'Laporkan kerusakan segera'],
                'risks' => ['Terjepit mesin', 'Terpeleset', 'Kontak dengan benda bergerak'],
                'controls' => ['Pasang safety fence', 'Gunakan emergency stop', 'Pelatihan rutin operator'],
                'apd_required' => ['Helm safety', 'Sepatu safety', 'Sarung tangan anti-slip', 'Rompi Hi-Vis'],
                'effective_date' => '2024-01-01',
                'category' => 'Conveyor Belt',
                'status' => 'aktif',
                'created_by' => $admin->id,
            ],
            [
                'code' => 'SOP-K3-003',
                'name' => 'Defensive Driving Kurir',
                'description' => 'Prosedur berkendara defensif bagi kurir lapangan untuk keselamatan di jalan raya.',
                'steps' => ['Periksa kendaraan sebelum berangkat', 'Gunakan helm SNI untuk motor', 'Patuhi rambu lalu lintas', 'Jaga jarak aman', 'Istirahat setiap 2 jam berkendara'],
                'risks' => ['Kecelakaan lalu lintas', 'Kelelahan', 'Cuaca buruk'],
                'controls' => ['Briefing harian sebelum berangkat', 'Jadwal istirahat wajib', 'GPS tracking kendaraan'],
                'apd_required' => ['Helm SNI', 'Jaket reflektif', 'Sarung tangan berkendara'],
                'effective_date' => '2024-01-01',
                'category' => 'Defensive Driving',
                'status' => 'aktif',
                'created_by' => $admin->id,
            ],
        ];

        $sopModels = [];
        foreach ($sops as $sop) {
            $sopModels[] = Sop::firstOrCreate(['code' => $sop['code']], $sop);
        }

        // ===== SOP EXECUTIONS (sample data) =====
        $employeeList = Employee::all();
        if ($employeeList->count() > 0) {
            for ($i = 0; $i < 30; $i++) {
                $emp  = $employeeList->random();
                $sop  = $sopModels[array_rand($sopModels)];
                $date = Carbon::now()->subDays(rand(1, 90));
                $statusOptions = ['sesuai', 'sesuai', 'sesuai', 'tidak_sesuai', 'perlu_perbaikan'];

                SopExecution::create([
                    'execution_date' => $date,
                    'employee_id'    => $emp->id,
                    'sop_id'         => $sop->id,
                    'status'         => $statusOptions[array_rand($statusOptions)],
                    'notes'          => 'Pelaksanaan SOP rutin.',
                    'recorded_by'    => $supervisor->id,
                ]);
            }
        }

        // ===== K3 DOCUMENTS =====
        $documents = [
            ['title' => 'Kebijakan K3 JNE 2024', 'category' => 'kebijakan_k3', 'document_number' => 'DOC-K3-001', 'revision' => '01', 'effective_date' => '2024-01-01', 'status' => 'aktif', 'uploaded_by' => $admin->id],
            ['title' => 'HIRADC Gudang Sortir', 'category' => 'hiradc', 'document_number' => 'DOC-K3-002', 'revision' => '00', 'effective_date' => '2024-01-15', 'status' => 'aktif', 'uploaded_by' => $supervisor->id],
            ['title' => 'Emergency Response Plan - Kebakaran', 'category' => 'emergency_response', 'document_number' => 'DOC-K3-003', 'revision' => '00', 'effective_date' => '2024-02-01', 'status' => 'aktif', 'uploaded_by' => $admin->id],
            ['title' => 'Standar APD Gudang', 'category' => 'apd', 'document_number' => 'DOC-K3-004', 'revision' => '01', 'effective_date' => '2024-01-01', 'status' => 'aktif', 'uploaded_by' => $supervisor->id],
        ];

        foreach ($documents as $doc) {
            K3Document::firstOrCreate(['document_number' => $doc['document_number']], $doc);
        }

        // ===== AUDITS =====
        $audit1 = Audit::firstOrCreate(['audit_number' => 'AUD-INT-2024-001'], [
            'type'         => 'internal',
            'audit_date'   => '2024-03-15',
            'audit_date_end' => '2024-03-16',
            'auditor_name' => 'Siti Auditor',
            'area'         => 'Gudang Sortir & Inbound',
            'scope'        => 'Pemeriksaan kepatuhan SOP manual handling dan penggunaan APD',
            'standard'     => 'keduanya',
            'status'       => 'completed',
            'summary'      => 'Audit berjalan lancar. Ditemukan beberapa ketidaksesuaian minor.',
            'created_by'   => $auditor->id,
        ]);

        $audit2 = Audit::firstOrCreate(['audit_number' => 'AUD-INT-2024-002'], [
            'type'         => 'internal',
            'audit_date'   => Carbon::now()->addDays(14)->format('Y-m-d'),
            'auditor_name' => 'Siti Auditor',
            'area'         => 'Zona Outbound & Kurir',
            'standard'     => 'keduanya',
            'status'       => 'planned',
            'created_by'   => $auditor->id,
        ]);

        // ===== AUDIT FINDINGS =====
        $finding1 = AuditFinding::firstOrCreate(['audit_id' => $audit1->id, 'finding_number' => 'TEM-001'], [
            'description'  => 'Beberapa karyawan tidak menggunakan APD lengkap (helm safety) di area inbound.',
            'severity'     => 'minor',
            'area'         => 'Zona Inbound',
            'standard_ref' => 'ISO 45001:2018 Klausal 8.1',
            'recommendation' => 'Lakukan briefing ulang penggunaan APD dan pasang pengingat visual.',
            'status'       => 'closed',
        ]);

        $finding2 = AuditFinding::firstOrCreate(['audit_id' => $audit1->id, 'finding_number' => 'TEM-002'], [
            'description'  => 'APAR di zona sortir sudah melewati tanggal inspeksi (expired 3 bulan lalu).',
            'severity'     => 'major',
            'area'         => 'Zona Sortir',
            'standard_ref' => 'PP 50/2012 Lampiran II',
            'recommendation' => 'Segera jadwalkan penggantian dan inspeksi APAR oleh vendor bersertifikat.',
            'status'       => 'in_progress',
        ]);

        // ===== CAPA =====
        $employee1 = Employee::first();
        Capa::firstOrCreate(['capa_number' => 'CAPA-2024-001'], [
            'finding_id'         => $finding1->id,
            'audit_id'           => $audit1->id,
            'description'        => 'Peningkatan kepatuhan penggunaan APD di area inbound',
            'root_cause'         => 'Kurangnya pengawasan dan kesadaran karyawan terhadap pentingnya APD',
            'corrective_action'  => 'Briefing APD dilakukan setiap pagi selama 1 bulan, pemasangan rambu APD di pintu masuk',
            'preventive_action'  => 'Prosedur pengawasan APD dimasukkan ke dalam checklist harian supervisor',
            'pic_id'             => $employee1?->id,
            'target_date'        => Carbon::now()->subDays(10),
            'completed_date'     => Carbon::now()->subDays(15),
            'status'             => 'closed',
            'verified_by'        => $auditor->id,
        ]);

        Capa::firstOrCreate(['capa_number' => 'CAPA-2024-002'], [
            'finding_id'        => $finding2->id,
            'audit_id'          => $audit1->id,
            'description'       => 'Penggantian APAR yang sudah expired di zona sortir',
            'root_cause'        => 'Sistem reminder inspeksi APAR tidak berjalan dengan baik',
            'corrective_action' => 'Segera hubungi vendor untuk penggantian APAR',
            'preventive_action' => 'Implementasi sistem digital reminder inspeksi APAR bulanan',
            'pic_id'            => $employee1?->id,
            'target_date'       => Carbon::now()->addDays(7),
            'status'            => 'in_progress',
        ]);

        $this->command->info('✅ Database seeded successfully!');
        $this->command->line('');
        $this->command->info('👤 Login credentials:');
        $this->command->line('   Admin     : admin@smk3jne.com / password123');
        $this->command->line('   Supervisor: supervisor@smk3jne.com / password123');
        $this->command->line('   Auditor   : auditor@smk3jne.com / password123');
        $this->command->line('   Karyawan  : karyawan@smk3jne.com / password123');
    }
}
