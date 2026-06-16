<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('monitoring_forms', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('department_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('form_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_id')->constrained('monitoring_forms')->onDelete('cascade');
            $table->enum('field_type', [
                'text', 'number', 'yes_no', 'checklist', 'dropdown', 'date', 'photo', 'signature', 'rating',
            ]);
            $table->string('label');
            $table->json('options')->nullable();
            $table->boolean('is_required')->default(false);
            $table->unsignedInteger('order')->default(0);
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('form_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_id')->constrained('monitoring_forms')->onDelete('cascade');
            $table->enum('assigned_to_type', ['department', 'user']);
            $table->unsignedBigInteger('assigned_to_id');
            $table->enum('frequency', ['daily', 'weekly', 'monthly', 'once']);
            $table->date('due_date')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('created_at')->useCurrent();

            $table->index(['assigned_to_type', 'assigned_to_id']);
        });

        Schema::create('form_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_id')->constrained('monitoring_forms')->onDelete('cascade');
            $table->foreignId('assignment_id')->nullable()->constrained('form_assignments')->onDelete('set null');
            $table->foreignId('submitted_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('department_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamp('submitted_at')->nullable();
            $table->json('data');
            $table->enum('status', ['submitted', 'draft'])->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('form_submissions');
        Schema::dropIfExists('form_assignments');
        Schema::dropIfExists('form_fields');
        Schema::dropIfExists('monitoring_forms');
    }
};
