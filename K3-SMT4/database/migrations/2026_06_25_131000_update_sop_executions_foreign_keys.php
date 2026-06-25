<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Drop the old foreign key with ON DELETE RESTRICT
        DB::statement("ALTER TABLE sop_executions DROP CONSTRAINT IF EXISTS sop_executions_sop_id_foreign");
        // Re-add with ON DELETE CASCADE so SOP can be deleted even if it has executions
        DB::statement("ALTER TABLE sop_executions ADD CONSTRAINT sop_executions_sop_id_foreign FOREIGN KEY (sop_id) REFERENCES sops(id) ON DELETE CASCADE");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE sop_executions DROP CONSTRAINT IF EXISTS sop_executions_sop_id_foreign");
        DB::statement("ALTER TABLE sop_executions ADD CONSTRAINT sop_executions_sop_id_foreign FOREIGN KEY (sop_id) REFERENCES sops(id) ON DELETE RESTRICT");
    }
};