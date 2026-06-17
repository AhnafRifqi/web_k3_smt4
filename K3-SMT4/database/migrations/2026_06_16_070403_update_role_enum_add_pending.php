<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // PostgreSQL: drop existing CHECK constraint on role column, add updated one with 'pending'
        DB::statement("ALTER TABLE users DROP CONSTRAINT IF EXISTS users_role_check");
        DB::statement("ALTER TABLE users ADD CONSTRAINT users_role_check CHECK (role IN ('admin', 'supervisor_k3', 'auditor', 'karyawan', 'pending'))");
        DB::statement("ALTER TABLE users ALTER COLUMN role SET DEFAULT 'pending'");
    }

    public function down(): void
    {
        // Revert: remove 'pending' from allowed values
        DB::statement("ALTER TABLE users DROP CONSTRAINT IF EXISTS users_role_check");
        DB::statement("ALTER TABLE users ADD CONSTRAINT users_role_check CHECK (role IN ('admin', 'supervisor_k3', 'auditor', 'karyawan'))");
        DB::statement("ALTER TABLE users ALTER COLUMN role SET DEFAULT 'karyawan'");
    }
};