<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Drop the old CHECK constraint created by enum('kebijakan_k3','sop','hiradc','apd','emergency_response','audit','training','lainnya')
        DB::statement("ALTER TABLE k3_documents DROP CONSTRAINT IF EXISTS k3_documents_category_check");

        // Add new constraint with all categories including the new ones from K3Document::categoryOptions()
        DB::statement("ALTER TABLE k3_documents ADD CONSTRAINT k3_documents_category_check CHECK (category IN (
            'kebijakan_k3', 'sop', 'hiradc', 'apd', 'emergency_response',
            'audit', 'training', 'legal_regulatory', 'records_evidence', 'lainnya'
        ))");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE k3_documents DROP CONSTRAINT IF EXISTS k3_documents_category_check");
        DB::statement("ALTER TABLE k3_documents ADD CONSTRAINT k3_documents_category_check CHECK (category IN (
            'kebijakan_k3', 'sop', 'hiradc', 'apd', 'emergency_response',
            'audit', 'training', 'lainnya'
        ))");
    }
};