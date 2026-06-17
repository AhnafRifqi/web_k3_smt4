<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hazards', function (Blueprint $table) {
            $table->id();
            $table->string('hazard_number')->unique();
            $table->string('location');
            $table->foreignId('department_id')->nullable()->constrained()->onDelete('restrict');
            $table->text('task_description');
            $table->text('hazard_description');
            $table->string('hazard_type'); // physical, chemical, biological, ergonomic, psychosocial, electrical, mechanical
            $table->integer('likelihood')->unsigned(); // 1-5
            $table->integer('severity')->unsigned(); // 1-5
            $table->integer('risk_score'); // computed: likelihood * severity
            $table->string('risk_level'); // low, medium, high, extreme
            $table->text('existing_controls')->nullable();
            $table->text('additional_controls')->nullable();
            $table->foreignId('responsible_person_id')->nullable()->constrained('employees')->onDelete('set null');
            $table->date('target_completion_date')->nullable();
            $table->string('status'); // identified, controlled, closed
            $table->foreignId('sop_id')->nullable()->constrained('sops')->onDelete('set null');
            $table->foreignId('identified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hazards');
    }
};