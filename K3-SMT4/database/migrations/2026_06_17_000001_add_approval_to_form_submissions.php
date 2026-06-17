<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('form_submissions', function (Blueprint $table) {
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete()->after('submitted_at');
            $table->timestamp('reviewed_at')->nullable()->after('reviewed_by');
            $table->string('approval_status')->nullable()->after('reviewed_at'); // pending_approval, approved, rejected
            $table->text('review_notes')->nullable()->after('approval_status');
        });
    }

    public function down(): void
    {
        Schema::table('form_submissions', function (Blueprint $table) {
            $table->dropForeign(['reviewed_by']);
            $table->dropColumn(['reviewed_by', 'reviewed_at', 'approval_status', 'review_notes']);
        });
    }
};
