<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // MySQL requires raw query to modify ENUM values
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'supervisor_k3', 'auditor', 'karyawan', 'pending') DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'supervisor_k3', 'auditor', 'karyawan') DEFAULT 'karyawan'");
    }
};