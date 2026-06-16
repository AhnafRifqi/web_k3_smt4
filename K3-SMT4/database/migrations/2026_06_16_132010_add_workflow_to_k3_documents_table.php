<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('k3_documents', function (Blueprint $table) {
            // Version control
            $table->foreignId('parent_document_id')->nullable()->after('id')->constrained('k3_documents')->onDelete('set null');
            $table->string('version')->default('1.0')->after('revision');

            // Workflow
            $table->string('workflow_status')->default('draft')->after('status'); // draft, under_review, approved, obsolete

            // Approval chain
            $table->foreignId('submitted_by')->nullable()->after('uploaded_by')->constrained('users')->onDelete('set null');
            $table->foreignId('reviewed_by')->nullable()->after('submitted_by')->constrained('users')->onDelete('set null');
            $table->foreignId('approved_by')->nullable()->after('reviewed_by')->constrained('users')->onDelete('set null');

            // Timestamps
            $table->timestamp('submitted_at')->nullable()->after('approved_by');
            $table->timestamp('reviewed_at')->nullable()->after('submitted_at');
            $table->timestamp('approved_at')->nullable()->after('reviewed_at');

            // Review expiry
            $table->date('review_due_date')->nullable()->after('approved_at');
        });
    }

    public function down(): void
    {
        Schema::table('k3_documents', function (Blueprint $table) {
            $table->dropColumn([
                'parent_document_id', 'version', 'workflow_status',
                'submitted_by', 'reviewed_by', 'approved_by',
                'submitted_at', 'reviewed_at', 'approved_at',
                'review_due_date',
            ]);
        });
    }
};