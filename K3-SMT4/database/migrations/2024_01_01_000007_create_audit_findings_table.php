<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_findings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_id')->constrained('audits')->onDelete('cascade');
            $table->string('finding_number');
            $table->text('description');
            $table->enum('severity', ['minor', 'major', 'critical'])->default('minor');
            $table->string('area')->nullable();
            $table->string('standard_ref')->nullable(); // Referensi standar yang dilanggar
            $table->text('recommendation')->nullable();
            $table->enum('status', ['open', 'in_progress', 'closed'])->default('open');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_findings');
    }
};
