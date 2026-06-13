<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sops', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Kode SOP
            $table->string('name');
            $table->text('description')->nullable();
            $table->json('steps')->nullable();       // Langkah Kerja (array)
            $table->json('risks')->nullable();       // Risiko
            $table->json('controls')->nullable();    // Pengendalian Risiko
            $table->json('apd_required')->nullable(); // APD Wajib
            $table->date('effective_date');
            $table->string('file_url')->nullable();  // File PDF SOP
            $table->string('category')->nullable();  // Manual Handling, Conveyor, etc
            $table->enum('status', ['aktif', 'revisi', 'tidak_aktif'])->default('aktif');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sops');
    }
};
