<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('capas', function (Blueprint $table) {
            $table->id();
            $table->string('capa_number')->unique();
            $table->foreignId('finding_id')->nullable()->constrained('audit_findings')->onDelete('set null');
            $table->foreignId('audit_id')->nullable()->constrained('audits')->onDelete('set null');
            $table->text('description');
            $table->text('root_cause')->nullable();
            $table->text('corrective_action')->nullable();
            $table->text('preventive_action')->nullable();
            $table->foreignId('pic_id')->nullable()->constrained('employees')->onDelete('set null');
            $table->date('target_date');
            $table->date('completed_date')->nullable();
            $table->enum('status', ['open', 'in_progress', 'closed'])->default('open');
            $table->string('evidence_url')->nullable(); // Bukti Perbaikan
            $table->text('verification_notes')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('capas');
    }
};
