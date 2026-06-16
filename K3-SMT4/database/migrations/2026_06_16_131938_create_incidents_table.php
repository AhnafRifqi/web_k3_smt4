<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->string('incident_number')->unique();
            $table->string('title');
            $table->text('description');
            $table->dateTime('incident_date');
            $table->string('location');
            $table->foreignId('department_id')->nullable()->constrained()->onDelete('restrict');
            $table->string('incident_type'); // near_miss, first_aid, medical_treatment, lost_time_injury, fatality, property_damage, environmental
            $table->string('severity'); // low, medium, high, critical
            $table->text('injured_persons')->nullable();
            $table->text('witnesses')->nullable();
            $table->text('immediate_action_taken')->nullable();
            $table->foreignId('reported_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('investigated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('status'); // reported, under_investigation, corrective_action, closed
            $table->text('root_cause')->nullable();
            $table->text('lesson_learned')->nullable();
            $table->json('evidence_urls')->nullable();
            $table->boolean('capa_required')->default(false);
            $table->foreignId('incident_capa_id')->nullable()->constrained('capas')->onDelete('set null');
            $table->dateTime('closed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incidents');
    }
};