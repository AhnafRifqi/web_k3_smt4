<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sop_executions', function (Blueprint $table) {
            $table->id();
            $table->date('execution_date');
            $table->foreignId('employee_id')->constrained('employees')->onDelete('restrict');
            $table->foreignId('sop_id')->constrained('sops')->onDelete('restrict');
            $table->enum('status', ['sesuai', 'tidak_sesuai', 'perlu_perbaikan'])->default('sesuai');
            $table->json('checklist')->nullable(); // Checklist item per SOP
            $table->text('notes')->nullable();
            $table->string('photo_url')->nullable(); // Foto Bukti
            $table->foreignId('recorded_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sop_executions');
    }
};
