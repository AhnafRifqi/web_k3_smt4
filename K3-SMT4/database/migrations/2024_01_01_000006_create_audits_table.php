<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audits', function (Blueprint $table) {
            $table->id();
            $table->string('audit_number')->unique();
            $table->enum('type', ['internal', 'eksternal'])->default('internal');
            $table->date('audit_date');
            $table->date('audit_date_end')->nullable();
            $table->string('auditor_name');
            $table->string('audit_agency')->nullable(); // Lembaga Audit (untuk eksternal)
            $table->string('area');
            $table->text('scope')->nullable();
            $table->enum('standard', ['iso_45001', 'pp_50_2012', 'keduanya'])->default('keduanya');
            $table->json('checklist')->nullable();
            $table->text('summary')->nullable();
            $table->enum('status', ['planned', 'ongoing', 'completed', 'cancelled'])->default('planned');
            $table->string('report_url')->nullable(); // Laporan Audit PDF
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audits');
    }
};
