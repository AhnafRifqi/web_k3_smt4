<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('k3_documents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('category', [
                'kebijakan_k3',
                'sop',
                'hiradc',
                'apd',
                'emergency_response',
                'audit',
                'training',
                'lainnya'
            ]);
            $table->string('document_number')->unique();
            $table->string('revision')->default('00');
            $table->date('effective_date');
            $table->string('file_url')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['aktif', 'obsolete', 'draft'])->default('aktif');
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('k3_documents');
    }
};
