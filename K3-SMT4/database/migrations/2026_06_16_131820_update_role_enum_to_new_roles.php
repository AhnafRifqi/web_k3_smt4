<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // PostgreSQL: drop existing CHECK constraint on role column, add updated one with new roles
        DB::statement("ALTER TABLE users DROP CONSTRAINT IF EXISTS users_role_check");
        DB::statement("ALTER TABLE users ADD CONSTRAINT users_role_check CHECK (role IN ('super_admin', 'k3_manager', 'k3_officer', 'dept_head', 'employee', 'auditor', 'viewer'))");
        DB::statement("ALTER TABLE users ALTER COLUMN role SET DEFAULT 'employee'");

        // Map old roles to new roles
        DB::statement("UPDATE users SET role = 'super_admin' WHERE role = 'admin'");
        DB::statement("UPDATE users SET role = 'k3_manager' WHERE role = 'supervisor_k3'");
        DB::statement("UPDATE users SET role = 'employee' WHERE role = 'karyawan'");
        DB::statement("UPDATE users SET role = 'employee' WHERE role = 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users DROP CONSTRAINT IF EXISTS users_role_check");
        DB::statement("ALTER TABLE users ADD CONSTRAINT users_role_check CHECK (role IN ('admin', 'supervisor_k3', 'auditor', 'karyawan', 'pending'))");
        DB::statement("ALTER TABLE users ALTER COLUMN role SET DEFAULT 'pending'");

        DB::statement("UPDATE users SET role = 'admin' WHERE role = 'super_admin'");
        DB::statement("UPDATE users SET role = 'supervisor_k3' WHERE role = 'k3_manager'");
        DB::statement("UPDATE users SET role = 'karyawan' WHERE role = 'employee'");
    }
};